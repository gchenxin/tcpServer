<?php 
    class Task extends Stackable{
        public function __construct($no){
            $this->no = $no;
        }
        
        public function run(){
            $data = json_decode(file_get_contents('pip.json'));
            for($i = 0; $i < 3; $i++){
                echo "task{$this->no}run:{$i}".PHP_EOL;
                sleep(1);
            }
            $this->status = true;
        }
        
        public function isFinish(){
            return $this->status;
        }
    }
    
    $pool = new Pool(3,'Worker');
    
    $taskList = [];
    for($i = 0; $i < 10; $i++){
        $taskList[$i] = new Task($i+1);
        $pool->submit($taskList[$i]);
    }
    
    $pool->shutdown();
    
    $pool->collect(function($work){
        return $work->isFinish();
    });

?>