window.scanUnprocessedCodeBlocks = (function highlightCodeBlocks() {
    'use strict';

    prepareCodeLanguages();
    // document.addEventListener('DOMContentLoaded', scanUnprocessedCodeBlocks);

    // SyntaxHighlighter.all();

    return scanUnprocessedCodeBlocks;

    // Extend SyntaxHighlighter with property object declaring supported programming languages used by CKEditor and SyntaxHighlighter itself
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

    function scanUnprocessedCodeBlocks(event, root = document) {
        const preElements = [...root.querySelectorAll('pre[class*="brush:"]')];
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
})();

window.addInteractiveBarToCodeBlocks = (function interactiveCodeBlockBar() {
    'use strict';

    const MIN_LINES_NUMBER_TO_COLLAPSE_CODE = 30;
    const languages = {};
    prepareLanguages();

    const getCodeBlockBarFeatureItems = initInteractiveFeatures();

    // document.addEventListener('DOMContentLoaded', prepareLanguages);
    // Object.defineProperty(window, 'addInteractiveBarToCodeBlocks', {
    //     configurable: false,
    //     writable: false,
    //     value: addInteractiveBarToCodeBlocks
    // });

    // TODO: check this on backend side - perhaps in separate Pull Request
    if (window.location.pathname.search(/\d+/) === 1) {
        const postsContainer = document.querySelector('.qa-main');
        const rawCodeBlocks = scanUnprocessedCodeBlocks(null, postsContainer);
        rawCodeBlocks.forEach((codeBlock) => {
            /*
             * 1st argument notifies function that the page is not /ask.html - so different blocks of code collapsing method will be used
             * 2nd parameter notifies function if it can "turn on" Copy To Clipboard function - so user can copy code inside block within button click
             */
            const postProcessCodeBlock = addInteractiveBarToCodeBlocks(false, /*[*/codeBlock /*processedCodeBlock*//*]*/);

            const origCodeBlockParent = codeBlock.parentNode;
            SyntaxHighlighter.highlight(null, codeBlock);
            // const processedCodeBlock = origCodeBlockParent.querySelector('.syntaxhighlighter');
            const processedCodeBlock = [...origCodeBlockParent.querySelectorAll('.syntaxhighlighter')].pop();
            postProcessCodeBlock(processedCodeBlock);
        });
    }

    return addInteractiveBarToCodeBlocks;

    function prepareLanguages() {
        SyntaxHighlighter.languages.entries.forEach(([name, code]) => languages[code] = name);
    }

    // TODO: adjust function usages to second parameter not being array!!!
    function addInteractiveBarToCodeBlocks(isInsidePreview, chosenCodeBlocks) {
        // getCodeBlocks(isInsidePreview, chosenCodeBlocks).forEach(decorateCodeBlock);
        const { codeBlockBar, codeBlockParent } = decorateCodeBlock(getCodeBlocks(isInsidePreview, chosenCodeBlocks));

        return function postProcessCodeBlock(processedCodeBlock) {
            const codeBlockDefaultParent = processedCodeBlock.parentNode;

            codeBlockParent.insertBefore(processedCodeBlock, codeBlockDefaultParent);
            codeBlockDefaultParent.remove();

            if (codeBlockBar.classList.contains('is-collapsible')) {
                processedCodeBlock.classList.add('collapsed-block');
            }
        };

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

            const codeBlockParent = document.createElement('div');
            codeBlockParent.classList.add('syntaxhighlighter-parent');

            codeBlock.parentNode.insertBefore(codeBlockParent, codeBlock);
            codeBlockParent.append(codeBlockBar, codeBlock);

            requestAnimationFrame(() => codeBlockBar.classList.remove('block-bar-transparency'));

            return { codeBlockBar, codeBlockParent };
        }
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
            getLanguageName(codeBlock) {
                const languageExplicitName = codeBlock.dataset && languages[codeBlock.dataset.codeLangName];
                return languageExplicitName || SyntaxHighlighter.defaults['code-language'].fullName;
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
