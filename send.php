#!/usr/bin/php
<?php
require_once '/usr/share/php/PhpAmqpLib/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'admin', 'admin');
$channel = $connection->channel ();

$channel->queue_declare('hello', false, false, false, false);

$msg = new AMQPMessage($argv[1]);
$channel->basic_publish($msg, '', 'hello');

echo " [x] Sent '$argv[1]'\n";

$channel->close();
$connection->close();

?>
