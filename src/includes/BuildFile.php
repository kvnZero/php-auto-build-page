<?php
namespace AutoBuild;

class BuildFile
{
    private $base_file;

    private $output_file;

    private $search_key;

    private $file_fd;

    private $file_content;

    public function setBaseFile($filename = '') {
        $this->base_file = $filename;
    }

    public function setOutPutFath($filename = '') {
        $this->output_file = $filename;
    }

    public function setSearchKey(array $keys_table = []) {
        $this->search_key = $keys_table;
    }

    public function build()
    {
        $this->loadFile();
        $this->getFileContent();
        $this->replaceFileContent();
        $this->outputToFile();
    }

    private function loadFile()
    {
        if($this->file_fd) return $this->file_fd;
        $this->file_fd = fopen($this->base_file, "r") or die('Unable to open file');
    }

    private function getFileContent()
    {
        if(!$this->file_fd){
            $this->loadFile();
        }
        $this->file_content = fread($this->file_fd, filesize($this->base_file));
        fclose($this->file_fd);
        unset($this->file_fd);
    }

    private function replaceFileContent()
    {
        if(empty($this->search_key)) return;

        array_walk($this->search_key, [$this, 'replaceKeyRule']);

        $search_keys   = array_keys($this->search_key);
        $search_values = array_values($this->search_key);

        $this->file_content = str_replace($search_keys, $search_values, $this->file_content);
    }

    private function outputToFile()
    {
        $new_file_fd = fopen($this->output_file, "w") or die("Unable to open file!");
        fwrite($new_file_fd, $this->file_content);
        fclose($new_file_fd);
    }

    private function replaceKeyRule($item, &$key){
        $key = sprintf("{{%s}}", $key);
    }
}