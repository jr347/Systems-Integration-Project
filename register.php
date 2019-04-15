<?php
require_once '/usr/share/php/PhpAmqpLib/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

<<<<<<< HEAD
$userN = $_GET["inputUser"];
$passw = $_GET["inputPassword"];
$firstN = $_GET["inputFName"];
$lastN = $_GET["inputLName"];
$email = $_GET["inputEmail"];
echo "$user, $passw, $firstN, $lastN, $email";
=======
$userN = $_POST["inputUser"];
$passw = $_POST["inputPassword"];
$fname = $_POST["inputFName"];
$lname = $_POST["inputLName"];
$email = $_POST["inputEmail"];
echo "$userN, $passw, $fname, $lname, $email";
>>>>>>> fce7127cac45468850b4423d37b29b0bcc6624df

$connection = new AMQPStreamConnection('localhost', 5672, 'admin', 'admin');
$channel = $connection->channel ();

$channel->exchange_declare('direct_logs', 'direct', false, false, false);

$severity = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'info';

$data = array();
<<<<<<< HEAD
$data['type'] = "$register";
=======
$data['type'] = "register";
>>>>>>> fce7127cac45468850b4423d37b29b0bcc6624df
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



