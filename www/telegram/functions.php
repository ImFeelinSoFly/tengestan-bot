<?php
	include('db.php');
	
	function is_cmd($cmd)
	{
		global $db;
		$s = "SELECT `id`,`name` FROM `struktura` WHERE `pid` = '2'";
		$r = mysqli_query($db,$s);
		$num = mysqli_num_rows($r);
		if($num > 0){ return true; } else { return false; }
	}
	
	function get_setting($col)
	{
		global $db;
		$s = "SELECT `$col` FROM `setting` WHERE `id` = '1'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok[$col];
	}
	
	
	function get_pid($cmd)
	{
		global $db;
		$s = "SELECT `id` FROM `struktura` WHERE `name` = '$cmd'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok['id'];
	}
	
	function get_pid_id($cmd_id)
	{
		global $db;
		$s = "SELECT `pid` FROM `struktura` WHERE `id` = '$cmd_id'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok['pid'];
	}
	
	function get_pid_cmd($cmd_id)
	{
		global $db;
		$s = "SELECT `pid` FROM `struktura` WHERE `id` = '$cmd_id'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok['pid'];
	}
	
		
	function write($s)
	{
		#return false; # dis
		$f = fopen($p.'/var/www/telegram/log.txt','a+');
		fwrite($f,$s."\n");
		fclose($f);
	}
	
	function filt($str)
	{
		$str = strip_tags($str);
		$str = htmlspecialchars($str);
		$str = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a",'`'), 
							array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z',''), $str);
		return $str;
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
	
	
	function get_content($id)
	{
		global $db;
		$s = "SELECT `content` FROM `struktura` WHERE `id` = '$id'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return unicode_char($ok['content']);
	}
	
	
	function get_setting_data($col,$id)
	{
		global $db;
		$r = mysqli_query($db,"SELECT `$col` FROM `setting_payment` WHERE `id` = '$id'");
		$ok = mysqli_fetch_assoc($r);
		return $ok[$col];
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
	
	function get_referer_id($ref)
	{
		global $db;
		$s = "SELECT `referer` FROM `Users` WHERE `id_chat` = '$ref'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok['referer'];
		
		
	}
	
	
	function new_user($user,$username,$id_chat,$referer = '',$id_bot = 0)
	{
		global $db;
		global $url;
		$user = filt($user);
	
		$discount = 0;
		if($id_bot > 0){ $s_param1 = "AND `id_bot` = '$id_bot'"; } else { $s_param1 = ''; }

		$r = mysqli_query($db,"SELECT `id`,`count_msg` FROM `Users` WHERE `id_chat` = '$id_chat' $s_param1");
		$num = mysqli_num_rows($r);
		
		if($num > 0)
		{
			$ok = mysqli_fetch_assoc($r);
			$msg = intval($ok['count_msg']);
			$msg++;
			mysqli_query($db,"UPDATE `Users` SET `count_msg` = '$msg' WHERE `id_chat` = '$id_chat' $s_param1");
			
		}
		
		if($num == 0)
		{
			$date = time();
			$rec = create_pay_btc();
			list($btc_address,$payment_code) = explode('|',$rec);
			
			$s = "INSERT INTO `Users` (`user`,`username`,`id_chat`,`date`,`referer`,`lock`,`discount`,`btc_address`,`secret_pay_code`,`id_bot`) 
							VALUES ('$user','$username','$id_chat','$date','$referer','0','$discount','$btc_address','$payment_code','$id_bot')";
			if($id_chat > 0){ mysqli_query($db,$s); }
			
			$free_money = 50;
			if($free_money > 0)
			{
				set_current('balance',$free_money,$id_chat);
				
				$content = array(
					'chat_id' => $id_chat,
					'text' => "🔔 Вам добавлено <b>$free_money RUB</b> тестовых.\n",
					'parse_mode'=>'HTML',
					'disable_notification'=>false,
				);
				file_get_contents($url."/sendmessage?".http_build_query($content));
			}
	/*
			$catalog_id = get_setting('catalog_id');
			$r = mysqli_query($db,"SELECT `id`,`name` FROM `struktura` WHERE `active` = '0' AND `pid` = '$catalog_id'");
			$q = 0;
			while($ok = mysqli_fetch_assoc($r))
			{
				$buttons_inline['inline_keyboard'][$q][0]['text'] = $ok['name'];
				$buttons_inline['inline_keyboard'][$q][0]['callback_data'] = '/my_city:'.$ok['id'];
				$q++;
			}
			
			$info = "<b>Выберите город:</b>";
			$markup = json_encode($buttons_inline,true);
			$content = array(
				'chat_id' => $id_chat,
				'reply_markup' => $markup,
				'text' => $info,
				'parse_mode'=>'HTML',
			);
			$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
			$json = json_decode($data,true);
			$message_id  = $json['result']['message_id'];
			if($message_id > 0){ set_current('message_id',$message_id,$id_chat); }	
	*/
			
			$type_referer = get_setting('type_referer');
			$type_referer = intval($type_referer);
			
			$r = mysqli_query($db,"SELECT `id` FROM `list_referer` WHERE `id_chat` = '$id_chat' AND `referer` = '$referer'");
			$num = mysqli_num_rows($r);
			if(($num == 0) && ($id_chat > 0) && ($referer > 0))
			{
				mysqli_query($db,"INSERT INTO `list_referer` (`id_chat`,`referer`) VALUES ('$id_chat','$referer')");
			
				if($type_referer == 1)
				{
					$r1 = mysqli_query($db,"SELECT `id` FROM `list_referer` WHERE `referer` = '$referer'");
					$num = mysqli_num_rows($r1);
		
					if($num == 5)
					{
						$ref_friend_5 = get_setting('ref_friend_5');
						$discount = get_current('discount',$referer);
						$discount = $discount + $ref_friend_5;
						 set_current('discount',$discount,$referer);
						
						$content = array(
							'chat_id' => $referer,
							'text' => "🔔 Вам добавлена скидка на покупку в размене <b>$ref_friend_5 %</b> за 5 приглашенных друзей\n",
							'parse_mode'=>'HTML',
							'disable_notification'=>false,
						);
						file_get_contents($url."/sendmessage?".http_build_query($content));
					}
					
					if($num == 15)
					{
						$ref_friend_15 = get_setting('ref_friend_15');
						$discount = get_current('discount',$referer);
						$discount = $discount + $ref_friend_15;
						 set_current('discount',$discount,$referer);
						
						$content = array(
							'chat_id' => $referer,
							'text' => "🔔 Вам добавлена скидка на покупку в размене <b>$ref_friend_5 %</b> за 15 приглашенных друзей\n",
							'parse_mode'=>'HTML',
							'disable_notification'=>false,
						);
						file_get_contents($url."/sendmessage?".http_build_query($content));
					}
					
					if($num == 30)
					{
						$ref_friend_30 = get_setting('ref_friend_30');
						$discount = get_current('discount',$referer);
						$discount = $discount + $ref_friend_30;
						set_current('discount',$discount,$referer);
						
						$content = array(
							'chat_id' => $referer,
							'text' => "🔔 Вам добавлена скидка на покупку в размене <b>$ref_friend_30 %</b> за 5 приглашенных друзей\n",
							'parse_mode'=>'HTML',
							'disable_notification'=>false,
						);
						file_get_contents($url."/sendmessage?".http_build_query($content));
					}
					
					if($num == 50)
					{
						$ref_friend_50 = get_setting('ref_friend_50');
						$discount = get_current('discount',$referer);
						$discount = $discount + $ref_friend_50;
						set_current('discount',$discount,$referer);
						
						$content = array(
							'chat_id' => $referer,
							'text' => "🔔 Вам добавлена скидка на покупку в размене <b>$ref_friend_50 %</b> за 5 приглашенных друзей\n",
							'parse_mode'=>'HTML',
							'disable_notification'=>false,
						);
						file_get_contents($url."/sendmessage?".http_build_query($content));
					}
					
					if($num == 100)
					{
						$ref_friend_100 = get_setting('ref_friend_100');
						$discount = get_current('discount',$referer);
						$discount = $discount + $ref_friend_100;
						set_current('discount',$discount,$referer);
						
						$content = array(
							'chat_id' => $referer,
							'text' => "🔔 Вам добавлена скидка на покупку в размене <b>$ref_friend_100 %</b> за 5 приглашенных друзей\n",
							'parse_mode'=>'HTML',
							'disable_notification'=>false,
						);
						file_get_contents($url."/sendmessage?".http_build_query($content));
					}
			
					$ref = get_referer_id($referer);
					if($ref > 0)
					{ 
						$ref_bonus = get_setting('ref_at_friend');
						$discount = get_current('discount',$referer);
						$discount = $discount + $ref_bonus;
						set_current('discount',$discount,$referer);
							
						$content = array(
							'chat_id' => $ref,
							'text' => "➕ Вам начислена скидка! в размере <b>$ref_bonus %</b>.\nЗа приглашенного <b>вашим другом</b> пользователя <code>$user</code>.\n",
							'parse_mode'=>'HTML',
							'disable_notification'=>false,
						);
						file_get_contents($url."/sendmessage?".http_build_query($content));
					}
					
				}elseif($type_referer == 2)
				{
					$gain_proc = get_setting('gain_proc');
					$content = array(
						'chat_id' => $referer,
						'text' => "👌 <b>Вы привели нового реферала.</b>\n\nПри любой покупки вашего реферала, Вам будет начислено <b>$gain_proc%</b> от суммы заказа.",
						'parse_mode'=>'HTML',
						'disable_notification'=>false,
					);
					file_get_contents($url."/sendmessage?".http_build_query($content));
					
				}
			}
			return true;
		}else { return false; } 
		
	}

	
	function set_current($col,$value,$id_chat)
	{
		global $db;
		$s = "UPDATE `Users` SET `$col` = '$value' WHERE `id_chat` = '$id_chat' ";
		mysqli_query($db,$s);
	}

	function get_current($col,$id_chat)
	{
		global $db;
		$s = "SELECT `$col` FROM `Users` WHERE `id_chat` = '$id_chat' "; # AND `id_bot` = '0'
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok[$col];
	}

	function get_event($cmd_id)
	{
		global $db;
		$s = "SELECT `event_id` FROM `events` WHERE `cmd_id` = '$cmd_id'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok['event_id'];
	}
	
	function get_user_opt($id_chat,$col)
	{
		global $db;
		$s = "SELECT `$col` FROM `Users` WHERE `id_chat` = '$id_chat'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok[$col];
	}

	
	function set_balance($sum,$type,$id_chat)
	{
		global $db;
		$s = "SELECT `balance` FROM `Users` WHERE `id_chat` = '$id_chat'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		$balance = intval($ok['balance']);
		if($type == '-'){ $sum = $balance - $sum; } elseif($type == '+') { $sum = $balance + $sum; }
		
		$s = "UPDATE `Users` SET `balance` = '$sum' WHERE `id_chat` = '$id_chat'";
		mysqli_query($db,$s);
	}	

	

	function send_money($sum,$comment,$wallet)
	{
		$token = get_setting_data('api_key',7);
		$id = time()*1000;
		$id = strval($id);
		$data['id'] = $id;
		$data['sum']['amount'] = $sum;
		$data['sum']['currency'] = '643';
		$data['paymentMethod']['type'] = 'Account';
		$data['paymentMethod']['accountId'] = '643';
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
		$token = get_setting_data('api_key',2);
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
		
		$json = json_encode($data,true);
		

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
			
		
		
			
			
	
	function card_money_send($card,$sum,$card_id)
	{
		$token = get_setting_data('api_key',2);
		$evo = curl_init(); 
	
		$id = time()*1000;
		$id = strval($id);
		$data['id'] = $id;
		$data['sum']['amount'] = $sum;
		$data['sum']['currency'] = '643';
		$data['paymentMethod']['type'] = 'Account';
		$data['paymentMethod']['accountId'] = '643';
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
	
	function convertCurrency($amount, $from, $to){
	  $conv_id = "{$from}_{$to}";
	  $string = file_get_contents("http://free.currencyconverterapi.com/api/v3/convert?q=$conv_id&compact=ultra");
	  $json_a = json_decode($string, true);

	  $x = $amount * round($json_a[$conv_id], 4);
	  return round($x);
	}
	
	

	function convect_currency_($sum,$currency,$to_currency)
	{
	
		$options = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Accept-language: en\r\n" .
					"User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad
			)
		);	
		$context = stream_context_create($options);
		$json = file_get_contents("https://cash.rbc.ru/cash/json/converter_currency_rate/?currency_from=$currency&currency_to=$to_currency&source=cbrf&sum=$sum&date=", false, $context );

		$json = json_decode($json,true);
	
		$x = $json['data']['sum_result'];
		return round($x);
	}
		
	
	function get_changes_data($col,$id)
	{
		global $db;
		$r = mysqli_query($db,"SELECT `$col` FROM `exchange` WHERE `id` = '$id'");
		$ok = mysqli_fetch_assoc($r);
		return $ok[$col];
	}
	
	
	function get_exchange_list($col,$id)
	{
		global $db;
		$r = mysqli_query($db,"SELECT `$col` FROM `list_changes` WHERE `id` = '$id'");
		$ok = mysqli_fetch_assoc($r);
		return $ok[$col];
	}	
	
	function cancel_change($task_id,$id_chat)
	{
		global $db;
		mysqli_query($db,"UPDATE `list_changes` SET `status` = '4' WHERE `id` = '$task_id'");
		set_current('z_step',0,$id_chat);
		set_current('payment_send',0,$id_chat);
		set_current('payment_get',0,$id_chat);
		set_current('task_id',0,$id_chat);
	}
	
	
	function convert_sum($sum,$currency = 'RUB')
	  {  
		  $s = file_get_contents("https://blockchain.info/tobtc?currency=$currency&value=$sum");
		  return trim($s);
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
		}
		$result = $sum/100*$proc;
		$result = $result + $sum;
		$result = round($result);
		return $result;
		  
	  }
	  


	  
	  function check_btc_address($address)
	  {
		  $x = file_get_contents("http://codacoin.com/api/public.php?request=validate&address=$address");
		  $x = trim($x);
		  if($x == 'Valid'){ return true; } else { return false; }
	  }
	  
	  
	  function get_type_exchange($id1,$id2)
	  {
		  global $db;
		  $rx = mysqli_query($db,"SELECT `auto` FROM `exchange_struktura` WHERE `id1` = '$id2' AND `id2` = '$id1'");
		  $data_ = mysqli_fetch_assoc($rx);
		  return $data_['auto'];
	  }
	
	function get_number($st)
	{
		$st = intval($st);
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
		

	
	function is_base64($str){
		if ( base64_encode(base64_decode($str, true)) === $str){
		   return true;
		} else {
		   return false;
		}
	}
	
	function get_item_param($id,$col)
	{
		global $db;
		$s = "SELECT `$col` FROM `catalog` WHERE `id` = '$id'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok[$col];
		
	}
	
	function get_currency_val($id)
	{
		global $db;
		$s = "SELECT `name` FROM `currency_list` WHERE `id` = '$id' AND `chk` = '1'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok['name'];
	}
 
	
	function get_props($item,$id_chat = 0)
	{
		global $db;
		$s = "SELECT `id`,`name`,`alias` FROM `prop_list_catalog` WHERE  `private` = '0' AND `active` = '0'  ORDER BY `sid` ASC";
		$r = mysqli_query($db,$s);
	
		while($prop = mysqli_fetch_assoc($r))
		{
			 $type_access = get_item_param($item,'type_access');
			 if(($type_access == 2) && ($prop['alias'] == 'count')){ continue; }
			
			  if(($prop['id'] == 10) or ($prop['id'] == 19)){ continue; }
			  $this_prop = get_item_param($item,$prop['alias']);
			  $this_prop = str_replace('_',' ',$this_prop);
			  
			  if($prop['alias'] =='price')
			  { 
				$currency = get_currency_val(1); 
				$discount = get_current('discount',$id_chat);
				if($discount > 0)
				{
					$discount = get_proc_sum($this_prop,$discount);
					if($discount > 0)
					{ 
						$info_discount = "(<del>$this_prop</del> $currency)";
						$this_prop = $this_prop - $discount; 
					}else { $info_discount = ''; }
					
				}
				
				$this_prop = number_format($this_prop, 0,'.','.'); 
				$this_prop .= ' '.$currency;
			  }else { $info_discount = ''; }
			
			  
			  if(strlen($this_prop) > 0){ $info .= '<b>'.$prop['name'].":</b> $this_prop $info_discount"."\n"; }
			 
			
		}
		return $info;
	}	
	
 	function get_photo($id_cat,$table = 'cat_photo')
	{
		global $db;
		$s = "SELECT `file` FROM `$table` WHERE `id_cat` = '$id_cat'";
		$r = mysqli_query($db,$s);
		$ok = mysqli_fetch_assoc($r);
		return $ok['file'];
	} 
	
	function get_wallet_qiwi($amount)
	{
		global $db;
		$r = mysqli_query($db,"SELECT `id`,`balance`,`limit` FROM `multiple_qiwi` WHERE `status` = '1'");
		$num = mysqli_num_rows($r);
		if($num > 1)
		{
			while($ok = mysqli_fetch_assoc($r))
			{
				$balance = $ok['balance']+$amount;
				if($balance < $ok['limit']){ return $ok['id']; }
			}
		}else
		{
			$r = mysqli_query($db,"SELECT `id` FROM `multiple_qiwi` WHERE `status` = '1' LIMIT 0,1");
			$ok = mysqli_fetch_assoc($r);
			return $ok['id'];
		}
		
	}
	


	function zakaz_create($id_chat,$status,$suma,$buy,$pay_type = '',$info_client = '',$discount,$how,$tel,$id_bot)
	{
		global $db;
		$date = time();
		$qiwi_method = get_setting('qiwi_method');
		if(($pay_type == 1) && ($qiwi_method == 2))
		{
			$id_wallet_qiwi = get_wallet_qiwi($suma);
		}else { $id_wallet_qiwi = 0; }
		
		
		
		$s = "INSERT INTO `a_zakaz` (`date`,`id_chat`,`status`,`suma`,`buy`,`active`,`pay_type`,`info_client`,
															`discount`,`how_method`,`phone`,`bot_id`,`id_wallet_qiwi`) 
					VALUES ('$date','$id_chat','$status','$suma','$buy','0','$pay_type','$info_client','$discount',
																				'$how','$tel','$id_bot','$id_wallet_qiwi')";
		$r = mysqli_query($db,$s);
		$id = mysqli_insert_id($db); 
		
		return $id.'|'.$id_wallet_qiwi;
	}
	
	
	function get_multiple_qiwi_data($id,$col)
	{
		global $db;
		$r = mysqli_query($db,"SELECT `$col` FROM `multiple_qiwi` WHERE `id` = '$id'");
		$ok = mysqli_fetch_assoc($r);
		return $ok[$col];
	}
		
	
	function zakaz_add_items($id_chat,$id_item,$sum,$all_sum,$id_zakaz,$count)
	{
		global $db;
		$s = "INSERT INTO `a_zakaz_items` (`id_chat`,`id_item`,`sum`,`all_sum`,`id_zakaz`,`count`) 
								VALUES ('$id_chat','$id_item','$sum','$all_sum','$id_zakaz','$count')";
		$r = mysqli_query($db,$s);
		$id = mysqli_insert_id($db);
		return $id;
	}	
	
	
	function close_zakaz($id_zakaz)
	{
		global $db;
		$s = "UPDATE `a_zakaz` SET `status` = '3' WHERE `id` = '$zakaz_id' AND `active` = '0'";
		mysqli_query($db,$s);
		 
	}
	

	
	  function view_item_open($item,$id_chat)
	  {
			global $token;
			global $db;
			$url = "https://api.telegram.org/bot".$token;	
			
			$rx = mysqli_query($db,"SELECT `file`,`file_id` FROM `cat_photo` WHERE `id_cat` = '$item'");
			$num = mysqli_num_rows($rx);
			if($num > 0)
			{
				$pho = mysqli_fetch_assoc($rx);
				if(empty($pho['file_id'])){ $photo = 'https://'.$_SERVER['SERVER_NAME'].'/img/catalog/'.$pho['file']; }
				else { $photo = $pho['file_id']; }
			}
		
			$data = array('chat_id' => $id_chat,
						'photo' => $photo,
						#'caption' => $info,
						'parse_mode'=>'HTML',
						'disable_notification'=>false,);
		
			$query = $url."/sendPhoto?".http_build_query($data);
			$x = file_get_contents($query);
		
			$info .= get_props($item,$id_chat);
			
			$s = "SELECT `count` FROM `basket` WHERE `id_item` = '$item' AND `id_chat` = '$id_chat' AND `active` = '0'";
			$bask = mysqli_query($db,$s);
			$basket = mysqli_fetch_assoc($bask);
			$count = $basket['count'];
			#$count = get_item_param($item,'count');
			
			
			$price = get_item_param($item,'price');
			$currency = get_item_param($item,'currency');
			$currency = get_currency_val($currency);
			$number = $item;
			$price = $price * $count;
			$price = number_format($price, 2,'.','');
			 $currency = get_currency_val($item);
			 if($count > 0){ $info .= "\n <b>Цена</b>: $price $currency за ($count шт.)"; }
	
			  $callback_dec = "dec_item:-:$item";
			  $buttons_item['inline_keyboard'][0][0]['text'] = "\xE2\x9E\x96";;
			  $buttons_item['inline_keyboard'][0][0]['callback_data'] = $callback_dec ; 
			  
			  $btn_ = "($count шт.)";
			  $buttons_item['inline_keyboard'][0][1]['text'] = $btn_;
			  $buttons_item['inline_keyboard'][0][1]['callback_data'] = '/count';
			  
			  $callback_inc = "inc_item:+:$item";
			  $buttons_item['inline_keyboard'][0][2]['text'] =  "\xE2\x9E\x95";
			  $buttons_item['inline_keyboard'][0][2]['callback_data'] = $callback_inc; 
			  $buttons_item['inline_keyboard'][1][0]['text'] = "🛒 ". "Открыть корзину";
			  $buttons_item['inline_keyboard'][1][0]['switch_inline_query_current_chat'] = " Корзина";		

			 $markup = json_encode($buttons_item,true);
				
			 $data = array(
				'chat_id' => $id_chat,
				'reply_markup' => $markup,
				'text' => $info,
				'disable_notification'=>true,
				'parse_mode'=>'HTML'
				
			);
			$json = file_get_contents($url.'/sendmessage?'.http_build_query($data));
			$json = json_decode($json,true);
			$message_id  = $json['result']['message_id'];
			if($message_id > 0){ set_current('message_id',$message_id,$id_chat); }
	  }  

	  
	 function create_pay($id_zakaz,$item_id)
	 {
		# include('telegram/db.php');
		global $db;
		require_once('dash/lib/cryptobox.class.php');
	#	$amount_ = convert_currency_live('RUB', "USD", 100);
	#write("amount:$amount");
	#	$amount_ = convect_currency_($amount,'RUB','DSH');
	#echo $amount_;
		$userID     = "32443";
		$userFormat    = "COOKIE";
		$orderID    = $id_zakaz;
		$amountUSD    = $amount;

		$period      = "24 HOUR";   
		$def_language  = "ru";      
		$def_coin    = "dash";    

		#$coins = array('dash','bitcoin',);  
		$public_key_ = get_setting_data('public_key',$pay_id);
		$private_key_ = get_setting_data('secret_key',$pay_id);

		$all_keys = array( "dash" => array("public_key" => $public_key_, 
								"private_key" => $private_key_),
				"dash32" => array("public_key" => "33513AAopMdnDash77DASHPUBJhCKT8lcQHE02B0NG1wuTNcwS",  
				"private_key" => "33513AAopMdnDash77DASHPRVvM572SF50O4lLki8JjHWT90Pg"),
				); // Demo keys!

		$def_coin = strtolower($def_coin);
		if (!in_array($def_coin, $coins)) $coins[] = $def_coin;  


		$coinName = cryptobox_selcoin($coins, $def_coin);
		$public_key  = $all_keys[$coinName]["public_key"];
		$private_key = $all_keys[$coinName]["private_key"];
		write('public_key:'.$public_key." | private_key:".$private_key. " | coinName:".$coinName." | name_payment:".$name_payment);

		/** PAYMENT BOX **/
		$options = array(
		"public_key"    => $public_key,      // your public key from gourl.io
		"private_key"   => $private_key,  // your private key from gourl.io
		"webdev_key"    => "",           // optional, gourl affiliate key
		"orderID"       => $orderID,     // order id or product name
		"userID"          => $userID,   // unique identifier for every user
		"userFormat"    => $userFormat,   // save userID in COOKIE, IPADDRESS, SESSION  or MANUAL
		"amount"         => 0,          // product price in btc/bch/ltc/doge/etc OR setup price in USD below
		"amountUSD"     => $amountUSD,      // we use product price in USD
		"period"          => $period,   // payment valid period
		"language"      => $def_language    // text on EN - english, FR - french, etc
		);
		  
		$box = new Cryptobox($options);
		$coinName = $box->coin_name();

		$url_data =  $box->cryptobox_json_url();	 write('url_data:'.$url_data);
		$data = file_get_contents($url_data);
		$json = json_decode($data,true);

		$addr = $json['addr'];
		$all_sum = $json['amount'];
		$exchange_id = $json['order'];

		mysqli_query($db,"UPDATE `a_zakaz` SET `addr_coin` = '$addr' WHERE `id` = '$id_zakaz'");
		return $addr;
	 }

	
	
	  
	function show_data($id_chat,$info,$id_bot = 0)
	{	
		global $db;
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
	  	


	 
	 function ref_gain($id_chat,$zakaz_id,$id_bot = 0)
	 {
		global $db;
		if($id_bot == 0)
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
	
	
 
	function create_pay_btc()
	{
		$my_address = get_setting_data('wallet',4);
		$confirmations = get_setting_data('confirmations',4);
		$fee_level = get_setting_data('level_btc',4);	
		$callback = urlencode("http://".$_SERVER['SERVER_NAME']."/payment_bitcoin.php");
		$data = file_get_contents("https://bitaps.com/api/create/payment/". $my_address. "/" . $callback . "?confirmations=" . $confirmations. "&fee_level=" . $fee_level);
	# write("callback:$callback");
		$respond = json_decode($data,true);
		$address = $respond["address"]; 
		$payment_code = $respond["payment_code"]; 
		# $invoice = $respond["invoice"]; 
		return $address.'|'.$payment_code;
	}
				
	
	function static_add($chat_id)
	{
		global $db;
		$date = date('Y-m-d');
		$s = "SELECT `id` FROM `static_day` WHERE `date` = '$date' AND `id_chat` = '$chat_id'";
		$r = mysqli_query($db,$s);
		$num = mysqli_num_rows($r);
		
		if($num == 0)
		{
			$s = "INSERT INTO `static_day` (`date`,`id_chat`) VALUES ('$date','$chat_id')";
			mysqli_query($db,$s);
			
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

	function getFormSignature($account, $currency, $desc, $sum, $secretKey) {
		$hashStr = $account.'{up}'.$currency.'{up}'.$desc.'{up}'.$sum.'{up}'.$secretKey;
		return hash('sha256', $hashStr);
	}

?>