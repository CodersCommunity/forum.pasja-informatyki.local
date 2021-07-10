'use strict';

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
                    return concatenatedCode + fixSpaces(singleLineOfCode) + this.NEW_LINE;
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

        class CodeBlockFullScreen {
            constructor() {
                this.isFullScreen = false;
                const requestFullScreenMethodNames = ['requestFullScreen', 'requestFullscreen', 'webkitRequestFullScreen', 'webkitRequestFullscreen', 'mozRequestFullScreen'];
                const exitFullScreenMethodNames = ['exitFullscreen', 'webkitCancelFullScreen', 'webkitExitFullscreen', 'mozCancelFullScreen'];
                this.getRequestFullScreen = (element) => {
                    const supportedMethodName = requestFullScreenMethodNames.find(methodName => element[methodName])
                    
                    return element[supportedMethodName].bind(element) || getFallbackFullScreenFeatureMethod();
                }
                this.exitFullScreen = (() => {
                    const supportedMethodName = exitFullScreenMethodNames.find(methodName => document[methodName]);
                    
                    return document[supportedMethodName].bind(document) || getFallbackFullScreenFeatureMethod();
                })();
            }

            getFallbackFullScreenFeatureMethod() {
                return () => Promise.reject('FullScreen feature is unsupported!')
            }

            getFullScreenBtn() {
                const fullScreenBtn = document.createElement('button');
                fullScreenBtn.textContent = '[x]';
                fullScreenBtn.type = 'button';
                fullScreenBtn.addEventListener('click', async ({ target }) => {
                    const codeBlock = target.closest('.syntaxhighlighter-parent').querySelector('.syntaxhighlighter');
                    const isCodeBlockCollapsed = codeBlock.classList.contains('collapsed-block');
                    const fullScreenTarget = codeBlock.parentNode;
                    const requestFullScreen = this.getRequestFullScreen(fullScreenTarget);
                    
                    if (this.isFullScreen) {
                        await this.exitFullScreen().catch(console.error);
                    } else {
                        await requestFullScreen().catch((error) => {
                            console.error(error);
                            this.rawFullScreenToggle(fullScreenTarget);
                        });
                    }
                    
                    
                    if((!this.isFullScreen && isCodeBlockCollapsed) || (this.isFullScreen && !isCodeBlockCollapsed)) {
                        const collapsibleCodeBlockBtn = codeBlock.previousElementSibling.querySelector('.syntaxhighlighter-collapsible-button');
                        collapsibleCodeBlocks.toggleCodeBlockBtnCollapseState({ target: collapsibleCodeBlockBtn });
                    }

                    this.isFullScreen = !this.isFullScreen;
                });

                return fullScreenBtn;
            }

            rawFullScreenToggle(fullScreenTarget) {
                fullScreenTarget.classList.toggle('syntaxhighlighter-block-bar--full-screen');
            }
        }

        const collapsibleCodeBlocks = new CollapsibleCodeBlocks();
        const languageLabel = new LanguageLabel();
        const codeCopy = new CodeCopy();
        const codeBlockFullScreen = new CodeBlockFullScreen();

        return function getCodeBlockBarFeatureItems(codeBlock) {
            return [
                languageLabel.getLanguageLabel(codeBlock),
                collapsibleCodeBlocks.getCodeBlockCollapsingBtn(codeBlock),
                codeBlockFullScreen.getFullScreenBtn(),
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
