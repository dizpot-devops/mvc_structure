<?php


class Config
{
    private $config_directory = __DIR__ . "/../config/";
    private $configs = [];

    public function __construct($environment)
    {
        $this->config_directory = $this->config_directory;
        $this->load();
        $this->config_directory = $this->config_directory . $environment['path'] . "/";
        $this->load();
    }

    public function load() {
        if ($handle = opendir($this->config_directory)) {

            while (false !== ($entry = readdir($handle))) {
                if($entry == '.' || $entry == '..') { continue; }
                if(is_dir($this->config_directory . $entry)) { continue; }
                $info = explode('.',$entry);
                $info = $info[0];
                $this->configs[$info] = include $this->config_directory . $entry;
            }
            closedir($handle);
        }
    }
    public function get() {
        return $this->configs;
    }
}