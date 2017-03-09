<?php
require_once '/usr/share/php/PhpAmqpLib/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

try {
$userN = $_POST['userN'];
$passw = $_POST['passw'];
print "$userN, $passw";  //testing if value were set


$connection = new AMQPStreamConnection('localhost', 5672, 'admin', 'admin');
$channel = $connection->channel ();

$channel->queue_declare('hello', false, false, false, false);

//$severity = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'info';

$data = array();
$data['type'] = "login";
$data['username'] = "$userN";
$data['password'] = "$passw";

//$data = implode(' ', $data); //turn array into a string
$json_message = json_encode($data);
$msg = new AMQPMessage($json_message);

$channel->basic_publish($msg, '', 'hello' );

echo " [x] Sent '$data' \n";
}
catch (Exception $e) {
	echo 'Error: ' . $e->getMessage();
}
$channel->close();
$connection->close();

?>


