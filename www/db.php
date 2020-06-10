<?php
	ini_set('session.gc_maxlifetime', 2592000);
	ini_set('session.cookie_lifetime', 2592000);
	set_time_limit(0);
	error_reporting(0);
	ini_set('display_errors', 0);
	session_start();
		
	DEFINE('login','XXXXXXXXXXXX');
	DEFINE('pass','XXXXXXXXXXXX');
	DEFINE('host','localhost');
	DEFINE('base','XXXXXXXXXXXX');
	DEFINE('dir','pages_x/');
	DEFINE('root_dir',$_SERVER['DOCUMENT_ROOT']);

	$db = mysqli_connect(host,login,pass,base);
 	mysqli_query($db,'SET NAMES utf8');
	mysqli_query($db,'SET COLLATION_CONNECTION=utf8_bin');

	mysqli_select_db($db,base);
	global $db;

	$ssl = 'https://'; 
	global $ssl;
	
	$login_ = $_SESSION['login'];
	$pwd_ = $_SESSION['hash'];
	$rang = intval($_SESSION['rang']);
	$user_id = $_SESSION['user_id'];
	
	$session = autorize($login_,$pwd_);

	$token = get_setting('bot_token');
	$url = "https://api.telegram.org/bot".$token;	
?>
