<?php	
	include('functions.php');	
	$update = trim(file_get_contents("php://input"));
	$update = json_decode($update,true);
	if($update['test'] == true){ exit; }
	$comment = $update['payment']['comment'];
	$personId = $update['payment']['personId'];
	$amount = $update['payment']['total']['amount'];
	if(empty($comment)){ exit; }
	
	$type_payment = get_setting('type_payment');
	if($type_payment == 1)
	{
		$r = mysqli_query($db,"SELECT `id_chat`,`amount`,`bot_id` FROM `payment_users` 
						WHERE `pay_type` = '1' AND `status` = '0' AND `id` = '$comment'");
		$exist = mysqli_num_rows($r);
		if($exist < 0){ exit; }
		$data_x = mysqli_fetch_assoc($r);
		$id_chat = $data_x['id_chat'];

		if($data_x['bot_id'] > 0)
		{
			$rx = mysqli_query($db,"SELECT `token` FROM `bot_list` WHERE `id_bot` = '".$data_x['bot_id']."'");
			$data = mysqli_fetch_assoc($rx);
			$token = $data['token'];
		}else { $token = get_setting('bot_token'); }
		$url = "https://api.telegram.org/bot".$token;
	
		$message_id = get_current('message_id',$id_chat);
		if(($message_id > 0) )
		{
			$qur = array('message_id'=>$message_id,
						'chat_id'=>$id_chat,
						);
			$qur = '/deleteMessage?'.http_build_query($qur);
			file_get_contents($url.$qur);
		}
		
		$balance = get_current('balance',$id_chat);
		
		$balance = $balance + $amount;
		set_current('balance',$balance,$id_chat);
		
		$balance = number_format($balance, 0,'.','.');
		$amount_x = number_format($amount, 0,'.','.');
		$info = "➕ <b>Сумма: $amount_x RUB зачислена.</b>\n\n💰 <b>Ваш баланс:</b> <code>$balance RUB</code>";#echo $info;exit;
		show_data($id_chat,$info,$data_x['bot_id']);
		
		mysqli_query($db,"UPDATE `payment_users` SET `status` = '2', `amount` = '$amount' WHERE `id` = '$comment'");
		update_limit_wallet($personId,$amount);
	}else
	{
		$r = mysqli_query($db,"SELECT `id`,`id_chat`,`suma`,`pay_type`,`bot_id`,`id_wallet_qiwi`,`discount` FROM `a_zakaz` 
														WHERE `status` = '1' AND `id` = '$comment' AND `pay_type` = '1'");
		$exist = mysqli_num_rows($r);
		if($exist < 0){ exit; }
		$data_x = mysqli_fetch_assoc($r);
		$task_id = $data_x['id'];
		$pay_type = $data_x['pay_type'];
		$id_chat = $data_x['id_chat'];
		$sum = $data_x['suma'];
		if($data_x['discount'] > 0){ $sum = $data_x['discount']; set_current('discount',0,$id_chat); }
		$id_wallet_qiwi = $data_x['id_wallet_qiwi'];
		if($amount <> $sum){ echo "$amount <> $sum\n";exit; }
		
		if($data_x['bot_id'] > 0)
		{
			$rx = mysqli_query($db,"SELECT `token` FROM `bot_list` WHERE `id_bot` = '".$data_x['bot_id']."'");
			$data = mysqli_fetch_assoc($rx);
			$token = $data['token'];
		}else { $token = get_setting('bot_token'); }
		$url = "https://api.telegram.org/bot".$token;
		
		$message_id = get_current('message_id',$id_chat);
		if(($message_id > 0) )
		{
			$qur = array('message_id'=>$message_id,
						'chat_id'=>$id_chat,
						);
			$qur = '/deleteMessage?'.http_build_query($qur);
			file_get_contents($url.$qur);
		}
		
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
			if(intval($count_x) < 1){ $dis = ", `active` = '0'"; } else { $dis = ''; }
			
			mysqli_query($db,"UPDATE `catalog` SET `content` = '$param_' $dis WHERE `id` = '".$item."'");
			$x++;
		}	
		
		$pay_data .= "\n<code> - - - - -  Благодарим за покупку  - - - -</code>";

		$re = mysqli_query($db,"SELECT `id_item`,`count` FROM `a_zakaz_items` WHERE `id_chat` = '$id_chat' AND `id_zakaz` = '$task_id'");
		while($items = mysqli_fetch_assoc($re))
		{
			reward($items['id_item'],$items['count']);
		}

		show_data($id_chat,$pay_data,$items['bot_id']);
		mysqli_query($db,"UPDATE `a_zakaz` SET `status` = '2' WHERE `id` = '$task_id'");
		mysqli_query($db,"UPDATE `Users` SET `z_step` = '' , `tmp_data` = '', `id_zakaz` = '' WHERE `id_chat` = '$id_chat'");
		ref_gain($id_chat,$task_id,$items['bot_id']);
	}
?>