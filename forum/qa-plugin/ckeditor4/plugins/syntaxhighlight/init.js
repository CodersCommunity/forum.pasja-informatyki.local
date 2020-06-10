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
        const preElemsWithUnknownLang = getPreElemsWithNotAvailableBrush();

        const notFetchedBrushScripts = fetchNeededLangs(preElemsWithUnknownLang.notLoadedLangElems);
        console.warn('preElemsWithUnknownLang.unknownLangElems: ', preElemsWithUnknownLang.unknownLangElems, ' /preElemsWithUnknownLang.notLoadedLangElems: ', preElemsWithUnknownLang.notLoadedLangElems);
        fixUnavailableLangCodes([...preElemsWithUnknownLang.unknownLangElems, ...notFetchedBrushScripts]);

        function getPreElemsWithNotAvailableBrush() {
            const checkedUnknownLangNames = [];
            const checkedNotLoadedLangNames = [];

            const unknownLangElems = [];
            const notLoadedLangElems = [];

            scanPreElements();

            return {
                'notLoadedLangElems': notLoadedLangElems,
                'unknownLangElems': unknownLangElems
            };

            function scanPreElements() {
                for (let i = 0; i < preElements.length; i++) {
                    const pre = preElements[i];
                    const preClassName = pre.className;

                    if (checkedNotLoadedLangNames.includes(preClassName)) {
                        notLoadedLangElems.push(SyntaxHighlighter.defaults['code-language'].ctorName);
                        continue;
                    } else if (checkedUnknownLangNames.includes(preClassName)) {
                        unknownLangElems.push(pre);
                        continue;
                    }

                    matchPreElemsWithBrushes(pre, preClassName);
                }
            }

            function matchPreElemsWithBrushes(pre, preClassName) {
                let isBrashKnown = false;

                for (let j = 0; j < loadedBrushes.length; j++) {
                    const { name, aliases } = loadedBrushes[j];

                    if (getBrushRegExp(aliases.join('|')).test(preClassName)) {
                        isBrashKnown = true;
                    } else {
                        continue;
                    }

                    const foundAlias = aliases.find(alias => SyntaxHighlighter.languages.codes.includes(alias));

                    if (foundAlias) {
                        checkedNotLoadedLangNames.push(preClassName);
                        notLoadedLangElems.push(name);

                        break;
                    }
                }

                if (!isBrashKnown) {
                    checkedUnknownLangNames.push(preClassName);
                    unknownLangElems.push(pre);
                }
            }

            function getBrushRegExp(langName) {
                return new RegExp(`brush\:(${ langName });`);
            }
        }

        function fetchNeededLangs(neededBrushes) {
            const brushScripts = document.querySelectorAll('[src*="shBrush"]');
            const lastBrushScript = brushScripts[brushScripts.length - 1];
            const brushSrcPrefix = lastBrushScript.getAttribute('src')
                .split('/')
                .slice(0, -1)
                .join('/');

            const notFetchedBrushScripts = [];
            neededBrushes.forEach(loadBrushScript);

            return notFetchedBrushScripts;

            function loadBrushScript(ctorName) {
                const brushScript = document.createElement('script');
                brushScript.async = false;
                brushScript.addEventListener('error', onBrushLoadError, { once: true });
                brushScript.type = 'text/javascript';
                brushScript.src = `${ brushSrcPrefix }/shBrush${ ctorName }.js`;

                lastBrushScript.parentNode.insertBefore(brushScript, lastBrushScript);
            }

            function onBrushLoadError() {
                console.error(`Failed to load brush script from URL: ${ this.src }. Code blocks related to this brush will fallback to default code language "${ SyntaxHighlighter.defaults['code-language'].fullName }".`);
                notFetchedBrushScripts.push(this.src);
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
