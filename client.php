<?php
// 	$client = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
// 	$host = '127.0.0.1';
// 	$port = '1902';
// 	if(socket_connect($client,$host,$port)){
// 		socket_write($client,"1/there");
// 		$buf = socket_read($client,1024);
// 		var_dump('server apply:'.$buf);
// 		socket_close($client);
// 	}else{
// 		var_dump('连接超时');
// 	}

    $errno = null;
    $errstr = null;
    $fp = stream_socket_client("tcp://127.0.0.1:1902", $errno, $errstr, 30,STREAM_CLIENT_ASYNC_CONNECT);
    if (!$fp) {
        echo "$errstr ($errno)<br />\n";
    } else {
        fwrite($fp, "2/there");
//         while (!feof($fp)) {
//             echo fgets($fp, 1024);
//         }
        echo "\nsuccess";
        fclose($fp);
    }