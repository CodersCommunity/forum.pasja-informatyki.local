<?php

class autoflag_event
{
    const URL_REGEX = '/(http|ftp|https):\/\/([\w_-]+(?:\.[\w_-]+)+)([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])/';

    public function process_event($event, $userid, $handle, $cookieid, $params)
    {
        $pointsLimit = qa_opt('autoflag_points_limit');
        if (empty($pointsLimit) || $this->get_user_points($userid) > $pointsLimit) {
            return;
        }

        $postid = $params['postid'];
        $content = $params['content'];

        if ($event === 'q_post' || $event === 'a_post' || $event === 'c_post') {
            if ($this->is_added_suspicious($content)) {
                $this->add_flag($postid, qa_lang('autoflag/added_reason'));
            }
            return;
        }

        if ($event === 'q_edit') {
            if ($this->is_edited_suspicious($content, $params['oldquestion']['content'])) {
                $this->add_flag($postid, qa_lang('autoflag/edited_reason'));
            }
            return;
        }
        if ($event === 'a_edit') {
            if ($this->is_edited_suspicious($content, $params['oldanswer']['content'])) {
                $this->add_flag($postid, qa_lang('autoflag/edited_reason'));
            }
            return;
        }
        if ($event === 'c_edit') {
            if ($this->is_edited_suspicious($content, $params['oldcomment']['content'])) {
                $this->add_flag($postid, qa_lang('autoflag/edited_reason'));
            }
            return;
        }
    }

    private function is_added_suspicious(string $content): bool
    {
        preg_match_all(self::URL_REGEX, $content, $matches);

        return $this->contains_suspicious_domain($matches[2] ?? []);
    }

    private function is_edited_suspicious(string $content, string $oldContent): bool
    {
        preg_match_all(self::URL_REGEX, $content, $matches);
        preg_match_all(self::URL_REGEX, $oldContent, $oldMatches);
        $domains = $matches[2] ?? [];

        foreach ($matches[0] ?? [] as $index => $url) {
            if (in_array($url, array_values($oldMatches[0]))) {
                unset($domains[$index]);
            }
        }

        return $this->contains_suspicious_domain($domains);
    }

    private function contains_suspicious_domain(array $domains): bool
    {
        $allowedDomains = explode(',', qa_opt('autoflag_allowed_domains') ?? '');

        foreach ($domains as $domain) {
            if (!in_array($domain, $allowedDomains) && !in_array('www.' . $domain, $allowedDomains)) {
                return true;
            }
        }

        return false;
    }

    private function get_user_points(int $userid): int
    {
        $points = qa_db_select_with_pending(qa_db_user_points_selectspec($userid, true));

        return $points['points'];
    }

    private function add_flag(int $postId, string $reason)
    {
        require_once QA_INCLUDE_DIR . 'db/votes.php';
        require_once QA_INCLUDE_DIR . 'db/post-update.php';
        $userid = qa_opt('autoflag_user_id');

        qa_db_userflag_set($postId, $userid, true);
        qa_db_post_recount_flags($postId);
        qa_db_flaggedcount_update();

        qa_db_query_sub('
            INSERT INTO `^flagreasons` (`userid`, `postid`, `reasonid`, `notice`)
            VALUES (#, #, $, $)
            ON DUPLICATE KEY UPDATE `notice` = $
        ', $userid, $postId, 0, $reason, $reason);
    }
}
