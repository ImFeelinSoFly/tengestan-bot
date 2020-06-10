<?php
	ini_set('session.gc_maxlifetime', 2592000);
	ini_set('session.cookie_lifetime', 2592000);
	error_reporting(0);
	ini_set('display_errors', 0);
	
	session_start();
	DEFINE('base','XXXXXXXXXXXX');
	DEFINE('login','XXXXXXXXXXXX');
	DEFINE('pass','XXXXXXXXXXXX');
	DEFINE('host','localhost');
		
	$db = mysqli_connect(host,login,pass,base);
 	mysqli_query($db,'SET NAMES utf8');
	mysqli_query($db,'SET COLLATION_CONNECTION=utf8_bin');

	mysqli_select_db($db,base);
	global $db;

	$rec = strip_tags($_GET['mode']);
	list($mode,$id_bot) = explode(':',$rec);
	
	if($mode == 'alt')
	{
		$rx = mysqli_query($db,"SELECT `token` FROM `bot_list` WHERE `id_bot` = '$id_bot'");
		$data = mysqli_fetch_assoc($rx);
		$token = $data['token'];
		$url = "https://api.telegram.org/bot".$token;
	}else
	{
		$token = get_setting('bot_token');
		$url = "https://api.telegram.org/bot".$token;
		$id_bot = 0;
	}
?>
