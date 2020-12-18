<?php
namespace AutoBuild;

class BuildFile
{
    private $base_file;

    private $output_file;

    private $search_key = [];

    private $file_fd;

    private $file_content;

    private static $common_search_key = [];

    public function setBaseFile($filename = '') {
        $this->base_file = $filename;
    }

    public function setOutPutFath($filename = '') {
        $this->output_file = $filename;
    }

    public function setSearchKey(array $keys_table = []) {
        $this->search_key = $keys_table;
    }

    public function addSearchKey(array $keys_table = []) {
        $this->search_key += $keys_table;
    }

    public static function register_common_search_key($key, $callback) {
        self::$common_search_key[$key] = $callback;
    }

    public static function unregister_common_search_key($key) {
        unset(self::$common_search_key[$key]);
    }
    
    public function build()
    {
        $this->loadFile();
        $this->getFileContent();
        $this->replaceFileCommonContent();
        $this->replaceFileContent();
        $this->outputToFile();

        return $this->output_file;
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

    /**
     * 用于提取常用的search_key并通过回调方法去执行替换操作
     */
    private function replaceFileCommonContent()
    {
        $rule = "/{% ?(?<key>[a-z_]+ .+?) ?%}/";
        preg_match_all($rule, $this->file_content, $matches);
        if($matches){
            for ($i=0; $i < count($matches[0]); $i++) { 
                $arr = explode(' ', $matches['key'][$i]);
                if(in_array($arr[0], array_keys(self::$common_search_key))){
                    $key = $arr[0];
                    unset($arr[0]);
                    self::$common_search_key[$key]($key, $matches[0][$i], $this->file_content, join(' ', $arr));
                }
            }
        }
    }

    private function replaceFileContent()
    {
        if(empty($this->search_key)) return;

        $this->replaceKeyRule();

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

    private function replaceKeyRule(){
        foreach ($this->search_key as $key => $value){
            $new_key = sprintf("{{%s}}", $key);
            $this->search_key[$new_key] = $value;
            unset($this->search_key[$key]);
        }
    }
}