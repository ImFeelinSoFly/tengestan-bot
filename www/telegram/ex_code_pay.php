<?php
	global $id_chat;
	global $db;
	global $id_zakaz;
	
	$message_id = get_current('message_id',$id_chat);
	if(($message_id > 1) )
	{
		$qur = array('message_id'=>$message_id,
					'chat_id'=>$id_chat,
					);
		$qur = '/deleteMessage?'.http_build_query($qur);
		file_get_contents($url.$qur);
	}

	$r1 = mysqli_query($db,"SELECT `bot_id` FROM `a_zakaz` WHERE `id` = '$id_zakaz'");
	$order = mysqli_fetch_assoc($r1);
	
	if($order['bot_id'] > 0)
	{
		$rx = mysqli_query($db,"SELECT `token` FROM `bot_list` WHERE `id_bot` = '".$order['bot_id']."'");
		$data = mysqli_fetch_assoc($rx);
		$token = $data['token'];
		$url = "https://api.telegram.org/bot".$token;
	}

	$task_id = $id_zakaz;
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
			$pay_data .= "<b>$x-$num)</b>. ? <b>$name</b> <code>".$data_all[$q]."</code>\n";
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
	
	$pay_data .= "\n<code> - - - - -  Благодарим за покупку  - - - - - - -</code>";

	$re = mysqli_query($db,"SELECT `id_item`,`count` FROM `a_zakaz_items` WHERE `id_chat` = '$id_chat' AND `id_zakaz` = '$task_id'");
	while($items = mysqli_fetch_assoc($re))
	{
		reward($items['id_item'],$items['count']);
	}	
	
	show_data($id_chat,$pay_data,$items['bot_id']);
	mysqli_query($db,"UPDATE `a_zakaz` SET `status` = '2' WHERE `id` = '$task_id'");
	mysqli_query($db,"UPDATE `Users` SET `z_step` = '' , `tmp_data` = '', `id_zakaz` = '',
						`how_method` = '', `tmp_data` = '' WHERE `id_chat` = '$id_chat'");
	ref_gain($id_chat,$task_id,$items['bot_id']);

	

?>