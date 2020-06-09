;(() => {
    'use strict';

    prepareCodeLanguages();
    document.addEventListener('DOMContentLoaded', fixUnavailableLangCodes);
    SyntaxHighlighter.all();

    // Extend SyntaxHighlighter with property object declaring supported programming languages used by CKEditor and SyntaxHighlighter itself
    function prepareCodeLanguages() {
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

    function fixUnavailableLangCodes() {
        SyntaxHighlighter.defaults['code-language'] = {
            ctorName: 'Plain',
            value: 'plain',
            fullName: 'Plain (Text)'
        };

        const preElements = [...document.querySelectorAll('pre[class*="brush:"]')];
        const availableBrushes = Object
            .entries(SyntaxHighlighter.brushes)
            .map(([name, ctor]) => ({
                name,
                value: ctor.aliases
            }));
        const preElemsWithUnknownLang = getPreElemsWithUnknownLang();

        console.warn('[0]preElemsWithUnknownLang : ', preElemsWithUnknownLang);

        preElemsWithUnknownLang.forEach(replaceUnknownLangWithDefault);

        console.warn('[1]preElemsWithUnknownLang : ', preElemsWithUnknownLang);

        function getPreElemsWithUnknownLang() {
            const unknownLangElems = [];
            const unknownLangNames = [];

            for (let i = 0; i < preElements.length; i++) {
                const pre = preElements[i];

                if (unknownLangNames.includes(pre.className)) {
                    unknownLangElems.push(pre);
                    continue;
                }

                const matchedBrush = availableBrushes.find(({ name, value }) => {
                    const regExp = new RegExp(`brush\:(${ value.join('|') });`);
                    return regExp.test(pre.className);
                });

                if (!matchedBrush) {
                    unknownLangNames.push(pre.className);
                    unknownLangElems.push(pre);
                }
            }

            return unknownLangElems;
        }

        function replaceUnknownLangWithDefault(pre) {
            const oldToken = pre.classList.item(0);
            const newToken = `brush:${ SyntaxHighlighter.defaults['code-language'].value };`;

            pre.classList.replace(oldToken, newToken);
        }
    }
})();
