<?php


class IndexController extends AbsController
{
    public function index(){

        $list_html = '';
        foreach (post_file_list() as $filename) {
            if(empty($filename)) continue;
            $list_html .= '<li><a href="/?page=post&post='.$filename.'">'.$filename.'</a></li>';
        }

        $template_file = TEMPLATE_PATH.'_list.php';
        $search_keys   = [
            'list'=>$list_html
        ];


        $this->setSearchKeys($search_keys);
        $this->setTemplateFile($template_file);
    }
}