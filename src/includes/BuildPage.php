<?php
namespace AutoBuild;

use AutoBuild\common\BaseCache;
use Parsedown;

class BuildPage
{
    private $base_post_dir;

    private $base_content_dir;
    
    private $base_template_dir;

    private $build_class;

    private $cache_system;

    public function setBasePostDir($path) {
        $this->base_post_dir = $path;
    }

    public function setBaseContentDir($path) {
        $this->base_content_dir = $path;
    }

    public function setBasTemplateDir($path) {
        $this->base_template_dir = $path;
    }

    public function setBuildClass(BuildFile $class) {
        $this->build_class = $class;
    }

    public function setCacheSystem(BaseCache $class) {
        $this->cache_system = $class;
    }

    public function buildHtml($post_title) {
        $cache_filename = $this->cache_system->get_cache($post_title);
        if(!$cache_filename || $cache_filename < time()) {
            $file_fd = fopen($this->base_post_dir.'/'.$post_title.'.md', "r") or die('Unable to open file');
            $content = fread($file_fd, filesize($this->base_post_dir.'/'.$post_title.'.md'));
            $parsedown = new Parsedown();
            $content = $parsedown->text($content); 
            $cache_filename = $this->base_content_dir.'/'.$post_title.'.php';

            $this->build_class->setBaseFile($this->base_template_dir.'/_post.html')
                            ->setOutPutFath($cache_filename)
                            ->setSearchKey(['content' => $content])->build();
        
            $this->cache_system->set_cache($post_title);
        }

        return $cache_filename;
    }
}