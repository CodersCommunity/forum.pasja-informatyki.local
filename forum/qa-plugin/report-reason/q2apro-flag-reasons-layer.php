<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    
    public function head_script()
    {
        qa_html_theme_base::head_script();
        
        if(qa_is_logged_in() && $this->template === 'question') {
            $this->output('
                <script>
                    const flagAjaxURL = "'.qa_path('ajaxflagger').'";
                    const flagQuestionid = '.$this->content['q_view']['raw']['postid'].';
                </script>
            ');
            
            $this->output('
                <script type="text/javascript" src="'.QA_HTML_THEME_LAYER_URLTOROOT.'script.js"></script>
                <link rel="stylesheet" type="text/css" href="'.QA_HTML_THEME_LAYER_URLTOROOT.'styles.css">
            ');
        }
        
    }
    public function q_view_buttons($q_view)
    {
        if(qa_is_logged_in() && isset($q_view['form']['buttons']['flag']) && isset($q_view['raw']['postid'])) {
            // $q_view['form']['buttons']['flag']['tags'] is "name="q_doflag" onclick="qa_show_waiting_after(this, false);""
            $q_view['form']['buttons']['flag']['tags'] = 'data-postid="'.$q_view['raw']['postid'].'" data-posttype="q" ';
        }
        qa_html_theme_base::q_view_buttons($q_view);
    }
    
    public function a_item_buttons($a_item)
    {
        if(qa_is_logged_in() && isset($a_item['form']['buttons']['flag']) && isset($a_item['raw']['postid'])) {
            // remove default input tags from flag input 
            // $q_view['form']['buttons']['flag']['tags'] is "name="q_doflag" onclick="qa_show_waiting_after(this, false);""
            $a_item['form']['buttons']['flag']['tags'] = 'data-postid="'.$a_item['raw']['postid'].'" data-posttype="a" ';
        }
        qa_html_theme_base::a_item_buttons($a_item);
    }
    
    public function c_item_buttons($c_item)
    {
        if(qa_is_logged_in() && isset($c_item['form']['buttons']['flag']) && isset($c_item['raw']['postid'])) {
            // remove default input tags from flag input 
            // $q_view['form']['buttons']['flag']['tags'] is "name="q_doflag" onclick="qa_show_waiting_after(this, false);""
            $c_item['form']['buttons']['flag']['tags'] = 'data-postid="'.$c_item['raw']['postid'].'" data-posttype="c" data-parentid="'.$c_item['raw']['parentid'].'" ';
        }
        qa_html_theme_base::c_item_buttons($c_item);
    }

    public function body_hidden()
    {
        if(qa_is_logged_in() && $this->template === 'question') {
            $this->output('
            <div id="flagbox-popup">
                <div id="flagbox-center">
                    <div class="qa-flag-reasons-wrap">
                        <h4>
                            '.qa_lang('q2apro_flagreasons_lang/reason').'
                        </h4>
                        <label>
                            <input type="radio" name="qa-spam-reason-radio" value="1" checked>
                            <span>'.q2apro_flag_reasonid_to_readable(1).'</span>
                        </label>
                        <label>
                            <input type="radio" name="qa-spam-reason-radio" value="2">
                            <span>'.q2apro_flag_reasonid_to_readable(2).'</span>
                        </label>
                        <label>
                            <input type="radio" name="qa-spam-reason-radio" value="3">
                            <span>'.q2apro_flag_reasonid_to_readable(3).'</span>
                        </label>
                        <label>
                            <input type="radio" name="qa-spam-reason-radio" value="4">
                            <span>'.q2apro_flag_reasonid_to_readable(4).'</span>
                        </label>
                        <label>
                            <input type="radio" name="qa-spam-reason-radio" value="5">
                            <span>'.q2apro_flag_reasonid_to_readable(5).'</span>
                        </label>
                        <label>
                            <input type="radio" name="qa-spam-reason-radio" value="6">
                            <span>'.q2apro_flag_reasonid_to_readable(6).'</span>
                        </label>
                        
                        <div class="qa-spam-reason-text-wrap">
                            <p>
                                '.qa_lang('q2apro_flagreasons_lang/note').'
                            </p>
                            <input type="text" name="qa-spam-reason-text" class="qa-spam-reason-text" placeholder="'.qa_lang('q2apro_flagreasons_lang/enter_details').'">
                        </div>
                        
                        <input type="button" class="qa-gray-button qa-go-flag-send-button" value="'.qa_lang('q2apro_flagreasons_lang/send').'">
                        
                        <div class="closer">X</div>
                    </div>
                </div> 
            </div>
            ');
        }
        qa_html_theme_base::body_hidden();
        
    }
    
    public function post_tags($post, $class)
    {
        qa_html_theme_base::post_tags($post, $class);
ini_set('display_errors', 1);
        if($class === 'qa-q-view') {
            $postId = $post['raw']['postid'];
            $flagReasons = q2apro_get_postflags($postId);
            
            if(!empty($flagReasons)) {
                $flagsOut = '
                    <ul class="qa-flagreason-list">
                ';
                
                foreach ($flagReasons as $f) {
                    $userHandle = qa_userid_to_handle($f['userid']);
                    $reason = q2apro_flag_reasonid_to_readable($f['reasonid']);
                    $notice = $f['notice'];
                    
                    if(!empty($notice)) {
                        $notice = '
                        | 
                        <span class="flagreason-notice">'.$notice.'</span>
                        ';
                    }
                    $flagsOut .= '
                    <li>
                        <span class="flagreason-what">'.$reason.'</span>
                        | 
                        <span class="flagreason-who"><a href="'.qa_path('user').'/'.$userHandle.'">'.$userHandle.'</a></span>
                        '.$notice.'
                    </li>
                    ';
                }
                
                $flagsOut .= '
                    </ul>
                ';
                if(qa_get_logged_in_level() > QA_USER_LEVEL_EXPERT) {
                    $this->output('
                    <div class="qa-flag-wrap">
                          <div class="qa-flagreasons">
                                          '.$flagsOut.'
                    </div>
                </div>
                ');
                }
            }
        }
    }
    
    public function post_meta_flags($post, $class)
    {
        if(!empty($post['flags']['suffix'])) {
            if($class === 'qa-a-item' || $class === 'qa-c-item') {
                $flagInfo = q2apro_count_postflags_output($post['raw']['postid']);
                
                if(!empty($flagInfo)) {
                    if(qa_get_logged_in_level() > QA_USER_LEVEL_EXPERT) {
                        $post['flags']['suffix'] .= ': <br>'.$flagInfo;
                    }
                }
            }
        }
        qa_html_theme_base::post_meta_flags($post, $class);
    }

}
