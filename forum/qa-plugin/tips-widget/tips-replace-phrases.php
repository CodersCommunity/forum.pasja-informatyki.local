<?php

function replacePathHtml($str)
{
    while (preg_match('/!PATH_HTML\((.*?)\)!/', $str, $matches) === 1) {
        $str = preg_replace('/!PATH_HTML\(.*?\)!/', qa_path_html($matches[1]), $str, 1);
    }
    
    return $str;
}

function replaceWidgetImgSrc($str)
{
    while (preg_match('/!WIDGET_IMG_SRC\((.*?)\)!/', $str, $matches) === 1) {
        $str = preg_replace(
            '/!WIDGET_IMG_SRC\(.*?\)!/',
            '/qa-plugin/' . basename(dirname(__FILE__)) . '/icons/' . $matches[1],
            $str,
            1
        );
    }
    
    return $str;
}
