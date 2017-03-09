<?php
require_once '/usr/share/php/PhpAmqpLib/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$userN = $_POST["inputUser"];
$passw = $_POST["inputPassword"];
$fname = $_POST["inputFName"];
$lname = $_POST["inputLName"];
$email = $_POST["inputEmail"];
echo "$userN, $passw, $fname, $lname, $email";

$connection = new AMQPStreamConnection('localhost', 5672, 'admin', 'admin');
$channel = $connection->channel ();

$channel->exchange_declare('direct_logs', 'direct', false, false, false);

$severity = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'info';

$data = array();
$data['type'] = "register";
$data['username'] = "$userN";
$data['password'] = "$passw";
$data['firstname'] = "$fname";
$data['lastname'] = "$lname";
$data['email'] = "$email";

$data = implode(' ', $data);
$msg = new AMQPMessage($data);

$channel->basic_publish($msg, 'direct_logs', $severity);

echo " [x] Sent  $data \n";

$channel->close();
$connection->close();

?>



