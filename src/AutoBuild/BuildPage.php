<?php
namespace AutoBuild;

use Parsedown;

class BuildPage
{
    private $build_class;

    public function setBuildClass(BuildFile $class) {
        $this->build_class = $class;
    }

    public function build() {
        return $this->build_class->build();
    }

    public function buildPost($post_title) {
        $file_fd = fopen($this->base_post_dir.'/'.$post_title.'.md', "r") or die('Unable to open file');
        $content = fread($file_fd, filesize($this->base_post_dir.'/'.$post_title.'.md'));
        $parsedown = new Parsedown();
        $content = $parsedown->text($content); 
        $cache_filename = $this->base_content_dir.'/'.$post_title.'.php';

        $this->build_class->build();
    
        return $cache_filename;
    }
}