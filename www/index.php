<?php
	
	include('functions.php');
	global $db;
	if(!$session){ header('location: '.$ssl.$_SERVER['SERVER_NAME'].'/login.php'); }

	$act = strip_tags($_GET['act']);
	$i2 = strip_tags($_GET['i2']);
	preg_match("/^[a-zA-Z0-9\w]+/", $act,$actx);
	preg_match("/^[a-zA-Z0-9\w]+/", $i2,$i2x);
	$act = $actx[0];
	$i2 = $i2x[0];

	if($act == 'logout')
	{
		foreach($_SESSION as $p=>$x)
		{
			unset($_SESSION[$p]);
		}
		
		 header('location: '.$ssl.$_SERVER['SERVER_NAME'].'/login.php');
		 exit;
	}	
	if($rang == 2)
	{
		if(!is_permission($act)){ header('location: '.$ssl.$_SERVER['SERVER_NAME'].'/catalog/');exit; }
	}
	
	if(empty($act)){ $act = 'status'; }

	$title = get_title($act);
	if(empty($title)){ $title = 'Menu'; }
	

	
	include(dir.'head.php'); 
	include(dir.'header.php');
	
	if(!empty($act))
	{
	?>
		<input type='hidden' id='page_x' value='<?=$act;?>'>
	<?

		if(!empty($act))
		{
			$in = dir.'a_'.$act.'.php';
		}	
		
		if(file_exists($in)){ include($in); }
		if($act == 'bot'){ include(dir.'siderbar.php'); }
		?>

			
		<?
	}

	include(dir.'footer.php');
?>