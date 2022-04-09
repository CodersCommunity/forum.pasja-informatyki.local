<?php
/*
    Question2Answer by Gideon Greenspan and contributors
    http://www.question2answer.org/

    File: qa-include/qa-ajax-answer.php
    Description: Server-side response to Ajax create answer requests

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    More about this license: http://www.question2answer.org/license.php
*/

require_once QA_INCLUDE_DIR . 'app/users.php';
require_once QA_INCLUDE_DIR . 'app/limits.php';
require_once QA_INCLUDE_DIR . 'db/selects.php';

// Load relevant information about this question
$questionid = qa_post_text('a_questionid');
$userid = qa_get_logged_in_userid();

[$question, $childposts] = qa_db_select_with_pending(
    qa_db_full_post_selectspec($userid, $questionid),
    qa_db_full_child_posts_selectspec($userid, $questionid)
);

// Check if the question exists, is not hidden, and whether the user has permission to do this
if (isset($question['basetype']) && $question['basetype'] === 'Q') {
    if ($question['type'] === 'Q_HIDDEN') {
        echo "QA_AJAX_RESPONSE\n0\n" . qa_lang('question/question_hidden');
        return;
    }
    if ($question['closedbyid'] !== null) {
        echo "QA_AJAX_RESPONSE\n0\n" . qa_lang('question/question_closed');
        return;
    }
    if (qa_user_post_permit_error('permit_post_a', $question, QA_LIMIT_ANSWERS)) {
        echo "QA_AJAX_RESPONSE\n0\n" . qa_lang('question/answer_limit');
        return;
    }

    require_once QA_INCLUDE_DIR . 'app/captcha.php';
    require_once QA_INCLUDE_DIR . 'app/format.php';
    require_once QA_INCLUDE_DIR . 'app/post-create.php';
    require_once QA_INCLUDE_DIR . 'app/cookies.php';
    require_once QA_INCLUDE_DIR . 'pages/question-view.php';
    require_once QA_INCLUDE_DIR . 'pages/question-submit.php';

    // Try to create the new answer
    $usecaptcha = qa_user_use_captcha(qa_user_level_for_post($question));
    $answers = qa_page_q_load_as($question, $childposts);
    $answerid = qa_page_q_add_a_submit($question, $answers, $usecaptcha, $in, $errors);

    // If successful, page content will be updated via Ajax
    if ($answerid === null) {
        echo "QA_AJAX_RESPONSE\n0\n" . implode(' ', $errors ?? []);
        return;
    }

    $answer = qa_db_select_with_pending(qa_db_full_post_selectspec($userid, $answerid));
    $question = array_merge($question, qa_page_q_post_rules($question, null, null, $childposts));
    $answer = array_merge($answer, qa_page_q_post_rules($answer, $question, $answers, null));
    $usershtml = qa_userids_handles_html([$answer], true);
    $a_view = qa_page_q_answer_view($question, $answer, false, $usershtml, false);

    $themeclass = qa_load_theme_class(qa_get_site_theme(), 'ajax-answer', null, null);
    $themeclass->initialize();

    echo "QA_AJAX_RESPONSE\n1\n";

    // Send back whether the 'answer' button should still be visible
    echo (int)qa_opt('allow_multi_answers') . "\n";

    // Send back the count of answers
    $countanswers = $question['acount'] + 1;
    if ($countanswers == 1) {
        echo qa_lang_html('question/1_answer_title') . "\n";
    } else {
        echo qa_lang_html_sub('question/x_answers_title', $countanswers) . "\n";
    }

    // Send back the HTML
    $themeclass->a_list_item($a_view);

    return;
}

echo "QA_AJAX_RESPONSE\n0"; // fall back to non-Ajax submission if there were any problems
