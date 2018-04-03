<?php

class q2apro_flagreasons_admin
{
    public function init_queries($tablesLc) 
    {
        $tableName = qa_db_add_table_prefix('flagreasons');
        
        if(!in_array($tableName, $tablesLc)) {
            require_once QA_INCLUDE_DIR.'qa-app-users.php';

            return '
                CREATE TABLE `^flagreasons` (
                  `userid` int(10) UNSIGNED NOT NULL,
                  `postid` int(10) UNSIGNED NOT NULL,
                  `reasonid` int(10) UNSIGNED NOT NULL,
                  `notice` varchar(255) NULL,
                  PRIMARY KEY (userid, postid)
                ) 
                ENGINE=MyISAM DEFAULT CHARSET=utf8;
            ';
        }
        return null;
    }
    public function option_default($option) 
    {
        if('q2apro_flagreasons_enabled' === $option) return 1;
        else return null;
    }
    
    public function allow_template($template)
    {
        return ($template!='admin');
    }       
}

