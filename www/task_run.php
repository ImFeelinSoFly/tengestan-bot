<?php
	set_time_limit(0);
	include('functions.php');

	$act = strip_tags($_GET['action']);

	
	if($act == 'delivery')
	{	
		$date = date('m.d.Y');
		$r = mysqli_query($db,"SELECT `id`,`message`,`type_d`,`inline_link`,`inline_name`,`photo` FROM `delivery_list` 
									WHERE `date_run` = '$date' AND `status` = '0'");
		$num = mysqli_num_rows($r);
		if($num < 1){ exit; }

		$chat_id_admin = get_setting('chat_id_admin');
		$token = get_setting('bot_token');
		$url = "https://api.telegram.org/bot".$token;			
	
		
		while($ok = mysqli_fetch_assoc($r))
		{
			$all_send = 0;
			mysqli_query($db,"UPDATE `delivery_list` SET `status` = '1' WHERE `id` = '".$ok['id']."'");
			$msg_text = unicode_char($ok['message']);
			
			
			$photo = $ok['photo'];
			$ext = pathinfo($photo, PATHINFO_EXTENSION);
			$type_d = intval($ok['type_d']);

			switch($type_d)
			{
				case 0: $s_param = '';break;
				case 1: $s_param = '';break;
				case 2: $s_param = " AND `id_chat` = '$chat_id_admin'";
			}
			
			$st = 0;
			$r1 = mysqli_query($db,"SELECT `id_chat` FROM `Users` WHERE `id_bot` = '0' $s_param ORDER BY `id` DESC");
			while($users = mysqli_fetch_assoc($r1))
			{
				$id_chat = $users['id_chat'];
				if($type_d == 1)
				{
					$rx1 = mysqli_query($db,"SELECT `id` FROM `Users` WHERE `referer` = '$id_chat'");
					$exist = mysqli_num_rows($rx1);
					if($exist > 0)
					{ 
						$all_send++;
						mysqli_query($db,"UPDATE `delivery_list` SET `progress` = '$all_send' WHERE `id` = '".$ok['id']."'");
						continue; 
					}
				}
				
				#if($st == 60){ sleep(1); $st = 0; }
				#$st++;
				
				$user = get_current('user',$id_chat);
				$username = get_current('username',$id_chat);
				$sp = explode(' ',$user);
				if(count($sp) == 2){ list($name_user,$n) = explode(' ',$user); }
				elseif(count($sp) > 2){ $name_user = $sp[0].' '.$sp[1]; }
				
				$arr[0] = '{name_full}';
				$arr[1] = '{name_user}';
				$arr[2] = '{username}';
				
				$arr2[0] = $user;
				$arr2[1] = $name_user;
				$arr2[2] = $username;
				
				$msg = str_replace($arr,$arr2,$msg_text);
				
				$buttons_inline['inline_keyboard'][0][0]['text'] = unicode_char($ok['inline_name']);
				$buttons_inline['inline_keyboard'][0][0]['url'] = $ok['inline_link'];
				$markup = json_encode($buttons_inline,true);
				if(empty($ok['inline_link'])){ $markup = ''; }
				
				if(empty($photo))
				{
					$data = array(
							'chat_id' => $id_chat,
							'reply_markup' => $markup,
							'text' => $msg,
							'disable_notification'=>false,
							'disable_web_page_preview'=>true,
							'parse_mode'=>'HTML',
						);
						$action = 'sendmessage';
				}elseif($ext !== 'gif') 
				{
					$data = array('chat_id' => $id_chat,
						'photo' => 'https://'.$_SERVER['SERVER_NAME'].'/upload/delivery/'.$photo,
						'caption' => $msg,
						'reply_markup' => $markup,
						'parse_mode'=>'HTML',
						'disable_notification'=>false,
					);
					$action = 'sendPhoto';
				}elseif($ext == 'gif')
				{
					$data = array('chat_id' => $id_chat,
						'video' => 'https://'.$_SERVER['SERVER_NAME'].'/upload/delivery/'.$photo,
						'caption' => $msg,
						'reply_markup' => $markup,
						'parse_mode'=>'HTML',
						'disable_notification'=>false,
					);
					$action = 'sendVideo';
					
				}
					
				
				$json = file_get_contents($url.'/'.$action.'?'.http_build_query($data));
				$json = json_decode($json,true);
				if($json['ok']){ $lock = 0; } else { $lock = 1; }
				mysqli_query($db,"UPDATE `Users` SET `lock` = '$lock' WHERE `id_chat` = '".$id_chat."'");
				
				
				$all_send++;
				mysqli_query($db,"UPDATE `delivery_list` SET `progress` = '$all_send' WHERE `id` = '".$ok['id']."'");
				
				$rx = mysqli_query($db,"SELECT `status` FROM `delivery_list` WHERE `id` = '".$ok['id']."'");
				$ok_s = mysqli_fetch_assoc($rx);
				$status_ = intval($ok_s['status']);
				if($status_ == 6){ exit; }
				
			}
			
			mysqli_query($db,"UPDATE `delivery_list` SET `status` = '2' WHERE `id` = '".$ok['id']."'");
			
		}
		
	}
	
	
	if($act == 'check_wallets')
	{
		$r = mysqli_query($db,"SELECT `id`,`wallet`,`api_key` FROM `multiple_qiwi`");
		while($list = mysqli_fetch_assoc($r))
		{
			$id = $list['id'];
			if(strlen($list['api_key']) < 30){ mysqli_query($db,"UPDATE `multiple_qiwi` SET `status` = '3' WHERE `id` = '$id'");continue; }
			$balance = get_balance($list['api_key'],$list['wallet']);
			if($balance == $list['limit']){ mysqli_query($db,"UPDATE `multiple_qiwi` SET `status` = '2' WHERE `id` = '$id'"); }
			if($balance == 0){ mysqli_query($db,"UPDATE `multiple_qiwi` SET `status` = '3' WHERE `id` = '$id'"); }
			elseif($balance > 0){ mysqli_query($db,"UPDATE `multiple_qiwi` SET `status` = '1', `balance` = '$balance' WHERE `id` = '$id'"); }
		}
	}
	
	if($act == 'check_reserve')
	{
		$token = get_setting_data('api_key',$pay_type);
		$r = mysqli_query($db,"SELECT `id`,`date`,`pay_type`,`id_chat`,`bot_id` FROM `a_zakaz` WHERE `status` = '1'");
		while($list = mysqli_fetch_assoc($r))
		{
			$task_id = $list['id'];
			$pay_type = $list['pay_type'];
			$id_chat = $list['id_chat'];
			
			if($list['bot_id'] > 0)
			{
				$rx = mysqli_query($db,"SELECT `token` FROM `bot_list` WHERE `id_bot` = '".$list['bot_id']."'");
				$data = mysqli_fetch_assoc($rx);
				$token = $data['token'];
				$url = "https://api.telegram.org/bot".$token;
			}
			
			$sec = time() - $list['date'];
			$sec = round($sec);
			if($sec < 900){ echo "not 15 min:$sec sec<br>";continue; }
			if($pay_type == 4){ continue; }
			
			#echo "sec:$sec<br>";#continue;
			
			$r1 = mysqli_query($db,"SELECT `id_item`,`count` FROM `a_zakaz_items` WHERE `id_zakaz` = '".$list['id']."'");
			while($items = mysqli_fetch_assoc($r1))
			{
				$count_all = get_item_param($items['id_item'],'count');
				$count = $count_all + $items['count'];
			
				mysqli_query($db,"UPDATE `catalog` SET `count` = '$count' WHERE `id` = '".$items['id_item']."'");
			}
			
			
			$message_id = get_current('message_id',$id_chat);
			$qur = array('message_id'=>$message_id,
						'chat_id'=>$id_chat,
						);
			$qur = '/deleteMessage?'.http_build_query($qur);
			file_get_contents($url.$qur);
			
			show_data($id_chat,"❗️ <b>Заказ просрочен, прошло более 15 минут.</b>",$items['bot_id']);
			
			mysqli_query($db,"UPDATE `a_zakaz` SET `status` = '3' WHERE `id` = '$task_id'");
			mysqli_query($db,"UPDATE `Users` SET `z_step` = '' , `tmp_data` = '', `id_zakaz` = '',
									`how_method` = '', `tmp_data` = '' WHERE `id_chat` = '$id_chat'");
		}
		
	}
	
?>