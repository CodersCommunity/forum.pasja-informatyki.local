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



 /*    Feature: inform user about marking best answer, when he wants to close a topic
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

    Object.defineProperty(window, 'reloadBlocksOfCode', {
        configurable: false,
        writable: false,
        value: (postsToHighlight, ignoreAddingInteractiveBar) => {
            if (typeof SyntaxHighlighter === 'object' && SyntaxHighlighter && typeof SyntaxHighlighter.highlight === 'function') {
                const codeBlocks = [...postsToHighlight.querySelectorAll('pre')];

                window.scanUnprocessedCodeBlocks(null, postsToHighlight);

                const processedCodeBlocks = codeBlocks.map((codeBlock) => {
                    /*
                     * SyntaxHighlighter restructures processed element DOM, thus it loses it's parent.
                     * Temporary caching is needed to retrieve processed element within parent context afterwards.
                     */
                    const origCodeBlockParent = codeBlock.parentNode;
                    SyntaxHighlighter.highlight(null, codeBlock);
                    const processedCodeBlock = [...origCodeBlockParent.querySelectorAll('.syntaxhighlighter')].pop();

                    return processedCodeBlock;
                });

                if (!ignoreAddingInteractiveBar) {
                    window.addInteractiveBarToCodeBlocks(false, processedCodeBlocks);
                }
            } else {
                console.error('Cannot reload blocks of code, because SyntaxHighlighter is not available!');
            }
        }
    });

    /*
     * Feature: Post content preview as Modal
     * Date: 07.07.2016r.
     */

    function destroyModal()
    {
        const modal = document.querySelector('.post-preview-parent');
        modal.remove();

        const modalBackground = document.querySelector('.modal-background');
        modalBackground.remove();
    }

    function createPostPreviewModal(ckeInstanceName)
    {
        const modal = document.createElement('div');
        modal.classList.add('post-preview-parent');

        const closeModalButton = document.createElement('button');
        closeModalButton.textContent = 'X';
        closeModalButton.classList.add('close-preview-btn');
        closeModalButton.addEventListener('click', destroyModal);
        modal.appendChild(closeModalButton);

        const modalContent = document.createElement('div');
        modalContent.innerHTML = CKEDITOR.instances[ckeInstanceName].getData();
        modalContent.classList.add('post-preview');
        modal.appendChild(modalContent);

        const modalBackground = document.createElement('div');
        modalBackground.classList.add('modal-background');
        modalBackground.addEventListener('click', destroyModal);
        document.body.insertBefore(modalBackground, document.body.firstChild);

        const modalParent = document.querySelector('.qa-main-wrapper');
        modalParent.appendChild(modal);

        window.reloadBlocksOfCode(modalContent, true);
        window.addInteractiveBarToCodeBlocks(true);
    }

    function createPostPreviewButton(postForm, ckeInstanceName) {
        const buttonsLocation = postForm.querySelector('.qa-form-tall-buttons');

        const showPostPreviewButton = document.createElement('input');
        showPostPreviewButton.type = 'button';
        showPostPreviewButton.id = 'get-content-preview';
        showPostPreviewButton.value = 'Podgląd posta';
        showPostPreviewButton.classList.add('qa-form-tall-button', 'get-content-preview');

        showPostPreviewButton.addEventListener('click', function()
        {
           const modal = document.querySelector('.post-preview-parent');
           if (modal === null) {
               createPostPreviewModal(ckeInstanceName);
           }
        });

        buttonsLocation.appendChild(showPostPreviewButton);
    }


    // when Forum (sub)page DOM with it's CSSes and synchronously loaded scripts (excluding CKEDITOR, which needs separate Event Handling) are ready
    window.addEventListener('load', function() {
        const questionId = parseInt(location.pathname.split('/')[1]);
        const newQuestion = location.pathname.includes('ask');

        if (questionId) {
            /*
			 * 1st argument notifies function that the page is not /ask.html - so different blocks of code collapsing method will be used
			 * 2nd parameter notifies function if it can "turn on" Copy To Clipboard function - so user can copy code inside block within button click
			 */
            window.addInteractiveBarToCodeBlocks(false);
        }

        if (questionId || newQuestion) {
            CKEDITOR.on("instanceReady", function(event) {
                const currentInstanceName = event.editor.name;
                const contentTextarea = document.getElementsByName(currentInstanceName)[0];
                const postForm = contentTextarea.closest('form');

                createPostPreviewButton(postForm, currentInstanceName);
            });
        }
    });
}(document));

/*
 * Feature: preview HTML/CSS/JavaScript code from chosen post in codepen.io / jsfiddle.net
 */
;(() => {
    const questionId = parseInt(location.pathname.split('/')[1]);
    const newQuestion = location.pathname.includes('ask');

    if (questionId || newQuestion) {
        window.addEventListener('load', initSnippetsCreation);
    }

    function initSnippetsCreation() {
        const SNIPPET_LANG_MAP = Object.freeze({
            xml: 'html',
            css: 'css',
            jscript: 'js'
        });
        const NEW_LINE = '\r\n';

        const postsContent = document.querySelectorAll('.entry-content');
        postsContent.forEach(processCodeBlocksInPosts);

        function processCodeBlocksInPosts(postContent) {
            const blockOfCodeParents = postContent.querySelectorAll('.syntaxhighlighter-parent');

            if (blockOfCodeParents.length) {
                const langData = {
                    html: '',
                    css: '',
                    js: ''
                };

                blockOfCodeParents.forEach(block => processBlockOfCode(block, langData));

                const langDataHasAnyValue = Object.values(langData).some(Boolean);
                if (langDataHasAnyValue) {
                    const snippetsInsertionTarget = blockOfCodeParents[0].parentNode.parentNode;
                    const snippetsList = [createCodepenSnippet(langData), createJSFiddleSnippet(langData)];

                    addSnippets(snippetsList, snippetsInsertionTarget, postContent);
                }
            }
        }

        function processBlockOfCode(block, langData) {
            const codeContent = [...block.querySelectorAll('.code .line')]
                .reduce((codeLines, codeLine) => codeLines + codeLine.textContent + NEW_LINE, '');
            const codeLang = block.querySelector('.syntaxhighlighter').classList.item(1);
            const mappedSnippetLang = SNIPPET_LANG_MAP[codeLang];

            if (mappedSnippetLang) {
                langData[mappedSnippetLang] = codeContent;
            }
        }
    }

    /*
     * Code based on Codepen API tutorial: https://blog.codepen.io/documentation/api/prefill/
     */
    function createCodepenSnippet(codeData) {
        const codeAsJSON = JSON.stringify(codeData)
            // Quotes will screw up the JSON
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&apos;');

        const codepenSnippetForm = document.createElement('form');
        codepenSnippetForm.action = 'https://codepen.io/pen/define';
        codepenSnippetForm.method = 'POST';
        codepenSnippetForm.target = '_blank';
        codepenSnippetForm.classList.add('codepen-snippet');

        const dataCarrierInput = document.createElement('input');
        dataCarrierInput.type = 'hidden';
        dataCarrierInput.name = 'data';
        dataCarrierInput.value = codeAsJSON;

        const submitSnippet = document.createElement('input');
        submitSnippet.type = 'submit';
        submitSnippet.value = 'CodePen';

        codepenSnippetForm.append(dataCarrierInput, submitSnippet);

        return codepenSnippetForm;
    }

    /*
     * Code based on JSFiddle API tutorial: http://doc.jsfiddle.net/api/post.html
     */
    function createJSFiddleSnippet(jsfiddleData) {
        const jsfiddleSnippetForm = document.createElement('form');
        jsfiddleSnippetForm.action = 'https://jsfiddle.net/api/post/library/pure/';
        jsfiddleSnippetForm.method = 'POST';
        jsfiddleSnippetForm.target = '_blank';
        jsfiddleSnippetForm.classList.add('jsfiddle-snippet');

        const htmlTxt = document.createElement('textarea');
        htmlTxt.name = 'html';
        htmlTxt.value = jsfiddleData.html || '';

        const cssTxt = document.createElement('textarea');
        cssTxt.name = 'css';
        cssTxt.value = jsfiddleData.css || '';

        const jsTxt = document.createElement('textarea');
        jsTxt.name = 'js';
        jsTxt.value = jsfiddleData.js || '';

        const submitSnippet = document.createElement('input');
        submitSnippet.type = 'submit';
        submitSnippet.value = 'JSFiddle';

        jsfiddleSnippetForm.append(htmlTxt, cssTxt, jsTxt, submitSnippet);

        return jsfiddleSnippetForm;
    }

    function addSnippets(snippetsList, snippetsInsertionTarget, postContent) {
        const snippetsParent = document.createElement('div');
        snippetsParent.classList.add('snippets-parent');
        snippetsParent.append(...snippetsList);

        snippetsInsertionTarget.appendChild(snippetsParent);

        if (snippetsInsertionTarget.classList.contains('qa-c-item-content')) {
            snippetsParent.classList.add('inside-comment');
            postContent.classList.add('comment-snippets');
        }
    }
})();

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

                // TODO: handle 'getRanges of null' error
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

// Feature: interactive code block bar
;(() => {
    'use strict';

    const MIN_LINES_NUMBER_TO_COLLAPSE_CODE = 30;
    const languages = {};

    document.addEventListener('DOMContentLoaded', prepareLanguages);
    Object.defineProperty(window, 'addInteractiveBarToCodeBlocks', {
        configurable: false,
        writable: false,
        value: addInteractiveBarToCodeBlocks
    });

    const getCodeBlockBarFeatureItems = initInteractiveFeatures();

    function prepareLanguages() {
        SyntaxHighlighter.languages.entries.forEach(([name, code]) => languages[code] = name);
    }

    function addInteractiveBarToCodeBlocks(isInsidePreview, chosenCodeBlocks) {
        getCodeBlocks(isInsidePreview, chosenCodeBlocks).forEach(decorateCodeBlock);

        function getCodeBlocks(isInsidePreview, chosenCodeBlocks){
            if (chosenCodeBlocks) {
                return chosenCodeBlocks;
            }

            const highlightedCodeBlocksSelector = isInsidePreview ? '.post-preview-parent .syntaxhighlighter' : '.syntaxhighlighter';
            const highlightedCodeBlocks = document.querySelectorAll(highlightedCodeBlocksSelector);

            if (highlightedCodeBlocks.length) {
                return highlightedCodeBlocks;
            }

            const rawCodeBlocks = document.querySelectorAll('pre[class*="brush:"]');
            return rawCodeBlocks;
        }

        function decorateCodeBlock(codeBlock) {
            const codeBlockBar = document.createElement('div');
            codeBlockBar.classList.add('syntaxhighlighter-block-bar', 'block-bar-transparency');
            codeBlockBar.append(...getCodeBlockBarFeatureItems(codeBlock));

            if (codeBlockBar.querySelector('.syntaxhighlighter-collapsible-button')) {
                codeBlockBar.classList.add('is-collapsible');
            }

            codeBlock.parentNode.classList.add('syntaxhighlighter-parent');
            codeBlock.parentNode.insertBefore(codeBlockBar, codeBlock);

            requestAnimationFrame(() => codeBlockBar.classList.remove('block-bar-transparency'));
        }
    }

    function initInteractiveFeatures() {
        class CollapsibleCodeBlocks {
            constructor() {
                this.collapsedState = 'collapsed-state';
                this.expandedState = 'expanded-state';
            }

            prepareCollapsibleAnimationValue(codeBlock) {
                const codeBlockRawHeight = codeBlock.querySelector('table').clientHeight;
                codeBlock.style.setProperty('--code-block-raw-height', `${ codeBlockRawHeight }px`);
            }

            isCodeCollapsible(codeBlock) {
                const isLongCodeAtReply = codeBlock.querySelectorAll('.line').length >= MIN_LINES_NUMBER_TO_COLLAPSE_CODE;
                const isLongCodeAtQuestion = (codeBlock.innerHTML.includes('\n') && codeBlock.innerHTML.match(/\n/g).length + 1 >= MIN_LINES_NUMBER_TO_COLLAPSE_CODE);

                return isLongCodeAtReply || isLongCodeAtQuestion;
            }

            getCodeBlockCollapsingBtn(codeBlock) {
                if (!this.isCodeCollapsible(codeBlock)) {
                    return;
                }

                const codeBlockCollapsibleBtn = document.createElement('button');
                codeBlockCollapsibleBtn.classList.add('syntaxhighlighter-collapsible-button', this.collapsedState);
                codeBlockCollapsibleBtn.innerHTML = this.getCodeBlockCollapseBtnTxt(true);
                codeBlockCollapsibleBtn.type = 'button';
                codeBlockCollapsibleBtn.addEventListener('click', () => this.toggleCodeBlockBtnCollapseState(codeBlock, codeBlockCollapsibleBtn));

                codeBlock.classList.add('collapsed-block');

                this.prepareCollapsibleAnimationValue(codeBlock);

                return codeBlockCollapsibleBtn;
            }

            getCodeBlockCollapseBtnTxt(isCollapsed) {
                return isCollapsed ? 'Rozwiń' : 'Zwiń' ;
            }

            toggleCodeBlockBtnCollapseState(codeBlock, codeBlockCollapsibleBtn) {
                const isCodeBlockCollapsed = codeBlock.classList.contains('collapsed-block');

                codeBlockCollapsibleBtn.innerHTML = this.getCodeBlockCollapseBtnTxt(!isCodeBlockCollapsed);
                codeBlock.classList.toggle('collapsed-block', !isCodeBlockCollapsed);

                if (isCodeBlockCollapsed) {
                    codeBlockCollapsibleBtn.classList.replace(this.collapsedState, this.expandedState);
                } else {
                    codeBlockCollapsibleBtn.classList.replace(this.expandedState, this.collapsedState);
                }
            }
        }

        class LanguageLabel {
            getLanguageName(codeBlock) {
                const [zerothClassName, firstClassName] = codeBlock.classList;
                const languageExplicitName = languages[firstClassName];
                const languageImplicitName = languages[zerothClassName.slice(zerothClassName.indexOf(':') + 1, -1)];

                return languageExplicitName || languageImplicitName || SyntaxHighlighter.defaults['code-language'].fullName;
            }

            getLanguageLabel(codeBlock) {
                const languageNameLabel = document.createElement('div');
                languageNameLabel.textContent = this.getLanguageName(codeBlock);
                languageNameLabel.classList.add('syntaxhighlighter-language');

                return languageNameLabel;
            }
        }

        class CodeCopy {
            constructor() {
                this.NEW_LINE = '\r\n';
                this.initCopyingMethod();
            }

            initCopyingMethod() {
                this.isCopyByQueryCommand =
                    !!window.getSelection && document.queryCommandSupported('copy');
                this.isCopyByClipboardAPI = window.navigator.clipboard && window.navigator.clipboard.writeText;

                if (this.isCopyByClipboardAPI) {
                    this.isCopyingSupported = true;
                    this.copyToClipboard = this.copyByClipboardAPI;
                } else if (this.isCopyByQueryCommand) {
                    this.isCopyingSupported = true;
                    this.copyToClipboard = this.copyByQueryCommand;
                } else {
                    this.isCopyingSupported = false;
                    this.copyToClipboard = function() {
                        console.error('Copy to clipboard is not available!');
                    }
                }
            }

            getContentToCopy(target) {
                const blockOfCodeParent = target.parentNode.parentNode.parentNode;
                const linesOfCode = [...blockOfCodeParent.querySelector('.code .container').children];
                const contentToCopy = linesOfCode
                    .reduce((concatenatedCode, { textContent: singleLineOfCode }) => {
                        return concatenatedCode + singleLineOfCode + this.NEW_LINE;
                    }, '');

                return contentToCopy;
            }

            copyByClipboardAPI({ target }) {
                window.navigator.clipboard
                    .writeText(this.getContentToCopy(target))
                    .catch(() => this.tryFallbackToOlderCopyMethod(target));
            }

            tryFallbackToOlderCopyMethod(target) {
                if (this.isCopyByQueryCommand) {
                    this.copyByQueryCommand({ target });
                } else {
                    target.classList.add('content-copy-tooltip', 'content-copy-error');

                    setTimeout(() => {
                        target.classList.remove('content-copy-tooltip', 'content-copy-error');
                    }, 3000);
                }
            }

            copyByQueryCommand({ target }) {
                const textArea = document.createElement("textarea");
                textArea.classList.add('content-copy-placeholder');
                textArea.value = this.getContentToCopy(target);

                const selection = window.getSelection();
                if (selection.rangeCount) {
                    selection.removeAllRanges();
                }

                document.body.appendChild(textArea);

                const range = document.createRange();
                range.selectNode(textArea);
                selection.addRange(range);

                document.execCommand('copy');
                document.body.removeChild(textArea);
            }

            addClickListener(copyCodeBtn) {
                copyCodeBtn.addEventListener('click', this.copyToClipboard.bind(this));
            }

            getCopyToClipboardBtn() {
                const copyCodeBtn = document.createElement('button');
                copyCodeBtn.textContent = 'Kopiuj';
                copyCodeBtn.classList.add('content-copy-btn');
                copyCodeBtn.type = 'button';

                if (this.isCopyingSupported) {
                    this.addClickListener(copyCodeBtn);
                } else {
                    copyCodeBtn.classList.add('content-copy-tooltip');
                    copyCodeBtn.disabled = true;
                }

                return copyCodeBtn;
            }
        }

        const collapsibleCodeBlocks = new CollapsibleCodeBlocks();
        const languageLabel = new LanguageLabel();
        const codeCopy = new CodeCopy();

        return function getCodeBlockBarFeatureItems(codeBlock) {
            return [
                languageLabel.getLanguageLabel(codeBlock),
                collapsibleCodeBlocks.getCodeBlockCollapsingBtn(codeBlock),
                codeCopy.getCopyToClipboardBtn()
            ].filter(Boolean).map(wrapCodeBlockBarFeatureItem);
        }

        function wrapCodeBlockBarFeatureItem(item) {
            const wrapper = document.createElement('div');
            wrapper.classList.add('syntaxhighlighter-block-bar-item');
            wrapper.appendChild(item);

            return wrapper;
        }
    }
})();
