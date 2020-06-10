<?php		
	include('functions.php');
	$login = strip_tags($_POST['login']); $login = htmlspecialchars($login,ENT_QUOTES);
	$pass = strip_tags($_POST['password']); $pass = htmlspecialchars($pass,ENT_QUOTES);
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		if((!empty($login)) && (!empty($pass)))
		{
			if(autorize($login,$pass,1)){  header('location: '.$ssl.$_SERVER['SERVER_NAME'].'/status/'); }
					else {  header('location: '.$ssl.$_SERVER['SERVER_NAME'].'/login.php'); }
		} else { header('location: '.$ssl.$_SERVER['SERVER_NAME'].'/login.php'); }
	}
	
	if($session){ header('location: '.$ssl.$_SERVER['SERVER_NAME'].'/status/'); }

?>

<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Авторизация</title>
	<link rel="stylesheet" href="css/style_a.css">
	<!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<style>
	
	</style>
</head>
<body>

  <section class="container">
    <div class="login">
      <h1>Войти в личный кабинет</h1>
      <form method="post" action="login.php">
        <p><input type="text" name="login" value="" placeholder="Логин или Email"></p>
        <p><input type="password" name="password" value="" placeholder="Пароль"></p>
        <p class="submit"><input type="submit" name="commit" value="Войти"></p>
      </form>
    </div>

  </section>
</body>
</html>
