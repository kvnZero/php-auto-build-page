<?php


class ToolController extends AbsController
{
    public function index(){
        $list = [
            '理智消费' => "https://github.com/kvnZero/flutter_consume",
            'GithubPage显示听歌记录' => 'https://github.com/kvnZero/netease-music-show'
        ];

        $list_html = '';
        foreach ($list as $name => $url) {
            $list_html .= '<li><a href="'.$url.'">'.$name.'</a></li>';
        }

        $template_file = TEMPLATE_PATH.'_list.php';
        $search_keys   = [
            'list'=>$list_html
        ];

        $this->setSearchKeys($search_keys);
        $this->setTemplateFile($template_file);
    }
}