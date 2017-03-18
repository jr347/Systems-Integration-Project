<?php

class loginDB {

private $logindb;

public function __construct() {
	$this->logindb = new mysqli('localhost', 'root', 'godemper', 'login' );
	if ($this->logindb->connect_error){
	die('Connect Error (' . $this->logindb->connect_errno . ')' . $this->logindb->connect_error);
	}
	
	echo 'Success... ' . $this->logindb->host_info . "\n";
}

public function getInfo($usern, $passw){
	$uname = $this->logindb->real_escape_string($usern);
	$pwd = $this->logindb->real_escape_string($passw);
	$userInfo = "Select * from users where username = '$uname' AND password = '$pwd'";
	$response = $this->logindb->query($userInfo);
//	if(!$result){ throw new Exception("Database Error [{$this->logindb->errno}] {$this->logindb->error}"); }
	while ($row = $response->fetch_assoc())
	{
		echo "checking password for $uname".PHP_EOL;
		if ($row["password"] == $pwd)
		{
			echo "passwords match for $uname".PHP_EOL;
			return 1;// password match
		}
		echo "passwords did not match for $uname".PHP_EOL;
	}
	return 0;//no users matched username
}

public function newUser($usern, $passw, $email){
	$uname = $this->logindb->real_escape_string($usern);
	$pwd = $this->logindb->real_escape_string($passw);
	$em = $this->logindb->real_escape_string($email);
	$newInfo = "Insert into users (screenname, password, email) values ('$uname', '$pwd', '$em')";
	$response = $this->logindb->query($newInfo);
	return true;
}

}

?>
