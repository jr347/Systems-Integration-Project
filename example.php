<?php
require_once('mysqlhelp.php.inc');
include('session.php');

if($_SERVER["REQUEST_METHOD"] == "POST"){
$request = array();
$request['email'] = $_SESSION['new']['email'];
$request['phone'] = $_SESSION['new']['phone'];
$request['iname'] = $_SESSION['new']['iname'];
$request['cpname'] = $_POST['cpname'];
$request['address'] = $_POST['address'];
$request['city'] = $_POST['city'];
$request['state'] = $_POST['state'];
$request['zcode'] = $_POST['zcode'];
$request['account'] = $_POST['account'];
$request['cname'] = $_POST['cname'];
$request['ctitle'] = $_POST['ctitle'];
$request['username'] = $_SESSION['username'];
$login = new loginDB();
$result = $login->newLead($request);
var_dump($result);
if($result){
        $message = "Succesful!";
}
else { $message = "Submission already exist!"; }
}
?>

