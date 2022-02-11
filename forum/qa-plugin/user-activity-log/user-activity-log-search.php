<?php

class user_activity_search{
    
    private $searchArr;
    private $dbResult;
    private $userLevel;

    public function match_request($request) 
    {
        $this->userLevel = qa_get_logged_in_level();
        $this->searchArr = $_POST;
        if(!isset($_POST['condition'])){
            header('Location: ../../');
            exit;
        }

        if($this->userLevel >= QA_USER_LEVEL_EDITOR){
            if(isset($this->searchArr)){
                $this->dbSearch();
                if(isset($this->dbResult)){
                    return $request == 'admin/user-activity-log-search';
                }
            }
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


            //print_r($dateArr);

            if(is_numeric($dateArr[0]) && is_numeric($dateArr[0])){
                $date = $dateArr[0];
                if(isset($dateArr[1]) && is_numeric($dateArr[1])){
                    $date = $date.'-'.$dateArr[1];
                    if(is_numeric($dateArr[2]) && is_numeric($dateArr[2])){
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

            //print_r($dateArr);
        }
        
        
        
        
        $finalQuery = $finalQuery.' ORDER BY `datetime` DESC LIMIT 30';
        //print_r($finalQuery);
        if(strpos($finalQuery, 'LIKE')){
            $result = qa_db_query_sub($finalQuery, $this->searchArr['request'], $this->searchArr['date']."%");
        }else{
            $result = qa_db_query_sub($finalQuery, $this->searchArr['request']);
        }
       
        
        if(mysqli_num_rows($result) <= 30){
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
        $results = $this->dbResult;
        if($this->userLevel > QA_USER_LEVEL_EDITOR){
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
        }else if($this->userLevel == QA_USER_LEVEL_EDITOR){
            $tableHeader = 
            "<table><tr><th>".qa_lang_html('user-activity-log/datetime').' </th>'
                .'<th>'.qa_lang_html('user-activity-log/handle').'</th>'.
                '<th>'.qa_lang_html('user-activity-log/event').'</th>'.
            "</tr></table>";

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