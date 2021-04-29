<?php


class PostController extends AbsController
{
    public function index(){

        $filename = POST_PATH.'/'.CURRENT_POST.'.md';
        if(!file_exists($filename)){
            die("post not found");
        }
        $file_fd = fopen($filename , "r") or die('Unable to open file');
        $content = fread($file_fd, filesize($filename));
        fclose($file_fd);
        $search_keys = find_post_head_and_clear($content);
        $parsedown = new Parsedown();
        $content = $parsedown->text($content);

        $template_file = TEMPLATE_PATH.'_post.php';
        $search_keys   += [
            'content'=>$content
        ];

        $this->setSearchKeys($search_keys);
        $this->setTemplateFile($template_file);
    }
}