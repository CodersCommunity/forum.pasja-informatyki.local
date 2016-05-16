<?php

	class qa_html_theme_layer extends qa_html_theme_base {

		// check for post
		
		function doctype()
		{
			if(!isset($_POST['ajax_comment_vote'])) {
				qa_html_theme_base::doctype();
				if($this->request == 'admin/permissions' && function_exists('qa_register_plugin_phrases') && qa_get_logged_in_level()>=QA_USER_LEVEL_ADMIN) {
					$permits[] = 'permit_vote_c';
					foreach($permits as $optionname) {
						$value = qa_opt($optionname);
						$optionfield=array(
							'id' => $optionname,
							'label' => qa_lang_html('comment_voting/'.$optionname).':',
							'tags' => 'NAME="option_'.$optionname.'" ID="option_'.$optionname.'"',
							'error' => qa_html(@$errors[$optionname]),
						);					
						$widest=QA_PERMIT_ALL;
						$narrowest=QA_PERMIT_ADMINS;
						
						$permitoptions=qa_admin_permit_options($widest, $narrowest, (!QA_FINAL_EXTERNAL_USERS) && qa_opt('confirm_user_emails'));
						
						if (count($permitoptions)>1)
							qa_optionfield_make_select($optionfield, $permitoptions, $value,
								($value==QA_PERMIT_CONFIRMED) ? QA_PERMIT_USERS : min(array_keys($permitoptions)));
						$this->content['form']['fields'][$optionname]=$optionfield;

						$this->content['form']['fields'][$optionname.'_points']= array(
							'id' => $optionname.'_points',
							'tags' => 'NAME="option_'.$optionname.'_points" ID="option_'.$optionname.'_points"',
							'type'=>'number',
							'value'=>qa_opt($optionname.'_points'),
							'prefix'=>qa_lang_html('admin/users_must_have').'&nbsp;',
							'note'=>qa_lang_html('admin/points')
						);
						$checkboxtodisplay[$optionname.'_points']='(option_'.$optionname.'=='.qa_js(QA_PERMIT_POINTS).') ||(option_'.$optionname.'=='.qa_js(QA_PERMIT_POINTS_CONFIRMED).')';
					}
					qa_set_display_rules($this->content, $checkboxtodisplay);
				}
			}
		}

		function html()
		{
			$this->logged_in_userid = qa_get_logged_in_userid();
			if(qa_opt('voting_on_cs')) {
				if(isset($_POST['ajax_comment_vote'])) $this->ajaxCommentVote($_POST['ajax_comment_vote'],$_POST['ajax_comment_vote_id']);
				else qa_html_theme_base::html();
			}
			else qa_html_theme_base::html();
		}

		function head_script() {
			qa_html_theme_base::head_script();
			if(qa_opt('voting_on_cs') && isset($this->content['q_view'])) {
				$this->output("
<script type='text/javascript'>
	function ajaxCommentVote(elem, oldvote)
	{
		var ens=elem.getAttribute('name').split('_');
		var postid=ens[1];
		var vote=parseInt(ens[2]);
		var anchor=ens[3];
		var which=parseInt(ens[4]);

		var dataString = 'ajax_comment_vote_id='+postid+'&ajax_comment_vote='+vote;  

		jQuery.ajax({  
		  type: 'POST',  
		  url: '".qa_self_html()."',  
		  data: dataString,  
		  dataType: 'json',  
		  success: function(json) {
				if (json.status=='1') {
					switch(vote) {
						case 1:
							var up = 0;
							var up_type = '-selected';
							var down_type = false;
							break;
						case -1:
							var down = 0;
							var up_type = false;
							var down_type = '-selected';
							break;
						case 0:
							var up = 1;
							var down = -1;
							var up_type = '';
							var down_type = '';
							break;
					}".(!qa_opt('voting_down_cs')?"
					
					down_type = false;
":"")."
					elem.parentNode.innerHTML = (up_type!==false?'<div class=\"comment-vote-item'+up_type+'\" name=\"vote_'+ens[1]+'_'+up+'_c'+ens[1]+'_1\" onclick=\"ajaxCommentVote(this);\" title=\"'+json.up+'\">▲</div>':'')+(parseInt(json.data)!=0?'<div id=\"voting_'+ens[1]+'\" title=\"json.up\">'+(json.data!='0'?json.data:'')+'</div>':'')+(down_type!==false?'<div class=\"comment-vote-item'+down_type+'\" name=\"vote_'+ens[1]+'_'+down+'_c'+ens[1]+'_-1\" onclick=\"ajaxCommentVote(this);\" title=\"'+json.down+'\">▼</div>':'');

				} else if (json.status=='0') {
					var mess=document.getElementById('errorbox');
					if (!mess) {
						var mess=document.createElement('div');
						mess.id='errorbox';
						mess.className='qa-error';
						mess.innerHTML=json.data;
					}
					var postelem=document.getElementById(anchor);
					postelem.parentNode.insertBefore(mess, postelem);
				} else {
					alert('Unexpected response from server - please try again.');
				}
			} 
		});
		return false;
	} 	
</script>");
			}
		}
		function head_custom() {
			
			qa_html_theme_base::head_custom();
			if(qa_opt('voting_on_cs')) {
				
				$this->output('
<style>
	.comment-votable-container {
		width:100% !important;
	}
	.comment-vote-container {
		padding-right: 4px;
		text-align: center;
		vertical-align:top;
		width:12px;
		color: orange;
	}
	.comment-vote-item {
		color: silver;
	}

	.comment-vote-item:hover {
		color:black;
		cursor:pointer;
	}
	.comment-vote-item-selected {
		color:orange;
		cursor:pointer;
	}
	.comment-vote-item-disabled {
		display:none;
	}

</style>');
			}
		}

	// theme replacement functions

		function q_view($q_view)
		{
			if(qa_opt('voting_on_cs')) {
				$this->comment_votes = $this->logged_in_userid?qa_db_read_all_assoc(qa_db_query_sub('SELECT ^uservotes.vote AS vote, ^uservotes.postid AS postid  FROM ^posts,^uservotes WHERE ^uservotes.vote<>0 AND ^uservotes.userid=# AND ^uservotes.postid=^posts.postid AND ^posts.type=$',$this->logged_in_userid, 'C')):null;
			}
			qa_html_theme_base::q_view($q_view);
		}

		function c_item_main($c_item)
		{
			global $topage;
			if(qa_opt('voting_on_cs') && is_array($this->comment_votes) && isset($c_item['content']) && !isset($c_item['url']) && !strpos($c_item['content'],'question-closed-message')) {
				$vote=0;
				$flag=0;
				foreach($this->comment_votes as $vote) {
					if($vote['postid'] == $c_item['raw']['postid']) {
						$vote = (int)$vote['vote'];
						break;
					}
				}
				$netvotes = ($c_item['raw']['netvotes']!=0?$c_item['raw']['netvotes']:'');
				
				if(qa_permit_check('permit_vote_c')) {
					$this->output('<table class="comment-votable-container"><tr><td class="comment-vote-container">');
					switch($vote) {
						case 1:
							$up = 0;
							$up_type = '-selected';
							$down_type = false;
							break;
						case -1:
							$down = 0;
							$down_type = '-selected';
							$up_type = false;
							break;
						default:
							$up = 1;
							$down = -1;
							$up_type = '';
							$down_type = '';
							break;
					}
					
					if(!qa_opt('voting_down_cs') && $vote != -1) $down_type = false;
					
					// don't allow for own user
					
					if($c_item['raw']['userid'] != qa_get_logged_in_userid())
						$this->output(($up_type!==false?'<div class="comment-vote-item'.$up_type.'" name="vote_'.$c_item['raw']['postid'].'_'.$up.'_c'.$c_item['raw']['postid'].'_1" onclick="ajaxCommentVote(this);" title="'.qa_lang_html('main/vote'.($up == 0?'d':'').'_up_popup').'">▲</div>':'').($netvotes?'<div id="voting_'.$c_item['raw']['postid'].'">'.$netvotes.'</div>':'').($down_type!==false?'<div class="comment-vote-item'.$down_type.'" onclick="ajaxCommentVote(this);" name="vote_'.$c_item['raw']['postid'].'_'.$down.'_c'.$c_item['raw']['postid'].'_-1" title="'.qa_lang_html('main/vote'.($down == 0?'d':'').'_down_popup').'">▼</div>':''));
					else if ($netvotes) {
						$this->output('<div id="voting_'.$c_item['raw']['postid'].'">'.$netvotes.'</div>');
					
					}
					$this->output('</td><td>');
					qa_html_theme_base::c_item_main($c_item);
					$this->output('</td></tr></table>');
				}
				else if ($netvotes){
					$this->output('<table class="comment-votable-container"><tr><td class="comment-vote-container"><div id="voting_'.$c_item['raw']['postid'].'">'.$netvotes.'</div></td><td>');
					qa_html_theme_base::c_item_main($c_item);
					$this->output('</td></tr></table>');
				}
				else qa_html_theme_base::c_item_main($c_item);
			}
			else qa_html_theme_base::c_item_main($c_item);
		}

	// db variable
	
		var $logged_in_userid;
		
		var $comment_votes;
		
	// worker

		function ajaxCommentVote($vote, $postid) {
			global $topage,$qa_cookieid;
			
			$post=qa_db_select_with_pending(qa_db_full_post_selectspec($this->logged_in_userid, $postid));
			$voteerror = $this->comment_vote_error_html($post, $this->logged_in_userid, $topage);
			if ($voteerror===false) {
				$this->comment_vote_set($post, $this->logged_in_userid, qa_get_logged_in_handle(), $qa_cookieid, $vote);
				
				$comment = qa_db_single_select(qa_db_full_post_selectspec(null, $postid));
				
				$votes = $comment['netvotes'];
				
				$up_text = qa_lang_html('main/vote'.($vote == 1?'d':'').'_up_popup');
				$down_text = qa_lang_html('main/vote'.($vote == -1?'d':'').'_down_popup');
				
				echo '{"status":"1","data":"'.$votes.'","up":"'.$up_text.'","down":"'.$down_text.'"}';
			} else {
				echo '{"status":"0","data":"'.$voteerror.'"}';
			}

		}
	
		// 'hacked' function
	
		function comment_vote_error_html($post, $userid, $topage)
	/*
		Check if $userid can vote on $post, on the page $topage.
		Return an HTML error to display if there was a problem, or false if it's OK.
	*/
		{
			require_once QA_INCLUDE_DIR.'qa-app-users.php';

			if (
				is_array($post) &&
				qa_opt('permit_vote_c') &&
				( (!isset($post['raw']['userid'])) || (!isset($userid)) || ((int)$post['raw']['userid']!=$userid) )
			) {
				switch (qa_user_permit_error('permit_vote_c', 'V')) {
					case 'login':
						return qa_insert_login_links(qa_lang_html('main/vote_must_login'), $topage);
						break;
						
					case 'confirm':
						return qa_insert_login_links(qa_lang_html('main/vote_must_confirm'), $topage);
						break;
						
					case 'limit':
						return qa_lang_html('main/vote_limit');
						break;
						
					default:
						return qa_lang_html('users/no_permission');
						break;
						
					case false:
						return false;
				}
			
			} else
				return qa_lang_html('main/vote_not_allowed'); // voting option should not have been presented (but could happen due to options change)
		}

		function comment_vote_set($post, $userid, $handle, $cookieid, $vote)
	/*
		Actually set (application level) the $vote (-1/0/1) by $userid (with $handle and $cookieid) on $postid.
		Handles user points, recounting and event reports as appropriate.
	*/
		{
			require_once QA_INCLUDE_DIR.'qa-db-votes.php';

/*
			require_once QA_INCLUDE_DIR.'qa-db-points.php';
			require_once QA_INCLUDE_DIR.'qa-db-hotness.php';
			require_once QA_INCLUDE_DIR.'qa-app-limits.php';
*/		
			$vote=(int)min(1, max(-1, $vote));
			$oldvote=(int)qa_db_uservote_get($post['postid'], $userid);

			qa_db_uservote_set($post['postid'], $userid, $vote);
			qa_db_post_recount_votes($post['postid']);

/*
			
			$postisanswer=($post['basetype']=='A');
			
			$columns=array();
			
			if ( ($vote>0) || ($oldvote>0) )
				$columns[]=$postisanswer ? 'aupvotes' : 'qupvotes';

			if ( ($vote<0) || ($oldvote<0) )
				$columns[]=$postisanswer ? 'adownvotes' : 'qdownvotes';
	
			qa_db_points_update_ifuser($userid, $columns);
			
			qa_db_points_update_ifuser($post['userid'], array($postisanswer ? 'avoteds' : 'qvoteds', 'upvoteds', 'downvoteds'));
			
			if ($post['basetype']=='Q')
				qa_db_hotness_update($post['postid']);
			
			if ($vote<0)
				$action=$postisanswer ? 'a_vote_down' : 'q_vote_down';
			elseif ($vote>0)
				$action=$postisanswer ? 'a_vote_up' : 'q_vote_up';
			else
				$action=$postisanswer ? 'a_vote_nil' : 'q_vote_nil';
*/			
			if ($vote<0)
				$action='c_vote_down';
			elseif ($vote>0)
				$action='c_vote_up';
			else
				$action='c_vote_nil';
					
			//qa_report_write_action($userid, null, $action, null, null, null);

			qa_report_event($action, $userid, $handle, $cookieid, array(
				'postid' => $post['postid'],
				'vote' => $vote,
				'oldvote' => $oldvote,
			));
		}
	}

