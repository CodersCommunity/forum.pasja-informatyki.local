/*
	Question2Answer by Gideon Greenspan and contributors

	http://www.question2answer.org/


	File: qa-content/qa-question.js
	Version: See define()s at top of qa-include/qa-base.php
	Description: Javascript to handle question page actions


	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/

var qa_element_revealed=null;

function qa_toggle_element(elem)
{
	var e=elem ? document.getElementById(elem) : null;

	if (e && e.qa_disabled)
		e=null;

	if (e && (qa_element_revealed==e)) {
		qa_conceal(qa_element_revealed, 'form');
		qa_element_revealed=null;

	} else {
		if (qa_element_revealed)
			qa_conceal(qa_element_revealed, 'form');

		if (e) {
			if (e.qa_load && !e.qa_loaded) {
				e.qa_load();
				e.qa_loaded=true;
			}

			if (e.qa_show)
				e.qa_show();

			qa_reveal(e, 'form', function() {
				var t=$(e).offset().top;
				var h=$(e).height()+16;
				var wt=$(window).scrollTop();
				var wh=$(window).height();

				if ( (t<wt) || (t>(wt+wh)) )
					qa_scroll_page_to(t);
				else if ((t+h)>(wt+wh))
					qa_scroll_page_to(t+h-wh);

				if (e.qa_focus)
					e.qa_focus();
			});
		}

		qa_element_revealed=e;
	}

	return !(e||!elem); // failed to find item
}

function qa_submit_answer(questionid, elem)
{
	var params=qa_form_params('a_form');
	params.a_questionid=questionid;

	qa_ajax_post('answer', params, function(lines) {
			if (lines[0]=='1') {
				if (lines[1]<1) {
					var b=document.getElementById('q_doanswer');
					if (b)
						b.style.display='none';
				}

				const answerListTitle = document.getElementById('a_list_title');
				qa_set_inner_html(answerListTitle, 'a_list_title', lines[2]);
				qa_reveal(answerListTitle, 'a_list_title');

				const answerWrapper = document.createElement('div');
				answerWrapper.innerHTML = lines.slice(3).join("\n");

				const answerItem = answerWrapper.firstChild;
				answerItem.style.display='none';

				const answerList = document.getElementById('a_list');
				answerList.insertBefore(answerItem, answerList.firstChild);

				const answerFormParent = document.getElementById('anew');
				answerFormParent.qa_disabled=true;

				qa_conceal(answerFormParent, 'form');
				qa_reveal(answerItem, 'answer', () => {
					if (typeof window.reloadBlocksOfCode === 'function') {
						window.reloadBlocksOfCode(answerItem);
					}
				});
			} else if (lines[0]=='0') {
				const [, answerRefuseReason ] = lines;

				if (answerRefuseReason === 'ALREADY_CLOSED' || answerRefuseReason === 'UNAVAILABLE') {
					window.handleRefusedPost({
						postType: 'answer',
						postRefuseReason: answerRefuseReason,
						submitButton: elem
					});
				} else {
					document.forms['a_form'].submit();
				}
			} else {
				qa_ajax_error();
			}
		}
	);

	qa_show_waiting_after(elem, false);

	return false;
}

function qa_submit_comment(questionid, parentid, elem) {
	const params = qa_form_params(`c_form_${ parentid }`);
	params.c_questionid = questionid;
	params.c_parentid = parentid;
	params.last_comment_id = getLastCommentId();

	qa_ajax_post('comment', params, onSuccess);
	qa_show_waiting_after(elem, false);

	function onSuccess(lines) {
		if (lines[0] == '1') {
			const updatedCommentsList = updateCommentsList(lines);
			updateFormState();
			revealNewComment(lines[1])
				.then(() => {
					if (typeof window.reloadBlocksOfCode === 'function') {
						window.reloadBlocksOfCode(updatedCommentsList);
					}
				});
		} else if (lines[0] == '0') {
			const [, commentRefuseReason] = lines;

			if (commentRefuseReason === 'UNAVAILABLE') {
				window.handleRefusedPost({
					postType: 'comment',
					postRefuseReason: commentRefuseReason,
					parentId: parentid,
					parentPostIsQuestion: questionid === parentid,
					submitButton: elem
				});
			} else {
				document.forms['c_form_' + parentid].submit();
			}
		} else {
			qa_ajax_error();
		}
	}

	function getLastCommentId() {
		const lastCommentFromList = document
			.querySelector(`#c${ params.c_parentid }_list .qa-c-list-item:last-of-type`);
		const lastCommentId = lastCommentFromList && lastCommentFromList.id.slice(1);

		return lastCommentId;
	}

	function updateCommentsList(lines) {
		const commentsList = document.getElementById(`c${ parentid }_list`);
		const newComments = lines.slice(2).join('\n');

		if (params.last_comment_id) {
			const newCommentsTempParent = document.createElement('div');
			newCommentsTempParent.innerHTML = newComments;

			const newestComment = newCommentsTempParent.querySelector('.qa-c-list-item.comment:last-of-type');
			if (newestComment) {
				newestComment.style.display = 'none';
			}

			commentsList.append(...newCommentsTempParent.children);
		} else {
			commentsList.innerHTML = newComments;
		}

		commentsList.style.display = '';

		return commentsList;
	}

	function updateFormState() {
		const commentForm = document.getElementById(`c${ parentid }`);
		commentForm.qa_disabled = true;
		qa_conceal(commentForm, 'form');
	}

	function revealNewComment(commentId) {
		const addedComment = document.getElementById(commentId);
		if (addedComment) {
			addedComment.style.display = 'none';

			return new Promise((onCommentShown) => {
				qa_reveal(addedComment, 'comment', onCommentShown);
			});
		}
	}

	return false;
}

function qa_answer_click(answerid, questionid, target)
{
	var params={};

	params.answerid=answerid;
	params.questionid=questionid;
	params.code=target.form.elements.code.value;
	params[target.name]=target.value;

	qa_ajax_post('click_a', params,
		function (lines) {
			if (lines[0]=='1') {
				qa_set_inner_html(document.getElementById('a_list_title'), 'a_list_title', lines[1]);

				var l=document.getElementById('a'+answerid);
				var h=lines.slice(2).join("\n");

				if (h.length)
					qa_set_outer_html(l, 'answer', h);
				else
					qa_conceal(l, 'answer');

			} else {
				target.form.elements.qa_click.value=target.name;
				target.form.submit();
			}
		}
	);

	qa_show_waiting_after(target, false);

	return false;
}

function qa_comment_click(commentid, questionid, parentid, target)
{
	var params={};

	params.commentid=commentid;
	params.questionid=questionid;
	params.parentid=parentid;
	params.code=target.form.elements.code.value;
	params[target.name]=target.value;

	qa_ajax_post('click_c', params,
		function (lines) {
			if (lines[0]=='1') {
				var l=document.getElementById('c'+commentid);
				var h=lines.slice(1).join("\n");

				if (h.length)
					qa_set_outer_html(l, 'comment', h)
				else
					qa_conceal(l, 'comment');

			} else {
				target.form.elements.qa_click.value=target.name;
				target.form.submit();
			}
		}
	);

	qa_show_waiting_after(target, false);

	return false;
}

function qa_show_comments(questionid, parentid, elem)
{
	var params={};

	params.c_questionid=questionid;
	params.c_parentid=parentid;

	qa_ajax_post('show_cs', params,
		function (lines) {
			if (lines[0]=='1') {
				var commentsList=document.getElementById('c'+parentid+'_list');
				commentsList.innerHTML=lines.slice(1).join("\n");

				if (typeof window.reloadBlocksOfCode === 'function') {
					window.reloadBlocksOfCode(commentsList);
				}

				commentsList.style.display='none';
				qa_reveal(commentsList, 'comments');
			} else {
				qa_ajax_error();
			}
		}
	);

	qa_show_waiting_after(elem, true);

	return false;
}

function qa_form_params(formname)
{
	var es=document.forms[formname].elements;
	var params={};

	for (var i=0; i<es.length; i++) {
		var e=es[i];
		var t=(e.type || '').toLowerCase();

		if ( ((t!='checkbox') && (t!='radio')) || e.checked)
			params[e.name]=e.value;
	}

	return params;
}

function qa_scroll_page_to(scroll)

{
	$('html,body').animate({scrollTop: scroll}, 400);
}

function handleRefusedPost({ submitButton, postRefuseReason, postType, parentId, parentPostIsQuestion }) {
	submitButton.disabled = true;

	hideEmailNotificationOption();
	showSubmissionError();
	qa_hide_waiting(submitButton);

	function showSubmissionError() {
		const postSubmissionError = document.createElement('div');
		postSubmissionError.innerHTML = getSubmissionErrorContent();
		postSubmissionError.classList.add('post-submission-alert');

		submitButton.parentNode.insertBefore(postSubmissionError, submitButton);
	}

	function hideEmailNotificationOption() {
		const formNotifyElementName = parentId ? `c${ parentId }_notify` : 'a_notify';
		const emailNotificationOption = submitButton.form.elements[formNotifyElementName].parentNode.parentNode;
		emailNotificationOption.remove();
	}

	function getSubmissionErrorContent() {
		const postErrorDescription = postType === 'comment' ?
			'Nie można dodać komentarza.' :
			'Nie można dodać odpowiedzi.';
		const submissionErrorContent = {
			ALREADY_CLOSED: 'Nie można dodać odpowiedzi, ponieważ pytanie zostało zamknięte.<br>Jeśli chcesz, to dodaj swój post w formie komentarza.',
			UNAVAILABLE: `${ postErrorDescription } ${ getPostRelationDescription() }`
		};

		return submissionErrorContent[postRefuseReason];
	}

	function getPostRelationDescription() {
		if (postType === 'comment') {
			return parentPostIsQuestion ? 'Pytanie nie jest już dostępne.' : 'Odpowiedź nie jest już dostępna.';
		} else {
			return 'Temat nie jest już dostępny.';
		}
	}
}
