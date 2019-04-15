<<<<<<< HEAD
#!/usr/bin/php
=======
>>>>>>> fce7127cac45468850b4423d37b29b0bcc6624df
<?php
require_once '/usr/share/php/PhpAmqpLib/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

<<<<<<< HEAD
//$userN = $_GET["inputUser"];
//$passw = $_GET["inputPassword"];

$connection = new AMQPStreamConnection('localhost', 5672, 'admin', 'admin');
$channel = $connection->channel ();

$channel->exchange_declare('direct_logs', 'direct', false, false, false);

$severity = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'info';

$data = array();
$data['type'] = "login";
$data['username'] = "userN";
$data['password'] = "passw";

$data = implode(' ', $data);
$msg = new AMQPMessage($data);

$channel->basic_publish($msg, 'direct_logs', $severity);

echo " [x] Sent  $data \n";

$channel->close();
$connection->close();
=======
class RpcClient {
	private $connection;
	private $channel;
	private $callback_queue;
	private $response;
	private $corr_id;

	public function __construct() {
		$this->connection = new AMQPStreamConnection('192.168.43.125', 5672, 'IT490', '12345', 'Login');
		$this->channel = $this->connection->channel();
		list($this->callback_queue, ,) = $this->channel->queue_declare("login_send", 'direct', false, true, false);
		$this->channel->queue_bind('login_send', 'login_send');
		$this->channel->basic_consume(
		$this->callback_queue, '', false, false, false, false, array($this, 'on_response'));
	
	}
	
	public function on_response($rep) {
		if($rep->get('correlation_id') == $this->corr_id){
			$this->response = $rep->body;
		}
	}
	
	public function call($n) {
		$this->response = null;
		$this->corr_id = uniqid();

		$msg = new AMQPMessage(
			(string) $n,
			array('correlation_id' => $this->corr_id,
				'reply_to' => $this->callback_queue)
		);
		$this->channel->basic_publish($msg, '', 'login_send');
		while(!$this->response){
			$this->channel->wait();
		}
		$resp = $this->response;
		return $resp;
	}
};


$userN = $_POST['userN'];
$passw = $_POST['passw'];
print "$userN, $passw";  //testing if value were set

//$fibonacci_rpc = new FibonacciRpcClient();
//$response = $fibonacci_rpc->call(30);
//echo " [.] Got ", $response, "\n";

$data = array();
$data['type'] = "login";
$data['username'] = "$userN";
$data['password'] = "$passw";

$msg = json_encode($data);

$login_rpc = new RpcClient();
$response = $login_rpc->call($msg);
$results = json_decode($response, true);
echo " [.] Got ", $results, "\n";
>>>>>>> fce7127cac45468850b4423d37b29b0bcc6624df

?>


