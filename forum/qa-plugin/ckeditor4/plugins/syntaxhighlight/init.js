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

        const notFetchedBrushScripts = fetchNeededLangs(preElemsWithUnknownLang.notLoadedLangElems)
        fixUnavailableLangCodes([...preElemsWithUnknownLang.unknownLangElems, ...notFetchedBrushScripts]);

        function getPreElemsWithNotAvailableBrush() {
            const checkedUnknownLangNames = [];
            const checkedNotLoadedLangNames = [];

            const unknownLangElems = [];
            const notLoadedLangElems = [];

            for (let i = 0; i < preElements.length; i++) {
                const pre = preElements[i];

                if (checkedUnknownLangNames.includes(pre.className)) {
                    unknownLangElems.push(pre);
                    continue;
                }

                if (checkedNotLoadedLangNames.includes(pre.className)) {
                    notLoadedLangElems.push(pre);
                    continue;
                }

                // const matchedBrush = loadedBrushes.find(() => {});

                for (let j = 0; j < loadedBrushes.length; j++) {
                    const { name, aliases } = loadedBrushes[j];

                    const foundAlias = aliases
                        .find(alias => SyntaxHighlighter.languages.codes.includes(alias));

                    if (foundAlias) {
                        checkedNotLoadedLangNames.push(pre.className);
                        notLoadedLangElems.push(pre);

                        break;
                    }

                    const regExp = new RegExp(`brush\:(${ aliases.join('|') });`);

                    if (regExp.test(pre.className)) {
                        checkedUnknownLangNames.push(pre.className);
                        unknownLangElems.push(pre);

                        break;
                    }
                }
            }

            return {
                'notLoadedLangElems': notLoadedLangElems,
                'unknownLangElems': unknownLangElems
            };
        }

        function fetchNeededLangs(neededBrushes) {
            const brushScripts = document.querySelectorAll('[src*="shBrush"]');
            const lastBrushScript = brushScripts[brushScripts.length - 1];
            const brushSrcPrefix = lastBrushScript.src
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
