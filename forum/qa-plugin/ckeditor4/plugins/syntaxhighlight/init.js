;(() => {
    'use strict';

    document.addEventListener('DOMContentLoaded', fixUnavailableLangCodes);
    SyntaxHighlighter.all();

    function fixUnavailableLangCodes() {
        SyntaxHighlighter.defaults[ 'code-language' ] = {
            ctor: 'Plain',
            value: 'plain',
            fullName: 'Plain (Text)'
        };

        const preElements = [...document.querySelectorAll('pre[class*="brush:"]')];
        const availableBrushes = Object.entries(SyntaxHighlighter.brushes).map(([name, ctor]) => ({
            name,
            value: ctor.aliases
        }));
        const preWithUnknownLang = preElements.filter(pre => {
            const res = (availableBrushes.find(({ name, value }) => {
                const regExp = new RegExp(`brush\:(${value.join('|')});`);
                return regExp.test(pre.className);
            }) || { name: null });
            return !res.name;
        });
        console.warn('[0]preWithUnknownLang : ', preWithUnknownLang);

        preWithUnknownLang.forEach(pre => {
            const oldToken = pre.classList.item(0);
            const newToken = `brush:${SyntaxHighlighter.defaults[ 'code-language' ].value};`;

            pre.classList.replace(oldToken, newToken);
        })

        console.warn('[1]preWithUnknownLang : ', preWithUnknownLang);
    }
})();
