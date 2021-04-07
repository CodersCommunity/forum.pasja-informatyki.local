<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    function head_lines()
    {
        $level = qa_get_logged_in_level();
        if ($level >= QA_USER_LEVEL_EDITOR && $this->template === 'user') {
            $this->content['head_lines'][] = '<style>
                .qa-part-user-notes {
                    padding: 20px;
                    margin-bottom: 5px;
                    background: #fff;
                }
                .qa-part-user-notes span {
                    font-weight: normal;
                }
                .note-hidden {
                    display: none;
                }
                .notes-right {
                    text-align: right;
                    margin: 0;
                }
                .user-notes-info {
                    position: fixed;
                    background: #3498db;
                    top: 143px;
                    right: 0;
                    text-align: center;
                }
                .user-notes-info a {
                    color: #fff;
                    display: block;
                    padding: 5px;
                    font-size: 12px;
                }
                .user-notes-info span {
                    font-size: 16px;
                    font-weight: bold;
                }
                @media (max-width: 1179px) {
                    .user-notes-info {
                        display: none;
                    }
                }
                </style>';
        }

        parent::head_lines();
    }
}
