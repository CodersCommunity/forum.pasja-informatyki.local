<?php

class users_notes_widget
{
    private $db_table = 'usersnotes';
    private $show_max = 5;

    public function init_queries($tableslc)
    {
        $table = qa_db_add_table_prefix($this->db_table);
        if (!in_array($table, $tableslc)) {
            return 'CREATE TABLE IF NOT EXISTS `' . $table . '` (
                `id_note` INT NOT NULL AUTO_INCREMENT,
                `id_user` INT NOT NULL,
                `handle` VARCHAR(20) NOT NULL,
                `added_id_user` INT NOT NULL,
                `added_handle` VARCHAR(20) NOT NULL,
                `added_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `event` VARCHAR(20),
                `content` TEXT,
                PRIMARY KEY (`id_note`)
			) ENGINE=InnoDB CHARSET=utf8;';
        }
    }

    public function allow_template($template)
    {
        return $template === 'user';
    }

    public function allow_region($region)
    {
        return $region === 'main' || $region === 'full';
    }

    public function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
    {
        $level = qa_get_logged_in_level();
        if ($level >= QA_USER_LEVEL_EDITOR) {
            $status = '';

            $handle = qa_request_part(1);
            $userid = qa_handle_to_userid($handle);
            if (qa_clicked('user-notes-add')) {
                $content = strip_tags(qa_post_text('user-notes-content'));
                if (!empty($content)) {
                    $sql = 'INSERT INTO ^' . $this->db_table . ' (id_user, handle, added_id_user, added_handle, content) VALUES (#, #, #, #, #)';
                    qa_db_query_sub($sql, $userid, $handle, qa_get_logged_in_userid(), qa_get_logged_in_handle(), $content);
                    $status = qa_lang_html('users_notes/note_added');
                }
            }

            $sql = 'SELECT added_handle, added_date, event, content FROM ^' . $this->db_table . ' WHERE id_user=# ORDER BY added_date DESC';
            $data = qa_db_read_all_assoc(qa_db_query_sub($sql, $userid));

            $output = '<div class="qa-part-user-notes">
            <h2><a name="notes">' . qa_lang_html('users_notes/title') . '</a> <span class="qa-form-wide-note">' . qa_lang_html('users_notes/visible_info') . '</span></h2>';
            $output .= !empty($status) ? '<div class="qa-form-wide-ok">' . $status . '</div>' : '';

            $count = 0;
            if (!empty($data)) {
                $output .= '<table>';
                foreach ($data as $note) {
                    $noteContent = '';
                    if (!empty($note['event'])) {
                        if ($note['event'] === 'u_block') {
                            $noteContent = '<i>' . qa_lang_html('users_notes/event_u_block') . '</i>';
                        } elseif ($note['event'] === 'u_unblock') {
                            $noteContent = '<i>' . qa_lang_html('users_notes/event_u_unblock') . '</i>';
                        }
                    } else {
                        $regex = '/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';
                        $noteContent = preg_replace($regex, '<a href="$0" target="_blank">$0</a>', $note['content']);
                    }
                    $output .= '<tr' . ($count >= $this->show_max ? ' class="note-hidden"' : '') . '>
                        <td style="width: 18%">' . $note['added_date'] . '</td>
                        <td style="width: 15%">' . $note['added_handle'] . '</td>
                        <td>' . $noteContent . '</td>
                    </tr>';
                    $count++;
                }
                $output .= '</table>';
                if ($count > $this->show_max) {
                    $output .= '<p class="notes-right"><button id="user-notes-more" class="qa-form-wide-button">' . qa_lang_html('users_notes/more_button') . '</button></p>
                    <script>
                        var button = document.querySelector("#user-notes-more");
                        button.addEventListener("click", function(){
                            document.querySelectorAll(".note-hidden").forEach(function(note, index) {
                                note.style.display = "table-row";
                            });
                            button.style.display = "none";
                        });
                    </script>';
                }
            } else {
                $output .= qa_lang_html('users_notes/no_notes') . ' <button id="user-notes-open" class="qa-form-wide-button">' . qa_lang_html('users_notes/add_button') . '</button>
                <script>
                    var button = document.querySelector("#user-notes-open");
                    button.addEventListener("click", function(){
                        document.querySelector("#notes-form").style.display = "block";
                        button.style.display = "none";
                    });
                </script>';
            }

            $output .= '<form action="#notes" method="post" id="notes-form"' . (empty($data) ? ' style="display: none;"' : '') . '>
                <hr><label for="user-notes-content">' . qa_lang_html('users_notes/add_title') . ':</label>
                <textarea name="user-notes-content" id="user-notes-content" class="qa-form-tall-text" required></textarea>
                <p class="notes-right"><input type="submit" name="user-notes-add" value="' . qa_lang_html('users_notes/add_button') . '" class="qa-form-wide-button"></p>
            </form>
            </div>';

            if ($count > 0) {
                $output .= '<div class="user-notes-info"><a href="#notes">' . qa_lang_html('users_notes/notes_button') . '<br><span>' . $count . '</span></a></div>';
            }

            $themeobject->output($output);
        }
    }
}
