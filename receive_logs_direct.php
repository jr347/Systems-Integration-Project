#!/usr/bin/php
<?php
require_once '/usr/share/php/PhpAmqpLib/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
require_once('mysqlHelp.php.inc'); 

function doLogin ($username, $password)
{
	$login = new loginDB();
	$result = $login->getInfo($username, $password);
	
	if($result) {
		return "Server received request!";
	}
	else{
		return "Account does not exist.";
	}

}

function newRegister($request) {
	$register = new loginDB();
	$result = $login->newUser($request[0], $request[1], $request[2]);
	if($result){
		return "New user registered!";
	}
	else
		return "Unable to register.";
}

function requestProcessor($request){
	echo "Request Received".PHP_EOL;
	var_dump($request);
	echo '\n' . 'End Message';
	if(!isset($request['type']))
	{
		return "ERROR: unsupported message type";
	}
	switch($reqeust['type'])
	{
		case "register":
			return newRegister($request);
		case "login":
			return doLogin($request['username'], $request['password']);
	return "Request received and processed!";
	}
}

$connection = new AMQPStreamConnection('localhost', 5672, 'admin', 'admin');
$channel = $connection->channel();

$channel->exchange_declare('direct_logs', 'direct', false, false, false);

list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);
/*$severities = array_slice($argv, 1);
if(empty($severities)){
	file_put_contents('php://stderr', "Usage: $argv[0] [info] [warning] [error]\n");
	exit(1);
}

foreach($severities as $severity){
	$channel->queue_bind($queue_name, 'direct_logs', $severity);
}*/


echo ' [*] Waiting for logs. To exit press CTRL+C', "\n";

$callback = function($msg){
	echo ' [x] ', $msg->delivery_info['routing_key'], ':', $msg->body, "\n";
};

$channel->basic_consume($queue_name, '', false, true, false, false, $callback);

while(count($channel->callbacks)){
	$channel->wait();
}

$channel->close();
$connection->close();
?>

