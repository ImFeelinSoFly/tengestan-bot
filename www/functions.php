<?php
	include('db.php');
	
	function write($s)
	{
		return false;
		$f = fopen($p.$_SERVER['DOCUMENT_ROOT'].'/telegram/log.txt','a+');
		fwrite($f,$s."\n");
		fclose($f);
	}
	
	function get_title($title)
	{
		global $db;
		$s = "SELECT `name` FROM `a_menu` WHERE `alias` = '$title'";
		$r = mysqli_query($db, $s);
		$ok = mysqli_fetch_assoc($r);
		$num = mysqli_num_rows($r);
		if($num > 0){ return $ok['name']; } 
		
	}
	
	function get_current($col,$id_chat)
	{
		global $db;
		$s = "SELECT `$col` FROM `Users` WHERE `id_chat` = '$id_chat'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok[$col];
	}
	
	function get_current_users($col,$id_user_)
	{
		global $db;
		$s = "SELECT `$col` FROM `Accounts` WHERE `id` = '$id_user_'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok[$col];
	}
	
	
	function set_balance_user($sum,$type,$id_user_)
	{
		global $db;
		$s = "SELECT `balance` FROM `Accounts` WHERE `id` = '$id_user_'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		$balance = intval($ok['balance']);
		if($type == '-'){ $sum = $balance - $sum; } elseif($type == '+') { $sum = $balance + $sum; }
		
		$s = "UPDATE `Accounts` SET `balance` = '$sum' WHERE `id` = '$id_user_'";
		mysqli_query($db,$s);
	}	
		
	
	function get_setting($col)
	{
		global $db;
		$s = "SELECT `$col` FROM `setting` WHERE `id` = '1'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok[$col];
	}
	

	function autorize($login,$pwd,$type = 0)
	{
		global $db;
		if($type == 1){ $pwd = md5($pwd); }
		$s = "SELECT `id`,`rang` FROM `Accounts` WHERE `login` = '$login' AND `password` = '$pwd'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		if($ok['id'] > 0)
		{	
			$_SESSION['user_id'] = $ok['id'];
			$_SESSION['login'] = $login;
			$_SESSION['rang'] = $ok['rang'];
			$_SESSION['hash'] = $pwd;
			if(empty($_SESSION['num'])){ $_SESSION['num'] = 1; }
			return true;
		} else { return false; }	
	}

	function get_data_strukture($alias,$colomn)
	{
		global $db;
		$s = "SELECT `$colomn` FROM `struktura` WHERE `alias` = '$alias'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok[$colomn];
	}
	
	
	function get_content_strukture($id)
	{
		global $db;
		$s = "SELECT `content` FROM `struktura` WHERE `id` = '$id'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok['content'];
	}	

	
	function add_photo($i,$anket_id)
	{
		global $db;
		
		$s = "SELECT `id` FROM `photo` WHERE `anket_id` = '$anket_id'";
		$r = mysqli_query($db,$s);
		$num = mysqli_num_rows($r);
		
		if($num == 0)
		{
			$s = "INSERT INTO `photo` (`photo`,`anket_id`) VALUES ('$i','$anket_id')";
			mysqli_query($db,$s);
			$id = mysqli_insert_id($db);
			return $id;
		}
	}
	

	
	function get_id_name($name)
	{
		global $db;		
		$s = "SELECT `id` FROM `struktura` WHERE `name` = '$name'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok['id'];
	}
	
	function get_col($table,$colomn)
	{
		global $db;
		$s = "SELECT `$colomn` FROM `$table` WHERE `nick` = '$nick'";
		$r = mysqli_query($db,$s);
		
	}
	
	
	function get_head($col,$alias)
	{
		global $db;
		$s = "SELECT `$col` FROM `struktura` WHERE `alias` = '$alias'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok[$col];
	}
	
	
	function get_name_pid($id)
	{
		global $db;		
		$s = "SELECT `id` FROM `struktura` WHERE `id` = '$id'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok['id'];
	}


	
	function send_mail($to,$from,$tema,$msg)
	{
		$headers  = "Content-type: text/html; charset=UTF-8 \r\n"; 
		$headers .= "From:$from \r\n"; 
	
		
		if(mail($to, $tema, $msg, $headers))
		{ return true; } else { return false; }
	}	

	

	function send_money($sum,$comment,$wallet,$currency_x = 'RUB')
	{
		$token = get_setting_data('qiwi',7);
		
		$st = substr($wallet,0,1);
		if($st == 7){ $wallet = '+'.$wallet; }
		if($st == 8)
		{
			$st = substr($wallet,1,strlen($wallet));
			$wallet = '+7'.$st;
		}
		
		$currency_x = strtoupper($currency_x);
		$currency_['RUB'] = '643';
		$currency_['USD'] = '840';
		$currency = $currency_[$currency_x];
				
		$id = time()*1000;
		$id = strval($id);
		$data['id'] = $id;
		$data['sum']['amount'] = $sum;
		$data['sum']['currency'] = $currency;
		$data['paymentMethod']['type'] = 'Account';
		$data['paymentMethod']['accountId'] = $currency;
		$data['comment'] = $comment;
		$data['fields']['account'] = $wallet;
		
		$json = json_encode($data,true);

		$evo = curl_init(); 
		curl_setopt($evo, CURLOPT_URL, 'https://edge.qiwi.com/sinap/api/v2/terms/99/payments');
		curl_setopt($evo, CURLOPT_HEADER, false);
	
		curl_setopt($evo,CURLOPT_HTTPHEADER,array('Accept: application/json',
												"POST /sinap/api/v2/terms/99/payments HTTP/1.1",
												 'Content-Type: application/json',
												 'Host: edge.qiwi.com',
												 'Authorization: Bearer '.$token));
		curl_setopt($evo, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($evo, CURLOPT_BINARYTRANSFER,1);
		curl_setopt($evo,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($evo, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($evo, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($evo, CURLOPT_POST, true);
		curl_setopt($evo, CURLOPT_POSTFIELDS,$json);
		curl_setopt($evo, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.75 Safari/537.36');
		$res = curl_exec($evo);
		$json = json_decode($res,true); 
		
		$id = $json['transaction']['id'];
		#$stat = $json['transaction']['state']['code'];
		if($id > 0){ return true; } else { return false; }
		
	}
	
	
	
	function mobile_code($phone)
	{
		$evo = curl_init(); 
		$data = 'phone='.urlencode($phone);
		curl_setopt($evo, CURLOPT_URL, 'https://qiwi.com/mobile/detect.action');
		curl_setopt($evo, CURLOPT_HEADER, false);
	
		curl_setopt($evo,CURLOPT_HTTPHEADER,array('Accept: application/json',
												"POST /mobile/detect.action HTTP/1.1",
												 'Content-Type: application/x-www-form-urlencoded',
												 'Host: qiwi.com',
												 'Cache-Control: no-cache'));
		curl_setopt($evo, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($evo, CURLOPT_BINARYTRANSFER,1);
		curl_setopt($evo,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($evo, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($evo, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($evo, CURLOPT_POST, true);
		curl_setopt($evo, CURLOPT_POSTFIELDS,$data);
		curl_setopt($evo, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.75 Safari/537.36');
		$res = curl_exec($evo);
		$json = json_decode($res,true); 
		
		$code = $json['message'];	
		if($code > 0){ return $code;} else { return 0; }
		
	}	
	
	
	
	
	function send_mobile_money($sum,$wallet)
	{
		$token = get_setting_data('api_key',1);
		$code =  mobile_code($wallet);
		$wallet = str_replace('+','',$wallet);
		$wallet = substr($wallet,1,strlen($wallet));
		
		if($code < 0){  return 0; }
		
		$id = time()*1000;
		$id = strval($id);
		$data['id'] = $id;
		$data['sum']['amount'] = $sum;
		$data['sum']['currency'] = '643';
		$data['paymentMethod']['type'] = 'Account';
		$data['paymentMethod']['accountId'] = '643';
		$data['fields']['account'] = $wallet;
		
		$json = json_encode($data,true);#write($json);
		

		$evo = curl_init(); 
		curl_setopt($evo, CURLOPT_URL, 'https://edge.qiwi.com/sinap/api/v2/terms/'.$code.'/payments');
		curl_setopt($evo, CURLOPT_HEADER, false);
		curl_setopt($evo, CURLOPT_POST, true);
		curl_setopt($evo, CURLOPT_POSTFIELDS,$json);
		curl_setopt($evo,CURLOPT_HTTPHEADER,array('Accept: application/json',
												"GET /sinap/api/v2/terms/1/payments HTTP/1.1",
												 'Content-Type: application/json',
												 'Host: edge.qiwi.com',
												 'Authorization: Bearer '.$token));
		curl_setopt($evo, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($evo, CURLOPT_BINARYTRANSFER,1);
		curl_setopt($evo,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($evo, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($evo, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($evo, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 ');
		$res = curl_exec($evo);
		$json = json_decode($res,true); 
		$id = $json['transaction']['id'];
		if($id > 0){ return true; } else { return false; }
	}
			
		
	
	function card_info($card)
	{
		$evo = curl_init(); 
		$data = 'cardNumber='.$card;
		curl_setopt($evo, CURLOPT_URL, 'https://qiwi.com/card/detect.action');
		curl_setopt($evo, CURLOPT_HEADER, false);
	
		curl_setopt($evo,CURLOPT_HTTPHEADER,array('Accept: application/json',
												"POST /mobile/detect.action HTTP/1.1",
												 'Content-Type: application/x-www-form-urlencoded',
												 'Host: qiwi.com',
												 'Cache-Control: no-cache'));
		curl_setopt($evo, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($evo, CURLOPT_BINARYTRANSFER,1);
		curl_setopt($evo,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($evo, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($evo, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($evo, CURLOPT_POST, true);
		curl_setopt($evo, CURLOPT_POSTFIELDS,$data);
		curl_setopt($evo, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.75 Safari/537.36');
		$res = curl_exec($evo);
		$json = json_decode($res,true); 
		
		$code = $json['message'];	
		if($code > 0){ return $code;} else { return 0; }
		
	}	
			
			
			
	
	function card_money_send($card,$sum,$card_id,$currency_x = 'RUB')
	{
		$token = get_payment_setting('api_key',1);
		$evo = curl_init();
		
		$currency_['RUB'] = '643';
		$currency_['USD'] = '840';
		$currency = $currency_[$currency_x];
	
		$id = time()*1000;
		$id = strval($id);
		$data['id'] = $id;
		$data['sum']['amount'] = $sum;
		$data['sum']['currency'] = $currency;
		$data['paymentMethod']['type'] = 'Account';
		$data['paymentMethod']['accountId'] = $currency;
		$data['fields']['account'] = $card;
		
		$json = json_encode($data,true);
		
		curl_setopt($evo, CURLOPT_URL, "https://edge.qiwi.com/sinap/api/v2/terms/$card_id/payments");
		curl_setopt($evo, CURLOPT_HEADER, false);
	
		curl_setopt($evo,CURLOPT_HTTPHEADER,array('Accept: application/json',
												"POST /sinap/api/v2/terms/$card_id/payments HTTP/1.1",
												 'Content-Type: application/json',
												 'Host: edge.qiwi.com',
												 'Authorization: Bearer '.$token));;
		curl_setopt($evo, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($evo, CURLOPT_BINARYTRANSFER,1);
		curl_setopt($evo,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($evo, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($evo, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($evo, CURLOPT_POST, true);
		curl_setopt($evo, CURLOPT_POSTFIELDS,$json);
		curl_setopt($evo, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.75 Safari/537.36');
		$res = curl_exec($evo);
		$json = json_decode($res,true);
		$code = $json['transaction']['state']['code'];
		/*switch($json['message'])
		{
			case 'Недостаточно средств': $code = 100;break;
			case 'Извините, платеж на провайдера в настоящий момент не возможен': $code = 101;
		}
		*/
		
		if($code == 'Accepted'){ return true;} else { return false; }
		
	}


	function check_cc($cc, $extra_check = false){
		$cards = array(
			"visa" => "(4\d{12}(?:\d{3})?)",
			"amex" => "(3[47]\d{13})",
			"jcb" => "(35[2-8][89]\d\d\d{10})",
			"maestro" => "((?:5020|5038|6304|6579|6761)\d{12}(?:\d\d)?)",
			"solo" => "((?:6334|6767)\d{12}(?:\d\d)?\d?)",
			"mastercard" => "(5[1-5]\d{14})",
			"switch" => "(?:(?:(?:4903|4905|4911|4936|6333|6759)\d{12})|(?:(?:564182|633110)\d{10})(\d\d)?\d?)",
		);
		$names = array("Visa", "American Express", "JCB", "Maestro", "Solo", "Mastercard", "Switch");
		$matches = array();
		$pattern = "#^(?:".implode("|", $cards).")$#";
		$result = preg_match($pattern, str_replace(" ", "", $cc), $matches);
		if($extra_check && $result > 0){
			$result = (validatecard($cc))?1:0;
		}
		return ($result>0)?$names[sizeof($matches)-2]:false;
	}	
	
	
	
	function get_balance($token,$wallet)
	{
		#$token = get_setting_data('api_key',1);
		#$wallet = get_setting_data('wallet',1);
		$wallet = str_replace('+','',$wallet);

		

		$evo = curl_init(); 
		curl_setopt($evo, CURLOPT_URL, 'https://edge.qiwi.com/funding-sources/v2/persons/'.$wallet.'/accounts');
		curl_setopt($evo, CURLOPT_HEADER, false);

		curl_setopt($evo,CURLOPT_HTTPHEADER,array('Accept: application/json',
												"GET /funding-sources/v2/persons/$wallet/accounts HTTP/1.1",
												 'Content-Type: application/json',
												 'Host: edge.qiwi.com',
												 'Authorization: Bearer '.$token));
		curl_setopt($evo, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($evo, CURLOPT_BINARYTRANSFER,1);
		curl_setopt($evo,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($evo, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($evo, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($evo, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 ');
		$res = curl_exec($evo);
		if(empty($res)){ return 0; }
		$json = json_decode($res,true); 
		$balance = $json['accounts'][0]['balance']['amount'];
		$balance = round($balance);
		 return $balance;
	}	
	
	
	function get_multiple_qiwi_data($id,$col)
	{
		global $db;
		$r = mysqli_query($db,"SELECT `$col` FROM `multiple_qiwi` WHERE `id` = '$id'");
		$ok = mysqli_fetch_assoc($r);
		return $ok[$col];
	}
	
	function update_limit_wallet($wallet,$amount)
	{
		global $db;
		$r = mysqli_query($db,"SELECT `balance`,`limit` FROM `multiple_qiwi` WHERE `wallet` = '$wallet'");
		$ok = mysqli_fetch_assoc($r);
		$balance = $ok['balance']+$amount;
		if($balance >= $ok['limit']){ $status = 2; } else { $status = 1; }
		mysqli_query($db,"UPDATE `multiple_qiwi` SET `balance` = '$balance', `status` = '$status' WHERE `wallet` = '$wallet'");
	}	

	function histoty_qiwi($sum_x,$comment_x,$id_wallet_qiwi = 0)
	{
		if($id_wallet_qiwi == 0)
		{
			$wallet = get_setting_data('wallet',1);
			$token = get_setting_data('api_key',1);
		}else
		{
			$wallet = get_multiple_qiwi_data($id_wallet_qiwi,'wallet');
			$token = get_multiple_qiwi_data($id_wallet_qiwi,'api_key');
		}
		
		
		$d = strtotime('+1 days');
		$date = date('Y-m-d',$d); 
		$date1 .= $date."T".date('h:i:s').'-00:00';
	
		$d = strtotime('-1 days');
		$date = date('Y-m-d',$d);
		$date2 = $date."T".date('h:i:s').'-00:00';

		$param = array('rows'=>'10','sources'=>'QW_RUB','startDate'=>$date2,'endDate'=>$date1,'operation'=>'ALL');
		$evo = curl_init(); 
		curl_setopt($evo, CURLOPT_URL, 'https://edge.qiwi.com/payment-history/v1/persons/'.$wallet.'/payments/?'.http_build_query($param));
		curl_setopt($evo, CURLOPT_HEADER, false);

		curl_setopt($evo,CURLOPT_HTTPHEADER,array('Accept: application/json',
												"GET /funding-sources/v1/accounts/current HTTP/1.1",
												 'Content-Type: application/json',
												 'Host: edge.qiwi.com',
												 'Authorization: Bearer '.$token));
		curl_setopt($evo, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($evo, CURLOPT_BINARYTRANSFER,1);
		curl_setopt($evo,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($evo, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($evo, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($evo, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.75 Safari/537.36');
		$res = curl_exec($evo);
		$json = json_decode($res,true); 

		$comment_x = intval($comment_x);
		foreach($json['data'] as $item)
		{	
			$comment = $item['comment'];
			$sum = $item['sum']['amount'];
			if(($sum_x == $sum) && ($comment == $comment_x)){  $st = true;break;  } else { $st = false; }
			
			
		}
			return $st;
	}
			
	

	
	function send_crypto($to_addresses,$amount)
	{
		$secret_key = get_setting_data('secret_key',6);
		$api_key = get_setting_data('api_key',6);
		$from_addresses = get_setting_data('from_addresses',6);
		
		$content = array(
				'api_key' => $api_key,
				'amounts'=>$amount,
				'from_addresses' => $from_addresses,
				'to_addresses' => $to_addresses,
				'pin'=>$secret_key,
			);

		$data = file_get_contents('https://block.io/api/v2/withdraw_from_addresses/?'.http_build_query($content));

		$json = json_decode($data,true);
		if($json['status'] == 'success'){ return true; } else { return false; }
	}
		
		
	function convert_sum($sum,$currency = 'RUB')
	  {  
		  $s = file_get_contents("https://blockchain.info/tobtc?currency=$currency&value=$sum");
		  return trim($s);
	  }			

	function get_balance_bitcoin($addresses,$api_key)
	{
		$content = array(
				'api_key' => $api_key,
				'addresses' => $addresses,
			);

		$data = file_get_contents('https://block.io/api/v2/get_address_balance/?'.http_build_query($content));
		$json = json_decode($data,true);
		return $json['data']['balances'][0]['available_balance'];
	}		
		
	
		
		
	function add_action_log($type,$content,$info)
	{
		global $db;
		$s = "INSERT INTO `losg_action` (`type`,`content`,`info`) VALUES ('$type','$content','$info')";
		mysqli_query($db,$s);
		
	}
	
	
	
	
	function inc_sub($id,$table)
	{
		global $db;
		$s = "SELECT `this_sub` FROM `$table` WHERE `id` = '$id'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		$x = $ok['this_sub'];
		$x++;
		mysqli_query($db,"UPDATE `$table` SET `this_sub` = '$x' WHERE `id` = '$id'");
	}	
		
	function unicode_html($str)
	{
		$entity = preg_replace_callback('/[\x{80}-\x{10FFFF}]/u', function ($m) {
		$char = current($m);
		$utf = iconv('UTF-8', 'UCS-4', $char);
		return sprintf("&#x%s;", ltrim(strtoupper(bin2hex($utf)), "0"));
		}, $str);
		return $entity;
	}
	
	function unicode_char($str)
	{
		if(strstr($str,'&#x'))
		{
			$ok = mb_convert_encoding($str, 'UTF-8', 'HTML-ENTITIES');
			return $ok;
		} else { return $str; }
	}
	
	function get_changes_data($col,$id)
	{
		global $db;
		$r = mysqli_query($db,"SELECT `$col` FROM `exchange` WHERE `id` = '$id'");
		$ok = mysqli_fetch_assoc($r);
		return $ok[$col];
	}	



	function get_payment_setting($type,$col)
	{
		global $db;
		$r = mysqli_query($db,"SELECT `$col` FROM `setting_payment` WHERE `name` = '$type'");
		$ok = mysqli_fetch_assoc($r);
		return $ok[$col];
	}

	
	function set_current($col,$value,$id_chat)
	{
		global $db;
		$s = "UPDATE `Users` SET `$col` = '$value' WHERE `id_chat` = '$id_chat'";
		mysqli_query($db,$s);
	}
	
	function get_number($st)
	{
		$num[0] = '0️⃣';
		$num[1] = '1️⃣';
		$num[2] = '2️⃣';
		$num[3] = '3️⃣';
		$num[4] = '4️⃣';
		$num[5] = '5️⃣';
		$num[6] = '6️⃣';
		$num[7] = '7️⃣';
		$num[8] = '8️⃣';
		$num[9] = '9️⃣';
		$num[10] = '🔟';
		
		for($q=0;$q<strlen($st);$q++)
		{
			$value = substr($st,$q,1);
			$numer .= $num[$value];
		}
		
		return $numer;
	}
	

	function get_section($id,$table = 'cat_struktura')
	  {
		global $db;
		$s = "SELECT `id2` FROM `$table` WHERE `id1` = '$id'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		$id2 = $ok['id2'];

		$s = "SELECT `name`,`pid` FROM `struktura` WHERE `id` = '$id2'";
		$r = mysqli_query($db,$s);
		$ok1 = mysqli_fetch_assoc($r);
		$pid = $ok1['pid'];
		$name = $ok1['name'];

		$s = "SELECT `name` FROM `struktura` WHERE `id` = '$pid'";
		$r = mysqli_query($db,$s);
		$ok2 = mysqli_fetch_assoc($r);
		$pid1 = $ok2['pid'];
		
		$s = "SELECT `name` FROM `struktura` WHERE `id` = '$pid1'";
		$r1 = mysqli_query($db,$s);
		$ok3 = mysqli_fetch_assoc($r1);
		$pid = $ok3['pid'];
		if(!empty($ok3['name'])){ $name .= '/'.$ok3['name']; }

		  return $ok2['name'].'/'.$name;
		  
	  }
	
	function get_setting_data($col,$id)
	{
		global $db;
		$r = mysqli_query($db,"SELECT `$col` FROM `setting_payment` WHERE `id` = '$id'");
		$ok = mysqli_fetch_assoc($r);
		return $ok[$col];
	}

	function get_item_param($id,$col)
	{
		global $db;
		$s = "SELECT `$col` FROM `catalog` WHERE `id` = '$id'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok[$col];
		
	}	
	
	
	
	function get_count_item_zak($item,$id_zakaz)
	{
		global $db;
		$s = "SELECT `count` FROM `a_zakaz_items` WHERE `id_zakaz` = '$id_zakaz' AND `id_item` = '$item'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok['count'];
	}	
	
	function get_content($id)
	{
		global $db;
		$s = "SELECT `content` FROM `struktura` WHERE `id` = '$id'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return unicode_char($ok['content']);
	}	
	
	
	function get_char_unicode($cmd_id)
	{
		global $db;
		$s = "SELECT `id_unicode` FROM `unicode_struktura` WHERE `cmd_id` = '$cmd_id'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		$id_ = $ok['id_unicode'];
		
		$s = "SELECT `u` FROM `unicode` WHERE `id` = '$id_'";
		$r = mysqli_query($db,$s);
		$ok1 = mysqli_fetch_assoc($r);
		$i = base64_decode($ok1['u']);
		return $i;
	}
	

	  function get_proc_sum($val,$proc)
	  {
		$result = $val/100*$proc;
		$result = round($result);
		$result = round($result);
		return $result;
	  }	
	
	  
	  function get_proc($sum,$proc,$discount)
	  {
		if($discount > 0) 
		{
			$proc_ = get_proc_sum($proc,$discount);
			$proc = $proc - $proc_;
			#$proc = round($proc);
		}
		$result = $sum/100*$proc;
		$result = $result + $sum;
		$result = round($result);
		return $result;
		  
	  }
	  
	  
	function show_data($id_chat,$info,$id_bot = 0)
	{
		global $db;
		$id_bot = intval($id_bot);
		$type_payment = get_setting('type_payment');
		$basket_id = get_setting('basket_id');
		if($id_bot < 1)
		{
			$token = get_setting('bot_token');
			$url = "https://api.telegram.org/bot".$token;
		}else
		{
			$rx = mysqli_query($db,"SELECT `token` FROM `bot_list` WHERE `id_bot` = '".$id_bot."'");
			$data = mysqli_fetch_assoc($rx);
			$token = $data['token'];
			$url = "https://api.telegram.org/bot".$token;
		}
		
		$s = "SELECT `id`,`name` FROM `struktura` WHERE `pid` = '2' AND `active` = '0' ORDER BY `sid` ASC";
		$r = mysqli_query($db,$s);
		
		$item_x = 0;
		for($q=1;$q<10;$q++)
		{
			$s = "SELECT `id`,`name` FROM `struktura` WHERE `pid` = '2' AND `pos` = '$q' AND `active` = '0' ORDER BY `sid` ASC";
			$r = mysqli_query($db,$s);
			$num = mysqli_num_rows($r);
			if($num == 0){ continue; }
		
			$level = 0;
			$id_city = get_current('id_city',$id_chat);
			while($ok = mysqli_fetch_assoc($r))
			{
				if(($type_payment == 1) && ($ok['id'] == $basket_id)){ continue; }
				$ico = get_char_unicode($ok['id']);
				if(($id_city > 0) && ($ok['id'] == '692'))
				{
					$rx = mysqli_query($db,"SELECT `name` FROM `struktura` WHERE `id` = '$id_city'");
					$data_x = mysqli_fetch_assoc($rx);
					$button[$item_x][$level] = $ico.$ok['name'].'('.$data_x['name'].')';
				}else
					{
						if($ok['id'] == 692){ continue; }
						$button[$item_x][$level] =  $ico.' '.$ok['name'];
					}
				$level++;
			}
			$item_x++;
		}

			$param = array('keyboard' => $button, 'resize_keyboard' => true, 'one_time_keyboard' => false);

			$encodedMarkup = json_encode($param);
			$content = array(
				'chat_id' => $id_chat,
				'reply_markup' => $encodedMarkup,
				'text' => $info,
				'parse_mode'=>'HTML',
				'resize_keyboard'=>true,
				'one_time_keyboard' => false,
			);
			file_get_contents($url."/sendmessage?".http_build_query($content));		
	}
	  	
	function get_currency_val($id)
	{
		global $db;
		$s = "SELECT `name` FROM `currency_list` WHERE `id` = '$id' AND `chk` = '1'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok['name'];
	}	
	

	function convect_currency_($sum,$currency,$to_crypto = 0)
	{
	
		$options = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Accept-language: en\r\n" .
					"User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad
			)
		);	
		$context = stream_context_create($options);
		$json = file_get_contents('https://api.cryptonator.com/api/ticker/'.$curreny, false, $context );
		$json = json_decode($json,true);
	
		$x = $json['ticker']['price'];
		return $x*$sum;
	/*
		global $db;
		$currency = strtolower($currency);
		$r = mysqli_query($db,"SELECT `value` FROM `rate_currency` WHERE `name` = '$currency'");
		$ok = mysqli_fetch_assoc($r);
		$x = $ok['value'];
	
		$value = $x*$sum;		
		if((strlen($value) == 18) && (strpos($value,'.'))){ $value = substr($value,0,strlen($value)-8); }
			elseif(strlen($value) == 17){ $value = substr($value,0,strlen($value)-7); }
			elseif(strlen($value) == 16){ $value = substr($value,0,strlen($value)-6); }
		
		#write($currency." | x:$x | value: $value");
		if(strpos($value,'-')){ $value = $sum; }
		if($to_crypto == 0){ return round($value); } else { return $value; }
		*/
	}
	
	 
	 function ref_gain($id_chat,$zakaz_id)
	 {
		 global $db;
	     $token = get_setting('bot_token');
		 $url = "https://api.telegram.org/bot".$token;
		 $referer = get_current('referer',$id_chat);
		 if($referer > 0)
		 {
			$rx = mysqli_query($db,"SELECT `suma` FROM `a_zakaz` WHERE `id` = '$zakaz_id'");
			$dx = mysqli_fetch_assoc($rx);
			$sum = $dx['suma'];
			 
			$gain_proc = get_setting('gain_proc');
			$amount = get_proc_sum($sum,$gain_proc);
			$date = time();
			if($amount < 1){ return false; }
			mysqli_query($db,"INSERT INTO `orders_referers` (`zakaz_id`,`id_chat_client`,`referer`,`sum`,`date`) 
												VALUES ('$zakaz_id','$id_chat','$referer','$amount','$date')");
								
			$content = array(
				'chat_id' => $referer,
				'text' => "➕ Вам начислено <b>$amount RUB</b> это <b>$gain_proc%</b> от покупки, приглашенного вами покупателя.\n",
				'parse_mode'=>'HTML',
				'disable_notification'=>false,
			);
			file_get_contents($url."/sendmessage?".http_build_query($content));									
		 }
	 }
	  	
	function reward($item_id,$count)
	{
		global $db;
		$id = get_item_param($item_id,'amount_product');
		if($id == 0){ return false; }
		$user_id_ = get_item_param($item_id,'user_id');
		$r = mysqli_query($db,"SELECT `amount` FROM `product_value_units` WHERE `id` = '$id'");
		$data_t = mysqli_fetch_assoc($r);
		$amount =  $data_t['amount'] * $count;
		$date = date('m.d.Y');
		$time = time();
		mysqli_query($db,"INSERT INTO `sales_total` (`user_id`,`amount`,`date`,`count`,`item_id`,`time`) 
									VALUES ('$user_id_','$amount','$date','$count','$item_id','$time')");
	}
	

	function api_query($api_name, array $req = array())
	{
		$mt = explode(' ', microtime());
		$NONCE = $mt[1] . substr($mt[0], 2, 6);

		// API settings
		$key = get_setting_data('api_key',5);
		$secret = get_setting_data('secret_key',5);
		$url = "http://api.exmo.com/v1/$api_name";

		$req['nonce'] = $NONCE;

		$post_data = http_build_query($req, '', '&');

		$sign = hash_hmac('sha512', $post_data, $secret);

		$headers = array(
			'Sign: ' . $sign,
			'Key: ' . $key,
		);

		static $ch = null;
		if (is_null($ch)) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; PHP client; ' . php_uname('s') . '; PHP/' . phpversion() . ')');
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		// run the query
		$res = curl_exec($ch);
		if ($res === false) throw new Exception('Could not get reply: ' . curl_error($ch));
	   
		$dec = json_decode($res, true);
		if ($dec === null)
			throw new Exception('Invalid data received, please make sure connection is working and requested API exists');

		return $dec;
	}
	
	
	function is_permission($alias)
	{
		global $db;
		$r = mysqli_query($db,"SELECT `id` FROM `a_menu` WHERE `operator` = '1' AND `alias` = '$alias'");
		$num = mysqli_num_rows($r);
		if($num > 0){ return true; } else { return false; }
	}
	

	function get_count($table,$col = '',$val = 0,$format = 0)
	{
		global $db;
		if($col == ''){ $s = "SELECT `id` FROM `$table`"; } 
				else { $s = "SELECT `id` FROM `$table` WHERE `$col` = '$val'"; } 
		$r = mysqli_query($db,$s);
		$num = mysqli_num_rows($r);
		if($format == 0){ $num = number_format($num, 2,', ',', '); }
		return $num;
	}	
	
	function get_channel_id($id)
	{
		global $db;
		$r = mysqli_query($db,"SELECT `channel_id` FROM `list_channels_vip` WHERE `id` = '$id'");
		$data = mysqli_fetch_assoc($r);
		return $data['channel_id'];
	}
	
	function get_active_hookid($token)
	{
		$evo = curl_init(); 
		curl_setopt($evo, CURLOPT_URL, 'https://edge.qiwi.com/payment-notifier/v1/hooks/active');
		curl_setopt($evo, CURLOPT_HEADER, false);

		curl_setopt($evo,CURLOPT_HTTPHEADER,array('accept: */*',
												 'Host: edge.qiwi.com',
												 'Authorization: Bearer '.$token));
		curl_setopt($evo, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($evo, CURLOPT_PUT, true);
		curl_setopt($evo, CURLOPT_CUSTOMREQUEST, 'GET');
		
		$res = curl_exec($evo);
		$json = json_decode($res,true);
		return $json['hookId'];
	}
	
	function reg_webhook($url)
	{	
		global $db;
		$url = urlencode(utf8_encode($url));
		$token = get_setting_data('api_key',1);
		$hookId = get_active_hookid($token);
		
		if(!empty($hookId))
		{
			$data['hookType'] = 1;
			$data['param'] = $url;
			$data['txnType'] = 0;

			$evo = curl_init(); 
			curl_setopt($evo, CURLOPT_URL, 'https://edge.qiwi.com/payment-notifier/v1/hooks/'.$hookId);
			curl_setopt($evo, CURLOPT_HEADER, false);

			curl_setopt($evo,CURLOPT_HTTPHEADER,array('accept: */*',
													 'Host: edge.qiwi.com',
													 'Authorization: Bearer '.$token));
			curl_setopt($evo, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($evo, CURLOPT_PUT, true);
			curl_setopt($evo, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($evo, CURLOPT_POSTFIELDS,$data);
			$res = curl_exec($evo);
		}
		
		$data['hookType'] = 1;
		$data['param'] = $url;
		$data['txnType'] = 0;

		$evo = curl_init(); 
		curl_setopt($evo, CURLOPT_URL, 'https://edge.qiwi.com/payment-notifier/v1/hooks?hookType=1&param='.$url.'&txnType=0');
		curl_setopt($evo, CURLOPT_HEADER, false);

		curl_setopt($evo,CURLOPT_HTTPHEADER,array('accept: */*',
												 'Host: edge.qiwi.com',
												 'Authorization: Bearer '.$token));
		curl_setopt($evo, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($evo, CURLOPT_PUT, true);
		curl_setopt($evo, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($evo, CURLOPT_POSTFIELDS,$data);
		
		$res = curl_exec($evo);
		$json = json_decode($res,true);
		$hookId = $json['hookId'];
	
		if(!empty($hookId))
		{ 
			mysqli_query($db,"UPDATE `setting_payment` SET `hookId` = '$hookId' WHERE `id` = '1'");
			return true;
		} else { return false; }
	}	
	
	function add_webhook($token,$url)
	{
		$url = urlencode(utf8_encode($url));
		
		$data['hookType'] = 1;
		$data['param'] = $url;
		$data['txnType'] = 0;
		
		$evo = curl_init(); 
		curl_setopt($evo, CURLOPT_URL, 'https://edge.qiwi.com/payment-notifier/v1/hooks?hookType=1&param='.$url.'&txnType=0');
		curl_setopt($evo, CURLOPT_HEADER, false);

		curl_setopt($evo,CURLOPT_HTTPHEADER,array('accept: */*',
												 'Host: edge.qiwi.com',
												 'Authorization: Bearer '.$token));
		curl_setopt($evo, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($evo, CURLOPT_PUT, true);
		curl_setopt($evo, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($evo, CURLOPT_POSTFIELDS,$data);
		
		$res = curl_exec($evo);
		$json = json_decode($res,true);
		return $json['hookId'];
	}
	
	function del_webhook($id)
	{
		global $db;
		$rx = mysqli_query($db,"SELECT `hookId`,`api_key` FROM `multiple_qiwi` WHERE `id` = '$id'");
		$data_x = mysqli_fetch_assoc($rx);
		$token = $data_x['api_key'];
		$hookId = get_active_hookid($token);
		
		$evo = curl_init(); 
		curl_setopt($evo, CURLOPT_URL, 'https://edge.qiwi.com/payment-notifier/v1/hooks/'.$hookId);
		curl_setopt($evo, CURLOPT_HEADER, false);

		curl_setopt($evo,CURLOPT_HTTPHEADER,array('accept: */*',
												 'Host: edge.qiwi.com',
												 'Authorization: Bearer '.$token));
		curl_setopt($evo, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($evo, CURLOPT_PUT, true);
		curl_setopt($evo, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($evo, CURLOPT_POSTFIELDS,$data);
		$res = curl_exec($evo);
	}	
				
	
?>