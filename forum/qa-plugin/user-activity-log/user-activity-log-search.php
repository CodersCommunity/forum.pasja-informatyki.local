<?php

class user_activity_search
{
    
    private $searchArr;
    private $dbResult;

    public function match_request(string $request) 
    {
        $_SESSION['query'] = qa_sanitize_html($_POST['request']) ?? null;
        $_SESSION['condition'] = qa_sanitize_html($_POST['condition']) ?? null;
        $_SESSION['date'] = qa_sanitize_html($_POST['date'])?? null;
        $_SESSION['resultsCount'] = qa_sanitize_html($_POST['resultsCount']) ?? null;
        $_SESSION['sortBy'] = qa_sanitize_html($_POST['fromOldest']) ?? null;

        $this->searchArr = [
            'request' => $_SESSION['query'],
            'condition' => $_SESSION['condition'],
            'date' => $_SESSION['date'], 
            'resultsCount' => $_SESSION['resultsCount'], 
            'fromOldest' => $_SESSION['sortBy']
        ];
        if(!isset($_POST['condition']) || !isset($_POST['resultsCount'])){
            header('Location: ../../');
            exit;
        }

        if(qa_get_logged_in_level() >= QA_USER_LEVEL_EDITOR){
            if(isset($this->searchArr)){
                $this->dbSearch();
                if(isset($this->dbResult)){
                    return $request === 'user-activity-log-search';
                }
            }
        }else{
            return false;
        }
    }

    public function process_request(string $request)
    {
        $qa_content=qa_content_prepare();
        $qa_content['title']= '<a class = "to-right" href = "'.qa_path_to_root().'user-activity-log">'
            .qa_lang_html('user-activity-log/searchResults')
            .$this->searchArr['request'].'</a>';
            
        $qa_content['custom'] = $this->generateResultsTable();
        $qa_content['navigation']['sub']=qa_admin_sub_navigation();
        return $qa_content;
    }

    private function dbSearch()
    {
        $baseQuery = 'SELECT `datetime`, `ipaddress`, `handle`, `event`,`params` FROM qa_eventlog ';

        if(!empty($this->searchArr['request'])){

            switch ($this->searchArr['condition']) {
                case 'username':
                    $finalQuery = $baseQuery.'WHERE handle = $ ';
                    break;
                
                case 'type':
                    $finalQuery = $baseQuery.'WHERE event = $ ';
                    break;
                
                case 'ip':
                    if(qa_get_logged_in_level() > QA_USER_LEVEL_EDITOR){
                        $finalQuery = $baseQuery.'WHERE ipaddress = $ ';
                    }   
                break;
                    
                default:
                    $finalQuery = $baseQuery;
                break;
            }
            
            if(!empty($this->searchArr['date'])){
                $finalQuery = $finalQuery."AND";
            }
        }else{
            $finalQuery = $baseQuery." WHERE";
        }

        if(!empty($this->searchArr['date'])){
            $finalQuery = $this->validateDate($finalQuery);
        }

        
        
        if(!is_numeric($this->searchArr['resultsCount']) || empty($this->searchArr['resultsCount'])){
            $this->searchArr['resultsCount'] = 45;
        }
        $finalQuery = $finalQuery.' ORDER BY `datetime`';
        $finalQuery .= $this->searchArr['fromOldest'] ? ' DESC' : ' ASC';
        
        $finalQuery = $finalQuery.' LIMIT #';
        if(strpos($finalQuery, 'LIKE')){
            if(empty($this->searchArr['request'])){
                $result = qa_db_query_sub($finalQuery, $this->searchArr['date']."%", $this->searchArr['resultsCount']);
            }else{
                $result = qa_db_query_sub($finalQuery, $this->searchArr['request'], $this->searchArr['date']."%", $this->searchArr['resultsCount']);
            }
        }else{
            if(empty($this->searchArr['request'])){
                $result = qa_db_query_sub($finalQuery, $this->searchArr['resultsCount']);
            }else{
                $result = qa_db_query_sub($finalQuery, $this->searchArr['request'], $this->searchArr['resultsCount']);
            }
        }
       
        if(mysqli_num_rows($result) <= $this->searchArr['resultsCount']){
            $fullResultsArray = [];
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $fullResultsArray[] = $row;
            }
            $this->dbResult = $fullResultsArray;
            return true;
        }else{
            $this->dbResult = $result;
            return false;
        }

    }

    private function generateResultsTable()
    {
        $unfiltered = $this->dbResult;
        if(qa_get_logged_in_level() < QA_USER_LEVEL_ADMIN){
           $results = array_filter($unfiltered, function($field){
                switch($field['event']){
                    case 'q_vote_up': 
                    case 'q_vote_down': 
                    case 'q_vote_nil':
                    case 'a_vote_up':
                    case 'a_vote_down': 
                    case 'a_vote_nil': 
                    case 'c_vote_up': 
                    case 'c_vote_down': 
                    case 'c_vote_nil': 
                    case 'in_q_vote_up' :
                    case 'in_a_vote_up' :
                    case 'in_c_vote_up' :
                    case 'q_flag':
                    case 'a_flag':
                    case 'c_flag':
                    case 'q_unflag':
                    case 'a_unflag':
                    case 'c_unflag': return ""; break;
                }
                    return $field; 
            });
        }else{
            $results = $unfiltered;
        }

        $tableHeader = 
        "<table><tr><th>".qa_lang_html('user-activity-log/datetime').' </th>'.
            '<th>'.qa_lang_html('user-activity-log/handle').'</th>'.
            '<th>'.qa_lang_html('user-activity-log/event').'</th>';

        $tableContent = '';

        if(qa_get_logged_in_level() > QA_USER_LEVEL_EDITOR){
            $tableHeader = $tableHeader.'<th> '.qa_lang_html('user-activity-log/ipaddress').'</th>'.
                "</tr>";

            foreach($results as $row){
                $tableContent = $tableContent.'<tr>'.
                    '<td>'.$row['datetime'].'</td>'.
                    '<td>'.$row['handle'].'</td>'.
                    '<td>'.qa_lang_html('user-activity-log/'.$row['event']).'</td>'.
                    '<td> &nbsp;'.$row['ipaddress'].'</td>'.
                    '</tr>';
            }
        }else if(qa_get_logged_in_level() == QA_USER_LEVEL_EDITOR){
            $tableHeader = $tableHeader.'</tr>';
            foreach($results as $row){
                $tableContent = $tableContent.'<tr>'.
                    '<td>'.$row['datetime'].'</td>'.
                    '<td>'.$row['handle'].'</td>'.
                    '<td>'.qa_lang_html('user-activity-log/'.$row['event']).'</td>'.
                    '</tr>';
            }
        }

        $table = $tableHeader.$tableContent.'</table>';

        if(empty($results[0])){
            $table = qa_lang_html('user-activity-log/noResults');
        }

        return $table;
    }

    private function validateDate($finalQuery){
        $isValid = true;

        if(!empty($this->searchArr['date'])){
            $dateArr = explode('-', $this->searchArr['date']);
            if(isset($dateArr[0]) && is_numeric($dateArr[0])){
                $date = $dateArr[0];
                if(isset($dateArr[1]) && is_numeric($dateArr[1])){
                    $date = $date.'-'.$dateArr[1];
                    if(isset($dateArr[2])){
                        if(strlen($dateArr[2]) > 2){
                            [$day, $hours] = explode(' ', $dateArr[2]);
                            $dateArr[2] = $day;
                            $dateArr[3] = $hours;
                        }

                        $date = $date.'-'.$dateArr[2];
                    }
                }
            }else{
                $isValid = false;
            } 
        }else{
            $isValid = false;
        } 
        
        return $isValid ? $finalQuery.' `datetime` LIKE $' : $finalQuery;
    }
}