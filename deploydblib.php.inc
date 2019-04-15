<?php

class deployDB {

private $deploydb;

public function __construct() {
	$this->deploydb = new mysqli('localhost', 'root', 'godemper', 'deploy' );
	if ($this->logindb->connect_error){
	die('Connect Error (' . $this->logindb->connect_errno . ')' . $this->logindb->connect_error);
	}
	
	echo 'Success... ' . $this->logindb->host_info . "\n";
}

public function getNextPackage($req){
	$name = $this->deploydb->real_escape_string($req['packageName']);
	if($name == 'FEpackage-v'){
		$packageInfo = "Select Max(ver) from fe";
		$response = $this->deploydb->query($packageInfo);
		$count = $response->num_rows;
		if($count > 0){
			$result = $response->fetch_array(MYSQLI_ASSOC);
			return $result['Max(ver)'];
		}
		else{
			return 0;
			}
	}
	else {
		$name = $req['packageName'];
		$packageInfo = "Select Max(ver) AS Latest from be";
		$response = $this->deploydb->query($packageInfo);
		$count = $response->num_rows;
		if($count > 0){
			$result = $response->fetch_array(MYSQLI_ASSOC);
			return $result['Max(ver)'];
		}
		else{
			return 0;
			}	
	}


}

public function deployFile($req) {
	$data = $req['packageName'];
	$level = $req['level'];
	$fepackage = "Select Max(ver) AS Latest from fe";
	$bepackage = "Select Max(ver) AS Latest from be";
	if ($level == 'QA'){
		if($data == 'FEpackage-v'){
			$response = $this->deploydb->query($fepackage);
			$result = $response->fetch_array(MYSQLI_ASSOC);
			$send_file = shell_exec('scp /feqav-'.$result['Max(ver)'].'.tar.gz johnny@192.168.43.61:/home/johnny/Desktop');
			$install_file = shell_exec('./installFEQA.php');
			return true;
		}
		elseif($data == 'BEpackage-v'){
			$response = $this->deploydb->query($bepackage);
			$result = $response->fetch_array(MYSQLI_ASSOC);
			$send_file = shell_exec('scp /beqav-'.$result['Max(ver)'].'.tar.gz ethan@192.168.43.62:/home/ethan/Desktop');
			$install_file = shell_exec('./installBEQA.php');
			return true;
		}
				
	}
	elseif ($level == 'Prod'){
		if($data == 'FEpackage-v'){
			$response = $this->deploydb->query($fepackage);
			$result = $response->fetch_array(MYSQLI_ASSOC);
			$send_file = shell_exec('scp /feqav-'.$result['Max(ver)'].'.tar.gz johnny@192.168.43.63:/home/johnny/Desktop');
			$install_file = shell_exec('./installFEQA.php');
			return true;
		}
		elseif($data == 'BEpackage-v'){
			$response = $this->deploydb->query($bepackage);
			$result = $response->fetch_array(MYSQLI_ASSOC);
			$send_file = shell_exec('scp /beqav-'.$result['Max(ver)'].'.tar.gz ethan@192.168.43.64:/home/ethan/Desktop');
			$install_file = shell_exec('./installBEQA.php');
			return true;
		}
		
	}
	
}

}

?>
