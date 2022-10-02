'use strict';

const getCodeBlockMeta = (codeBlock) => {
    return {
        postId: codeBlock.closest('.post-preview') ? 'post-preview' : codeBlock.closest('[id]').id.match(/\d*/g).join(''),
        numberInPost: [
            ...codeBlock.parentNode.querySelectorAll('[data-code-lang-name], .syntaxhighlighter-parent')
        ].findIndex(item => item === codeBlock) + 1,
    };
};

const fixSpaces = (() => {
    const NBSP_CHAR_UNICODE_REG_EXP = /\u00a0/g;

    return (text) => {
        return text.replace(NBSP_CHAR_UNICODE_REG_EXP, ' ');
    };
})();

const rawCodeBlocksPreProcessor = () => {
    const BRUSHES_SELECTOR = 'pre[class*="brush:"]';

    prepareCodeLanguages();

    return scanUnprocessedCodeBlocks;

    /**
     * Extend SyntaxHighlighter object with supported programming languages
     * used by CKEditor and SyntaxHighlighter itself
     */
    function prepareCodeLanguages() {
        prepareDefaultLanguage();
        prepareAvailableLanguages();

        function prepareDefaultLanguage() {
            SyntaxHighlighter.defaults['code-language'] = {
                ctorName: 'Plain',
                alias: 'plain',
                fullName: 'Plain (Text)'
            };
        }

        function prepareAvailableLanguages() {
            const languagesEntries = Object.freeze([
                ['Bash (Shell)', 'bash'],
                ['C#', 'csharp'],
                ['C/C++', 'cpp'],
                ['CSS', 'css'],
                ['Delphi', 'delphi'],
                ['JavaScript', 'jscript'],
                ['Java', 'java'],
                ['Perl', 'perl'],
                ['PHP', 'php'],
                ['Plain (Text)', 'plain'],
                ['PowerShell', 'ps'],
                ['Python', 'python'],
                ['Ruby', 'ruby'],
                ['SQL', 'sql'],
                ['VB', 'vb'],
                ['XML/XHTML', 'xml']
            ]);

            Object.defineProperty(SyntaxHighlighter, 'languages', {
                configurable: false,
                writable: false,
                value: {}
            });

            Object.defineProperties(SyntaxHighlighter.languages, {
                entries: {
                    configurable: false,
                    writable: false,
                    value: languagesEntries
                },
                codes: {
                    configurable: false,
                    writable: false,
                    value: languagesEntries.map(([name, code]) => code)
                }
            });
        }
    }

    function scanUnprocessedCodeBlocks(inputElem = document) {
        const preElements = flattenInputElem(inputElem);
        const loadedBrushes = Object
            .entries(SyntaxHighlighter.brushes)
            .map(([name, ctor]) => ({
                name,
                aliases: ctor.aliases
            }));
        fixUnavailableLangCodes(getPreElemsWithNotAvailableBrush());

        return preElements;

        function getPreElemsWithNotAvailableBrush() {
            const checkedUnknownLangNames = [];
            const unknownLangElems = [];

            scanPreElements();

            return unknownLangElems;

            function scanPreElements() {
                for (let i = 0; i < preElements.length; i++) {
                    const pre = preElements[i];
                    const preClassName = pre.className;

                    if (checkedUnknownLangNames.includes(preClassName)) {
                        unknownLangElems.push(pre);
                        continue;
                    }

                    matchPreElemsWithBrushes(pre, preClassName);
                }
            }

            function matchPreElemsWithBrushes(pre, preClassName) {
                let isBrashKnown = false;

                for (let i = 0; i < loadedBrushes.length; i++) {
                    const { name, aliases } = loadedBrushes[i];
                    const brushRegExp = new RegExp(`brush\:(${ aliases.join('|') });`);
                    const [, brushLanguageName] = preClassName.match(brushRegExp) || [];

                    if (brushLanguageName) {
                        setPreElemCodeLanguageName(pre, brushLanguageName);
                        isBrashKnown = true;
                        break;
                    }
                }

                if (!isBrashKnown) {
                    checkedUnknownLangNames.push(preClassName);
                    unknownLangElems.push(pre);
                }
            }
        }

        function fixUnavailableLangCodes(unknownLangElems) {
            unknownLangElems.forEach(replaceUnknownLangWithDefault);

            function replaceUnknownLangWithDefault(pre) {
                const oldClassName = pre.className.split(';').find(getCodeLanguageNameGroup);
                const codeLanguageAlias = SyntaxHighlighter.defaults['code-language'].alias;
                const newClassName = `brush:${ codeLanguageAlias }`;

                pre.className = pre.className.replace(oldClassName, newClassName);
                setPreElemCodeLanguageName(pre, codeLanguageAlias);
            }
        }

        function getCodeLanguageNameGroup(classNameFragment) {
            return classNameFragment.split(':')[0] === 'brush';
        }

        function setPreElemCodeLanguageName(pre, languageName) {
            pre.dataset.codeLangName = languageName;
        }
    }

    function flattenInputElem(elem) {
        if (Array.isArray(elem)) {
            const normalizedElems = [];

            elem.forEach(elemItem => {
                elemItem.querySelectorAll(BRUSHES_SELECTOR).forEach((brush) => {
                    normalizedElems.push(brush);
                });
            });

            return normalizedElems;
        }

        return [...elem.querySelectorAll(BRUSHES_SELECTOR)];
    }
};

const postSnippets = () => {
    const SNIPPET_LANG_MAP = Object.freeze({
        xml: 'html',
        css: 'css',
        jscript: 'js'
    });
    const NEW_LINE = '\r\n';

    return addSnippetsToPost;

    function addSnippetsToPost(codeBlocks, snippetsInsertionLocation) {
        const langData = {
            html: '',
            css: '',
            js: ''
        };

        codeBlocks.forEach(codeBlock => processBlockOfCode(codeBlock, langData));

        const langDataHasAnyValue = Object.values(langData).some(Boolean);
        if (langDataHasAnyValue) {
            addSnippets(
                [createCodepenSnippet(langData), createJSFiddleSnippet(langData)],
                snippetsInsertionLocation
            );
        }
    }

    function processBlockOfCode(block, langData) {
        const codeContent = [
            ...block.querySelectorAll('.code .line')
        ].reduce((lines, codeLine) => lines + fixSpaces(codeLine.textContent) + NEW_LINE, '');
        const codeLang = block.parentNode.querySelector('[data-code-lang-alias]').dataset.codeLangAlias;
        const mappedSnippetLang = SNIPPET_LANG_MAP[codeLang];

        if (mappedSnippetLang) {
            langData[mappedSnippetLang] = codeContent;
        }
    }

    // Code based on Codepen API tutorial: https://blog.codepen.io/documentation/api/prefill/
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

    // Code based on JSFiddle API tutorial: http://doc.jsfiddle.net/api/post.html
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

    function addSnippets(snippetsList, snippetsInsertionLocation) {
        const snippetsParent = document.createElement('div');
        snippetsParent.classList.add('snippets-parent');
        snippetsParent.append(...snippetsList);

        snippetsInsertionLocation.insertAdjacentElement('afterend', snippetsParent);

        if (snippetsInsertionLocation.parentNode.classList.contains('qa-c-item-content')) {
            snippetsParent.classList.add('inside-comment');
            snippetsInsertionLocation.classList.add('comment-snippets');
        }
    }
};

const codeBlockInteractiveBar = () => {
    const codeHighlightingPostProcessHandler = (() => {
        const listeners = {};
        
        return {
            subscribe(postId, codeBlockNumber, callback) {
                if (!listeners[`${postId}_${codeBlockNumber}`]) {
                    listeners[`${postId}_${codeBlockNumber}`] = [];
                }

                listeners[`${postId}_${codeBlockNumber}`].push(callback);
            },
            notifyAll(postId, codeBlockNumber, processedCodeBlock) {
                if (listeners[`${postId}_${codeBlockNumber}`]) {
                    listeners[`${postId}_${codeBlockNumber}`]
                        .forEach((fn) => fn(processedCodeBlock));
                    listeners[`${postId}_${codeBlockNumber}`] = null;
                }
            },
        };
    })();
    const codeLanguages = getPreparedLanguages();
    const MIN_LINES_NUMBER_TO_COLLAPSE_CODE = 20;
    const getCodeBlockBarFeatureItems = initInteractiveFeatures();

    return addInteractiveBarToCodeBlocks;

    function getPreparedLanguages() {
        const languages = {};

        SyntaxHighlighter.languages.entries.forEach(([name, code]) => languages[code] = name);

        return languages;
    }

    function addInteractiveBarToCodeBlocks(chosenCodeBlocks) {
        const { codeBlockBar, codeBlockParent } = decorateCodeBlock(chosenCodeBlocks);

        return function postProcessCodeBlock(processedCodeBlock) {
            const codeBlockDefaultParent = processedCodeBlock.parentNode;

            codeBlockParent.insertBefore(processedCodeBlock, codeBlockDefaultParent);
            codeBlockDefaultParent.remove();

            if (codeBlockBar.classList.contains('is-collapsible')) {
                processedCodeBlock.classList.add('collapsed-block');
            }

            const { postId, numberInPost } = getCodeBlockMeta(processedCodeBlock.parentNode);
            codeHighlightingPostProcessHandler.notifyAll(postId, numberInPost, processedCodeBlock);
        };
    }

    function decorateCodeBlock(codeBlock) {
        const codeBlockBar = document.createElement('div');
        codeBlockBar.classList.add('syntaxhighlighter-block-bar', 'block-bar-transparency');
        codeBlockBar.append(...getCodeBlockBarFeatureItems(codeBlock));

        if (codeBlockBar.querySelector('.syntaxhighlighter-collapsible-button')) {
            codeBlockBar.classList.add('is-collapsible');
        }

        const codeBlockParent = document.createElement('div');
        codeBlockParent.classList.add('syntaxhighlighter-parent');

        codeBlock.parentNode.insertBefore(codeBlockParent, codeBlock);
        codeBlockParent.append(codeBlockBar, codeBlock);

        requestAnimationFrame(() => codeBlockBar.classList.remove('block-bar-transparency'));

        return { codeBlockBar, codeBlockParent };
    }

    function initInteractiveFeatures() {
        class CollapsibleCodeBlocks {
            constructor() {
                this.collapsedState = 'collapsed-state';
                this.expandedState = 'expanded-state';
            }

            prepareCollapsibleAnimationValue(codeBlock) {
                const rawHeightProperty = '--code-block-raw-height';

                if (!codeBlock.style.getPropertyValue(rawHeightProperty)) {
                    const codeBlockRawHeight = codeBlock.querySelector('table').clientHeight;
                    codeBlock.style.setProperty(rawHeightProperty, `${ codeBlockRawHeight }px`);
                }
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
                codeBlockCollapsibleBtn.addEventListener('click', this.toggleCodeBlockBtnCollapseState.bind(this));

                return codeBlockCollapsibleBtn;
            }

            getCodeBlockCollapseBtnTxt(isCollapsed) {
                return isCollapsed ? 'Rozwiń' : 'Zwiń' ;
            }

            toggleCodeBlockBtnCollapseState({ target: codeBlockCollapsibleBtn }) {
                const codeBlock = codeBlockCollapsibleBtn.closest('.syntaxhighlighter-parent').querySelector('.syntaxhighlighter');
                const isCodeBlockCollapsed = codeBlock.classList.contains('collapsed-block');

                this.prepareCollapsibleAnimationValue(codeBlock);

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
            getLanguageName(codeBlock, codeLangName) {
                const languageExplicitName = codeLangName && codeLanguages[codeLangName];
                return languageExplicitName || SyntaxHighlighter.defaults['code-language'].fullName;
            }

            getLanguageAlias(codeBlock) {
                return codeBlock.dataset && codeBlock.dataset.codeLangName;
            }

            getLanguageLabel(codeBlock) {
                const languageNameLabel = document.createElement('div');
                languageNameLabel.dataset.codeLangAlias = this.getLanguageAlias(codeBlock);
                languageNameLabel.textContent = this.getLanguageName(codeBlock, languageNameLabel.dataset.codeLangAlias);
                languageNameLabel.classList.add('syntaxhighlighter-language');

                return languageNameLabel;
            }
        }

        class CodeCopy {
            constructor(codeBlock, toggleDrawer) {
                this.NEW_LINE = '\r\n';
                this.isCopyingSupported = false;
                this.COPY_BTN_STATE = Object.freeze({
                    INITIAL: {
                        TEXT: 'Kopiuj',
                    },
                    SUCCESS: {
                        TEXT: 'Skopiowano!',
                        CLASS_NAME: 'content-copy-btn--success',
                        action: toggleDrawer.hide,
                    },
                    ERROR: {
                        TEXT: 'Błąd kopiowania!',
                        CLASS_NAME: 'content-copy-btn--error',
                        action: toggleDrawer.hide,
                    },
                    UNAVAILABLE: {
                        CLASS_NAME: 'content-copy-btn--unavailable',
                    }
                });
    
                this.initCopyingMethod();
    
                const { postId, numberInPost } = getCodeBlockMeta(codeBlock);
                codeHighlightingPostProcessHandler.subscribe(postId, numberInPost, (processedCodeBlock) => {
                    if (this.isCopyingSupported) {
                        return;
                    }
                    
                    const copyBtn = processedCodeBlock.previousElementSibling.querySelector('.content-copy-btn');
                    copyBtn.disabled = true;
                    copyBtn.textContent = this.COPY_BTN_STATE.INITIAL.TEXT;
                    copyBtn.classList.add(this.COPY_BTN_STATE.UNAVAILABLE.CLASS_NAME);
                });
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
                    this.copyToClipboard = () => {
                        console.error('Copy to clipboard is not available!');
                    };
                }
            }

            getContentToCopy(target) {
                const blockOfCodeParent = target.closest('.syntaxhighlighter-parent');
                const linesOfCode = [...blockOfCodeParent.querySelector('.code .container').children];
                const contentToCopy = linesOfCode
                .reduce((concatenatedCode, { textContent: singleLineOfCode }) => {
                    return concatenatedCode + fixSpaces(singleLineOfCode) + this.NEW_LINE;
                }, '');

                return contentToCopy;
            }

            copyByClipboardAPI({ target }) {
                window.navigator.clipboard
                    .writeText(this.getContentToCopy(target))
                    .then(() => this.notifyAboutCopyResult(target, this.COPY_BTN_STATE.SUCCESS))
                    .catch(() => this.tryFallbackToOlderCopyMethod(target));
            }

            tryFallbackToOlderCopyMethod(target) {
                if (this.isCopyByQueryCommand) {
                    const copyingProbableSuccess = this.copyByQueryCommand({ target });
                    
                    if (copyingProbableSuccess) {
                        this.notifyAboutCopyResult(target, this.COPY_BTN_STATE.SUCCESS);
                    } else {
                        this.notifyAboutCopyResult(target, this.COPY_BTN_STATE.ERROR);
                    }
                } else {
                    this.notifyAboutCopyResult(target, this.COPY_BTN_STATE.ERROR);
                }
            }
            
            notifyAboutCopyResult(target, state) {
                target.addEventListener('transitionend', () => {
                    setTimeout(() => {
                        if (state.action) {
                            state.action();
                        }
                        
                        target.classList.remove(state.CLASS_NAME);
                        target.textContent = this.COPY_BTN_STATE.INITIAL.TEXT;
                        target.disabled = false;
                    }, 1000);
                },{ once: true });
                
                target.classList.add(state.CLASS_NAME);
                target.textContent = state.TEXT;
                target.disabled = true;
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
    
                /*
                    document.execCommand(..) method returns boolean indicating whether requested command is supported by browser or not,
                    rather than informing whether that command was performed successfully or not.
                    Therefore, relying on that returned value does not give certainty of command effect.
                    However, as there is no simple way to determine the result, checking that boolean is done
                    in order to know the possible result in any way.
                
                    https://developer.mozilla.org/en-US/docs/Web/API/document/execCommand#return_value
                 */
                const copyingProbableSuccess = document.execCommand('copy');
                document.body.removeChild(textArea);
                
                return copyingProbableSuccess;
            }

            addClickListener(copyCodeBtn) {
                copyCodeBtn.addEventListener('click', this.copyToClipboard.bind(this));
            }

            getCopyToClipboardBtn() {
                const copyCodeBtn = document.createElement('button');
                copyCodeBtn.textContent = this.COPY_BTN_STATE.INITIAL.TEXT;
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
        
        class FeaturesDrawer {
            constructor(codeBlock) {
                this.featuresDrawerList = null;
                this.featuresDrawerBtn = null;
                this.clearAndExitSearch = null;
                this.featureDrawerOffClickListener = this.getFeatureDrawerOffClickListener();
                this.FEATURES_DRAWER_CLASSES = {
                    LIST: 'features-drawer-list',
                    LIST_ITEM: 'features-drawer-list__item',
                    BUTTON: 'features-drawer__button',
                    HIDDEN: 'features-drawer-list--hidden',
                    OPENED: 'features-drawer__button--opened',
                };
                
                this.initButton();
                this.initDOM(codeBlock);
            }
    
            initDOM(codeBlock) {
                this.featuresDrawerList = document.createElement('ul');
                this.featuresDrawerList.classList.add(this.FEATURES_DRAWER_CLASSES.LIST, this.FEATURES_DRAWER_CLASSES.HIDDEN);
    
                const { postId, numberInPost } = getCodeBlockMeta(codeBlock);
                codeHighlightingPostProcessHandler.subscribe(postId, numberInPost, (processedCodeBlock) => {
                    const insertionRef =
                      processedCodeBlock.previousElementSibling.querySelector(`.${ this.FEATURES_DRAWER_CLASSES.BUTTON }`);
    
                    insertionRef.parentNode.insertBefore(this.featuresDrawerList, insertionRef);
                });
            }
            
            initButton() {
                this.featuresDrawerBtn = document.createElement('button');
                this.featuresDrawerBtn.type = 'button';
                this.featuresDrawerBtn.textContent = 'Opcje';
                this.featuresDrawerBtn.classList.add(this.FEATURES_DRAWER_CLASSES.BUTTON);
                this.featuresDrawerBtn.addEventListener('click', (event) => {
                    if (this.featuresDrawerList.classList.contains(this.FEATURES_DRAWER_CLASSES.HIDDEN)) {
                        this.showDrawer(event);
                    } else {
                        this.hideDrawer();
                    }
                });
            }
            
            showDrawer(event) {
                if (!(event instanceof Event)) {
                    throw TypeError(`event argument should be instance of Event! Received: ${ event }`);
                }
    
                this.clearAndExitSearch();
                this.featuresDrawerList.classList.remove(this.FEATURES_DRAWER_CLASSES.HIDDEN);
                this.featuresDrawerBtn.classList.add(this.FEATURES_DRAWER_CLASSES.OPENED);
    
                // This event will also trigger document click listener attached below, because it is in bubbling (so the same) phase.
                event.stopPropagation();
    
                document.addEventListener('click', this.featureDrawerOffClickListener, { once: true });
            }
            
            hideDrawer() {
                this.featuresDrawerList.classList.add(this.FEATURES_DRAWER_CLASSES.HIDDEN);
                this.featuresDrawerBtn.classList.remove(this.FEATURES_DRAWER_CLASSES.OPENED);
    
                document.removeEventListener('click', this.featureDrawerOffClickListener, { once: true });
            }
            
            getFeatureDrawerOffClickListener() {
                return ({ target }) => {
                    if (!target.closest(`.${ this.FEATURES_DRAWER_CLASSES.LIST }`)) {
                        this.featuresDrawerList.classList.add(this.FEATURES_DRAWER_CLASSES.HIDDEN);
                        this.featuresDrawerBtn.classList.remove(this.FEATURES_DRAWER_CLASSES.OPENED);
                    }
                }
            }
            
            getFeaturesDrawerBtn() {
                return this.featuresDrawerBtn;
            }
            
            addFeatures(featureDOMs) {
                featureDOMs.forEach((element) => {
                    const listItem = document.createElement('li');
                    listItem.classList.add(this.FEATURES_DRAWER_CLASSES.LIST_ITEM);
                    listItem.appendChild(element);
    
                    this.featuresDrawerList.appendChild(listItem);
                });
            }
            
            assignClearAndExitSearch(clearAndExitSearch) {
                this.clearAndExitSearch = clearAndExitSearch;
            }
    
            getContainer() {
                return this.featuresDrawerList;
            }
        }
        
        class SearchThroughCode {
            constructor(codeBlock, featuresDrawerToggler, drawerContainer) {
                this.searchBtn = null;
                this.searchField = null;
                this.processedCodeBlock = null;
                this.codeContainer = null;
                this.drawerContainer = drawerContainer;
                this.featuresDrawerToggler = featuresDrawerToggler;
                this.choosePrevOccurrence = null;
                this.chooseNextOccurrence = null;
                this.chosenOccurrence = null;
                this.foundOccurrences = null;
                this.DEFAULT_OCCURRENCE_VALUE = '-';
                this.codeContainerOriginalHTML = '';
                this.currentOccurrenceIndex = -1;
                this.numberOfOccurrences = 0;
                this.foundPhrases = [];
                this.codeLineHeight = 0;
                this.CLASSES = {
                    SEARCH_WRAPPER: 'search-through-code__wrapper',
                    FIELDS_CONTAINER: 'search-through-code__fields-container',
                    FOUND: 'search-through-code__found-phrase',
                    HIGHLIGHTED: 'search-through-code__found-phrase--highlighted',
                    HIDDEN: 'search-through-code--hidden',
                    FOUND_PAIR_WHOLE: 'matched-whole',
                    FOUND_PAIR_BEGIN: 'matched-begin',
                    FOUND_PAIR_MIDDLE: 'matched-middle',
                    FOUND_PAIR_END: 'matched-end',
                };
                this.KEYS = {
                    ENTER: 'Enter',
                    ARROW_UP: 'ArrowUp',
                    ARROW_DOWN: 'ArrowDown',
                    ESCAPE: 'Escape',
                    F: 'f',
                };
                this.DRAWER_ADJUSTMENT_KEYS = {
                    HORIZONTAL_VALUE: '--horizontal-adjustment-value',
                    VERTICAL_VALUE: '--vertical-adjustment-value',
                };
    
                this.initCodeContainer(codeBlock);
            }
            
            initCodeContainer(codeBlock) {
                const { postId, numberInPost } = getCodeBlockMeta(codeBlock);
                codeHighlightingPostProcessHandler.subscribe(postId, numberInPost, (processedCodeBlock) => {
                    this.processedCodeBlock = processedCodeBlock;
                    this.codeContainer = processedCodeBlock.querySelector('.container');
                    this.codeContainerOriginalHTML = this.codeContainer.innerHTML;
                    this.codeLineHeight = processedCodeBlock.querySelector('.line').getBoundingClientRect().height;
    
                    this.createSearchField();
                    this.prepareSearchToBeOpenedByKeyboardShortcut();
                });
            }
    
            getSearchBtn() {
                this.searchBtn = document.createElement('button');
                this.searchBtn.type = 'button';
                this.searchBtn.textContent = 'Szukaj';
                this.searchBtn.addEventListener('click', () => this.toggleSearchFeature(true));
                
                return this.searchBtn;
            }
            
            createSearchField() {
                this.searchInput = document.createElement('input');
                this.searchInput.type = 'search';
                this.searchInput.addEventListener('input', this.doSearch.bind(this));
                this.searchInput.addEventListener('keydown', this.handleSearchNavByKeyboard.bind(this));
    
                const actionContainer = document.createElement('div');
                actionContainer.classList.add(this.CLASSES.FIELDS_CONTAINER);
                actionContainer.append(this.searchInput);

                const navContainer = document.createElement('div');
                navContainer.classList.add(this.CLASSES.FIELDS_CONTAINER);
                navContainer.innerHTML = `
                    <output>
                        <span data-search-nav="chosenOccurrence">${ this.DEFAULT_OCCURRENCE_VALUE }</span>
                        /
                        <span data-search-nav="foundOccurrences">${ this.DEFAULT_OCCURRENCE_VALUE }</span>
                    </output>
                    <button class="search-through-code__button" data-search-nav="prev" title="Poprzedni" disabled="true" type="button">&uarr;</button>
                    <button class="search-through-code__button" data-search-nav="next" title="Następny" disabled="true" type="button">&darr;</button>
                    <button class="search-through-code__button" data-search-close title="Zamknij" type="button">&#120;</button>
                `.trim();
                navContainer.addEventListener('click', this.handleSearchNav.bind(this));
                navContainer.querySelector('[data-search-close]').addEventListener('click', this.clearAndExit.bind(this));
    
                this.choosePrevOccurrence = navContainer.querySelector('[data-search-nav="prev"]');
                this.chooseNextOccurrence = navContainer.querySelector('[data-search-nav="next"]');
                this.chosenOccurrence = navContainer.querySelector('[data-search-nav="chosenOccurrence"]');
                this.foundOccurrences = navContainer.querySelector('[data-search-nav="foundOccurrences"]');
                
                this.searchField = document.createElement('div');
                this.searchField.classList.add(this.CLASSES.SEARCH_WRAPPER, this.CLASSES.HIDDEN);
                this.searchField.append(actionContainer, navContainer);
                
                this.drawerContainer.parentNode.insertBefore(this.searchField, this.drawerContainer);
            }
            
            prepareSearchToBeOpenedByKeyboardShortcut() {
                // Make code block container focusable, so it can receive 'keydown' event
                this.codeContainer.addEventListener('click', () => {
                    this.codeContainer.tabIndex = 0;
                    this.codeContainer.focus();
                });
                this.codeContainer.addEventListener('keydown', (event) => {
                    if (event.key === this.KEYS.F && event.ctrlKey) {
                        if (!this.searchField.classList.contains(this.CLASSES.HIDDEN)) {
                            return;
                        }
                        
                        event.preventDefault();
                        this.toggleSearchFeature(true);
                        
                        // turn off code block focus-ability until it will be clicked by mouse
                        this.codeContainer.tabIndex = -1;
                    }
                });
            }
    
            toggleSearchFeature(show) {
                this.featuresDrawerToggler.hide();
                this.searchField.classList.toggle(this.CLASSES.HIDDEN, !show);
    
                if (show) {
                    if (this.searchInput.value) {
                        this.doSearch({ target: this.searchInput }, true);
                    }
                    
                    this.searchInput.focus();
                }
            }
            
            clearAndExit() {
                this.codeContainer.innerHTML = this.codeContainerOriginalHTML;
                this.searchField.classList.add(this.CLASSES.HIDDEN);
            }
    
            doSearch({ target: { value } }, preserveLastOccurrenceIndex) {
                if (this.searchField.classList.contains(this.CLASSES.HIDDEN)) {
                    return;
                }
                
                this.codeContainer.innerHTML = this.codeContainerOriginalHTML;
                
                if (!value) {
                    this.foundPhrases = [];
                    this.currentOccurrenceIndex = -1;
                    
                    this.updateChosenOccurrence(this.DEFAULT_OCCURRENCE_VALUE);
                    this.updateFoundOccurrences(this.DEFAULT_OCCURRENCE_VALUE);
                    this.setDrawerContainerPosition();
                    
                    return;
                }
                
                this.numberOfOccurrences = 0;
    
                const escapedValue = value.replace(/\W/g, (match) => `\\${ match }`);
                let occurrenceCounter = 0;
                
                this.codeContainer.querySelectorAll('.line').forEach((codeLine, lineIndex, allLines) => {
                    const nextLineAvailable = allLines[lineIndex + 1];
                    const matchedIndexes = this._getMatchedIndexes(escapedValue, codeLine.textContent);
                    
                    if (matchedIndexes.length === 0) {
                        return;
                    }
                    
                    const denseIndexes = this._densifyIndexes(matchedIndexes);
                    const targetIndexes = this._getTargetIndexes(denseIndexes);
                    let charCounter = 0;
                    
                    // looping over childNodes, because some characters are inserted as text nodes, not HTML elements
                    codeLine.childNodes.forEach((codeFragment) => {
                        const chars = codeFragment.textContent.split('');
                        const outputChars = chars.reduce((result, char) => {
                            if (charCounter in targetIndexes) {
                                const {
                                    goingToNextFragment, lastCharInFragment
                                } = this._getFragmentNavMetadata(denseIndexes, charCounter);
                                
                                const occurrenceElement = document.createElement('span');
                                occurrenceElement.classList.add(this.CLASSES.FOUND, targetIndexes[charCounter]);
                                occurrenceElement.dataset.foundOccurrence = occurrenceCounter;
                                // browser will automatically escape weird/reserved characters
                                occurrenceElement.textContent = char;
    
                                result += occurrenceElement.outerHTML;
                                
                                if (goingToNextFragment || (lastCharInFragment && nextLineAvailable)) {
                                    occurrenceCounter++;
                                }
                            } else {
                                const charElement = document.createElement('span');
                                // browser will automatically escape weird/reserved characters
                                charElement.textContent = char;
                                
                                result += charElement.innerHTML;
                            }
                            
                            charCounter++;
                            
                            return result;
                        }, '');
                        
                        this._updateCodeFragmentContent(codeFragment, outputChars);
                    });
    
                    this.numberOfOccurrences += denseIndexes.length;
                });
    
                this.foundPhrases = [...this.codeContainer.querySelectorAll(`.${ this.CLASSES.FOUND }`)];
                
                if (this.foundPhrases.length) {
                    if (!preserveLastOccurrenceIndex) {
                        this.currentOccurrenceIndex = 0;
                    }
                    
                    this.updateChosenOccurrence(this.currentOccurrenceIndex + 1);
                    this.updateFoundOccurrences(this.numberOfOccurrences);
                } else {
                    this.currentOccurrenceIndex = 0;
                    this.updateChosenOccurrence(this.currentOccurrenceIndex);
                    this.numberOfOccurrences = 0;
                    this.updateFoundOccurrences(this.numberOfOccurrences);
                    this.setDrawerContainerPosition();
                }
            }
            
            _getMatchedIndexes(escapedValue, codeLineContent) {
                const matchedIndexes = [];
                const uniqueIndexes = new Set();
                const searchRegExp = new RegExp(escapedValue, 'gdi');
                let lineMatches = null;
    
                while (lineMatches = searchRegExp.exec(codeLineContent)) {
                    const [start, end] = lineMatches.indices[0];
                    const decrementedEnd = end - 1;
        
                    if (!uniqueIndexes.has(start) && !uniqueIndexes.has(decrementedEnd)) {
                        matchedIndexes.push([start, decrementedEnd]);
                    }
        
                    uniqueIndexes.add(start).add(decrementedEnd);
                }
                
                return matchedIndexes;
            }
            
            _densifyIndexes(matchedIndexes) {
                return matchedIndexes.map(([start, end]) => {
                    const denseArray = [
                        start,
                        ...new Array(end - start).fill(start).map((num, i) => num + i),
                        end
                    ];
        
                    return [...new Set(denseArray)];
                });
            }
    
            _getTargetIndexes(denseIndexes) {
                const _targetIndexes = [];
                
                for (let i = 0; i < denseIndexes.length; i++) {
                    const denseIndexesGroup = denseIndexes[i];
        
                    denseIndexesGroup.forEach((matchedCharIndex, indexInMatchedGroup) => {
                        _targetIndexes[matchedCharIndex] = this._mapIndexToClassName(
                          denseIndexesGroup.length, indexInMatchedGroup
                        );
                    });
                }
                
                return _targetIndexes;
            }
            
            _mapIndexToClassName(numOfIndexes, index) {
                if (numOfIndexes === 0) {
                    throw Error('numOfIndexes cannot be 0!');
                } else if (numOfIndexes === 1) {
                    return this.CLASSES.FOUND_PAIR_WHOLE;
                } else if (numOfIndexes === 2) {
                    return index === 0 ? this.CLASSES.FOUND_PAIR_BEGIN : this.CLASSES.FOUND_PAIR_END;
                } else {
                    if (index === 0) {
                        return this.CLASSES.FOUND_PAIR_BEGIN;
                    } else if (index === numOfIndexes - 1) {
                        return this.CLASSES.FOUND_PAIR_END;
                    } else {
                        return this.CLASSES.FOUND_PAIR_MIDDLE;
                    }
                }
            }
            
            _getFragmentNavMetadata(denseIndexes, charCounter) {
                let goingToNextFragment = false;
                let lastCharInFragment = false;
                
                for (let fragmentIdx = 0; fragmentIdx < denseIndexes.length; fragmentIdx++) {
                    const indexesGroup = denseIndexes[fragmentIdx];
        
                    lastCharInFragment = indexesGroup[indexesGroup.length - 1] === charCounter;
                    const nextFragmentExists = (fragmentIdx + 1) in denseIndexes;
        
                    if (lastCharInFragment && nextFragmentExists) {
                        goingToNextFragment = true;
                        break;
                    }
                }
    
                return { goingToNextFragment, lastCharInFragment };
            }
    
            _updateCodeFragmentContent(codeFragment, outputChars) {
                if (codeFragment.nodeType === Node.ELEMENT_NODE) {
                    codeFragment.innerHTML = outputChars;
                } else {
                    // HTML code cannot be inserted in non Element type of Node, so it need to be swapped for one
                    const codeFragmentAsElement = document.createElement('code');
                    codeFragmentAsElement.innerHTML = outputChars;
    
                    codeFragment.parentNode.insertBefore(codeFragmentAsElement, codeFragment);
                    codeFragment.parentNode.removeChild(codeFragment);
                }
            }
            
            handleSearchNav({ target }) {
                if (this.foundPhrases.length === 0) {
                    return;
                }
                
                const navDirection = target.dataset.searchNav;
                
                if (navDirection) {
                    if (target === this.chooseNextOccurrence) {
                        if (this.currentOccurrenceIndex < this.numberOfOccurrences - 1) {
                            this.currentOccurrenceIndex++;
                        } else {
                            this.currentOccurrenceIndex = 0;
                        }
                    } else if (target === this.choosePrevOccurrence) {
                        if (this.currentOccurrenceIndex > 0) {
                            this.currentOccurrenceIndex--;
                        } else {
                            this.currentOccurrenceIndex = this.numberOfOccurrences - 1;
                        }
                    } else {
                        throw TypeError(`Unknown search navigation target: ${ target.outerHTML }`);
                    }
                    
                    this.updateChosenOccurrence(this.currentOccurrenceIndex + 1);
                }
            }

            handleSearchNavByKeyboard(event) {
                const clickEvent = new Event('click', { bubbles: true });
                
                if (event.key === this.KEYS.ENTER) {
                    event.preventDefault();
    
                    if (event.shiftKey) {
                        this.choosePrevOccurrence.dispatchEvent(clickEvent);
                    } else {
                        this.chooseNextOccurrence.dispatchEvent(clickEvent);
                    }
                } else if (event.key === this.KEYS.ARROW_UP) {
                    this.choosePrevOccurrence.dispatchEvent(clickEvent);
                } else if (event.key === this.KEYS.ARROW_DOWN) {
                    this.chooseNextOccurrence.dispatchEvent(clickEvent);
                } else if (event.key === this.KEYS.ESCAPE) {
                    event.preventDefault();
                    this.clearAndExit();
                }
            }
            
            updateChosenOccurrence(value) {
                if (!value || value === this.DEFAULT_OCCURRENCE_VALUE) {
                    this.choosePrevOccurrence.disabled = true;
                    this.chooseNextOccurrence.disabled = true;
                } else {
                    this.choosePrevOccurrence.disabled = false;
                    this.chooseNextOccurrence.disabled = false;
                    
                    const matchedOccurrences = this.foundPhrases.filter((phraseElement) => {
                        const elementMatchesWithCurrentOccurrence =
                          Number(phraseElement.dataset.foundOccurrence) === this.currentOccurrenceIndex;
                        phraseElement.classList.toggle(this.CLASSES.HIGHLIGHTED, elementMatchesWithCurrentOccurrence);
                        
                        return elementMatchesWithCurrentOccurrence;
                    });
    
                    const phrasePartsInRow = {
                        firstPart: matchedOccurrences[0],
                        lastPart: matchedOccurrences.length === 1 ?
                          matchedOccurrences[0] : matchedOccurrences[matchedOccurrences.length - 1],
                    };
                    
                    const scrollData = this.scrollToOccurrence(phrasePartsInRow);
                    
                    if (scrollData) {
                        this.adjustSearchContainerPosition(scrollData);
                    }
                }
                
                this.chosenOccurrence.textContent = value;
            }
    
            scrollToOccurrence(phrasePartsInRow) {
                const isBlockScrollable =
                  (
                    this.processedCodeBlock.previousElementSibling.classList.contains('is-collapsible') &&
                    this.processedCodeBlock.classList.contains('collapsed-block')
                  ) || (this.processedCodeBlock.clientWidth !== this.processedCodeBlock.scrollWidth);
                
                if (!isBlockScrollable) {
                    return;
                }
                
                const codeLine = phrasePartsInRow.firstPart.parentNode.parentNode;
                const { shouldScrollVertically, topScroll, bottomScroll } = this._handleVerticalScroll(codeLine);
                const {
                    shouldScrollHorizontally, leftScroll, currentLeftOffset, currentRightOffset
                } = this._handleHorizontalScroll(phrasePartsInRow);
                
                if (shouldScrollVertically || shouldScrollHorizontally) {
                    this.processedCodeBlock.scroll({
                        top: topScroll,
                        left: leftScroll,
                    });
                }
                
                return {
                    top: topScroll,
                    left: currentLeftOffset,
                    right: currentRightOffset,
                    bottom: bottomScroll,
                };
            }
            
            _handleVerticalScroll(codeLine) {
                const codeLineNumber = [
                    ...codeLine.parentNode.children
                ].findIndex((line) => line === codeLine);
                const blockScrollPositionStart = Math.ceil(this.processedCodeBlock.scrollTop / this.codeLineHeight);
                const blockScrollPositionEnd = blockScrollPositionStart +
                  (Math.floor(this.processedCodeBlock.getBoundingClientRect().height / this.codeLineHeight) - 1);
                const shouldScrollVertically = blockScrollPositionStart > codeLineNumber || blockScrollPositionEnd <= codeLineNumber;
                const topScroll = codeLineNumber * this.codeLineHeight;
                const bottomScroll = (codeLineNumber + 1) * this.codeLineHeight;
                
                return { shouldScrollVertically, topScroll, bottomScroll };
            }
            
            _handleHorizontalScroll({ firstPart, lastPart }) {
                const isOccurrencePartDescendantOfCodeBlock = this.processedCodeBlock.contains(firstPart);
                if (!isOccurrencePartDescendantOfCodeBlock) {
                    throw Error('Found occurrence parts are not descendants of code block!');
                }
    
                const currentPartsParentOffset = firstPart.offsetParent;
    
                if (!currentPartsParentOffset.matches('.container') || currentPartsParentOffset.offsetParent !== this.processedCodeBlock) {
                    throw Error('Invalid offset parents to setup searched occurrences for scrolling properly!');
                }
    
                const codeBlockScrollLeft = this.processedCodeBlock.scrollLeft;
                const codeBlockClientWidth = this.processedCodeBlock.clientWidth;
                const rowOffsetLeft = currentPartsParentOffset.offsetLeft;
                const [ leftSpace, rightSpace ] = [
                  this.__getOccurrencePartExtraSpace(firstPart), this.__getOccurrencePartExtraSpace(lastPart)
                ];
                const occurrenceStartOffset = rowOffsetLeft + firstPart.offsetLeft - leftSpace;
                const occurrenceEndOffset = rowOffsetLeft + lastPart.offsetLeft + Math.ceil(lastPart.getBoundingClientRect().width) + rightSpace;
                const shouldScrollLeft = codeBlockScrollLeft > occurrenceStartOffset;
                const shouldScrollRight = codeBlockClientWidth + codeBlockScrollLeft < occurrenceEndOffset;
                const shouldScrollHorizontally = shouldScrollLeft || shouldScrollRight;
                const scrollRight = occurrenceEndOffset - codeBlockClientWidth;
                
                let leftScroll = codeBlockScrollLeft;
    
                if (shouldScrollHorizontally) {
                    leftScroll = shouldScrollLeft ? occurrenceStartOffset : scrollRight;
                }
    
                return {
                    currentLeftOffset: occurrenceStartOffset - leftScroll,
                    currentRightOffset: occurrenceEndOffset - leftScroll,
                    shouldScrollHorizontally, leftScroll,
                };
            }
            
            __getOccurrencePartExtraSpace(part) {
                let { borderLeft, paddingLeft, left } = window.getComputedStyle(part, '::before');
                borderLeft = Math.abs(Number.parseInt(borderLeft)) || 0;
                paddingLeft = Math.abs(Number.parseInt(paddingLeft)) || 0;
                left = Math.abs(Number.parseInt(left)) || 0;
                
                return borderLeft + paddingLeft + left;
            }
    
            adjustSearchContainerPosition(scrollData) {
                const searchFieldOffsetParent = this.searchField.offsetParent;
                
                if (searchFieldOffsetParent !== this.searchField.parentNode) {
                    throw TypeError(
                      `searchFieldOffsetParent is not the proper parent! searchFieldOffsetParent: ${ searchFieldOffsetParent.outerHTML }`
                    );
                }
                
                const searchFieldOffsetLeft = this.searchField.offsetLeft + searchFieldOffsetParent.offsetLeft;
                const searchFieldOffsetRight = this.searchField.offsetWidth + searchFieldOffsetLeft;
                const searchFieldOffsetTop =
                  searchFieldOffsetParent.parentNode.offsetHeight - (
                    (
                      (searchFieldOffsetParent.parentNode.offsetHeight - searchFieldOffsetParent.offsetHeight) / 2
                    ) + this.searchField.offsetTop
                  );
                const searchFieldOffsetBottom = searchFieldOffsetTop + this.searchField.offsetHeight;
                const searchFieldHorizontallyCoversOccurrence =
                  scrollData.right > searchFieldOffsetLeft && scrollData.left < searchFieldOffsetRight;
                const searchFieldVerticallyCoversOccurrence =
                  searchFieldOffsetTop + this.processedCodeBlock.scrollTop <= scrollData.top &&
                  searchFieldOffsetBottom + this.processedCodeBlock.scrollTop >= scrollData.bottom;
                const shouldAdjustHorizontally = searchFieldHorizontallyCoversOccurrence && searchFieldVerticallyCoversOccurrence;
                const shouldAdjustVertically = shouldAdjustHorizontally && scrollData.left < this.searchField.offsetWidth;
    
                if (shouldAdjustVertically) {
                    const verticalAdjustValue = scrollData.bottom - this.processedCodeBlock.scrollTop;
                    this.setDrawerContainerPosition({ verticalValue: verticalAdjustValue });
                } else if (shouldAdjustHorizontally) {
                    const horizontalAdjustValue = (searchFieldOffsetRight - scrollData.left) * -1;
                    this.setDrawerContainerPosition({ horizontalValue: horizontalAdjustValue });
                } else {
                    this.setDrawerContainerPosition();
                }
            }
            
            setDrawerContainerPosition(position = {}) {
                const horizontalValue = Math.min(0, position.horizontalValue || 0);
                const verticalValue = Math.max(0, position.verticalValue || 0);
                
                this.searchField.style.setProperty(this.DRAWER_ADJUSTMENT_KEYS.HORIZONTAL_VALUE, horizontalValue);
                this.searchField.style.setProperty(this.DRAWER_ADJUSTMENT_KEYS.VERTICAL_VALUE, verticalValue);
            }
            
            updateFoundOccurrences(value) {
                this.foundOccurrences.textContent = value;
            }
        }

        class CodeBlockFullScreen {
            constructor(featuresDrawerToggler) {
                this.featuresDrawerToggler = featuresDrawerToggler;
                this.MINIMUM_CODE_BLOCK_LONGEST_LINE_LENGTH = 30;
                this.MINIMUM_WIDTH_FOR_FULL_SCREEN = 400;
                this.FALLBACK_FULL_SCREEN_CONTAINER_CLASS_NAME = 'syntaxhighlighter-fallback-full-screen-container';
                this.MODERN_FULL_SCREEN_CENTERING_CLASS_NAME = 'syntaxhighlighter-parent--center-full-screen';
                this.enableFullScreen = true;
                this.isFullScreen = false;
                this.isModernFullScreenFeatureSupported = !!(Element.prototype.requestFullscreen && document.exitFullscreen);
                this.fullScreenBtn = null;
            }

            setupCodeBlockResizeWatcher(codeBlock) {
                const codeBlockMaxLineLength = Math.max(
                    ...codeBlock.childNodes[0].textContent.split('\n').map(line => line.length)
                );
                this.enableFullScreen = codeBlockMaxLineLength >= this.MINIMUM_CODE_BLOCK_LONGEST_LINE_LENGTH;

                if (!this.enableFullScreen) {
                    return;
                }

                if (!window.ResizeObserver) {
                    return;
                }

                const { postId, numberInPost } = getCodeBlockMeta(codeBlock);

                codeHighlightingPostProcessHandler.subscribe(postId, numberInPost, (processedCodeBlock) => {
                    let hideFullScreenButton = false;
                    
                    const resizeObserver = new ResizeObserver((entries) => {
                        entries.forEach((entry) => {
                            if (entry.contentBoxSize) {
                                if (Array.isArray(entry.contentBoxSize)) {
                                    entry.contentBoxSize.forEach((size) => {
                                        hideFullScreenButton = size.inlineSize <= this.MINIMUM_WIDTH_FOR_FULL_SCREEN;
                                    });
                                } else {
                                    /*
                                        Firefox deviation:
                                        https://caniuse.com/?search=contentboxsize#:~:text=Implemented%20as%20a%20single%20object
                                    */
                                    hideFullScreenButton = entry.contentBoxSize.inlineSize <= this.MINIMUM_WIDTH_FOR_FULL_SCREEN;
                                }
                            }
                        });

                        this.fullScreenBtn.classList.toggle('syntaxhighlighter-block-bar-item__full-screen-btn--unavailable', hideFullScreenButton);
                        this.fullScreenBtn.disabled = hideFullScreenButton;
                    });
                    resizeObserver.observe(processedCodeBlock);
                });
            }
            
            getFullScreenBtn() {
                this.fullScreenBtn = document.createElement('button');
                this.fullScreenBtn.classList.add('syntaxhighlighter-block-bar-item__full-screen-btn');
                if (!this.enableFullScreen) {
                    this.fullScreenBtn.classList.add('syntaxhighlighter-block-bar-item__full-screen-btn--unavailable');
                }
                this.fullScreenBtn.textContent = 'Pełny ekran';
                this.fullScreenBtn.type = 'button';
                
                if (this.enableFullScreen) {
                    this.fullScreenBtn.addEventListener('click', this.fullScreenOnClick.bind(this));
                } else {
                    this.fullScreenBtn.disabled = true;
                    this.fullScreenBtn.addEventListener('click', () => console.error('Full screen is not available!'));
                }

                return this.fullScreenBtn;
            }

            async fullScreenOnClick({ target }) {
                const codeBlock = target.closest('.syntaxhighlighter-parent').querySelector('.syntaxhighlighter');
                const fullScreenTarget = codeBlock.parentNode;
                
                if (this.isModernFullScreenFeatureSupported) {
                    if (this.isFullScreen) {
                        await document.exitFullscreen()
                            .then(() => {
                                fullScreenTarget.classList.remove(this.MODERN_FULL_SCREEN_CENTERING_CLASS_NAME);
                                this.postProcessFullScreenToggle(codeBlock);
                            })
                            .catch(console.error);
                    } else {
                        /*
                            User might exit full screen via ESC or native browser button, instead of
                            re-using fullScreenBtn, which won't trigger it's click event.
                            Thus, optional post processing is needed in such case.

                            await is not used with Promise to prevent code from stoppping it's execution.
                            Full screen event listener needs to be attached before entering full screen
                            and it needs to run in background, because it waits for user to exit the full screen.
                        */
                        this.listenToFullScreenExitEvent().then(() => {
                            if (this.isFullScreen) {
                                this.postProcessFullScreenToggle(codeBlock);
                            }
                        });

                        await fullScreenTarget.requestFullscreen()
                            .then(() => fullScreenTarget.classList.add(this.MODERN_FULL_SCREEN_CENTERING_CLASS_NAME))
                            .catch(() => this.fallbackFullScreenToggle(fullScreenTarget))
                            .finally(() => this.postProcessFullScreenToggle(codeBlock));
                    }
                } else {
                    this.fallbackFullScreenToggle(fullScreenTarget);
                    document.body.classList.toggle('qa-disable-scroll', !this.isFullScreen);
                    this.postProcessFullScreenToggle(codeBlock);
                }
            }

            fallbackFullScreenToggle(fullScreenTarget) {
                if (this.isFullScreen) {
                    const fullScreenContainer = document.querySelector(`.${this.FALLBACK_FULL_SCREEN_CONTAINER_CLASS_NAME}`);
                    const markerElement = document.getElementById('syntaxhighlighterFullScreenMarker');

                    markerElement.parentNode.insertBefore(fullScreenTarget, markerElement);
                    markerElement.remove();
                    fullScreenContainer.remove();
                } else {
                    const fullScreenContainer = document.createElement('aside');
                    fullScreenContainer.classList.add(this.FALLBACK_FULL_SCREEN_CONTAINER_CLASS_NAME);
                    
                    const { width, height } = window.getComputedStyle(fullScreenTarget);
                    const markerElement = document.createElement('div');
                    markerElement.id = 'syntaxhighlighterFullScreenMarker';
                    markerElement.style.width = width;
                    markerElement.style.height = height;
                    
                    fullScreenTarget.parentNode.insertBefore(markerElement, fullScreenTarget);
                    document.body.appendChild(fullScreenContainer);
                    fullScreenContainer.appendChild(fullScreenTarget);
                }
                

                fullScreenTarget.classList.toggle('syntaxhighlighter-block--full-screen');
            }

            listenToFullScreenExitEvent() {
                return new Promise((resolve) => {
                    document.addEventListener('fullscreenchange', function listener() {
                        if (!document.fullscreenElement) {
                            document.removeEventListener('fullscreenchange', listener);
                            resolve();
                        }
                    });
                });
            }

            postProcessFullScreenToggle(codeBlock) {
                const isCodeBlockCollapsed = codeBlock.classList.contains('collapsed-block');
                const isCodeBlockCollapsible = codeBlock.previousElementSibling.classList.contains('is-collapsible');

                if (isCodeBlockCollapsible) {
                    const collapsibleCodeBlockBtn = codeBlock.previousElementSibling.querySelector('.syntaxhighlighter-collapsible-button');

                    if ((!this.isFullScreen && isCodeBlockCollapsed) || (this.isFullScreen && !isCodeBlockCollapsed)) {
                        // this breaks SOLID and should be done along with extending CollapsibleCodeBlocks class API... but i was lazy here :(
                        const codeBlockRawHeight = '--code-block-raw-height';
                        if (this.isFullScreen) {
                            codeBlock.style.removeProperty(codeBlockRawHeight);
                        } else {
                            const heightValue = codeBlock.scrollHeight + (codeBlock.scrollHeight - codeBlock.clientHeight);
                            codeBlock.style.setProperty(codeBlockRawHeight, `${heightValue}px`);
                        }
                        
                        collapsibleCodeBlocks.toggleCodeBlockBtnCollapseState({ target: collapsibleCodeBlockBtn });
                        this.featuresDrawerToggler.hide();
                    }
                    
                    collapsibleCodeBlockBtn.disabled = !this.isFullScreen;
                }

                this.isFullScreen = !this.isFullScreen;
            }

            checkIfFullScreenIsActive() {
                return !!this.isFullScreen;
            }
        }

        class HorizontalCodeBlockExtenderOnHover {
            #overflowingRoots = document.querySelectorAll('.qa-main-wrapper, .qa-main');
            #horizontallyExtendedCodeBlock = null;
            #abortController = null;
            // TODO: CollapsibleCodeBlocks class should expose method returning that button or toggling disable state by itself
            #collapsibleToggleBtn = null;
            #checkIfFullScreenIsActive;

            constructor(codeBlock, checkIfFullScreenIsActive) {
                this.#checkIfFullScreenIsActive = checkIfFullScreenIsActive;
                const { postId, numberInPost } = getCodeBlockMeta(codeBlock);

                codeHighlightingPostProcessHandler.subscribe(postId, numberInPost, (processedCodeBlock) => {
                    this.#collapsibleToggleBtn = processedCodeBlock.previousElementSibling.querySelector('.syntaxhighlighter-collapsible-button');

                    processedCodeBlock.addEventListener('mouseenter', this.#extendCodeBlock.bind(this));
                    // attach `mouseout` to parent to let user use interactive bar while block is extended
                    processedCodeBlock.parentNode.addEventListener('mouseleave', this.#collapseCodeBlock.bind(this));
                });
            }

            #extendCodeBlock({ target }) {
                const isCodeBlock = target.classList.contains('syntaxhighlighter');
                const isCodeBlockCollapsed = target.classList.contains('collapsed-block');
                const scrollOffset = target.clientWidth < target.scrollWidth ? (target.scrollWidth - target.clientWidth) : 0;

                if (
                    isCodeBlock && scrollOffset && !isCodeBlockCollapsed && 
                    this.#collapsibleToggleBtn && !this.#checkIfFullScreenIsActive()
                ) {
                    if (this.#horizontallyExtendedCodeBlock) {
                        // prepare re-hover
                        this.#abortController?.abort();
                        this.#abortController = new AbortController();
                        target.parentNode.addEventListener('transitionend', () => {
                            this.#extendCodeBlock({ target });
                        }, { once: true, signal: this.#abortController.signal });
                    } else {
                        this.#collapsibleToggleBtn.disabled = true;
                        this.#horizontallyExtendedCodeBlock = target;
                        this.#toggleRootsOverflowing(target, true);
                        /*
                            make sure roots overflow is still "active" to avoid possible race condition 
                            with other collapsing code block, which may turn roots overflow off
                        */
                        target.addEventListener('transitionend', () => this.#toggleRootsOverflowing(target, true), { once: true });

                        const qaMainWrapperWidth = Number.parseInt(window.getComputedStyle(this.#overflowingRoots[0]).width);
                        const qaMainWrapperOffsetLeft = this.#overflowingRoots[0].getBoundingClientRect().left;
                        const offsetToQaBodyWrapper = Math.abs(qaMainWrapperOffsetLeft - target.getBoundingClientRect().left);
                        const targetOutputWidth = Math.min(target.scrollWidth, qaMainWrapperWidth);
                        const targetOutputLeft = Math.min(scrollOffset / 2, offsetToQaBodyWrapper);

                        target.style.setProperty('--extended-horizontal-width', `${targetOutputWidth}px`);
                        target.style.setProperty('--max-extended-horizontal-width', `${qaMainWrapperWidth}px`);
                        target.style.setProperty('--offset-to-qa-body-wrapper', targetOutputLeft);
                        target.classList.add('syntaxhighlighter--horizontally-extended');
                        target.previousElementSibling.classList.add('syntaxhighlighter-block-bar--horizontally-extensible');
                    }
                }
            }

            #collapseCodeBlock({ target }) {
                const isSyntaxHighlighterParent = target.classList.contains('syntaxhighlighter-parent');

                if (isSyntaxHighlighterParent && this.#horizontallyExtendedCodeBlock) {
                    // abort possibly scheduled re-hover
                    {
                        this.#abortController?.abort();
                        this.#abortController = null;
                    }
                    this.#horizontallyExtendedCodeBlock.classList.remove('syntaxhighlighter--horizontally-extended');
                    this.#horizontallyExtendedCodeBlock.previousElementSibling.classList.remove('syntaxhighlighter-block-bar--horizontally-extensible');

                    target.addEventListener('transitionend', () => {
                        this.#toggleRootsOverflowing(target, false);
                        this.#collapsibleToggleBtn.disabled = false;
                        this.#horizontallyExtendedCodeBlock = null;
                    }, { once: true });
                }
            }

            #toggleRootsOverflowing(target, shouldOverflow) {
                const popupAsRoot = target.closest('.post-preview');
                const overflowMaybeVisible = shouldOverflow ? 'visible' : null;

                if (popupAsRoot) {
                    popupAsRoot.style.overflow = overflowMaybeVisible;
                } else {
                    this.#overflowingRoots.forEach(root => root.style.overflow = overflowMaybeVisible);
                }
            }
        }

        const collapsibleCodeBlocks = new CollapsibleCodeBlocks();
        const languageLabel = new LanguageLabel();
        
        return function getCodeBlockBarFeatureItems(codeBlock) {
            const featuresDrawer = new FeaturesDrawer(codeBlock);
            const featuresDrawerToggler = Object.freeze({
                show: () => {
                    featuresDrawer.showDrawer(new MouseEvent('click'));
                },
                hide: () => {
                    featuresDrawer.hideDrawer();
                },
            });
            const codeCopy = new CodeCopy(codeBlock, featuresDrawerToggler);
            const searchThroughCode = new SearchThroughCode(
              codeBlock, featuresDrawerToggler, featuresDrawer.getContainer(),
            );
            const codeBlockFullScreen = new CodeBlockFullScreen(featuresDrawerToggler);
            
            codeBlockFullScreen.setupCodeBlockResizeWatcher(codeBlock);
            featuresDrawer.addFeatures([
                searchThroughCode.getSearchBtn(),
                codeCopy.getCopyToClipboardBtn(),
                codeBlockFullScreen.getFullScreenBtn(),
            ].filter(Boolean));
            featuresDrawer.assignClearAndExitSearch(searchThroughCode.clearAndExit.bind(searchThroughCode));

            new HorizontalCodeBlockExtenderOnHover(codeBlock, codeBlockFullScreen.checkIfFullScreenIsActive.bind(codeBlockFullScreen));

            return [
                languageLabel.getLanguageLabel(codeBlock),
                collapsibleCodeBlocks.getCodeBlockCollapsingBtn(codeBlock),
                featuresDrawer.getFeaturesDrawerBtn(),
            ].filter(Boolean).map(wrapCodeBlockBarFeatureItem);
        }

        function wrapCodeBlockBarFeatureItem(item) {
            const wrapper = document.createElement('div');
            wrapper.classList.add('syntaxhighlighter-block-bar-item');
            wrapper.appendChild(item);

            return wrapper;
        }
    }
};

const codeBlocksHighlighterAndDecorator = (scanUnprocessedCodeBlocks, addInteractiveBarToCodeBlocks, addSnippetsToPost) => {
    const snippetsIntermediateConfig = {
        postName: null,
        postContentDOM: null,
        postCodeBlocks: null,
    };

    return highlightAndDecorateCodeBlocks;

    function highlightAndDecorateCodeBlocks(
        postsToHighlight = document.querySelector('.qa-main'), shouldAddSnippets = true
    ) {
        const rawCodeBlocks = scanUnprocessedCodeBlocks(postsToHighlight);
        rawCodeBlocks.forEach((rawCodeBlock, index) => {
            const isLastIteration = rawCodeBlocks.length - 1 === index;
            processRawCodeBlocks(rawCodeBlock, shouldAddSnippets, isLastIteration);
        });
    }

    function processRawCodeBlocks(codeBlock, shouldAddSnippets, isLastIteration) {
        const postProcessCodeBlock = addInteractiveBarToCodeBlocks(codeBlock);

        const origCodeBlockParent = codeBlock.parentNode;
        SyntaxHighlighter.highlight(null, codeBlock);

        const processedCodeBlock = [...origCodeBlockParent.querySelectorAll('.syntaxhighlighter')].pop();
        postProcessCodeBlock(processedCodeBlock);

        if (shouldAddSnippets) {
            prepareCodeBlocksForSnippetsAddition(processedCodeBlock, isLastIteration);
        }
    }

    function prepareCodeBlocksForSnippetsAddition(processedCodeBlock, isLastIteration) {
        const postContentDOM = processedCodeBlock.closest('.entry-content');

        if (!postContentDOM) {
            return;
        }

        const postName = postContentDOM.previousElementSibling;

        if (postName !== snippetsIntermediateConfig.postName) {
            if (snippetsIntermediateConfig.postName) {
                addSnippetsAndClearConfigObj();
            }

            snippetsIntermediateConfig.postName = postName;
            snippetsIntermediateConfig.postContentDOM = postContentDOM;
            snippetsIntermediateConfig.postCodeBlocks = [processedCodeBlock];
        } else {
            snippetsIntermediateConfig.postCodeBlocks.push(processedCodeBlock);
        }

        if (isLastIteration) {
            addSnippetsAndClearConfigObj();
        }
    }

    function addSnippetsAndClearConfigObj() {
        addSnippetsToPost(snippetsIntermediateConfig.postCodeBlocks, snippetsIntermediateConfig.postContentDOM);
        Object.keys(snippetsIntermediateConfig).forEach(key => snippetsIntermediateConfig[key] = null);
    }
};

const initSyntaxHighlighterFeatures = () => {
    if (typeof SyntaxHighlighter !== 'object' || !SyntaxHighlighter || typeof SyntaxHighlighter.highlight !== 'function') {
        throw new TypeError('Cannot highlight and decorate blocks of code, because SyntaxHighlighter is not available!');
    }

    window.highlightAndDecorateCodeBlocks = codeBlocksHighlighterAndDecorator(
        rawCodeBlocksPreProcessor(), codeBlockInteractiveBar(), postSnippets()
    );
    highlightAndDecorateCodeBlocks();
}

if (areSyntaxHighlighterFeaturesNeeded()) {
    initSyntaxHighlighterFeatures();
} else {
    console.warn('SyntaxHighlighter features are not needed on this page, so they will not be initiated.');
}

function areSyntaxHighlighterFeaturesNeeded() {
    const { pathname } = location;
    const isAskOrTopicPage =
        pathname
        .split('/')
        .some(part => part === 'ask' || parseInt(part));
    const isAdminSubPage = /admin\/(flagged|hidden)/.test(pathname);

    return isAskOrTopicPage || isAdminSubPage;
}
