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
            'request' => qa_sanitize_html($_SESSION['query']),
            'condition' => qa_sanitize_html($_SESSION['condition']),
            'date' => qa_sanitize_html($_SESSION['date']), 
            'resultsCount' => qa_sanitize_html($_SESSION['resultsCount']), 
            'fromOldest' => qa_sanitize_html($_SESSION['sortBy'])
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
            .$this->searchArr['username'].'</a>';
            
        $qa_content['custom'] = $this->generateResultsTable();
        $qa_content['navigation']['sub']=qa_admin_sub_navigation();
        return $qa_content;
    }

    private function dbSearch()
    {
        $baseQuery = 'SELECT `datetime`, `ipaddress`, `handle`, `event`,`params` FROM qa_eventlog ';

        if(!empty($this->searchArr['request'])){
            $this->searchArr['username'] = $this->searchArr['request'];

            switch ($this->searchArr['condition']) {
                case 'username':
                    $this->searchArr['request'] = $this->findUsersID($this->searchArr['request']);
                    if(!$this->searchArr['request']){
                        $finalQuery = $baseQuery;
                        break;
                    }
                    $finalQuery = $baseQuery.'WHERE userid = $ ';
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
            $this->searchArr['username'] = " ";
            $finalQuery = $baseQuery;
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
            $this->dbResult = qa_db_read_all_assoc($result);
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
                $toExclude = [
                    'q_vote_up',
                    'q_vote_down', 
                    'q_vote_nil',
                    'a_vote_up',
                    'a_vote_down', 
                    'a_vote_nil', 
                    'c_vote_up',
                    'c_vote_down', 
                    'c_vote_nil',
                    'in_q_vote_up' ,
                    'in_a_vote_up' ,
                    'in_c_vote_up' ,
                    'q_flag',
                    'a_flag',
                    'c_flag',
                    'q_unflag',
                    'a_unflag',
                    'c_unflag',
                    'q_clearflags',
                    'a_clearflags',
                    'c_clearflags',
                ];
                $isToExclude = false;
                if(in_array($field['event'], $toExclude)){
                    $isToExclude = true;
                }

                return $isToExclude ? "" : $field;
            });
        }else{
            $results = $unfiltered;
        }

        $table = $this->displayResultsTable($results);

        if(empty($results[0])){
            $table = qa_lang_html('user-activity-log/noResults');
        }

        return $table;
    }

    private function validateDate(string $finalQuery)
    {
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
        
        return $isValid ? $finalQuery.' WHERE `datetime` LIKE $' : $finalQuery;
    }

    private function findUsersID(string $user)
    {
        $query = "SELECT `handle`, `userid` FROM `qa_users` WHERE `handle` = $";

        $stmt= qa_db_query_sub($query, $user);
        if(mysqli_num_rows($stmt) > 0){
            $result = qa_db_read_one_assoc($stmt);
            return $result['userid'];
        }else{
            return false;
        }
      
    }

    private function findUsersPostsLinks(string $event,string $params)
    {
        $first = substr($event, 0, 1);
        if($first != 'q' && $first != 'a' && $first != 'c'){
            return qa_lang_html('user-activity-log/'.$event);
        }else{
            $paramsArray = qa_string_to_words($params);
            $postID = $paramsArray[1];
            $query = "SELECT `title`, `type`, `parentid` FROM `qa_posts` WHERE `postid` = $";
            $stmt = qa_db_query_sub($query, $postID);
            $result = qa_db_read_one_assoc($stmt);
            $postTitle = $result['title'] ?? null;
            $postType = $result['type'] ?? null;
            $postsParentId = $result['parentid'] ?? null;
            
            switch($postType){
                case 'Q':
                    return '<a href = "'.qa_q_path_html($postID, $postTitle, true).'">'.
                                qa_lang_html('user-activity-log/'.$event)
                                .'</a>';
                case 'C':
                    $result = $this->findParentPost($postsParentId);
                    $parentTitle = $result['title'] ?? null;
                    $parentsType = $result['type'] ?? null;
                    $commentsParentId = $result['parentid'] ?? null;
                    if($parentsType == 'A'){
                        $result = $this->findParentPost($commentsParentId);
                        $parentTitle = $result['title'] ?? null;
                        $postsParentId = $commentsParentId;
                    }
                        return '<a href = "'.qa_q_path_html($postsParentId, $parentTitle, true, 'C', $postID).'">'.
                                    qa_lang_html('user-activity-log/'.$event)
                                    .'</a>';
                    
                case 'A':
                    $result = $this->findParentPost($postsParentId);
                    $parentTitle = $result['title'] ?? null;
                    return '<a href = "'.qa_q_path_html($postsParentId, $parentTitle, true, 'A', $postID).'">'.
                                qa_lang_html('user-activity-log/'.$event)
                                .'</a>';
                default:
                    return qa_lang_html('user-activity-log/'.$event);
                
    
            }
        }
    }

    private function findParentPost(int $parentsid)
    {
        $query = "SELECT `title`, `type`, `parentid` FROM `qa_posts` WHERE `postid` = $";
        $stmt = qa_db_query_sub($query, $parentsid);
        $result = qa_db_read_one_assoc($stmt);
        return $result;
    }

    private function displayResultsTable($data){
        $tableHeader = 
        "<table><thead><tr><th>".qa_lang_html('user-activity-log/datetime').' </th>'.
            '<th>'.qa_lang_html('user-activity-log/handle').'</th>'.
            '<th>'.qa_lang_html('user-activity-log/event').'</th>';

        qa_get_logged_in_level() > QA_USER_LEVEL_EDITOR ?
            $tableHeader = $tableHeader.'<th> '.qa_lang_html('user-activity-log/ipaddress').'</th>' : 
            $tableHeader = $tableHeader;

        $tableHeader = $tableHeader.'</tr></thead><tbody>';
        $tableContent = '';

            foreach($data as $row){
                $tableContent = $tableContent.'<tr>'.
                    '<td>'.$row['datetime'].'</td>'.
                    '<td>'.$row['handle'].'</td>'.
                    '<td>'.$this->findUsersPostsLinks($row['event'], $row['params']).'</td>';
                
                qa_get_logged_in_level() > QA_USER_LEVEL_EDITOR ? 
                    $tableContent = $tableContent.'<td>'.$row['ipaddress'].'</td>' : 
                    $tableContent = $tableContent;
            }
        
        $tableContent = $tableHeader.$tableContent.'</tbody></table>';

        return $tableContent.'</tbody></table>';
    }
}