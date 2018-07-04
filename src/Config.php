<?php 
    namespace server;
    
    class Config{
        private $config;
        public function __construct(){
            $this->config = json_decode(file_get_contents(CONF_PATH.'/config.json'));
        }
        public function get($name){
            return $this->config->$name;
        }
        
        public function set($name,$value){
            $this->config->$name = $value;
            file_put_contents(CONF_PATH.'/config.json',json_encode($this->config));
        }
    }
?>