<?php

//**************************************************************************************************************
// Description:     This file contains a list of functions used for authenticating users or creating new users
//		    to access the service provided. The getInfo function verifies whether the user/password combination
//		    exist. It will throw a general error message to the user based on whether 1. The username is
//		    valid or 2. The password is incorrect. The newUser function adds a new user to the database for 
//		    the service.
//**************************************************************************************************************

class loginDB {

private $logindb;

public function __construct() {
	$this->logindb = new mysqli('localhost', 'root', 'godemper', 'login' );
	if ($this->logindb->connect_error){
	die('Connect Error (' . $this->logindb->connect_errno . ')' . $this->logindb->connect_error);
	}
	
	echo 'Success... ' . $this->logindb->host_info . "\n";
}

public function getInfo($req){
	$uname = $this->logindb->real_escape_string($req['username']);
	$pwd = $this->logindb->real_escape_string($req['password']);
	$userInfo = "Select * from users where username = '$uname' AND password = '$pwd'";
	$response = $this->logindb->query($userInfo);
//	if(!$result){ throw new Exception("Database Error [{$this->logindb->errno}] {$this->logindb->error}"); }
	while ($row = $response->fetch_assoc())
	{
		echo "checking password for $uname".PHP_EOL;
		if ($row["password"] == $pwd)
		{
			echo "passwords match for $uname".PHP_EOL;
			return true;// password match
		}
		echo "passwords did not match for $uname".PHP_EOL;
	}
	return false;//no users matched username
}

public function newUser($req){
	$uname = $this->logindb->real_escape_string($req['username']);
	$pwd = $this->logindb->real_escape_string($req['password']);
	$em = $this->logindb->real_escape_string($req['email']);
	$firstn = $this->logindb->real_escape_string($req['first_name']);
	$lastn = $this->logindb->real_escape_string($req['last_name']);
	$user_exist = "Select * from users where username = '$uname'";
	$check = $this->logindb->query($user_exist);
	$rows = $check->num_rows;
	if($rows > 0){
		return false;
	}
	else {
		$newInfo = "Insert into users (username, password, first_name, last_name,email, role) values ('$uname', '$pwd', '$firstn', '$lastn','$em', 'user')";
		$response = $this->logindb->query($newInfo);
		return true;
	}
}

}

?>
