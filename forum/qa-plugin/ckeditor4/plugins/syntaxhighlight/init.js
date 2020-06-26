;(() => {
    'use strict';

    prepareCodeLanguages();
    exposeScanUnprocessedCodeBlocks();
    // document.addEventListener('DOMContentLoaded', scanUnprocessedCodeBlocks);

    if (window.location.pathname.search(/\d+/) === 1) {
        const postsContainer = document.querySelector('.qa-main');
        const rawCodeBlocks = scanUnprocessedCodeBlocks(null, postsContainer);
        rawCodeBlocks.forEach((codeBlock) => {
            const origCodeBlockParent = codeBlock.parentNode;
            SyntaxHighlighter.highlight(null, codeBlock);
            const processedCodeBlock = origCodeBlockParent.querySelector('.syntaxhighlighter');
            /*
             * 1st argument notifies function that the page is not /ask.html - so different blocks of code collapsing method will be used
             * 2nd parameter notifies function if it can "turn on" Copy To Clipboard function - so user can copy code inside block within button click
             */
            window.addInteractiveBarToCodeBlocks(false, [processedCodeBlock]);
        });
    }

    // SyntaxHighlighter.all();

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

                    if (brushRegExp.test(preClassName)) {
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
                const oldToken = pre.classList.item(0);
                const newToken = `brush:${ SyntaxHighlighter.defaults['code-language'].alias };`;

                pre.classList.replace(oldToken, newToken);
            }
        }
    }

    function exposeScanUnprocessedCodeBlocks() {
        Object.defineProperty(window, 'scanUnprocessedCodeBlocks', {
            configurable: false,
            writable: false,
            value: scanUnprocessedCodeBlocks
        });
    }
})();
