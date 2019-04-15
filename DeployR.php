#!/usr/bin/php
<?php
require_once '/usr/share/php/PhpAmqpLib/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
<<<<<<< HEAD
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Channel\AbstractChannel;
require_once('deploydblib.php.inc'); 
function sendFile ($req)
{
	$deploy = new deploydb();
	$retult = $deploy->deployFile($req);
	return $result;
}

function nextPackage($data){
	$deploy = new deploydb();
	$result = $deploy->getNextPackage($data);
	return $result;	
}
=======
require_once('mysqlHelp.php.inc'); 
function sendFile ()
{
	chdir('./');
	$send_file = shell_exec('./example.sh'); 
	
}

>>>>>>> fce7127cac45468850b4423d37b29b0bcc6624df

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
<<<<<<< HEAD
		case "deploy":
			return sendFile($request);
		case "nextPackage":
			return nextPackage($request);
=======
		case "deployRequest":
			return sendFile();
>>>>>>> fce7127cac45468850b4423d37b29b0bcc6624df
//	return "Request received and processed!";
	}
}


<<<<<<< HEAD
$connection = new AMQPStreamConnection('192.168.43.104', 5672, 'IT490', '12345', 'Login');
=======
$connection = new AMQPStreamConnection('192.168.43.125', 5672, 'IT490', '12345', 'Login');
>>>>>>> fce7127cac45468850b4423d37b29b0bcc6624df
$channel_send = $connection->channel();
$channel_rec = $connection->channel();
$channel_send->queue_declare('deploy_receive, false, true, false, false');

echo " [x] Awaiting RPC request\n";
$callback = function($req) {
	global $channel_send;
	$n = $req->body;
	$json_message = json_decode($n, true);
	echo " [.] Received(", $json_message['type'], ")\n";
	$resl = requestProcessor($json_message);
	//var_dump($resl);
	$results = json_encode($resl, true);
	$msg = new AMQPMessage(
		(string) $results,
		array('correlation_id'=> $req->get('correlation_id')),
		array('delivery_mode' => 2));
	$channel_send->basic_publish($msg, '', 'deploy_receive');

	//$req->delivery_info['channel']->basic_publish(
		//$msg, '', $req->get('reply_to'));
	$req->delivery_info['channel']->basic_ack(
		$req->delivery_info['delivery_tag']);
};

//$channel->basic_qos(null, 1, null);
$channel_rec->basic_consume('deploy_send', '', false, false, false, false, $callback);

while(count($channel_rec->callbacks)){
	$channel_rec->wait();
}

$channel_rec->close();
$connection->close();
?>
