<?php

class user_activity_search{
    
    private $searchArr;
    private $dbResult;

    public function match_request($request) 
    {
        $this->searchArr = $_POST;
        if(!isset($_POST['condition']) || !isset($_POST['resultsCount'])){
            header('Location: ../../');
            exit;
        }

        if(qa_get_logged_in_level() >= QA_USER_LEVEL_EDITOR){
            if(isset($this->searchArr)){
                $this->dbSearch();
                if(isset($this->dbResult)){
                    return $request == 'user-activity-log-search';
                }
            }
        }else{
            return false;
        }
    }

    public function process_request($request)
    {
        $qa_content=qa_content_prepare();
        $qa_content['title']= qa_lang_html('user-activity-log/searchResults').$this->searchArr['request'];
        
        $qa_content['custom'] = $this->generateResultsTable();
        $qa_content['navigation']['sub']=qa_admin_sub_navigation();
        return $qa_content;
    }

    private function dbSearch()
    {
        $baseQuery = 'SELECT `datetime`, `ipaddress`, `handle`, `event` FROM qa_eventlog ';

        if($this->searchArr['condition'] == 'username'){
            $finalQuery = $baseQuery.'WHERE  handle = $';
        }else if($this->searchArr['condition'] == 'type'){
            $finalQuery = $baseQuery.'WHERE event = $';
        }else if($this->searchArr['condition'] == 'ip'){
            if($this->userLevel > QA_USER_LEVEL_EDITOR){
                $finalQuery = $baseQuery.'WHERE ipaddress = $';
            }   
        }

        if($this->searchArr['date'] != ""){
            $dateArr = explode('-', $this->searchArr['date']);


            if(is_numeric($dateArr[0]) && is_numeric($dateArr[0])){
                $date = $dateArr[0];
                if(isset($dateArr[1]) && is_numeric($dateArr[1])){
                    $date = $date.'-'.$dateArr[1];
                    if(isset($dateArr[2]) && is_numeric($dateArr[2])){
                        if(strlen($dateArr[2]) > 2){
                            $time = explode(' ', $dateArr[2]);
                            $dateArr[2] = $time[0];
                            $dateArr[3] = $time[1];
                        }
                        
                        $date = $date.'-'.$dateArr[2];
                    }
                }
                $finalQuery = $finalQuery.'AND `datetime` LIKE $';
            }

        }
        
        
        if(!is_numeric($this->searchArr['resultsCount'])){
            $this->dbResult = [];
            return 0;
        }
        
        $finalQuery = $finalQuery.' ORDER BY `datetime` DESC LIMIT #';
        if(strpos($finalQuery, 'LIKE')){
            $result = qa_db_query_sub($finalQuery, $this->searchArr['request'], $this->searchArr['date']."%", $this->searchArr['resultsCount']);
        }else{
            $result = qa_db_query_sub($finalQuery, $this->searchArr['request'], $this->searchArr['resultsCount']);
        }
       
        if(mysqli_num_rows($result) <= $this->searchArr['resultsCount']){
            $fullResultsArray = [];
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $fullResultsArray[] = $row;
            }
            $this->dbResult = $fullResultsArray;
            return 1;
        }else{
            $this->dbResult = $result;
            return 0;
        }

    }

    private function generateResultsTable()
    {
        $unfiltered = $this->dbResult;
        if(qa_get_logged_in_level() < QA_USER_LEVEL_ADMIN){
           $results = array_filter($unfiltered, function($field){
                switch($field['event']){
                    case 'q_vote_up': return ""; break;
                    case 'q_vote_down': return ""; break;
                    case 'q_vote_nil': return ""; break;
                    case 'a_vote_up':return ""; break;
                    case 'a_vote_down': return ""; break;
                    case 'a_vote_nil': return ""; break;
                    case 'c_vote_up': return ""; break;
                    case 'c_vote_down': return ""; break;
                    case 'c_vote_nil': return ""; break;
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

        if(qa_get_logged_in_level() > QA_USER_LEVEL_EDITOR){
            $tableHeader = 
                "<table><tr><th>".qa_lang_html('user-activity-log/datetime').' </th>'.
                    '<th> '.qa_lang_html('user-activity-log/ipaddress').'</th>'.
                    '<th>'.qa_lang_html('user-activity-log/handle').'</th>'.
                    '<th>'.qa_lang_html('user-activity-log/event').'</th>'.
                "</tr>";

            $tableContent = '';

            foreach($results as $row){
                $tableContent = $tableContent.'<tr>'.
                    '<th>'.$row['datetime'].'</th>'.
                    '<th> &nbsp;'.$row['ipaddress'].'</th>'.
                    '<th>'.$row['handle'].'</th>'.
                    '<th>'.qa_lang_html('user-activity-log/'.$row['event']).'</th>'.
                    '</tr>';
            }
        }else if(qa_get_logged_in_level() == QA_USER_LEVEL_EDITOR){
            $tableHeader = 
                "<table><tr><th>".qa_lang_html('user-activity-log/datetime').' </th>'.
                    '<th>'.qa_lang_html('user-activity-log/handle').'</th>'.
                    '<th>'.qa_lang_html('user-activity-log/event').'</th>'.
                "</tr>";

            $tableContent = '';

            foreach($results as $row){
                $tableContent = $tableContent.'<tr>'.
                    '<th>'.$row['datetime'].'</th>'.
                    '<th>'.$row['handle'].'</th>'.
                    '<th>'.qa_lang_html('user-activity-log/'.$row['event']).'</th>'.
                    '</tr>';
            }
        }

        $table = $tableHeader.$tableContent.'</table>';

        if(!isset($results[0])){
            $table = qa_lang_html('user-activity-log/noResults');
        }

        return $table;
    }
}
