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
	switch($request['type'])
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

$channel->queue_declare('hello', false, false, false, false);

echo ' [*] Waiting for message. To exit press CTRL+C', "\n";

$callback = function($msg) {
	echo " [x] Received ", $msg->body, "\n";
	$body = $msg->getBody();
	$payload = json_decode($body, true);
	return requestProcessor($payload);
};

$channel->basic_consume('hello', '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
	$channel->wait();
}

?>
