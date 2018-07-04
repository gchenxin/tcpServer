<?php 
    namespace server;
    
    class Log{
        public static function setLog($str){
            $files = fopen(LOG_PATH.'/err.log','a+');
            $log = "[" . date('Y-m-d H:i:s',time()) . "]\t##" . $str ."\n";
            fwrite($files,$log);
        }
    }
?>