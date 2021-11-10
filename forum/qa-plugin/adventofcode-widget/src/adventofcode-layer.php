<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    function head_css()
    {
        $this->output('<style>
.aoc-widget {
    text-align: center;
    font-size: 14px;
}

.aoc-widget__title {
    margin-bottom: 0;
}

.aoc-widget__ol {
    padding-left: 23px;
    text-align: left;
    font-size: 14px;
}
</style>');

        parent::head_css();
    }
}
