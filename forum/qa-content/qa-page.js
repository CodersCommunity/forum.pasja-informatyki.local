/*
	Question2Answer by Gideon Greenspan and contributors

	http://www.question2answer.org/


	File: qa-content/qa-page.js
	Version: See define()s at top of qa-include/qa-base.php
	Description: Common Javascript including voting, notices and favorites


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

function qa_reveal(elem, type, callback)
{
	if (elem)
		$(elem).slideDown(400, callback);
}

function qa_conceal(elem, type, callback)
{
	if (elem)
		$(elem).slideUp(400);
}

function qa_set_inner_html(elem, type, html)
{
	if (elem)
		elem.innerHTML=html;
}

function qa_set_outer_html(elem, type, html)
{
	if (elem) {
		var e=document.createElement('div');
		e.innerHTML=html;
		elem.parentNode.replaceChild(e.firstChild, elem);
	}
}

function qa_show_waiting_after(elem, inside)
{
	if (elem && !elem.qa_waiting_shown) {
		var w=document.getElementById('qa-waiting-template');

		if (w) {
			var c=w.cloneNode(true);
			c.id=null;

			if (inside)
				elem.insertBefore(c, null);
			else
				elem.parentNode.insertBefore(c, elem.nextSibling);

			elem.qa_waiting_shown=c;
		}
	}
}

function qa_hide_waiting(elem)
{
	var c=elem.qa_waiting_shown;

	if (c) {
		c.parentNode.removeChild(c);
		elem.qa_waiting_shown=null;
	}
}

function qa_vote_click(elem)
{
	var ens=elem.name.split('_');
	var postid=ens[1];
	var vote=parseInt(ens[2]);
	var code=elem.form.elements.code.value;
	var anchor=ens[3];

	qa_ajax_post('vote', {postid:postid, vote:vote, code:code},
		function(lines) {
			if (lines[0]=='1') {
				qa_set_inner_html(document.getElementById('voting_'+postid), 'voting', lines.slice(1).join("\n"));

			} else if (lines[0]=='0') {
				var mess=document.getElementById('errorbox');

				if (!mess) {
					var mess=document.createElement('div');
					mess.id='errorbox';
					mess.className='qa-error';
					mess.innerHTML=lines[1];
					mess.style.display='none';
				}

				var postelem=document.getElementById(anchor);
				var e=postelem.parentNode.insertBefore(mess, postelem);
				qa_reveal(e);

			} else
				qa_ajax_error();
		}
	);

	return false;
}

function qa_notice_click(elem)
{
	var ens=elem.name.split('_');
	var code=elem.form.elements.code.value;

	qa_ajax_post('notice', {noticeid:ens[1], code:code},
		function(lines) {
			if (lines[0]=='1')
				qa_conceal(document.getElementById('notice_'+ens[1]), 'notice');
			else if (lines[0]=='0')
				alert(lines[1]);
			else
				qa_ajax_error();
		}
	);

	return false;
}

function qa_favorite_click(elem)
{
	var ens=elem.name.split('_');
	var code=elem.form.elements.code.value;

	qa_ajax_post('favorite', {entitytype:ens[1], entityid:ens[2], favorite:parseInt(ens[3]), code:code},
		function (lines) {
			if (lines[0]=='1')
				qa_set_inner_html(document.getElementById('favoriting'), 'favoriting', lines.slice(1).join("\n"));
			else if (lines[0]=='0') {
				alert(lines[1]);
				qa_hide_waiting(elem);
			} else
				qa_ajax_error();
		}
	);

	qa_show_waiting_after(elem, false);

	return false;
}

function qa_ajax_post(operation, params, callback)
{
	jQuery.extend(params, {qa:'ajax', qa_operation:operation, qa_root:qa_root, qa_request:qa_request});

	jQuery.post(qa_root, params, function(response) {
		var header='QA_AJAX_RESPONSE';
		var headerpos=response.indexOf(header);

		if (headerpos>=0)
			callback(response.substr(headerpos+header.length).replace(/^\s+/, '').split("\n"));
		else
			callback([]);

	}, 'text').fail(function(jqXHR) { if (jqXHR.readyState>0) callback([]) });
}

function qa_ajax_error()
{
	alert('Unexpected response from server - please try again or switch off Javascript.');
}


/* NEW FEATURES */

;(function(document)
{
	'use strict';
	
	/*
	 * Feature: Collapsable blocks of code
	 * Author: ChrissP92 - https://github.com/ChrissP92
	 * Date: 05.07.2016r.
	 */
	
	/*;(*/function handleCodeCollapsing(insidePreview /*document*/)
	{
		'use strict';
		
		/*
		 *	!!!! IMPORTANT VARIABLE !!!!
		 * 
		 * Set number of lines when block of code should be able to collapse (so it's considered as being too long)
		 * 
		 * !!!! IMPORTANT VARIABLE !!!!
		 */
		var numberOfLines = 30;
		
		// languages got from Forum site DOM
		var languages = {
			'brush:as3;' : 'actionscript',
			'brush:applescript;' : 'applescript',
			'brush:bash;' : 'bash-shell',
			'brush:cf;' : 'coldfusion',
			'brush:csharp;' : 'c#',
			'brush:cpp;' : 'C++',
			'brush:css;' : 'css',
			'brush:delphi;' : 'delphi',
			'brush:diff;' : 'diff',
			'brush:erl;' : 'erlang',
			'brush:groovy;' : 'groovy',
			'brush:hx;' : 'haxe',
			'brush:jscript;' : 'javascript',
			'brush:java;' : 'java',
			'brush:javafx;' : 'java-fx',
			'brush:perl;' : 'perl',
			'brush:php;' : 'php',
			'brush:plain;' : 'plain-text',
			'brush:ps;' : 'powershell',
			'brush:python;' : 'python',
			'brush:ruby;' : 'ruby',
			'brush:scss;' : 'sass',
			'brush:scala;' : 'scala',
			'brush:sql;' : 'sql',
			'brush:tap;' : 'tap',
			'brush:ts;' : 'typescript',
			'brush:vb;' : 'vb',
			'brush:xml;' : 'xml-xhtml'
		}
								
		/*function collapseOrExpand()
		{*/
			/*Array.from(document.getElementsByTagName('pre')).forEach(function(elem)
			{
					if (elem.classList.contains())console.log('Found blocks of code: ' , document.querySelectorAll('pre[class*="brush:"]'));
			});*/
			
			
			
			// buttons for actions like: Answer, Comment
			/*var actionBtns = Array.from(document.querySelectorAll('input[name*="_docomment"]'));
			actionBtns.push( document.getElementById('q_doanswer') );
			
			console.log('all action btns: ', actionBtns);*/
			
			var blocks = insidePreview ? Array.from(document.querySelectorAll('.post-preview-parent pre[class*="brush:"]')) : Array.from(document.querySelectorAll('pre[class*="brush:"]'));
			
			console.log('Found blocks of code: ' , blocks, ' true/false ', insidePreview);
			
			// get all <pre> tags which are wrappers for (CKEditor) code and loop them
			/*Array.from(document.querySelectorAll('.post-preview-parent pre[class*="brush:"]')*/blocks/* document.getElementsByTagName('pre')*/.forEach(function(block)
			{						
				// set each block attribute 'data-lang' to let CSS add :after pseudo elements with language name written inside block
				block.setAttribute('data-lang', languages[block.classList[0]]);
				
				// when code-block has new lines and their number is greater than maximum number of lines before being collapsed
				if (block.innerHTML.indexOf('\n') > -1 && block.innerHTML.match(/\n/g).length + 1 >= numberOfLines)
				{
					// add CSS class to do some styling
					block.classList.add('collapsed');
					// add attribute to let CSS :before pseudo element set it's content to appropriate state of code-block
					block.setAttribute('data-state', '-- Rozwiń --');

					// when user clicks on code-block
					block.addEventListener('click', function(ev)
					{					
						/*
						 * when block-code is collapsed or not - change <pre> attribute and add/remove CSS class
						 * to notify user the state of code-block
						 */
						if (block.classList.contains('collapsed'))
						{
							block.classList.remove('collapsed');
							block.setAttribute('data-state', '-- Zwiń --');
						}
						else
						{
							block.classList.add('collapsed');
							block.setAttribute('data-state', '-- Rozwiń --');
						}
					});
				}
				
				console.log('Collapsed: ', block);
			});
		/*}
		
		if (insidePreview)
		{
			collapseOrExpand();
		}
		
		else
		{
			// when DOM is ready invoke function, which will prepare blocks to collapse/expand
			////window.addEventListener('DOMContentLoaded', collapseOrExpand);
			collapseOrExpand();
		}*/
		
	}////(document));
	
	/*
	 * Feature: Post content preview as Modal
	 * Author: ChrissP92 - https://github.com/ChrissP92
	 * Date: 07.07.2016r.
	 */
	 
	/*;(*/function postPreview(ckeCurrentInstance, placeForBtn/*document*/)
	{
		'use strict';
		
		/*window.addEventListener('DOMContentLoaded', function()
		{*/
			// get <div> and set it as Modal parent
			var modalParent = document.querySelector('.qa-main-wrapper');
			
			var showModalBtn = document.createElement('button');
			var modalBackground = document.createElement('div');

			modalBackground.classList.add('modal-background');
			
			showModalBtn.id = 'get-content-preview';
			showModalBtn.innerHTML = 'Podgląd posta';
			showModalBtn.classList.add('qa-form-tall-button', 'get-content-preview');
			
			if (placeForBtn)
			{				
				placeForBtn.appendChild(showModalBtn);
				
				console.log('[RESPONSE preview] ', placeForBtn);
			}			
			
			else
			{
				console.log('[ASK preview]');
				
				document.querySelector(/*'.qa-form-tall-buttons [value="Odpowiedz"], */'.qa-form-tall-buttons [value="Zadaj pytanie"]').parentNode.appendChild(showModalBtn);
			}
			
			////console.log('preview btn?: ', showModalBtn);
			
			function modalEventHandler(modalWrapper, closeBtn)
			{
				function hideModal(ev)
				{
					var parent = modalWrapper.parentNode;
					
					console.log('modal: ', ev.target);
					
					closeBtn.removeEventListener('click', hideModal);
					
					document.body.removeChild(modalBackground);
					parent.removeChild(modalWrapper);
				}
				
				closeBtn.addEventListener('click', hideModal);
			}

			
			showModalBtn.addEventListener('click', function(ev)
			{			
				////console.log('prevented?');
				
				ev.preventDefault();
				
				var modal = document.getElementById('content-preview-parent');
				
				if (!modal)
				{
					var modal = document.createElement('div');
					var modalContent = document.createElement('div');
					var closeModalBtn = document.createElement('button');
					var ckeFullInstanceName;

					if (ckeCurrentInstance)
						ckeFullInstanceName = ckeCurrentInstance + '_content';
					else
						ckeFullInstanceName = Object.keys(CKEDITOR.instances)[0];
					
					////modalWrapper.id = 'content-preview-parent';
					
					modal.classList.add('post-preview-parent');
					
					console.log('[Current instance] / only first / correct full: ', JSON.stringify(ckeCurrentInstance), '/', JSON.stringify(Object.keys(CKEDITOR.instances)[0]), '/', JSON.stringify(ckeFullInstanceName));
					
					// get current CKEditor content (provided by it's API) and insert it to <div>
					modalContent.innerHTML = CKEDITOR.instances[/*Object.keys(CKEDITOR.instances)[0]*/ /*ckeCurrentInstance*/ ckeFullInstanceName].getData();
					modalContent.classList.add('post-preview');
					
					closeModalBtn.innerHTML = 'X';
					closeModalBtn.classList.add('close-preview-btn');
					
					// invoke function and pass it Modal, then it can be possible to remove Modal as well as it's eventListener
					modalEventHandler(modal, closeModalBtn);
					
					document.body.insertBefore(modalBackground, document.body.firstChild);
					modal.appendChild(closeModalBtn);
					
					modal.appendChild(modalContent);
					modalParent.appendChild(modal);
					
					console.log('Post preview content: ', modal);
					
					/*
					 * prepare blocks of code inside Preview to be collapsed/expanded
					 * "true" parameter lets to display collapsing blocks inside Preview Modal
					 */
					handleCodeCollapsing(true);
				}
			});
		/*});*/
	}////(document));
	
	// run function, which maintain blocks of code collapsing and expanding
	////handleCodeCollapsing();
	window.addEventListener('DOMContentLoaded', function()
	{	
		// find number in URL - so it's sure that topic is being viewed/opened
		var url = location.pathname.split('/')/*; 
		/*var idx = url*/.findIndex(function(elem)
		{
			return Number(elem);
		}); 
		
		function addListener(ev)
		{
			////console.log('btn Parent: ', ev.target, '/' , ev.target.parentNode);
			
			checkCkeditor(ev.target);
		}
		
		function checkCkeditor(btnLocation, ask)
		{
			// CKEDITOR's API event, which indicates that editor is loaded
			CKEDITOR.on("instanceReady", function(ev)
			{			 
				if (btnLocation)
				{
					////console.log('CLICKED BUTTON: ', btnLocation);
				 
					var prepareCkeInstance = /*document.querySelector('[name="q_docomment"]')*/btnLocation.getAttribute('onclick');
					var ckeInstanceName = prepareCkeInstance.slice(prepareCkeInstance.indexOf('(') + 2, -2);
					var ckeInstanceParent;
					var ckeInstanceDom;
					var previewBtnLocation;
					
					if (ckeInstanceName === 'anew')
						ckeInstanceName = 'a';
					
					////console.log('Searching for ckeditor: ', ckeInstanceName);
					
					////ckeInstanceDom = document.querySelector('iframe[title*="Edytor tekstu sformatowanego, ' + ckeInstanceName + '"]');
					
					ckeInstanceParent = Array.from(document.querySelectorAll('.qa-form-tall-table')).find(function(elem)
					{
						return elem.querySelector('iframe[title*="Edytor tekstu sformatowanego, ' + ckeInstanceName + '"]');
					});
					
					previewBtnLocation = ckeInstanceParent.querySelector('.qa-form-tall-buttons');
					
					console.log('CKEditor is ready... ', ckeInstanceName, '/ DOM /' , ckeInstanceDom, '/ Parent /', previewBtnLocation);
					 
					postPreview(ckeInstanceName, previewBtnLocation);
				}
				
				else postPreview();
			});
		}
		
		// when URL contains number - so user is on topic subsite (not on main or other forum subsite nor asking the new question)
		if (/*idx*/ url > 0)
		{			
			// buttons for actions like: Answer, Comment
			var actionBtns = Array.from(document.querySelectorAll('input[name*="_docomment"]'));
			actionBtns.push( document.getElementById('q_doanswer') );
			
			////console.log('all action btns: ', actionBtns);
						
			handleCodeCollapsing();
			
			actionBtns.forEach(function(btn)
			{
				btn.addEventListener('click', addListener);
			});
		}
		
		// when user is creating new question
		else if (location.pathname.indexOf('ask') > 0)
		{
			checkCkeditor(false, true);
		}
		else console.error('Unpredicted Forum URL: ', location.pathname);
		
	});
	
}(document));