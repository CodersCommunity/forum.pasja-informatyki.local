<?php

class user_activity_search{
    
    private $searchArr;
    private $dbResult;
    private $userLevel;

    public function match_request($request) 
    {
        $this->userLevel = qa_get_logged_in_level();
        if($this->userLevel >= QA_USER_LEVEL_EDITOR){
            $this->searchArr = $_POST;
            if($this->dbSearch()){
                
            }
            return $request == 'admin/user-activity-log-search';
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
        $baseQuery = 'SELECT `datetime`, `ipaddress`, `handle`, `event` FROM qa_eventlog WHERE ';

        if($this->searchArr['condition'] == 'username'){
            $finalQuery = $baseQuery.'handle = $';
        }else if($this->searchArr['condition'] == 'type'){
            $finalQuery = $baseQuery.'event = $';
        }else if($this->searchArr['condition'] == 'ip'){
            if($this->userLevel > QA_USER_LEVEL_EDITOR){
                $finalQuery = $baseQuery.'ipaddress = $';
            }   
        }
        
        $finalQuery = $finalQuery.' LIMIT 30';
        $result = qa_db_query_sub($finalQuery, $this->searchArr['request']);
        
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
            "<table><tr><th>".qa_lang_html('user-activity-log/datetime').'</th>'.
                '<th>'.qa_lang_html('user-activity-log/ipaddress').'</th>'.
                '<th>'.qa_lang_html('user-activity-log/handle').'</th>'.
                '<th>'.qa_lang_html('user-activity-log/event').'</th>'.
            "</tr>";

            $tableContent = '';

            foreach($results as $row){
                $tableContent = $tableContent.'<tr>'.
                    '<th>'.$row['datetime'].'</th>'.
                    '<th>'.$row['ipaddress'].'</th>'.
                    '<th>'.$row['handle'].'</th>'.
                    '<th>'.qa_lang_html('user-activity-log/'.$row['event']).'</th>'.
                    '</tr>';
            }
        }else if($this->userLevel == QA_USER_LEVEL_EDITOR){
            $tableHeader = 
            "<table><tr><th>".qa_lang_html('user-activity-log/datetime').'</th>'
                .'<th>'.qa_lang_html('user-activity-log/handle').'</th>'.
                '<th>'.qa_lang_html('user-activity-log/event').'</th>'.
            "</tr></table>";
        }

        $table = $tableHeader.$tableContent.'</table>';
        return $table;
    }
}