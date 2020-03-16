<?php
declare(strict_types=1);

class block_pw_page
{
    private $directory;
    private $urltoroot;

    public function load_module($directory, $urltoroot): void
    {
        $this->directory = $directory;
        $this->urltoroot = $urltoroot;
    }

    public function match_request($request): bool
    {
        if ($request=='okidoki') {
            var_dump(321);die;

            return true;
        }
        echo "<script>console.log({json_encode($request)});</script>";

        var_dump(124);die;
        return false;
    }

    public function process_request($request): void
    {
        // prevent empty userid
        $userid = qa_get_logged_in_userid();
        if(empty($userid)) {
            echo 'Userid is empty!';
            return;
        }

        $qa_content = qa_content_prepare();

        return $qa_content;
    }
}