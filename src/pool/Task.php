<?php 
    namespace server\pool;
    
    class Task extends \Stackable{
        private $client;
        private $order;
        public function __construct(&$client,$order){
            $this->client = $client;
            $this->order = $order;
        }
        
        public function explain(){
            $buf = explode('/',$this->order);
            switch ($buf[0]){
                case 1:
                    $buf[1].="";
                    break;
                case 2:
                    sleep(5);
                    echo "success\n";
                    break;
                default:
                    $buf[1] = "param error";
            }
            socket_write($this->client,$buf[1],strlen($buf[1]));
            \server\Log::setLog("已返回数据到客户端");
        }
        
        public function run(){
            //业务逻辑
            $this->explain();
        }
    }
?>