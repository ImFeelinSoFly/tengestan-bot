<?php
	include('functions.php');

	$address_btc = strip_tags($_POST['address']);
	if(empty($address_btc)){ return false; }

	$confirmations_all = get_setting_data('confirmations',4);
	$confirmations = intval($_POST['confirmations']);
	$tx_hash = strip_tags($_POST['tx_hash']);
	$amount = $_POST['amount'] / 100000000; 

	$r = mysqli_query($db,"SELECT `id_zakaz`,`id_chat` FROM `Users` WHERE `btc_address` = '$address_btc'");
	$data_x = mysqli_fetch_assoc($r);
	$id_chat = $data_x['id_chat'];
	$id_zakaz = $data_x['id_zakaz'];
	$date = time();
	
	$type_payment = get_setting('type_payment');
	if($type_payment == 1)
	{
		$r1 = mysqli_query($db,"SELECT `id`,`status`,`bot_id` FROM `payment_users` WHERE `tx_hash` = '$tx_hash' ");
		$is = mysqli_num_rows($r1);
		if($is == 0)
		{
			mysqli_query($db,"UPDATE `payment_users` SET `tx_hash` = '$tx_hash' WHERE `id` = '$id_zakaz'");
			echo 'fix pay';
		}else
		{
			$pay_data = mysqli_fetch_assoc($r1);
			if(intval($pay_data['status']) > 1){ echo 'end pay';return false; }
		}
		
		if($pay_data['bot_id'] > 0)
		{
			$rx = mysqli_query($db,"SELECT `token` FROM `bot_list` WHERE `id_bot` = '".$pay_data['bot_id']."'");
			$data = mysqli_fetch_assoc($rx);
			$token = $data['token'];
		}else { $token = get_setting('bot_token'); }
		$url = "https://api.telegram.org/bot".$token;
		
		
		$confirmations_ico = get_number($confirmations);
		$confirmations_all_ico = get_number($confirmations_all);
		$wallet = get_current('btc_address',$id_chat);
		
		$message_id = get_current('message_id',$id_chat);
		if(($message_id > 1) )
		{
			$qur = array('message_id'=>$message_id,
						'chat_id'=>$id_chat,
						);
			$qur = '/deleteMessage?'.http_build_query($qur);
			file_get_contents($url.$qur);
		}
		
		if($confirmations >= $confirmations_all)
		{ 
			$r = mysqli_query($db,"SELECT `id`,`id_chat`,`amount` FROM `payment_users` WHERE `id` = '$id_zakaz'");
			$exist = mysqli_num_rows($r);
			if($exist < 0){ exit;; }
			
			$data_x = mysqli_fetch_assoc($r);
			$id_chat = $data_x['id_chat'];
			$sum = $data_x['amount'];
		
			mysqli_query($db,"UPDATE `payment_users` SET `status` = '2' WHERE `id` = '$id_zakaz'");
			$balance = get_current('balance',$id_chat);
			
			$balance = $balance + $sum;
			set_current('balance',$balance,$id_chat);
			
			$balance = number_format($balance, 0,'.','.');
			$sum = number_format($sum, 0,'.','.');
			$info = "👍 <b>Сумма: $amount ฿ - $sum RUB. Зачислена.</b>\n\n💰 <b>Ваш баланс:</b> <code>$balance RUB</code>";
			show_data($id_chat,$info,$pay_data['bot_id']);
			echo $_POST["invoice"];
			#echo 'pay';
			
			$chat_id_admin = get_setting('chat_id_admin');
			$user = get_current('user',$id_chat);
			$content = array(
					'chat_id' => $chat_id_admin,
					'text' => "✔️ <b>Пополнение баланса $amount RUB | Юзер: $user</b>",
					'parse_mode'=>'HTML',
				);
			file_get_contents($url."/sendmessage?".http_build_query($content));
		}elseif(!empty($tx_hash))
		{
			# Wait confir
			$info = "<b>Новое подтверждение</b>\n";
			$info .= "\n♻️ <b>Статус:</b>  <code>Количество подтверждений:</code> <b>$confirmations_ico / $confirmations_all_ico</b>\n";
			$info .= "\n\n❕ <i>При поступлении платежа, вы получите уведомление.</i>";
			
			$content = array(
					'chat_id' => $id_chat,
					'text' => $info,
					'parse_mode'=>'HTML',
				);		
			$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
			$json = json_decode($data,true);
			$message_id  = $json['result']['message_id'];
			if($message_id > 0){ set_current('message_id',$message_id,$id_chat); }
		}
		
		

		exit;
	}

	
	
	
	
	
	
	$r1 = mysqli_query($db,"SELECT `id`,`status` FROM `a_zakaz` WHERE `tx_hash` = '$tx_hash' ");
	$is = mysqli_num_rows($r1);
	if($is == 0)
	{
		mysqli_query($db,"UPDATE `a_zakaz` SET `tx_hash` = '$tx_hash' WHERE `id` = '$id_zakaz'");
		echo 'fix pay';
	}else
	{
		$pay_data = mysqli_fetch_assoc($r1);
		if(intval($pay_data['status']) > 1){ echo 'end pay';return false; }
	}
	
	$r1 = mysqli_query($db,"SELECT `id`,`id_chat`,`suma`,`pay_type`,`discount`,`bot_id` FROM `a_zakaz` 
								WHERE `status` = '1' AND `id` = '$id_zakaz' AND `pay_type` = '4'");
	$orders = mysqli_fetch_assoc($r1);
	
	if($orders['bot_id'] > 0)
	{
		$rx = mysqli_query($db,"SELECT `token` FROM `bot_list` WHERE `id_bot` = '".$orders['bot_id']."'");
		$data = mysqli_fetch_assoc($rx);
		$token = $data['token'];
		$url = "https://api.telegram.org/bot".$token;
	}
	
	$task_id = $orders['id'];
	$pay_type = $orders['pay_type'];
	$id_chat = $orders['id_chat'];
	$sum = $orders['suma'];
	$discount = $orders['discount'];
		
	$payment_name = get_setting_data('name',$pay_type);
	$wallet_check = get_setting_data('wallet',$pay_type);
	$token = get_setting_data('api_key',$pay_type);
	$payment_name = strtolower($payment_name);
	$id_zakaz = get_current('id_zakaz',$id_chat);
	$currency = get_currency_val(4);

	$r3 = mysqli_query($db,"SELECT `id_item`,`count`,`sum`,`all_sum` FROM `a_zakaz_items` 
							WHERE `id_chat` = '$id_chat' AND `id_zakaz` = '$id_zakaz'");
	$list_items = '';
	while($items = mysqli_fetch_assoc($r3))
	{
		$item = $items['id_item'];
		$count = $items['count'];
		$price =  $items['sum']; 
		
		$all_sum = $items['all_sum'];
		$name = get_item_param($item,'name');
	
		$name = get_item_param($item,'name');
		$list_items .= "\n   <b>".$name."</b> - $count шт <b>x</b> ".$price." = <code>".$all_sum." $currency.</code>\n<code> -  -  -  - -  -  -  -  -</code>";
	}
	
	
	if($discount > 0)
	{
		$info_discount = "(вместо <b>$sum $currency</b>)";
		$sum = $discount;
		set_current('discount',0,$id_chat);
	}
	
	$confirmations_ico = get_number($confirmations);
	$confirmations_all_ico = get_number($confirmations_all);
	$wallet = get_current('btc_address',$id_chat);
	
	if($confirmations >= $confirmations_all)
	{ 
		# PAY
		$update_message = 1; 
		$r1 = mysqli_query($db,"SELECT `id`,`id_item`,`count` FROM `a_zakaz_items` WHERE `id_zakaz` = '$task_id'");
		$x = 1;
		$pay_data = "<code> - - - - -  Ваша покупка - -  - - - - - -</code>\n\n";
		while($items = mysqli_fetch_assoc($r1))
		{
			$item = $items['id_item'];
			$count = $items['count'];
			
			$name = get_item_param($item,'name');
			
			$list_ = get_item_param($item,'content');
			$data_all =  explode("\n",$list_);
			
			$num = 1;
			for($q=0;$q<$count;$q++)
			{
				$pay_data .= "<b>$x-$num)</b>. ✅ <b>$name</b> <code>".$data_all[$q]."</code>\n";
				unset($data_all[$q]);
				$num++;
			}	
			$param_ = implode("\n",$data_all);
			
			$count_all = get_item_param($item,'count');
			$count_x = $count_all - $count;
			if(intval($count_x) == 0){ $dis = ", `active` = '0'"; } else { $dis = ''; }
			
			mysqli_query($db,"UPDATE `catalog` SET `content` = '$param_' $dis WHERE `id` = '".$item."'");
			$x++;
		}	

		$pay_data .= "\n<code> - - - - -  Благодарим за покупку  - - - -</code>";
		
		show_data($id_chat,$pay_data,$items['bot_id']);
		
		mysqli_query($db,"UPDATE `a_zakaz` SET `status` = '2' WHERE `id` = '$task_id'");
		mysqli_query($db,"UPDATE `Users` SET `z_step` = '' , `tmp_data` = '', `id_zakaz` = '',
								`how_method` = '', `tmp_data` = '' WHERE `id_chat` = '$id_chat'");
																		
		ref_gain($id_chat,$task_id,$items['bot_id']);

		$re = mysqli_query($db,"SELECT `id_item`,`count` FROM `a_zakaz_items` WHERE `id_chat` = '$id_chat' AND `id_zakaz` = '$id_zakaz'");
		while($items = mysqli_fetch_assoc($re))
		{
			reward($items['id_item'],$items['count']);
		}
	
		echo $_POST ["invoice"];
		echo 'pay';
	}elseif(!empty($tx_hash))
	{
		# Wait confir
		$info = "📦 <b>Оплата заказа</b> <code>#$id_zakaz</code>\n\n";
		$info .= "➖ <b>Список покупок:</b> ➖";
		$info .= $list_items."\n\n";
		$info .= "📝 <b>Реквизиты оплаты через $payment_name:</b>\n\n";
		$info .= "<b>Сумма для оплаты:</b> <code>$sum $currency</code> $info_discount\n";
		$info .= "<b>Номер счета:</b> <code>$wallet</code>\n";
		$info .= "\n♻️ <b>Статус:</b>  <code>Количество подтверждений:</code> <b>$confirmations_ico / $confirmations_all_ico</b>\n";
		$info .= "\n\n❕ <i>Обработка платежа\Выдача покупки проходит в авто-режиме.</i>";
	}
	
	mysqli_query($db,"UPDATE `a_zakaz` SET `confirmations` = '$confirmations' WHERE `tx_hash` = '$tx_hash' AND `id_chat` = '$id_chat'");

			$message_id = get_current('message_id',$id_chat);
			#if(($message_id > 1) && ($update_message == 1))
			#{
				$qur = array('message_id'=>$message_id,
							'chat_id'=>$id_chat,
							);
				$qur = '/deleteMessage?'.http_build_query($qur);
				file_get_contents($url.$qur);
			#}		
		
		if($confirmations  < 1)
		{
			$buttons_inline['inline_keyboard'][0][0]['text'] = '❕ Отменить заказ';
			$buttons_inline['inline_keyboard'][0][0]['callback_data'] = '/cancel_zakaz:'.$id_zakaz;
		
			$markup = json_encode($buttons_inline);
		}else { $markup = ''; }
		
		#if($update_message == 0){ $action = 'editmessagetext'; } else { $action = 'sendmessage'; }
		$action = 'sendmessage';
			$content = array(
				'chat_id' => $id_chat,
				'reply_markup' => $markup,
				'message_id'=>$message_id,
				'text' => $info,
				'parse_mode'=>'HTML',
				'disable_web_page_preview'=>true,
				'disable_notification'=>false,
			);

			$data = file_get_contents($url.'/'.$action.'?'.http_build_query($content));				
			$json = json_decode($data,true);
			$message_id  = $json['result']['message_id'];
			if($message_id > 0){ set_current('message_id',$message_id,$id_chat); }

	
?>