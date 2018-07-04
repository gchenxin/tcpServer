<?php
    namespace server;
    
    class Server{
        private $socket;
        private $host;
        private $port;
        private $callback;
        private $sockStream;
        private $protocol;
        private $clientNum;
        private $clientArr;
        private $status;
        private $pool;
        
        public function __construct($host = null,$port = null,$sockStream = null,$protocol = null){
            $config = new Config();
            Log::setLog("读取配置文件....");
            //读取默认的host
            if(empty($host)){
                $host = $config->get('host');
            }
            $this->host = $host;
            //读取默认的port
            if(empty($port)){
                $port = $config->get('port');
            }
            $this->port = $port;
            //读取默认的sockStream
            if(empty($sockStream)){
                $sockStream = $config->get('sockStream');
            }
            $this->sockStream = $sockStream;
            //读取默认的protocol
            if(empty($protocol)){
                $protocol = $config->get('protocol');
            }
            $this->protocol = $protocol;
            $this->socket = socket_create(AF_INET,$this->sockStream,$this->protocol);
            socket_bind($this->socket,$this->host,$this->port);
            Log::setLog("初始化网络连接...");
            if(!socket_listen($this->socket,$config->get('queue_size'))){
                Log::setLog("端口监听失败！");
                die('端口监听失败');
            }
            $this->clientNum = 0;
            Log::setLog("服务器已开启！已连接客户端数量：{$this->clientNum},工作组数量：{$config->get('workerSize')},任务队列大小：{$config->get('queue_size')}");
            $this->pool = new \Pool($config->get('workerSize'),"\\server\\pool\\MyWorker");
            
        }
        
        public function start(){
            while(true){
                if(!empty($this->status)){
                    Log::setLog("服务器不再接受客户端请求，将在完成当前任务后关闭！");
                    break;
                }
                $client = socket_accept($this->socket);
                $remoteAddr = '';
                socket_getpeername($client, $remoteAddr);
                $this->clientNum++;
                $this->clientArr[substr(md5($remoteAddr),0,10)] = [
                    'addr'  =>  $remoteAddr,
                    'socket'=>  $client
                ];
                $buf = socket_read($client,1024);
                Log::setLog("客户端{$remoteAddr}连接成功，已连接客户端数量：{$this->clientNum}");
                Log::setLog("已接收到客户端{$remoteAddr}的信号：{$buf}");
                //提交给线程池处理
                $this->pool->submit(new pool\Task($client,$buf));
                socket_close($client);
                $this->clientNum--;
                unset($this->clientArr[substr(md5($remoteAddr),0,10)]);
            }
        }
        
        public function __destruct(){
            $this->status = 1;
            Log::setLog("释放资源......");
            $this->pool->shutdown();
            if($this->socket)
                socket_close($this->socket);
            Log::setLog("服务器已关闭！");
        }
    }
    