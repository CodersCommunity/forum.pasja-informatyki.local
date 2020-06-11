;(() => {
    'use strict';

    prepareCodeLanguages();
    document.addEventListener('DOMContentLoaded', scanUnprocessedCodeBlocks);
    SyntaxHighlighter.all();

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
                // ['ActionScript3','as3'],
                // ['AppleScript','applescript'],
                ['Bash (Shell)', 'bash'],
                //
                ['ColdFusion', 'cf'],
                //
                ['C#', 'csharp'],
                ['C/C++', 'cpp'],
                ['CSS', 'css'],
                ['Delphi', 'delphi'],
                // ['Diff','diff'],
                // ['Erlang','erl'],
                // ['Groovy','groovy'],
                ['Javascript', 'jscript'],
                ['Java', 'java'],
                // ['Java FX','javafx'],
                ['Perl', 'perl'],
                ['PHP', 'php'],
                ['Plain (Text)', 'plain'],
                ['PowerShell', 'ps'],
                ['Python', 'python'],
                ['Ruby', 'ruby'],
                //
                ['Sass', 'scss'],
                //
                // ['Scala','scala'],
                ['SQL', 'sql'],
                // ['TAP','tap'],
                ['VB', 'vb'],
                ['XML/XHTML', 'xml']
            ]); //erl, scala, diff, tap, cf, applescript, javafx, scss, as3, groovy

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

    function scanUnprocessedCodeBlocks() {
        const preElements = [...document.querySelectorAll('pre[class*="brush:"]')];
        const loadedBrushes = Object
            .entries(SyntaxHighlighter.brushes)
            .map(([name, ctor]) => ({
                name,
                aliases: ctor.aliases
            }));
        fixUnavailableLangCodes(getPreElemsWithNotAvailableBrush());

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
            console.warn('[0]unknownLangElems : ', unknownLangElems);

            unknownLangElems.forEach(replaceUnknownLangWithDefault);

            console.warn('[1]unknownLangElems : ', unknownLangElems);

            function replaceUnknownLangWithDefault(pre) {
                const oldToken = pre.classList.item(0);
                const newToken = `brush:${ SyntaxHighlighter.defaults['code-language'].alias };`;

                pre.classList.replace(oldToken, newToken);
            }
        }
    }
})();
