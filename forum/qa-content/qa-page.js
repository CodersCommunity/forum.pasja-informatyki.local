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



 /*	Feature: inform user about marking best answer, when he wants to close a topic
 */
 ;(function(document)
 {
	 'use strict';

	 if (location.href.indexOf('state=close') > 0)
 	{
		window.addEventListener('DOMContentLoaded', function()
		{
			var parent = document.querySelector('.qa-c-form .qa-form-tall-table tbody');
			var last = document.querySelector('.qa-c-form .qa-form-tall-table tbody tr:last-child');
			var informParent = document.createElement('tr');
			var inform  = document.createElement('td');

			inform.innerHTML = 'Jeśli otrzymałeś odpowiedź, która rozwiązała Twój problem - oznacz ją jako <span class="closing-topic-info-bold">"najlepsza"</span>. Pomoże to odwiedzającym ten temat znaleźć rozwiązanie opisanego problemu.';

			inform.classList.add('closing-topic-info');
			informParent.appendChild(inform);

			parent.insertBefore(informParent, last);
		});
	}
 }(document));

 /* ////////////////////
 *
 * NEW FEATURES
 *
 * ////////////////////
 */
;(function(document)
{
	'use strict';

	// check if browser supports 'select()' and 'copy' commands
	var isClipboardSupported = (window.getSelection && document.queryCommandSupported('copy') && navigator.userAgent.indexOf('Firefox') < 0);

	/*
	 * Feature: preview HTML/CSS/JavaScript code from chosen post in codepen.io / jsfiddle.net
	 */
	 function viewHtmlCssJs()
	 {
		/*
		 * Adapted code from Codepen API tutorial: https://blog.codepen.io/documentation/api/prefill/
		 */
		function createCodepenSnippet(codepenData)
		{
			var codeAsJSON = JSON.stringify(codepenData)
				// Quotes will screw up the JSON
				.replace(/"/g, "&​quot;") // careful copy and pasting, I had to use a zero-width space here to get markdown to post this.
				.replace(/'/g, "&apos;");

			var codepenSnippetForm = document.createElement('form');
			codepenSnippetForm.action = 'https://codepen.io/pen/define';
			codepenSnippetForm.method = 'POST';
			codepenSnippetForm.target= '_blank';
			codepenSnippetForm.classList.add('codepen-snippet');

			var dataCarrierInput = document.createElement('input');
			dataCarrierInput.type = 'hidden';
			dataCarrierInput.name = 'data';
			dataCarrierInput.value = codeAsJSON;

                        var submitSnippet = document.createElement('input');
                        submitSnippet.type = 'submit';
                        submitSnippet.value = 'CODEPEN';

			codepenSnippetForm.appendChild(dataCarrierInput);
			codepenSnippetForm.appendChild(submitSnippet);

			return codepenSnippetForm;
		}

		/*
		 * Adapted code from JSFiddle API tutorial: http://doc.jsfiddle.net/api/post.html
		 */
		function createJsfiddleSnippet(jsfiddleData)
		{
			var jsfiddleSnippetForm = document.createElement('form');
			jsfiddleSnippetForm.action = 'https://jsfiddle.net/api/post/library/pure/';
			jsfiddleSnippetForm.method = 'POST';
			jsfiddleSnippetForm.target = '_blank';
			jsfiddleSnippetForm.classList.add('jsfiddle-snippet');

			var htmlTxt = document.createElement('textarea');
			htmlTxt.name = 'html';
			htmlTxt.value = jsfiddleData.html || '';

			var cssTxt = document.createElement('textarea');
			cssTxt.name = 'css';
			cssTxt.value = jsfiddleData.css || '';

			var jsTxt = document.createElement('textarea');
			jsTxt.name = 'js';
			jsTxt.value = jsfiddleData.js || '';

                        var selectHTML = document.createElement('select');
                        selectHTML.name = 'panel_html';
			var selectCSS = document.createElement('select');
                        selectCSS.name = 'panel_css';
                        var selectJS = document.createElement('select');
                        selectJS.name = 'panel_js';

                        var htmlVersion = document.createElement('option');
                        htmlVersion.value = 0;
                        htmlVersion.textContent = 'HTML';
                        htmlVersion.setAttribute('selected', 'selected');

                        var cssCleanVersion = document.createElement('option');
                        cssCleanVersion.value = 0;
                        cssCleanVersion.textContent = 'CSS';
                        cssCleanVersion.setAttribute('selected', 'selected');
                        var cssPreProcessorVersion = document.createElement('option');
                        cssPreProcessorVersion.value = 1;
                        cssPreProcessorVersion.textContent = 'SCSS';

                        var jsCleanVersion = document.createElement('option');
                        jsCleanVersion.value = 0;
                        jsCleanVersion.textContent = 'JavaScript';
                        jsCleanVersion.setAttribute('selected', 'selected');
                        var jsCoffeeVersion = document.createElement('option');
                        jsCoffeeVersion.value = 1;
                        jsCoffeeVersion.textContent = 'CoffeeScript';
                        var jsOldVersion = document.createElement('option');
                        jsOldVersion.value = 2;
                        jsOldVersion.textContent = 'JavaScript 1.7';

			var submitSnippet = document.createElement('input');
			submitSnippet.type = 'submit';
			submitSnippet.value = 'JSFIDDLE';

                        selectHTML.appendChild(htmlVersion);
                        selectCSS.appendChild(cssCleanVersion);
                        selectCSS.appendChild(cssPreProcessorVersion);
                        selectJS.appendChild(jsCleanVersion);
                        selectJS.appendChild(jsCoffeeVersion);
                        selectJS.appendChild(jsOldVersion);

                        jsfiddleSnippetForm.appendChild(selectHTML);
                        jsfiddleSnippetForm.appendChild(selectCSS);
                        jsfiddleSnippetForm.appendChild(selectJS);
			jsfiddleSnippetForm.appendChild(submitSnippet);

			jsfiddleSnippetForm.appendChild(htmlTxt);
			jsfiddleSnippetForm.appendChild(cssTxt);
			jsfiddleSnippetForm.appendChild(jsTxt);

			return jsfiddleSnippetForm;
		}

		// add Codepen and JSFiddle snippets buttons to each post/comment, which has HTML/CSS/JavaScript code inside blocks
		function addSnippets(data, parent)
		{
			var codepenSnippet = createCodepenSnippet(data);
			var jsfiddleSnippet = createJsfiddleSnippet(data);

			var snippetsParent = document.createElement('div');
			snippetsParent.classList.add('snippets-parent');
			snippetsParent.appendChild(codepenSnippet);
			snippetsParent.appendChild(jsfiddleSnippet);

			parent.appendChild(snippetsParent);
			if ( parent.classList.contains( 'qa-c-item-content' ) )
			{
				snippetsParent.classList.add( 'inside-comment' );
				snippetsParent.parentNode.querySelector('.entry-content').classList.add('below-snippets');
			}
		}

		var posts = Array.from(document.querySelectorAll('.entry-content'));

		posts.forEach(function(post)
		{
		   var blockOfCodeParents = post.querySelectorAll('.syntaxhighlighter-parent');
		   var canAddSnippets = true;

		   if (blockOfCodeParents.length)
		   {
				 var blocksInPost = {};
				 var data = {};
				 var htmlCode = '';
				 var cssCode = '';
				 var jsCode = '';
				 var parent = Array.from(blockOfCodeParents)[0].parentNode.parentNode;

				 Array.from(blockOfCodeParents).forEach(function(block)
				 {
					var code = '';

					Array.from(block.querySelectorAll('.code .line')).forEach(function(line)
					{
						code += line.textContent + '\r\n';
					});

					blocksInPost[block.firstElementChild.nextSibling.classList[1]] = code;
				 });

				 Object.keys(blocksInPost).forEach(function(language)
				 {
					switch (language)
					{
						case 'css' : cssCode += blocksInPost.css;
                                    data.css = cssCode;
                                    break;
						case 'xml' : htmlCode += blocksInPost.xml;
                                    data.html = htmlCode;
                                    break;
						case 'jscript' : jsCode += blocksInPost.jscript;
                                        data.js = jsCode;
                                    break;
						default : canAddSnippets = false;
                                    break;
					}
				 });

				 if (canAddSnippets)
					 addSnippets(data, parent);
		   }
		});
	 }

	/*
	 *	Feature: copy code from code-block to clipboard on button click - then user can paste it wherever he wants into
	 */
	function copyToClipboard(ev)
	{
		// prevent page refresh (or something weird) as default button action
		ev.preventDefault();
		if ( !ev.defaultPrevented ) { return false; }

		var code = [];
		var t = ev.target;
		var blockOfCodeBar = t.parentNode.parentNode;

		// get block of code content - practically all lines of code inside
		Array.from(blockOfCodeBar.querySelector('.code .container').children).forEach(function(lineOfCode)
		{
			code.push(lineOfCode.textContent);
		});

		/*
		 * In order to be able to copy the code inside block into the clipboard - so user can easily paste it wherever he wants - within single button click
		 * a code must be first selected (or highlighted in human meaning), so JavaScript can copy it.
		 * However selecting is only possible on HTML elements that are 'inputs', such as <textarea>.
		 * That's why below code creates <textarea> (which has 'display: none'; in CSS) and inserts there code content from block,
		 * copy it to clipboard and removes it (so user can't really see temporary <textarea> element appears) after whole process.
		 */
		var textArea = document.createElement("textarea");
		textArea.classList.add('content-copy');

		code.forEach(function(singleLineOfCode)
		{
			textArea.value += singleLineOfCode + '\r\n';
		});

		document.body.appendChild(textArea);

		// if anything on the page is selected (a.k.a highlighted) - clear the selection
		if (window.getSelection().rangeCount)
			window.getSelection().removeAllRanges();

		/*
		 * Below code will select given DOM elements
		 * and create Range Object, so that text content can be selected (a.k.a highlighted)
		 * Modified script from source: http://stackoverflow.com/a/1173319/4983840
		 */
		var range = document.createRange();
		range.selectNode( textArea );
		window.getSelection().addRange(range);

		// copy content that is select inside Document - so that is only textarea
		document.execCommand('copy');

		// remove <textarea> from DOM
		document.body.removeChild(textArea);
	}


	/*
	 * Feature: Collapsable blocks of code
	 * Date: 05.07.2016r.
	 */
	function handleCodeCollapsing(insidePreview, addCopyBtn)
	{
		/*
		 * !!!! IMPORTANT VARIABLE !!!!
		 *
		 * Set number of lines when block of code should be able to collapse (so it's considered as being too long)
		 *
		 * !!!! IMPORTANT VARIABLE !!!!
		 */
		var numberOfLines = 30;

		// languages got from Forum site DOM
		var languages = {
			'as3' : 'actionscript',
			'applescript' : 'applescript',
			'bash' : 'bash-shell',
			'cf' : 'coldfusion',
			'csharp' : 'C#',
			'cpp' : 'C/C++',
			'css' : 'CSS',
			'delphi' : 'delphi',
			'diff' : 'diff',
			'erl' : 'erlang',
			'groovy' : 'groovy',
			'jscript' : 'JavaScript',
			'java' : 'Java',
			'javafx' : 'Java-FX',
			'perl' : 'perl',
			'php' : 'PHP',
			'plain' : 'plain-text',
			'ps' : 'powershell',
			'python' : 'Python',
			'ruby' : 'Ruby',
			'scss' : 'SASS',
			'scala' : 'scala',
			'sql' : 'SQL',
			'tap' : 'tap',
			'vb' : 'VB',
			'xml' : 'XML-xHTML'
		};

		var blocks = insidePreview ? Array.from(document.querySelectorAll('.post-preview-parent .syntaxhighlighter')) : Array.from(document.querySelectorAll('.syntaxhighlighter'));

		// when 'blocks' are still unavailable - it probably is happening on /ask page (with preview modal displayed). Then check for <pre> tags
		if (!blocks.length)
			blocks = Array.from(document.querySelectorAll('pre[class*="brush:"]'));

		blocks.forEach(function(block)
		{
			var blockBar = document.createElement('div');
			var blockButton = document.createElement('button');
			var languageName = document.createElement('div');
			var copyCodeBtn = document.createElement('button');

			blockBar.classList.add('syntaxhighlighter-block-bar');

            languageName.classList.add('syntaxhighlighter-language');

            /*
             * Check number of lines of code inside block and compare it with maximum set accepted number - collapse block when it's greater than max.
             */
            var isLongCodeAtReply = block.querySelectorAll('.line').length >= numberOfLines;
            var isLongCodeAtAsk = (block.innerHTML.indexOf('\n') > -1 && block.innerHTML.match(/\n/g).length + 1 >= numberOfLines);

			if (isLongCodeAtReply || isLongCodeAtAsk)
			{
				blockButton.classList.add('syntaxhighlighter-button');
				blockButton.textContent = '-- Rozwiń --';

				block.classList.add('collapsed-block');

				blockButton.addEventListener('click', function(ev)
				{
					// prevent... dummy (refresh page) default action of button
					ev.preventDefault();

					/*
					* when block-code is collapsed or not - write info on button and add/remove CSS class
					* to notify user the state of code-block
					*/
					if (block.classList.contains('collapsed-block'))
					{
						block.classList.remove('collapsed-block');
						blockButton.textContent = '-- Zwiń --';
					}
					else
					{
						block.classList.add('collapsed-block');
						blockButton.textContent = '-- Rozwiń --';
					}
				});

				blockBar.appendChild(blockButton);
			}

			// based on each code-block CSS class - find out what language is used inside it
            languageName.textContent = languages[block.classList[1]] || languages[block.classList[0].slice(block.classList[0].indexOf(':') + 1, -1)];

			blockBar.appendChild(languageName);

			copyCodeBtn.setAttribute('type', 'button');
			copyCodeBtn.textContent = 'Kopiuj';
			copyCodeBtn.classList.add('content-copy-btn');

			if (addCopyBtn && window.hasOwnProperty('SyntaxHighlighter'))
				copyCodeBtn.addEventListener('click', copyToClipboard);
			else
				copyCodeBtn.classList.add('content-copy-btn-disabled');

			blockBar.appendChild(copyCodeBtn);

			block.parentNode.classList.add('syntaxhighlighter-parent');
			block.parentNode.insertBefore(blockBar, block);

		});
	}

	/*
	 * Feature: Post content preview as Modal
	 * Date: 07.07.2016r.
	 */
	function postPreview(ckeCurrentInstance, placeForBtn)
	{
		var modalParent = document.querySelector('.qa-main-wrapper');

		var showModalBtn = document.createElement('button');

		var modalBackground = document.createElement('div');
		modalBackground.classList.add('modal-background');

		showModalBtn.id = 'get-content-preview';
		showModalBtn.innerHTML = 'Podgląd posta';
		showModalBtn.classList.add('qa-form-tall-button', 'get-content-preview');

		if (!placeForBtn)
		{
			var alternativePlaceForBtn = document.querySelector('.qa-form-tall-buttons [value="Zadaj pytanie"]')
                                        || document.querySelector('.qa-form-tall-buttons [value="Zapisz"]')
                                        || document.querySelector('.qa-form-tall-buttons [value="Odpowiedz"]');
			alternativePlaceForBtn.parentNode.appendChild(showModalBtn);
		} else if ( placeForBtn.querySelector( '#' + showModalBtn.id ) )
			return;
		else if (placeForBtn)
			placeForBtn.appendChild(showModalBtn);

		function modalEventHandler(modalWrapper, closeBtn)
		{
			function hideModal()
			{
				var modalWrapperParent = modalWrapper.parentNode;

				closeBtn.removeEventListener('click', hideModal);
				modalBackground.removeEventListener('click', hideModal);

				document.body.removeChild(modalBackground);
                                modalWrapperParent.removeChild(modalWrapper);
			}

			// close Modal on btn click
			closeBtn.addEventListener('click', hideModal);
			// close Modal on background click
			modalBackground.addEventListener('click', hideModal);
		}

		showModalBtn.addEventListener('click', function(ev)
		{
			ev.preventDefault();

			var modal = document.getElementById('content-preview-parent');

			if (!modal)
			{
				var modalContent = document.createElement('div');
				var closeModalBtn = document.createElement('button');
				var ckeFullInstanceName = ckeCurrentInstance ? ckeCurrentInstance + '_content' : Object.keys(CKEDITOR.instances)[0];

                                modal = document.createElement('div');

				modal.classList.add('post-preview-parent');

				// get current CKEditor content (provided by it's API) and insert it to <div>
				modalContent.innerHTML = CKEDITOR.instances[ckeFullInstanceName].getData();
				modalContent.classList.add('post-preview');

				closeModalBtn.innerHTML = 'X';
				closeModalBtn.classList.add('close-preview-btn');

				// invoke function and pass it Modal, then it can be possible to remove Modal as well as it's eventListener
				modalEventHandler(modal, closeModalBtn);

				document.body.insertBefore(modalBackground, document.body.firstChild);
				modal.appendChild(closeModalBtn);

				modal.appendChild(modalContent);
				modalParent.appendChild(modal);

				if (window.hasOwnProperty('SyntaxHighlighter'))
				    SyntaxHighlighter.highlight();

				/*
				 * prepare blocks of code inside Preview to be collapsed/expanded
				 * "true" parameter lets to display collapsing blocks inside Preview Modal
				 */
				handleCodeCollapsing(true, isClipboardSupported);
			}
		});
	}

	// when Forum (sub)page DOM with it's CSSes and synchronously loaded scripts (excluding CKEDITOR, which needs separate Event Handling) are ready
	window.addEventListener('load', function()
	{
		function addListener(ev)
		{
			checkCkeditor(ev.target);
		}

		function checkCkeditor(btnLocation)
		{
			/*
			 * Explicit CKEDITOR EventHandling
			 * When editor is available: get it's instance, then get place for preview-button location based on it.
			 */
			CKEDITOR.on("instanceReady", function()
			{
				if (btnLocation)
				{
					var prepareCkeInstance = btnLocation.getAttribute('onclick');
					var ckeInstanceName = prepareCkeInstance.slice(prepareCkeInstance.indexOf('(') + 2, -2);

					if (ckeInstanceName === 'anew')
						ckeInstanceName = 'a';

					var ckeInstanceParent = Array.from(document.querySelectorAll('.qa-form-tall-table')).find(function(elem)
					{
						return elem.querySelector('iframe[title*="Edytor tekstu sformatowanego, ' + ckeInstanceName + '"]');
					});

					var previewBtnLocation = ckeInstanceParent.querySelector('.qa-form-tall-buttons');

					postPreview(ckeInstanceName, previewBtnLocation);
				}
				else
					postPreview();
			});
		}

        /*
         * In the following Forum link example: "http://forum.pasja-informatyki.pl/153635/pracujmy-razem-nad-kodem-forum"
         * , the number between slashes (as above it's: "/153635/") allows to be sure, that opened subpage is displaying some Topic.
         * So to recognize if opened page is Topic indeed - let's find number in URL
         */
        var numFoundInLink = location.pathname.split('/').findIndex(function(elem)
        {
            return Number(elem);
        });

        var inTopic = numFoundInLink > 0;
        var inPostEdit = location.href.indexOf('state=') > -1;
        var inCreatingQuestion = location.pathname.indexOf('ask') > -1;

		if (inTopic && !inPostEdit)
		{
			// prepare Array for actions like: Answer, Comment, Edit
			var actionBtns = [];

			var mainAnswerBtn = document.getElementById('q_doanswer');
			/** Be sure there is answer button. It's not available when topic is closed */
			if ( mainAnswerBtn )
				actionBtns.push( mainAnswerBtn );

			Array.from(document.querySelectorAll('input[name*="_docomment"]')).forEach(function(comment)
			{
				actionBtns.push( comment );
			});

			Array.from(document.querySelectorAll('input[name*="_doedit"]')).forEach(function(edit)
			{
				actionBtns.push( edit );
			});

			/*
			 * 1st argument notifies function that the page is not /ask.html - so different blocks of code collapsing method will be used
			 * 2nd parameter notifies function if it can "turn on" Copy To Clipboard function - so user can copy code inside block within button click
			 */
			handleCodeCollapsing(false, isClipboardSupported);

			var foundAnyAnswerInTopic = document.querySelector('.answer');

                        if (!foundAnyAnswerInTopic)
				checkCkeditor(false);
			else
			{
				actionBtns.forEach(function(btn)
				{
					btn.addEventListener('click', addListener);
				});
			}

			// run function that will add buttons to dynamically make codepen.io/jsfiddle.net snippets for HTML/CSS/JavaScript
			viewHtmlCssJs();
		}

		// when user wants to add new question or edit his question/answer/comment
		else if (inCreatingQuestion || inPostEdit)
		{
			checkCkeditor(false);
		}
	});
}(document));

/**
 * Feature: Make the topic's author nick style different from other users
 */
( function ( document ) {

    'use strict';

    window.addEventListener( 'DOMContentLoaded', function() {
        var isTopicPage = !!Number( location.pathname.split( '/' )[ 1 ] );
        if ( isTopicPage ) {
            styleTopicAuthor();
            lookForUpdates();
        }
    } );

    function styleTopicAuthor() {

        var author = document.querySelector( '.qa-q-view-who-data .nickname' );
        var authorNick = author.textContent;
        author.classList.add( 'topic-author' );

        var repliesData = {
            answerQuery: '.qa-a-item-who-data',
            commentQuery: '.qa-c-item-who-data',
            answersAndComments: function ( query ) {
                [].slice.call( document.querySelectorAll( query ) ).forEach( function( replyType ) {
                    var nick = replyType.querySelector( '.nickname' );

                    if ( nick.textContent === authorNick ) {
                        nick.classList.add( 'topic-author' );
                    }
                } );
            }
        };
        repliesData.answersAndComments( repliesData.answerQuery );
        repliesData.answersAndComments( repliesData.commentQuery );
    }

    /** When anybody add a comment, then styleTopicAuthor() will run again */
    function lookForUpdates() {

        var topicMainContent = document.querySelector( '.qa-main' );

        topicMainContent.addEventListener( 'click', function( ev ) {

            var activity = [
                'Odpowiedz na ten komentarz',
                'Skomentuj tę odpowiedź',
                'Skomentuj to pytanie',
                'Odpowiedz na to pytanie'
            ];
            var areCommentsExpanded = ev.target.classList.contains( 'qa-c-item-expand' );

            if ( activity.indexOf( ev.target.title ) > -1 || areCommentsExpanded ) {

                var usersResponsesList;

                if ( ev.target.name === 'q_doanswer' ) {
                    usersResponsesList = topicMainContent.querySelector( '#a_list' );
                } else if ( ev.target.value === 'skomentuj' ) {
                    usersResponsesList = ev.target.parentNode.nextElementSibling;
                } else if ( ev.target.value === 'odpowiedz' ) {
                    var idNumber = ev.target.name;
                    idNumber = idNumber.slice( 1, idNumber.indexOf( '_' ) );

                    usersResponsesList = topicMainContent.querySelector( '[id*="' + idNumber + '_list"]' );
                } else if ( areCommentsExpanded ) {
                    var target = ev.target.parentNode.parentNode;
                    var mutationObserver = new MutationObserver( function() {
                        styleTopicAuthor();
                        mutationObserver.disconnect();
                    } );

                    mutationObserver.observe( target, { childList: true } );
                    return;
                }

                var commentBtn = usersResponsesList.parentNode.parentNode.querySelector( 'input[value="Skomentuj"]' );
                var answerBtn = topicMainContent.querySelector( 'input[value="Odpowiedz"]' );

                var responseBtn = ev.target.name === 'q_doanswer' ? answerBtn : commentBtn;

                CKEDITOR.on( 'instanceReady', function() {
                    responseBtn.addEventListener( 'click', function() {
                        /**
                         * MutationObserver will watch for new comments and/or answer to be added.
                         * It will update style of author nickname, when he in fact will make a comment or answer.
                         * */
                        var observer = new MutationObserver( function( mutations ) {
                            mutations.forEach( function() {
                                styleTopicAuthor();

                                /** stop observing */
                                observer.disconnect();
                            } );
                        } );

                        var config = { childList: true };
                        observer.observe( usersResponsesList, config );
                    } );
                } )
            }
        } );
    }

} ( document ) );

/**
 * Feature - Remove unnecessary # from tags, when creating new post (question)
 */
;( function( document ) {

    'use strict';

    if ( location.href.includes( '/ask' ) ) {
        window.addEventListener( 'DOMContentLoaded', () => {
            const tags = document.getElementById( 'tags' );
            const askQuestionBtn = document.querySelector( 'input[value="Zadaj pytanie"]' );

            if ( askQuestionBtn ) {
                askQuestionBtn.addEventListener( 'click', () => {
                    tags.value = tags.value.split( ' ' ).reduce( ( acc, tag, idx, tagsArray ) => {
                        const extraSpace = idx === tagsArray.length - 1 ? '' : ' ';

                        if ( tag.startsWith( '#' ) ) {
                            return acc + tag.slice( 1 ) + extraSpace;
                        } else {
                            return acc + tag + extraSpace;
                        }
                    }, '' );
                } );
            }
        } );
    }

}( document ) );

/*
 * Feature: add @annotation with nick of user, to whom current comment refers to
 */
;( function() {

    'use strict';

    window.addEventListener( 'DOMContentLoaded', () => {
        const qaMainElement = document.querySelector( '.qa-main' );

        if ( qaMainElement ) {
            const addAnnotationToCommentedUser = ( relativeDomRef, ckeCurrentInstance ) => {
                const chosenCommentAuthor = relativeDomRef.querySelector( '.vcard.author' ).textContent;
                const ckeTxt = ckeCurrentInstance.document.$.querySelector( 'p' );

                let currentContent = '';

                if ( ckeTxt.innerHTML ) {
                    currentContent = ckeTxt.innerHTML.replace( /[<]strong[>](.*)[,<\/]strong[>]/, '' );
                }

                ckeTxt.innerHTML = `<strong>@${ chosenCommentAuthor },</strong> ${ currentContent }`;

                return ckeTxt;
            };

            const setCursorToAnnotationEnd = ( editor, ckeTxt ) => {
                editor.focus();

                const currentRange = editor.getSelection().getRanges()[ 0 ];
                const ckeNode = new CKEDITOR.dom.node( ckeTxt );
                const newRange = new CKEDITOR.dom.range( currentRange.document );

                newRange.moveToPosition( ckeNode, CKEDITOR.POSITION_BEFORE_END );
                newRange.select();
            };

            const findCkeInstancePrefix = ( instanceSource, ancestorFormAction ) => {
                const isAnswer = instanceSource === 'q_doanswer';

                if ( isAnswer ) {
                    return;
                }

                const isQuestionComment = instanceSource === 'q_docomment';

                if ( isQuestionComment ) {
                    return `c${ ancestorFormAction.split( '/' ).find( Number ) }`;
                }
                else {
                    return `c${ instanceSource.slice( 1, instanceSource.indexOf( '_' ) ) }`;
                }
            };

            const handleCkeInstance = ( evt, chosenCommentCKEInstance, eTarget ) => {
                setCursorToAnnotationEnd( evt.editor, addAnnotationToCommentedUser(
                    eTarget.parentNode.parentNode.parentNode, CKEDITOR.instances[ chosenCommentCKEInstance ]
                    )
                );

                evt.removeListener();
            };

            qaMainElement.addEventListener( 'click', ( ev ) => {
                const eTarget = ev.target;

                if ( eTarget.value === 'skomentuj' || eTarget.value === 'odpowiedz' ) {
                    const matchedInstanceName = findCkeInstancePrefix( eTarget.name, eTarget.form.action );
                    const allCommentsForInstance = eTarget.form.querySelector( '[name="question-comments-list"]' ).children;
                    const allCommentsForInstanceLength = allCommentsForInstance.length;
                    const isResponseToLastComment = allCommentsForInstanceLength && allCommentsForInstance[ allCommentsForInstanceLength - 1 ].contains( eTarget );

                    if ( !matchedInstanceName || !allCommentsForInstanceLength || isResponseToLastComment ) {
                        return;
                    }

                    const chosenCommentCKEInstance = Object.keys( CKEDITOR.instances ).find( ( instanceName ) => {
                        return instanceName.includes( matchedInstanceName );
                    } );

                    CKEDITOR.instances[ chosenCommentCKEInstance ].on( 'focus', ( ckeEvt ) => {
                        handleCkeInstance( ckeEvt, chosenCommentCKEInstance, eTarget );
                    } );
                }
            } );
        }
    } );
} () );
