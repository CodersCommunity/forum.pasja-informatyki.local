/*
    Question2Answer by Gideon Greenspan and contributors

    http://www.question2answer.org/


    File: qa-content/qa-ask.js
    Version: See define()s at top of qa-include/qa-base.php
    Description: Javascript for ask page and question editing, including tag auto-completion


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

function qa_title_change(value)
{
    qa_ajax_post('asktitle', {title:value}, function(lines) {
        if (lines[0]=='1') {
            if (lines[1].length) {
                qa_tags_examples=lines[1];
                qa_tag_hints(true);
            }

            if (lines.length>2) {
                let simelem=document.getElementById('similar');
                if (simelem)
                    simelem.innerHTML=lines.slice(2).join('\n');
            }

        } else if (lines[0]=='0')
            alert(lines[1]);
        else
            qa_ajax_error();
    });

    qa_show_waiting_after(document.getElementById('similar'), true);
}

function qa_html_unescape(html)
{
    return html.replace(/&amp;/g, '&').replace(/&quot;/g, '"').replace(/&lt;/g, '<').replace(/&gt;/g, '>');
}

function qa_html_escape(text)
{
    return text.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

function qa_tag_click(link)
{
    let elem=document.getElementById('tags');
    let parts=qa_tag_typed_parts(elem);

    // removes any HTML tags and ampersand
    let tag=qa_html_unescape(link.innerHTML.replace(/<[^>]*>/g, ''));

    let separator=qa_tag_onlycomma ? ', ' : ' ';

    // replace if matches typed, otherwise append
    let newvalue=(parts.typed && (tag.toLowerCase().indexOf(parts.typed.toLowerCase())>=0))
        ? (parts.before+separator+tag+separator+parts.after+separator) : (elem.value+separator+tag+separator);

    // sanitize and set value
    if (qa_tag_onlycomma)
        elem.value=newvalue.replace(/[\s,]*,[\s,]*/g, ', ').replace(/^[\s,]+/g, '');
    else
        elem.value=newvalue.replace(/[\s,]+/g, ' ').replace(/^[\s,]+/g, '');

    elem.focus();
    qa_tag_hints();

    return false;
}

function qa_tag_hints(skipcomplete)
{
    let elem=document.getElementById('tags');
    let html='';
    let completed=false;

    // first try to auto-complete
    if (qa_tags_complete && !skipcomplete) {
        let parts=qa_tag_typed_parts(elem);

        if (parts.typed) {
            html=qa_tags_to_html((qa_html_unescape(qa_tags_examples+','+qa_tags_complete)).split(','), parts.typed.toLowerCase());
            completed=html ? true : false;
        }
    }

    // otherwise show examples
    if (qa_tags_examples && !completed)
        html=qa_tags_to_html((qa_html_unescape(qa_tags_examples)).split(','), null);

    // set title visiblity and hint list
    document.getElementById('tag_examples_title').style.display=(html && !completed) ? '' : 'none';
    document.getElementById('tag_complete_title').style.display=(html && completed) ? '' : 'none';
    document.getElementById('tag_hints').innerHTML=html;
}

function qa_tags_to_html(tags, matchlc)
{
    let html='';
    let added=0;
    let tagseen={};

    for (var i=0; i<tags.length; i++) {
        let tag=tags[i];
        let taglc=tag.toLowerCase();

        if (!tagseen[taglc]) {
            tagseen[taglc]=true;

            if ( (!matchlc) || (taglc.indexOf(matchlc)>=0) ) { // match if necessary
                if (matchlc) { // if matching, show appropriate part in bold
                    let matchstart=taglc.indexOf(matchlc);
                    let matchend=matchstart+matchlc.length;
                    inner='<span style="font-weight:normal;">'+qa_html_escape(tag.substring(0, matchstart))+'<b>'+
                        qa_html_escape(tag.substring(matchstart, matchend))+'</b>'+qa_html_escape(tag.substring(matchend))+'</span>';
                } else // otherwise show as-is
                    inner=qa_html_escape(tag);

                html+=qa_tag_template.replace(/\^/g, inner.replace('$', '$$$$'))+' '; // replace ^ in template, escape $s

                if (++added>=qa_tags_max)
                    break;
            }
        }
    }

    return html;
}

function qa_caret_from_end(elem)
{
    if (document.selection) { // for IE
        elem.focus();
        let sel=document.selection.createRange();
        sel.moveStart('character', -elem.value.length);

        return elem.value.length-sel.text.length;

    } else if (typeof(elem.selectionEnd)!='undefined') // other browsers
        return elem.value.length-elem.selectionEnd;

    else // by default return safest value
        return 0;
}

function qa_tag_typed_parts(elem)
{
    let caret=elem.value.length-qa_caret_from_end(elem);
    let active=elem.value.substring(0, caret);
    let passive=elem.value.substring(active.length);

    // if the caret is in the middle of a word, move the end of word from passive to active
    if (
        active.match(qa_tag_onlycomma ? /[^\s,][^,]*$/ : /[^\s,]$/) &&
        (adjoinmatch=passive.match(qa_tag_onlycomma ? /^[^,]*[^\s,][^,]*/ : /^[^\s,]+/))
    ) {
        active+=adjoinmatch[0];
        passive=elem.value.substring(active.length);
    }

    // find what has been typed so far
    let typedmatch=active.match(qa_tag_onlycomma ? /[^\s,]+[^,]*$/ : /[^\s,]+$/) || [''];

    return {before:active.substring(0, active.length-typedmatch[0].length), after:passive, typed:typedmatch[0]};
}

function qa_category_select(idprefix, startpath)
{
    let startval=startpath ? startpath.split("/") : [];
    let setdescnow=true;

    for (let l=0; l<=qa_cat_maxdepth; l++) {
        let elem=document.getElementById(idprefix+'_'+l);

        if (elem) {
            if (l) {
                if (l<startval.length && startval[l].length) {
                    let val=startval[l];

                    for (let j=0; j<elem.options.length; j++)
                        if (elem.options[j].value==val)
                            elem.selectedIndex=j;
                } else
                    let val=elem.options[elem.selectedIndex].value;
            } else
                val='';

            if (elem.qa_last_sel!==val) {
                elem.qa_last_sel=val;

                let subelem=document.getElementById(idprefix+'_'+l+'_sub');
                if (subelem)
                    subelem.parentNode.removeChild(subelem);

                if (val.length || (l==0)) {
                    subelem=elem.parentNode.insertBefore(document.createElement('span'), elem.nextSibling);
                    subelem.id=idprefix+'_'+l+'_sub';
                    qa_show_waiting_after(subelem, true);

                    qa_ajax_post('category', {categoryid:val},
                        (function(elem, l) {
                            return function(lines) {
                                let subelem=document.getElementById(idprefix+'_'+l+'_sub');
                                if (subelem)
                                    subelem.parentNode.removeChild(subelem);

                                if (lines[0]=='1') {
                                    elem.qa_cat_desc=lines[1];

                                    const addedoption=false;

                                    if (lines.length>2) {
                                        const subelem=elem.parentNode.insertBefore(document.createElement('span'), elem.nextSibling);
                                        subelem.id=idprefix+'_'+l+'_sub';
                                        subelem.innerHTML=' ';

                                        const newelem=elem.cloneNode(false);

                                        newelem.name=newelem.id=idprefix+'_'+(l+1);
                                        newelem.options.length=0;

                                        if (l ? qa_cat_allownosub : qa_cat_allownone)
                                            newelem.options[0]=new Option(l ? '' : elem.options[0].text, '', true, true);

                                        for (let i=2; i<lines.length; i++) {
                                            let parts=lines[i].split('/');

                                            if (String(qa_cat_exclude).length && (String(qa_cat_exclude)==parts[0]))
                                                continue;

                                            newelem.options[newelem.options.length]=new Option(parts.slice(1).join('/'), parts[0]);
                                            addedoption=true;
                                        }

                                        if (addedoption) {
                                            subelem.appendChild(newelem);
                                            qa_category_select(idprefix, startpath);

                                        }

                                        if (l==0)
                                            elem.style.display='none';
                                    }

                                    if (!addedoption)
                                        set_category_description(idprefix);

                                } else if (lines[0]=='0')
                                    alert(lines[1]);
                                else
                                    qa_ajax_error();
                            }
                        })(elem, l)
                    );

                    setdescnow=false;
                }

                break;
            }
        }
    }

    if (setdescnow)
        set_category_description(idprefix);
}

function set_category_description(idprefix)
{
    const n=document.getElementById(idprefix+'_note');

    if (n) {
        desc='';

        for (let l=1; l<=qa_cat_maxdepth; l++) {
            let elem=document.getElementById(idprefix+'_'+l);

            if (elem && elem.options[elem.selectedIndex].value.length)
                desc=elem.qa_cat_desc;
        }

        n.innerHTML=desc;
    }
}


/*
 * check SPOJ content when User creates new topic (asks a question)
 */
;(function (document)
{
    'use strict';

    window.addEventListener('load', function()
    {
        const titleDetected = false;
        const editorDetected = false;

        const alertDiv = document.createElement('div');
        alertDiv.id = 'spoj-alert';
        alertDiv.innerHTML = 'Twoje pytanie dotyczy zadania z serwisu SPOJ?<br>Nie psuj zabawy innym - nie umieszczaj całego kodu i zapoznaj się z <a href="http://forum.pasja-informatyki.pl/90416/spoj-zasady-umieszczania-postow?show=90416#q90416" target="_blank">tym tematem</a>.';
        alertDiv.classList.add('spoj-alert');

        function detectSpoj(place, ev)
        {
            if (ev.target.id === 'title')
            {
                if (ev.target.value.toLowerCase().indexOf('spoj') > -1)
                {
                    if (!document.getElementById(alertDiv))
                    {
                        // place spoj alert below CKEditor
                        place.appendChild(alertDiv);

                        titleDetected = true;
                    }
                }
                else
                {
                    titleDetected = false;

                    if (document.getElementById(alertDiv.id) && !editorDetected)
                    {
                        // remove spoj alert warning
                        place.removeChild(alertDiv);
                    }
                }
                ////console.log('I [title / /editor] detected: ', titleDetected, '/', editorDetected);
            }
        }

        CKEDITOR.on('instanceReady', function(ev)
        {
            const iframe = document.querySelector('iframe[title^="Edytor tekstu sformatowanego"]');

            // get CKEditor DOM from <iframe>
            const ckeditor = iframe.contentWindow.document.body;
            const editorFrame = ( document.getElementById( 'cke_content' ) || document.getElementById( 'cke_q_content' ) ).parentNode;

            // when user writes topic title
            ( document.querySelector( 'input[name="q_title"]' ) || document.getElementById('title') ).addEventListener('input', function(ev)
            {
                detectSpoj(editorFrame, ev);
            });

            ckeditor.addEventListener('input', function(evt)
            {
                // when script detect that user wrote "pl.spoj.com" inside CKEditor
                if (evt.target.innerHTML.indexOf('pl.spoj.com') > -1)
                {
                    if (!document.getElementById(alertDiv))
                    {
                        // place spoj alert below CKEditor
                        editorFrame.appendChild(alertDiv);

                        editorDetected = true;
                    }
                }
                else
                {
                    editorDetected = false;

                    if (document.getElementById(alertDiv.id) && !titleDetected)
                    {
                        editorFrame.removeChild(alertDiv);
                    }
                }

                ////console.log('E [title / /editor] detected: ', titleDetected, '/', editorDetected);
            });
        });
    });
}(document));


/*
 * Suggest inserting code in appropriate blocks, when question category is "Programowanie"
 */
;( function() {
    'use strict';

    window.addEventListener( 'DOMContentLoaded', () => {
        if ( location.href.includes( '/ask' ) ) {
            CKEDITOR.on( 'instanceReady', () => {
                const showIncorrectCodePlacementWarning = () => {
                    const warningDomElement = document.createElement( 'div' );
                    warningDomElement.classList.add( 'incorrect-code-placement-warning' );
                    warningDomElement.innerHTML = `
                        Czy to pytanie nie powinno zawierać kodu? Jeśli tak, upewnij się, że wstawiłeś go w przeznaczony do tego bloczek.
                         <br>
                         Instrukcję znajdziesz 
                        <a target="_blank" href="https://forum.pasja-informatyki.pl/faq#jak-wstawic-kod-zrodlowy" title="Jak wstawić kod źródłowy?">tutaj</a>.
                    `;
                    askQuestionBtn.parentNode.insertBefore( warningDomElement, askQuestionBtn.parentNode.firstElementChild );
                };
                const questionEditorDocument = CKEDITOR.instances.content.document.$;
                const askQuestionBtn = document.getElementById( '__form-send' );
                const categorySelect = document.getElementById( 'category_1' );
                const excludedSubCategories = [ 'Hostingi, domeny', 'Systemy CMS', 'Algorytmy' ];
                const isChosenSubCategoryExcluded = ( chosenSubCategory ) => {
                    return excludedSubCategories.some( ( subCategory ) => {
                        return chosenSubCategory === subCategory;
                    } );
                };

                // Use capture phase on <body> to block submitting before any [onclick] on button will be triggered.
                document.body.addEventListener( 'click', function preventSubmit( event ) {
                    if ( event.srcElement && event.srcElement.id === askQuestionBtn.id ) {
                        const subCategorySelect = document.getElementById( 'category_2' );

                        if ( subCategorySelect ) {
                            const selectedCategory = categorySelect.options[ categorySelect.selectedIndex ].textContent;
                            const selectedSubCategory = subCategorySelect.options[ subCategorySelect.selectedIndex ].textContent;

                            if ( selectedCategory === 'Programowanie' && !isChosenSubCategoryExcluded( selectedSubCategory ) ) {
                                const isCodeBlockInEditor = !!( questionEditorDocument.querySelector( '[class^="brush"]' ) );

                                if ( !isCodeBlockInEditor ) {
                                    event.preventDefault();
                                    event.stopPropagation();

                                    showIncorrectCodePlacementWarning();
                                    document.body.removeEventListener( 'click', preventSubmit, true );
                                }
                            }
                        }
                    }
                }, true );
            } );
        }
    } );

} () );
