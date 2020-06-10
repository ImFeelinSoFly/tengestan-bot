<?php
	include('functions.php');
	if(empty($_POST['label'])){ header("HTTP/1.0 404 Not Found"); }

	$update = file_get_contents("php://input");
	$codepro = $_POST['codepro'];
	$amount = round($_POST['withdraw_amount']);
	$sender = $_POST['sender']; 
	$datetime = $_POST['datetime'];
	$notif_type = $_POST['notification_type'];
	$label = intval($_POST['label']);

	$type_payment = get_setting('type_payment');
	if(($type_payment == 1) && ($codepro == 'false'))
	{
		$token = get_setting('bot_token');
		$url = "https://api.telegram.org/bot".$token;
		
		$r = mysqli_query($db,"SELECT `id`,`id_chat`,`amount`,`bot_id` FROM `payment_users` WHERE `status` = '0' AND `id` = '$label'");
		$exist = mysqli_num_rows($r);
		if($exist < 1){ exit; }
		$list = mysqli_fetch_assoc($r);
		
		if($list['bot_id'] > 0)
		{
			$rx = mysqli_query($db,"SELECT `token` FROM `bot_list` WHERE `id_bot` = '".$list['bot_id']."'");
			$data = mysqli_fetch_assoc($rx);
			$token = $data['token'];
		}else { $token = get_setting('bot_token'); }
		$url = "https://api.telegram.org/bot".$token;
		
		
		$task_id = $list['id'];
		$id_chat = $list['id_chat'];
		$sum = $list['amount'];
	
		mysqli_query($db,"UPDATE `payment_users` SET `status` = '2' WHERE `id` = '$task_id'");
		$message_id = get_current('message_id',$id_chat);
		if(($message_id > 1) )
		{
			$qur = array('message_id'=>$message_id,
						'chat_id'=>$id_chat,
						);
			$qur = '/deleteMessage?'.http_build_query($qur);
			file_get_contents($url.$qur);
		}
		
		$balance = get_current('balance',$id_chat);
		
		$balance = $balance + $sum;
		set_current('balance',$balance,$id_chat);
		
		$balance = number_format($balance, 0,'.','.');
		$sum = number_format($sum, 0,'.','.');
		$info = "👍 <b>Сумма: $sum RUB зачислена.</b>\n\n💰 <b>Ваш баланс:</b> <code>$balance RUB</code>";
		show_data($id_chat,$info,$list['bot_id']);
		
		$chat_id_admin = get_setting('chat_id_admin');
		$user = get_current('user',$id_chat);
		$content = array(
				'chat_id' => $chat_id_admin,
				'text' => "✔️ <b>Пополнение баланса $amount RUB | Юзер: $user</b>",
				'parse_mode'=>'HTML',
			);
		file_get_contents($url."/sendmessage?".http_build_query($content));
		exit;
	}

	if($codepro == 'false')
	{
		$r = mysqli_query($db,"SELECT `id`,`id_chat`,`suma`,`pay_type`,`bot_id`,`discount` FROM `a_zakaz` 
								WHERE `status` = '1' AND `active` = '0' AND `id` = '$label' ");
		$exist = mysqli_num_rows($r);
		if($exist < 0){ exit;; }
		
		$order = mysqli_fetch_assoc($r);
		$task_id = $label;
		$pay_type = $order['pay_type'];
		$id_chat = $order['id_chat'];
		$sum = $order['suma'];
		if($order['discount'] > 0){ $sum = $order['discount']; set_current('discount',0,$id_chat); }
		if($amount <> $sum){ exit; }
		
		if($order['bot_id'] > 0)
		{
			$rx = mysqli_query($db,"SELECT `token` FROM `bot_list` WHERE `id_bot` = '".$order['bot_id']."'");
			$data = mysqli_fetch_assoc($rx);
			$token = $data['token'];
		}else { $token = get_setting('bot_token'); }
		$url = "https://api.telegram.org/bot".$token;

		$message_id = get_current('message_id',$id_chat);
		if(($message_id > 1) )
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
			if(intval($count_x) == 0){ $dis = ", `active` = '0'"; } else { $dis = ''; }
			
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