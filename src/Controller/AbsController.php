<?php

use AutoBuild\BuildFile;
use AutoBuild\BuildPage;

class AbsController
{
    protected $template_file;
    protected $search_keys;

    public function build($include_file)
    {
        $buildFile = new BuildFile();
        $buildFile->setBaseFile($this->template_file);
        $buildFile->setOutPutFath($include_file);
        $buildFile->setSearchKey($this->search_keys);

        $buildPage = new BuildPage();
        $buildPage->setBuildClass($buildFile);
        $buildPage->build();
        file_set_cache_now($include_file);
    }

    protected function setTemplateFile($filename){
        $this->template_file = $filename;
    }

    protected function setSearchKeys($search_keys){
        $this->search_keys = $search_keys;
    }
}