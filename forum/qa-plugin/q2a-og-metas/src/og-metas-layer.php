<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    public function head_metas()
    {
        $siteTitle = $this->content['site_title'] ?? '';
        $pageTitle = !empty($this->request) ? strip_tags($this->content['title'] ?? '') : '';
        $title = (!empty($pageTitle) ? ($pageTitle . ' - ') : '') . $siteTitle;

        $metas = [
            'og:url' => $this->content['canonical'] ?? qa_path_absolute($this->request),
            'og:site_name' => $siteTitle,
            'og:title' => $title,
            'og:description' => $this->content['description'] ?? '',
            'og:image' => qa_opt('og_metas_image_url'),
            'og:type' => 'website',
            'twitter:card' => 'summary',
        ];

        foreach ($metas as $property => $content) {
            if (empty($property) || empty($content)) {
                continue;
            }

            $this->output(sprintf('<meta property="%s" content="%s">', $property, $content));
        }

        parent::head_metas();
    }
}
