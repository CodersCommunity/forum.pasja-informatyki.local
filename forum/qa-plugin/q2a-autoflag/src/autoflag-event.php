<?php

class autoflag_event
{
    const URL_REGEX = '/(http|ftp|https):\/\/([\w_-]+(?:\.[\w_-]+)+)([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])/';

    const REMOVED_CHARS_PERCENT_LIMIT = 30;

    public function process_event($event, $userid, $handle, $cookieid, $params)
    {
        $pointsLimit = qa_opt('autoflag_points_limit');
        if (empty($pointsLimit)) {
            return;
        }

        $postid = $params['postid'];
        $content = $params['content'];

        if (in_array($event, ['q_post', 'a_post', 'c_post']) && $this->get_user_points($userid) < $pointsLimit) {
            if ($this->has_added_suspicious_link($content)) {
                $this->add_flag($postid, qa_lang('autoflag/suspicious_link_added_reason'));
            }
            return;
        }

        if (in_array($event, ['q_edit', 'a_edit', 'c_edit'])) {
            if ($this->has_removed_content($content, $params['oldcontent'])) {
                $this->add_flag($postid, qa_lang('autoflag/removed_content_reason'));
                return;
            }
            if ($this->get_user_points($userid) < $pointsLimit && $this->has_edited_suspicious_link($content, $params['oldcontent'])) {
                $this->add_flag($postid, qa_lang('autoflag/suspicious_link_edited_reason'));
            }
            return;
        }
    }

    private function has_added_suspicious_link(string $content): bool
    {
        preg_match_all(self::URL_REGEX, $content, $matches);

        return $this->contains_suspicious_domain($matches[2] ?? []);
    }

    private function has_edited_suspicious_link(string $content, string $oldContent): bool
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

    private function has_removed_content(string $content, string $oldContent): bool
    {
        $oldLength = mb_strlen($oldContent);
        $newLength = mb_strlen($content);
        $limit = $oldLength * (self::REMOVED_CHARS_PERCENT_LIMIT / 100);

        return $oldLength - $limit > $newLength;
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
