<?php
	if(strlen($_SERVER['HTTP_USER_AGENT']) > 0){ exit; }
	include('functions.php');
	global $url;
	global $token;
	global $db;
	global $mode;
	
    $content = file_get_contents("php://input");
    $update = json_decode($content, true);
	$file_id = $update['message']['photo'][1]['file_id'];
	if(empty($file_id)){ $update['message']['photo'][0]['file_id']; }
    $message = $update["message"];
	$id_chat = $message["chat"]["id"];
    $text = $message["text"];
	$user = filt($message['from']['first_name']);
	if(!empty($message['from']['last_name'])){ $user = $user.' '.filt($message['from']['last_name']); }
	$username = $message['from']['username'];

	$admin_ = get_setting('chat_id_admin');
	$id_chat_admin = intval($admin_);
	$type_payment = get_setting('type_payment');
	$basket_id = get_setting('basket_id');

	if(strstr($text,'/start ')){ list($n,$referer) = explode('/start ',$text); $text = '/start'; } else { $referer = ''; }
	if($referer == 'create_zakaz'){ $text = 'Оформить'; }

	if(stristr($text,'view-item:'))
	{
		list($com,$item) = explode(':',$text);
		view_item_open($item,$id_chat);
	}
	
	
	if(($text == '/stop') && ($type_payment == 1))
	{
		$message_id = get_current('message_id',$id_chat);
		if(($message_id > 1) )
		{
			$qur = array('message_id'=>$message_id,
						'chat_id'=>$id_chat,
						);
			$qur = '/deleteMessage?'.http_build_query($qur);
			file_get_contents($url.$qur);
		}
				
		set_current('z_step',0,$id_chat);
		set_current('method_pay',0,$id_chat);
		set_current('query',0,$id_chat);
		#$text = '/start';
		$cmd_id_ = 3;
		//$text = 3;
		
		$id_zakaz = get_current('id_zakaz',$id_chat);
		$r1 = mysqli_query($db,"SELECT `id_item`,`count` FROM `a_zakaz_items` WHERE `id_zakaz` = '".$id_zakaz."'");
		while($items = mysqli_fetch_assoc($r1))
		{
			$count_all = get_item_param($items['id_item'],'count');
			$count = $count_all + $items['count'];
			mysqli_query($db,"UPDATE `catalog` SET `count` = '$count' WHERE `id` = '".$items['id_item']."'");
		}
	}
	
	if(!empty($update['message']['contact']['phone_number']))
	{
		$tel = $update['message']['contact']['phone_number'];
		$id_chat = $message["chat"]["id"];
	
		set_current('phone',$tel,$id_chat);
		
		 $data = array(
			'chat_id' => $id_chat,
			'text' => "✅ Номер <b>$tel</b> приклеплен к заказу!",
			'disable_notification'=>true,
			'parse_mode'=>'HTML',
		);
		
		set_current('z_step','change_payment',$id_chat);
		file_get_contents($url.'/sendmessage?'.http_build_query($data));
		$z_step = 'change_payment';
	}	
	
	
	  $inline_query = false;
	  if(!empty($update['inline_query']['id']))
	  {
		$inline_query = true;
		$inline_id = $update['inline_query']['id'];
		$query = $update['inline_query']['query'];
		$id_chat = $update["inline_query"]["from"]['id'];
		
		$user = $update["inline_query"]["from"]['first_name'];
		$username = $update["inline_query"]["from"]['username'];
		$id_static = 0;
		$i = 0;
		
	
	if(strstr($query,'Корзина'))
	{
		$s = "SELECT `id`,`id_item`,`count` FROM `basket` WHERE `id_chat` = '$id_chat' AND `active` = '0' ORDER BY `id` ASC";
		$r_ = mysqli_query($db,$s);
		$num = mysqli_num_rows($r_);
		if($num == 0){ exit; }
	
		$st = 1;
		$price_all = 0;
		while($basket = mysqli_fetch_assoc($r_))
		{
			$id = $basket['id'];
			$item = $basket['id_item'];
			$count = $basket['count'];
			
			$photo = get_photo($item);
			$photo = $ssl.$_SERVER['SERVER_NAME'].'/img/catalog/'.$photo;
			
			$ext = pathinfo($photo, PATHINFO_EXTENSION);
			

			$name = get_item_param($item,'name');
			$description = get_item_param($item,'description');
			$price = get_item_param($item,'price');
			$currency = get_item_param($item,'currency');
			$currency = get_currency_val($currency);
			
			$price = $price * $count;
			$price_all = $price_all+$price;
			$price = number_format($price, 2,'.','.');
			$currency = get_currency_val(1);
			$name = "$name ($count шт. = $price $currency)✏"; 
			$info = "$description";

			if($ext !== 'gif')
			{
				$results_[$i]['type'] = 'photo';
				$results_[$i]['id'] = $id_static;
				$results_[$i]['photo_url'] = $photo;
				$results_[$i]['thumb_url'] = $photo;
				$results_[$i]['photo_width'] = '150';
				$results_[$i]['photo_height'] = '150';
				$results_[$i]['title'] = $price;
				$results_[$i]['description'] = $info;
				$results_[$i]['caption'] = 'caption';
			} else 
			{
				$results_[$i]['type'] = 'gif';
				$results_[$i]['id'] = $id_static;
				$results_[$i]['gif_url'] = $photo;
				$results_[$i]['thumb_url'] = $photo;
				$results_[$i]['gif_width'] = '150';
				$results_[$i]['gif_height'] = '150';
				$results_[$i]['title'] = $price;
				$results_[$i]['description'] = $info;
				$results_[$i]['caption'] = 'caption';
			}
			
			$id_static++;
			$results_[$i]['type'] = 'article';
			$results_[$i]['id'] = $id_static;
			$results_[$i]['title'] = $name;
			$results_[$i]['message_text'] = 'view-item:'.$item;
			$results_[$i]['parse_mode'] = 'Markdown';
			$i++;	
			
		}
			$discount = get_current('discount',$id_chat);
			if($discount > 0)
			{
				$price_disc = get_proc_sum($price_all,$discount);
				$price_all = $price_all - $price_disc;
			}
			$price_all = number_format($price_all, 2,'.','.');
			#$currency_ = get_currency_val($item);
			$photo = 'https://banner2.kisspng.com/20180316/jqq/kisspng-checkbox-check-mark-checklist-clip-art-checkmark-picture-5aab5cdc5fce90.5402687215211798683924.jpg';
			$id_static++;
			$results_[$i]['type'] = 'photo';
			$results_[$i]['id'] = $id_static;
			$results_[$i]['photo_url'] = $photo;
			$results_[$i]['thumb_url'] = $photo;
			$results_[$i]['photo_width'] = '50';
			$results_[$i]['photo_height'] = '150';
			$results_[$i]['title'] = $price;
			$results_[$i]['description'] = 'Сумма: '.$price_all." $currency\nВсего: $i шт.";
			$results_[$i]['caption'] = 'caption';
		
			$id_static++;
			$results_[$i]['type'] = 'article';
			$results_[$i]['id'] = $id_static;
			$results_[$i]['title'] = "Оформить заказ ✅";
			$results_[$i]['message_text'] = '🚩 Оформить';
			$results_[$i]['parse_mode'] = 'Markdown';
		
			$i++;
			$photo = 'http://dailymagic.info/wp-content/plugins/wp-support-plus-responsive-ticket-system/asset/images/close_btn.png';
			$id_static++;
			$results_[$i]['type'] = 'photo';
			$results_[$i]['id'] = $id_static;
			$results_[$i]['photo_url'] = $photo;
			$results_[$i]['thumb_url'] = $photo;
			$results_[$i]['photo_width'] = '150';
			$results_[$i]['photo_height'] = '150';
			$results_[$i]['title'] = '1111';
			$results_[$i]['description'] = "";
			$results_[$i]['caption'] = 'caption';
		
		
			$id_static++;
			$results_[$i]['type'] = 'article';
			$results_[$i]['id'] = $id_static;
			$results_[$i]['title'] = "Назад🔺";
			$results_[$i]['message_text'] = '←';
			$results_[$i]['parse_mode'] = 'Markdown';
			
			$id_static++;
			$results_[$i]['type'] = 'article';
			$results_[$i]['id'] = $id_static;
			$results_[$i]['title'] = "Скрыть";
			$results_[$i]['message_text'] = 'hide';
			$results_[$i]['parse_mode'] = 'Markdown';

			$buttons_inline['inline_keyboard'][0][0]['text'] = "OPEN666";
			$buttons_inline['inline_keyboard'][0][0]['switch_inline_query'] = " 33332233233";
	
		$results_x = json_encode($results_);#write($results_x);
		
		$content = array(
			'inline_query_id' => $inline_id,
			'results'=>$results_x,
			'cache_time'=>2,
			'is_personal'=>true,
			'switch_pm_text'=>'✅ Оформить заказ →',
			'switch_pm_parameter'=>'create_zakaz',
		);
		
		$url_ = "https://api.telegram.org/bot$token/answerInlineQuery?";
		$r = file_get_contents($url_.http_build_query($content));	

	}	
	
	
	
	

	  }  
	
	
	$callback = 0;
	if(!empty($update['callback_query']['data']))
	{
		$callback_query_id = $update['callback_query']['id'];
		$cmd_id = $update['callback_query']['data'];
		$id_chat = $update['callback_query']['from']['id'];
		$username = $update['callback_query']['from']['username'];
		$user = $update['callback_query']['from']['first_name'].' '.$update['callback_query']['from']['last_name'];
		$callback = 1;

		
 /*
	 if($cmd_id == 7)
	 {
		$content = get_content($cmd_id);
		$btc = rand(99999,99999999);
		$content = str_replace('{RAND_BTC_ADDR}',$btc,$content);

		$content = array(
				'chat_id' => $id_chat,
				'text' => $content,
				'parse_mode'=>'HTML',
				'disable_notification'=>true,
			);

		file_get_contents($url."/sendmessage?".http_build_query($content));
		exit;
		 
	 }
*/	 
	 
	if(stristr($cmd_id,'/add_basket:'))
	{	
		if($type_payment == 1){ exit; }
		list($com,$item,$message_id) = explode(':',$cmd_id);
		
		$r = mysqli_query($db,"SELECT `count` FROM `basket` WHERE `id_chat` = '$id_chat' AND `id_item` = '$item'");
		$num = mysqli_num_rows($r);
		$bask = mysqli_fetch_assoc($r);
		$count = $bask['count'];
		$count_all = get_item_param($item,'count');
		
		if($count_all < 1)
		{
			$content = array(
				'callback_query_id' => $callback_query_id,
				'show_alert'=>false,
				'text' => '❗️Ошибка!  Кол-во товара не указанно!',
			);

			$url_ = "https://api.telegram.org/bot$token/answerCallbackQuery?";
			file_get_contents($url_.http_build_query($content));
			exit;

		}			
				
		if($num == 0)
		{
			mysqli_query($db,"INSERT INTO `basket` (`id_item`,`count`,`id_chat`) VALUES ('$item','1','$id_chat')");
			$append = '(1)';
			
			$content = array(
						'callback_query_id' => $callback_query_id,
						'show_alert'=>false,
						'text' => '✅ Добавлено в корзину',
					);

			$url_ = "https://api.telegram.org/bot$token/answerCallbackQuery?";
			file_get_contents($url_.http_build_query($content));
		}else 
		{
			$count++;
			if($count >= $count_all)
			{
				$content = array(
					'callback_query_id' => $callback_query_id,
					'show_alert'=>false,
					'text' => '❗️Ошибка!  Товаров всего:'.$count_all.'шт.',
				);

				$url_ = "https://api.telegram.org/bot$token/answerCallbackQuery?";
				file_get_contents($url_.http_build_query($content));
				exit;
			}else
			{
				$content = array(
					'callback_query_id' => $callback_query_id,
					'show_alert'=>false,
					'text' => '✅ Добавлено в корзину',
				);

				$url_ = "https://api.telegram.org/bot$token/answerCallbackQuery?";
				file_get_contents($url_.http_build_query($content));
				
				$append = "($count)";
				mysqli_query($db,"UPDATE `basket` SET `count` = '$count' WHERE `id_chat` = '$id_chat' AND `id_item` = '$item'");
			}
		}
			
	
		
		
		#$rx = mysqli_query($db,"SELECT `message_id` FROM `message_id` WHERE `tmp_id` = '$tmp_id'");
		#$ok1 = mysqli_fetch_assoc($rx);
		#$message_id = $ok1['message_id'];
		
			$info = get_props($item,$id_chat);
		
			$buttons_inline['inline_keyboard'][0][0]['text'] = "➕ ". "Добавить в корзину".$append;
			$buttons_inline['inline_keyboard'][0][0]['callback_data'] = "/add_basket:".$item.':'.$message_id;
			$buttons_inline['inline_keyboard'][1][0]['text'] = "🛒 ". "Открыть корзину";
			$buttons_inline['inline_keyboard'][1][0]['switch_inline_query_current_chat'] = " Корзина";
			$markup = json_encode($buttons_inline,true);
			$content = array(
				'chat_id' => $id_chat,
				'message_id'=>$message_id,
				'reply_markup' => $markup,
				#'text' => $info,
				'caption' => $info,
				'parse_mode'=>'HTML',
				'resize_keyboard'=>true,
				'one_time_keyboard' => false,
			);
		
			$x = file_get_contents($url.'/editmessageCaption?'.http_build_query($content));
		
	}
		
		
	if($cmd_id == '/create_zakaz')
	{
			if($type_payment == 1){ exit; }
			$step_get_name = get_setting('step_get_name');
			$how = get_setting('how');
			$get_phone = get_setting('get_phone');
			
			if($step_get_name == 1)
			{
				$step_text = get_setting('step_text');
			
				$message_id = get_current('message_id',$id_chat);
				$content = array(
					'chat_id' => $id_chat,
					'message_id'=>$message_id,
					'text' => $step_text,
					'parse_mode'=>'HTML',
					'disable_web_page_preview'=>true,
					'disable_notification'=>false,
				);

				$data = file_get_contents($url.'/editmessagetext?'.http_build_query($content));		
				set_current('z_step','step_text',$id_chat);	
				exit;
			}elseif($how == 1)
			{
				$message_id = get_current('message_id',$id_chat);
				
				$item_x = 0;
				$r1 = mysqli_query($db,"SELECT `id`,`name` FROM `how_delivery`");
				while($how = mysqli_fetch_assoc($r1))
				{
					$buttons_inline['inline_keyboard'][$item_x][0]['text'] = '✅ '.$how['name'];
					$buttons_inline['inline_keyboard'][$item_x][0]['callback_data'] = '/how_type:'.$how['id'];
					$item_x++;
				}
				
				$markup = json_encode($buttons_inline);
				
				$content = array(
					'chat_id' => $id_chat,
					'reply_markup' => $markup,
					'message_id'=>$message_id,
					'text' => '❕ <b>Выберите способ доставки</b>',
					'parse_mode'=>'HTML',
					'disable_web_page_preview'=>true,
					'disable_notification'=>false,
				);

				$data = file_get_contents($url.'/editmessagetext?'.http_build_query($content));		
				set_current('z_step','get_how',$id_chat);	
				exit;
			}elseif($get_phone == 1)
			{
				$button[0][0]['text'] = "\xF0\x9F\x93\x9E  Отправить номер";
				$button[0][0]['request_contact'] = true;
		
				$opt_btn = array('keyboard' => $button,
							'resize_keyboard' => true,
							'one_time_keyboard' => true
				);
				
				$markup = json_encode($opt_btn,true);
				
				$content = array(
					'chat_id' => $id_chat,
					'reply_markup' => $markup,
					'text' => '❕ <b>Укажите номер</b>',
					'parse_mode'=>'HTML',
					'disable_web_page_preview'=>true,
					'disable_notification'=>false,
				);
				
				$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));	
				set_current('z_step','get_phone',$id_chat);
			}else
			{
				set_current('z_step','change_payment',$id_chat);
				$z_step = 'change_payment';
			}
	}
	
	if(strstr($cmd_id,'/how_type:'))
	{
		if($type_payment == 1){ exit; }
		list($com,$id_how) = explode(':',$cmd_id);
		$get_phone = get_setting('get_phone');
		
		set_current('how_method',$id_how,$id_chat);
		
		if($get_phone == 1)
		{
			$button[0][0]['text'] = "\xF0\x9F\x93\x9E Отправить мой номер";
			$button[0][0]['request_contact'] = true;
    
			$opt_btn = array('keyboard' => $button,
						'resize_keyboard' => true,
						'one_time_keyboard' => true
			);
			
			$markup = json_encode($opt_btn,true);
			
			$content = array(
				'chat_id' => $id_chat,
				'reply_markup' => $markup,
				'text' => "❕ <b>Напишите Ваш контактный номер\nили нажмите нажмите Отправить.</b>",
				'parse_mode'=>'HTML',
				'disable_web_page_preview'=>true,
				'disable_notification'=>false,
			);
			
			$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));	

			$message_id = get_current('message_id',$id_chat);
			if(($message_id > 1) )
			{
				$qur = array('message_id'=>$message_id,
							'chat_id'=>$id_chat,
							);
				$qur = '/deleteMessage?'.http_build_query($qur);
				file_get_contents($url.$qur);
				set_current('message_id',0,$id_chat);
				set_current('z_step','get_phone',$id_chat);
			}		
			exit;
		}else
		{
			set_current('z_step','change_payment',$id_chat);
			$z_step = 'change_payment';
		}
	}
	
	if($cmd_id == '/clear_basket')
	{
			if($type_payment == 1){ exit; }
			mysqli_query($db,"DELETE FROM `basket` WHERE `id_chat`= '$id_chat'");
			
			
			$content = array(
				'callback_query_id' => $callback_query_id,
				'show_alert'=>false,
				'text' => '♻️ Корзина очищена!',
			);

			$url_ = "https://api.telegram.org/bot$token/answerCallbackQuery?";
			file_get_contents($url_.http_build_query($content));					
			
			$message_id = get_current('message_id',$id_chat);
			$content = array(
				'chat_id' => $id_chat,
				'message_id'=>$message_id,
				'text' => 'ℹ️ Корзина пуста.',
				'parse_mode'=>'HTML',
				'disable_web_page_preview'=>true,
				'disable_notification'=>false,
			);

			$data = file_get_contents($url.'/editmessagetext?'.http_build_query($content));		
		
	}
	

	if((stristr($cmd_id,'inc_item:')) or (stristr($cmd_id,'dec_item:')))
	{
		list($com,$type,$item) = explode(':',$cmd_id);
		
		$r = mysqli_query($db,"SELECT `count` FROM `basket` WHERE `id_chat` = '$id_chat' AND `id_item` = '$item'");
		$num = mysqli_num_rows($r);
		$bask = mysqli_fetch_assoc($r);
		$count = $bask['count'];
		
		switch($type)
		{
			case '+':
				$count_all = get_item_param($item,'count');	
				if($count+1 <= $count_all ){$count++;} break;
			case '-': 
				if($count > 1){ $count--; }
		}
	
			$info = get_props($item,$id_chat);
			$currency = get_currency_val(1);
			$price = get_item_param($item,'price');
			
			if($count > 0){ $price = $price * $count; }
			$price = number_format($price, 0,'.','.');
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
			$message_id = get_current('message_id',$id_chat);
			$content = array(
				'chat_id' => $id_chat,
				'message_id'=>$message_id,
				'reply_markup' => $markup,
				'text' => $info,
				'parse_mode'=>'HTML',
				'resize_keyboard'=>true,
				'one_time_keyboard' => false,
			);
			
			$x = file_get_contents($url.'/editmessagetext?'.http_build_query($content));		
		
		
		mysqli_query($db,"UPDATE `basket` SET `count` = '$count' WHERE `id_chat` = '$id_chat' AND `id_item` = '$item'");
	}
	
	
	if(stristr($cmd_id,'/payment:'))
	{
			if($type_payment == 1){ exit; }
			list($com,$pay_id) = explode(':',$cmd_id);
			$pay_id = intval($pay_id);
			$name_payment = get_setting_data('name',$pay_id);
			
			
			$r = mysqli_query($db,"SELECT `id_item`,`count` FROM `basket` WHERE `id_chat` = '$id_chat' AND `count` > 0");
			while($items = mysqli_fetch_assoc($r))
			{
				$item = $items['id_item'];
				$count = $items['count'];
				
				$price = get_item_param($item,'price');
				$price_all = $count * $price;
				$all_sum = $all_sum + $price_all;
			}
			
			switch($name_payment)
			{
				case 'Bitcoin':
					$currency = get_currency_val(4);
				break;
				default:
					$currency = get_currency_val(1);
			}
			
			$discount = get_current('discount',$id_chat);
			if($discount > 0)
			{
				$discount = get_proc_sum($all_sum,$discount);
				$discount = $all_sum - $discount;
				if($name_payment == 'Bitcoin'){ $discount = convert_sum($discount); }
				$info_discount = "(вместо <b>$all_sum $currency</b>)";
			}
			
			switch($name_payment)
			{
				case 'Bitcoin':
					$sum = convert_sum($sum);
					$all_sum = convert_sum($all_sum);
					$discount = convert_sum($discount);
					break;
				case 'Dash':
					 $all_sum = convect_currency_($all_sum,'RUR','USD');
					 break;
					 
			}
		#	write('name_payment:'.$name_payment.' | all_sum:'.$all_sum);exit;
			
			$step_get_name = get_setting('step_get_name');
			
			if($step_get_name == 1){ $tmp_data = get_current('tmp_data',$id_chat); } else { $tmp_data = ''; }
			$how = get_current('how_method',$id_chat);
			$tel = get_current('phone',$id_chat);
			$recv = zakaz_create($id_chat,1,$all_sum,0,$pay_id,$tmp_data,$discount,$how,$tel,$id_bot);
			list($id_zakaz,$id_wallet_qiwi) = explode('|',$recv);
			#if($discount > 0){ $all_sum = $discount; }
			
			set_current('id_zakaz',$id_zakaz,$id_chat);
			$r = mysqli_query($db,"SELECT `id_item`,`count` FROM `basket` WHERE `id_chat` = '$id_chat' AND `count` > 0");
			while($items = mysqli_fetch_assoc($r))
			{
				$item = $items['id_item'];
				$count = $items['count'];
				
				$price = get_item_param($item,'price');
				$price_all = $count * $price;
				$name = get_item_param($item,'name');
				
				zakaz_add_items($id_chat,$item,$price,$price_all,$id_zakaz,$count);
				
				if($name_payment == 'Bitcoin')
				{
					$price = convert_sum($price);
					$price_all = convert_sum($price_all);
				} else { $price_all = number_format($price_all, 0,'.','.'); }
				
				$name = get_item_param($item,'name');
				$list_items .= "\n   <b>".$name."</b> - $count шт <b>x</b> ".$price." = <code>".$price_all." $currency.</code>\n<code> -  -  -  - -  -  -  -  -</code>";
				
				$count_all = get_item_param($items['id_item'],'count');
				$count = $count_all - $items['count'];
				mysqli_query($db,"UPDATE `catalog` SET `count` = '$count' WHERE `id` = '".$items['id_item']."'");
			}
			
			$content = array(
				'callback_query_id' => $callback_query_id,
				'show_alert'=>false,
				'text' => "✅ Заказ №$id_zakaz создан!",
			);

			$url_ = "https://api.telegram.org/bot$token/answerCallbackQuery?";
			file_get_contents($url_.http_build_query($content));				

			if($id_wallet_qiwi == 0)
			{
				$wallet = get_setting_data('wallet',$pay_id);
				$name_payment = get_setting_data('name',$pay_id);
			}else
			{
				$wallet = get_multiple_qiwi_data($id_wallet_qiwi,'wallet');
				$name_payment = get_setting_data('name',$pay_id);
			}
			
		#	$wallet = get_setting_data('wallet',$pay_id);
		#	$name_payment = get_setting_data('name',$pay_id);
			
			if($discount > 0){ $all_sum = $discount; }
			$exmo_code = false;
			$inline_m = 0;
			switch($name_payment)
			{
				case 'Card':
					$inline_m = 1;
					$url_pay = "https://money.yandex.ru/transfer?receiver=$wallet&sum=$all_sum&successURL=&quickpay-back-url=&shop-host=&label=$id_zakaz&targets=%D0%9E%D0%BF%D0%BB%D0%B0%D1%82%D0%B0%20%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0%20%23$id_zakaz&comment=$id_zakaz&origin=form&selectedPaymentType=AC&destination=%D0%9E%D0%BF%D0%BB%D0%B0%D1%82%D0%B0%20%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0%20%23$id_zakaz&form-comment=$id_zakaz&short-dest=";
					$info_pay = "<b>Перевод на карту:</b>".' <a href="'.$url_pay.'">Перейти к оплате с карты</a>'."\n\n";
					break;
				case 'Yandex':
					$inline_m = 1;
					$url_pay = "https://money.yandex.ru/transfer?receiver=$wallet&sum=$all_sum&successURL=&quickpay-back-url=&shop-host=&label=$id_zakaz&targets=%D0%9E%D0%BF%D0%BB%D0%B0%D1%82%D0%B0%20%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0%20%23$id_zakaz&comment=$id_zakaz&origin=form&selectedPaymentType=PC&destination=%D0%9E%D0%BF%D0%BB%D0%B0%D1%82%D0%B0%20%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0%20%23$id_zakaz&form-comment=$id_zakaz&short-dest=";
					$info_pay = "<b>Перевод на $name_payment:</b>".' <a href="'.$url_pay.'">Перейти к оплате</a>'."\n\n";
					break;
				case 'Qiwi':
					$inline_m = 1;
					$url_pay = "https://qiwi.com/payment/form/99?extra[%27account%27]=$wallet&extra[%27comment%27]=$id_zakaz&amountInteger=$all_sum&amountFraction=0&currency=643&blocked[1]=account&blocked[2]=comment";
					$info_pay = "<b>Номер счета:</b> <code>$wallet</code>\n";
					break;
				case 'unitpay':
					$pub_key = get_setting_data('pub_key',8);
					$secretKey = get_setting_data('secret_key',8);
					$desc = "Оплата заказа:$id_zakaz";
					$account = $id_zakaz;
					$signature = getFormSignature($account, 'RUB', $desc, $all_sum, $secretKey);
					$url_pay = "https://unitpay.ru/pay/$pub_key?sum=$all_sum&account=$account&desc=$desc&signature=$signature&currency=RUB";
					$info_pay = "<b>Выполните оплату на $name_payment:</b>".' <a href="'.$url_pay.'">Перейти к оплате</a>'."\n\n";	
					$inline_m = 1;
					break;
				case 'Bitcoin':
					$wallet = get_current('btc_address',$id_chat);
					$confirmations_all = get_setting_data('confirmations',4);
					$info_pay = "<b>Номер счета:</b> <code>$wallet</code>\n";
					$confirmations_ico = get_number(0);
					$confirmations_all_ico = get_number($confirmations_all);
					$inline_m = 0;
					$info_pay .= "\n♻️ <b>Статус:</b>  <code>Количество подтверждений:</code> <b>$confirmations_ico / $confirmations_all_ico</b>\n";
				break;
				case 'Exmo-code':
					$exmo_code = true;
					$inline_m = 0;
					$info_pay = '';
					break;
				case 'Free-kassa':
					$merchant_id = get_setting_data('pub_key',9);
					$secret_word = get_setting_data('secret_key',9);
					$sign = md5($merchant_id.':'.$all_sum.':'.$secret_word.':'.$id_zakaz);
					mysqli_query($db,"UPDATE `a_zakaz` SET `signature` = '$sign' WHERE `id` = '$id_zakaz'");
					$url_pay = "https://www.free-kassa.ru/merchant/cash.php?m=$merchant_id&oa=$all_sum&o=$id_zakaz&s=$sign&lang=ru&us_id_zakaz=$id_zakaz";
					$info_pay = "<b>Выполните оплату на $name_payment:</b>".' <a href="'.$url_pay.'">Перейти к оплате</a>'."\n\n";	
					$inline_m = 1;
					break;
					default:
						$inline_m = 0;
						$info_pay = "<b>Номер счета:</b> <code>$wallet</code>\n";
			}
			
						
			$info = "📦 <b>Оплата заказа</b> <code>#$id_zakaz</code>\n\n";
			$info .= "➖ <b>Список покупок:</b> ➖";
			$info .= $list_items."\n\n";
			if(!$exmo_code){ $info .= "📝 <b>Реквизиты оплаты через $name_payment:</b>\n\n"; }
			else
				{ 
					$name_payment = strtoupper($name_payment);
					$info .= "🗞 <b>Способ оплаты:</b> <code>$name_payment</code>\n";
					$info .= "ℹ️ <b>Валюта:</b> <code>RUB</code>\n";
				}
			
			#if($discount > 0){ $all_sum = $discount; }
			$info .= "💰 <b>Сумма для оплаты:</b> <code>$all_sum $currency</code> $info_discount\n";
			$info .= $info_pay;
			#if(($pay_id !== 4) && ($pay_id !== 5) && (!$exmo_code)){ $info .= "<b>Примечание к платежу:</b> <code>$id_zakaz</code>"; }
			if($pay_id == 1){ $info .= "<b>Примечание к платежу:</b> <code>$id_zakaz</code>"; }
			if(!$exmo_code){ $info .= "\n❕ <i>Обработка платежа\Выдача покупки проходит в авто-режиме.</i>"; }
				else
					{ 
						$info .= "\n📥 <b>Отправьте сюда Exmo-код на сумму</b> <code>$all_sum $currency</code>\n\n";
						$info .= "\n❗️ <b>В случае если сумма exmo-кода не совпадет с указанной, вам будет выполнен возврат виде нового exmo-кода.</b> \n";
					}
			
			if($inline_m == 1)
			{
				$buttons_inline['inline_keyboard'][0][0]['text'] = '👉 Перейти к оплатите';
				$buttons_inline['inline_keyboard'][0][0]['url'] = $url_pay;
				$buttons_inline['inline_keyboard'][1][0]['text'] = '❕ Отменить заказ';
				$buttons_inline['inline_keyboard'][1][0]['callback_data'] = '/cancel_zakaz:'.$id_zakaz;
				$markup = json_encode($buttons_inline);
			}elseif($inline_m == 0)
			{
				$buttons_inline['inline_keyboard'][0][0]['text'] = '❕ Отменить заказ';
				$buttons_inline['inline_keyboard'][0][0]['callback_data'] = '/cancel_zakaz:'.$id_zakaz;
				$markup = json_encode($buttons_inline);
			}elseif($inline_m == 3){ $markup = ''; }
					
			$message_id = get_current('message_id',$id_chat);
			$content = array(
				'chat_id' => $id_chat,
				'reply_markup' => $markup,
				'message_id'=>$message_id,
				'text' => $info,
				'parse_mode'=>'HTML',
				'disable_web_page_preview'=>true,
				'disable_notification'=>false,
			);

			$data = file_get_contents($url.'/editmessagetext?'.http_build_query($content));	
			set_current('z_step','wait_payment',$id_chat);
			set_current('pay_id',$pay_id,$id_chat);
			
			mysqli_query($db,"DELETE FROM `basket` WHERE `id_chat`= '$id_chat'");
			exit;
			
	}	
	
	if(stristr($cmd_id,'/cancel_zakaz:'))
	{
		list($com,$id_zakaz) = explode(':',$cmd_id);
		
		$message_id = get_current('message_id',$id_chat);
		$content = array(
			'chat_id' => $id_chat,
			'message_id'=>$message_id,
			'text' => "❌ Заказ #$id_zakaz отменен.",
			'parse_mode'=>'HTML',
			'disable_web_page_preview'=>true,
			'disable_notification'=>false,
		);

		$data = file_get_contents($url.'/editmessagetext?'.http_build_query($content));
		#close_zakaz($id_zakaz);
		
		$r1 = mysqli_query($db,"SELECT `id_item`,`count` FROM `a_zakaz_items` WHERE `id_zakaz` = '".$id_zakaz."'");
		while($items = mysqli_fetch_assoc($r1))
		{
			$count_all = get_item_param($items['id_item'],'count');
			$count = $count_all + $items['count'];
			mysqli_query($db,"UPDATE `catalog` SET `count` = '$count' WHERE `id` = '".$items['id_item']."'");
		}
		
		mysqli_query($db,"UPDATE `a_zakaz` SET `status` = '3' WHERE `id` = '$id_zakaz'");
		set_current('z_step','0',$id_chat);
		$text = '/start';
	}
	
	
	if(stristr($cmd_id,'/my_city:'))
	{
		list($com,$id_city) = explode(':',$cmd_id);
		set_current('id_city',$id_city,$id_chat);
		$text = '/start';
		
		$message_id = get_current('message_id',$id_chat);
		if(($message_id > 1) )
		{
			$qur = array('message_id'=>$message_id,
						'chat_id'=>$id_chat,
						);
			$qur = '/deleteMessage?'.http_build_query($qur);
			file_get_contents($url.$qur);
			set_current('message_id',0,$id_chat);
		}		
		
	}
	
	if(strstr($cmd_id,'/pay_item:'))
	{
		list($com,$id_item,$message_id) = explode(':',$cmd_id);
		$price = get_item_param($id_item,'price');
		$bot_id = get_item_param($id_item,'bot_id');
		$balance = get_current('balance',$id_chat);
		$discount = get_current('discount',$id_chat);
		 
		if($discount > 0)
		{
			$discount_sum = get_proc_sum($price,$discount);
			if($discount_sum > 0){ $price_self = $price - $discount_sum; }
		}else { $price_self = $price; }
		
		if($price_self > $balance)
		{
			$content = array(
				'callback_query_id' => $callback_query_id,
				'show_alert'=>true,
				'text' => "❗️ Недостаточно средств для покупки!",
			);

			$url_ = "https://api.telegram.org/bot$token/answerCallbackQuery?";
			file_get_contents($url_.http_build_query($content));
			exit;
		}
			
		
		if($discount > 0)
		{
			$discount = get_proc_sum($price,$discount);
			if($discount > 0){ $old_price = $price; $price = $price - $discount; }
			set_current('discount',0,$id_chat);
			
			$content = array(
				'callback_query_id' => $callback_query_id,
				'show_alert'=>true,
				'text' => "✔️ Скидка использована, вместо $old_price RUB списано: $price RUB.",
			);

			$url_ = "https://api.telegram.org/bot$token/answerCallbackQuery?";
			file_get_contents($url_.http_build_query($content));
		}
	
		$balance = $balance - $price;
		set_current('balance',$balance,$id_chat);
		
		$count = get_item_param($id_item,'count');
		$count--;
		if($count < 1){ $dis_param = ", `active` = '0'"; } else { $dis_param = ''; }
		
		$list_ = get_item_param($id_item,'content');
		$data_items =  explode("\n",$list_);
		$pay_info = $data_items[0];
		unset($data_items[0]);
		$data_items = implode("\n",$data_items);
		mysqli_query($db,"UPDATE `catalog` SET `count` = '$count', `content` = '$data_items' $dis_param WHERE `id` = '$id_item'");
	
		$info = get_props($id_item,$id_chat);
		
		$buttons_inline['inline_keyboard'][0][0]['text'] = "Купить";
		$buttons_inline['inline_keyboard'][0][0]['callback_data'] = "/pay_item:".$id_item.':'.$message_id;
		
		$markup = json_encode($buttons_inline,true);
		$data = array('chat_id' => $id_chat,
			'reply_markup' => $markup,
			'message_id'=>$message_id,
			'caption' => $info,
			'parse_mode'=>'HTML',
			'disable_notification'=>false,
		);
		$query = $url."/editMessageCaption?".http_build_query($data);
		$json_photo = file_get_contents($query);
		
		
		
		$pay_data = "🔑 <b>Ваша покупка:</b>\n\n";
		$pay_data .= "<code>$pay_info</code>";
		$content = array(
				'chat_id' => $id_chat,
				'text' => $pay_data,
				'parse_mode'=>'HTML',
			);
		file_get_contents($url."/sendmessage?".http_build_query($content));	
		
		$balance = number_format($balance, 0,'.','.');
		$content = array(
				'callback_query_id' => $callback_query_id,
				'show_alert'=>false,
				'text' => "👍 Благодарим за покупку! 💰 Баланс: $balance RUB.",
			);

		$url_ = "https://api.telegram.org/bot$token/answerCallbackQuery?";
		file_get_contents($url_.http_build_query($content));
		
		$recv = zakaz_create($id_chat,2,$price,1,6,'','','','',$bot_id);
		list($id_zakaz,$id_wallet_qiwi) = explode('|',$recv);
		zakaz_add_items($id_chat,$id_item,$price,$price,$id_zakaz,1);
		
		ref_gain($id_chat,$id_zakaz,$bot_id);
		$re = mysqli_query($db,"SELECT `id_item`,`count` FROM `a_zakaz_items` WHERE `id_chat` = '$id_chat' AND `id_zakaz` = '$id_zakaz'");
		while($items = mysqli_fetch_assoc($re))
		{
			reward($items['id_item'],$items['count']);
		}
	}
	
	
	if($cmd_id == '/add_balance')
	{
		set_current('z_step','add_balance',$id_chat);
		
		$buttons_inline['inline_keyboard'][0][0]['text'] = "Отмена";
		$buttons_inline['inline_keyboard'][0][0]['callback_data'] = '/cancel_balance';
		
		$markup = json_encode($buttons_inline);	
		$message_id = get_current('message_id',$id_chat);
		$min_sum_balance = get_setting('min_sum_balance');
		$info = "❕ <b>Укажите сумму пополнения</b>\n\nМинимальная сумма пополнения: <b>$min_sum_balance RUB</b>.";
		$content = array(
				'chat_id' => $id_chat,
				'message_id'=>$message_id,
				'reply_markup' => $markup,
				'text' => $info,
				'parse_mode'=>'HTML',
				'disable_notification'=>true,
			);

		file_get_contents($url."/editmessagetext?".http_build_query($content));
		exit;
	}
	
	if($cmd_id == '/cancel_balance')
	{
		$content = get_content(1);
		$markup = json_encode($buttons_inline);
		
		$r1 = mysqli_query($db,"SELECT `id` FROM `list_referer` WHERE `referer` = '$id_chat'");
		$ref_count = mysqli_num_rows($r1);
		
		$type_referer = get_setting('type_referer');
		if($type_referer == 2)
		{
			$sum = 0;
			$ref_money = 0;
			$r2 = mysqli_query($db,"SELECT `sum` FROM `orders_referers` WHERE `referer` = '$id_chat'");
			while($ref = mysqli_fetch_assoc($r2))
			{
				$ref_money = $sum + $ref['sum'];
			}
			$ref_money = number_format($ref_money, 0,'.','.');
		}else{ $discount = get_current('discount',$id_chat); }
		$gain_proc = get_setting('gain_proc');
		
		
		
		
		$arr[0] = '{name}';
		$arr[1] = '{username}';
		$arr[2] = '{id_chat}';
		$arr[3] = '{all_referer}';
		$arr[6] = '{discount}';
		$arr[7] = '{ref_money}';
 		$arr[8] = '{gain_proc}';
		$arr[9] = '{day_sub}';
		$arr[10] = '{USER_BALANCE}';
		
		$arr2[0] = '<code>'.$user.'</code>';
		$arr2[1] = '<code>'.$username.'</code>';
		$arr2[2] = '<code>'.$id_chat.'</code>';
		$arr2[3] = '<code>'.$ref_count.'</code>';
		if($type_referer == 1){ $arr2[6] = '👥 <b>Скидка:</b> <code>'.$discount.'%</code>'; $arr2[7] = ''; }
			else{ $arr2[7] = '💴 <b>Заработано:</b> <code>'.$ref_money." RUB</code>"; $arr2[6] = ''; }
		$arr2[8] = '<code>'.$gain_proc.'%</code>';
		$arr2[9] = "<code>".$day_sub.'</code>';
		if($type_payment == 1)
		{
			$balance = get_current('balance',$id_chat);
			$balance = number_format($balance, 0,'.','.');
			$arr2[10] = "💰 <b>Мой баланс</b> <code>$balance RUB</code>";
			
			$buttons_inline['inline_keyboard'][0][0]['text'] = "➕ Пополнить баланс";
			$buttons_inline['inline_keyboard'][0][0]['callback_data'] = '/add_balance';
			$buttons_inline['inline_keyboard'][0][1]['text'] = "📃 История";
			$buttons_inline['inline_keyboard'][0][1]['callback_data'] = '/history_balance';
		}else { $arr2[10] = ''; }
		
		$info = str_replace($arr,$arr2,$content);
	
		$markup = json_encode($buttons_inline);
		$message_id = get_current('message_id',$id_chat);
		$content = array(
				'chat_id' => $id_chat,
				'message_id'=>$message_id,
				'reply_markup' => $markup,
				'text' => $info,
				'parse_mode'=>'HTML',
				'disable_notification'=>true,
			);
		
		$data = file_get_contents($url."/editmessagetext?".http_build_query($content));
		
		set_current('z_step','',$id_chat);
		exit;
	}
	
	if(strstr($cmd_id,'/payment_balance:'))
	{
		list($com,$pay_id) = explode(':',$cmd_id);
		$all_sum = get_current('amount_add',$id_chat);
		
		if($pay_id == 4){ $btc_address = get_current('btc_address',$id_chat); $btc_amount = convert_sum($all_sum); } else { $btc_address = ''; }
		
		$time = time();
		mysqli_query($db,"INSERT INTO `payment_users` (`id_chat`,`amount`,`date`,`pay_type`,`btc_address`,`btc_amount`,`bot_id`) 
								VALUES ('$id_chat','$all_sum','$time','$pay_id','$btc_address','$btc_amount','$id_bot')");
		$id_zakaz = mysqli_insert_id($db);
		set_current('id_zakaz',$id_zakaz,$id_chat);
		
		$pay_id = intval($pay_id);
		$wallet = get_setting_data('wallet',$pay_id);
		$name_payment = get_setting_data('name',$pay_id);
		
		
		$qiwi_method = get_setting('qiwi_method');
		if(($pay_id == 1) && ($qiwi_method == 2))
		{
			$id_wallet_qiwi = get_wallet_qiwi($all_sum);
			$wallet = get_multiple_qiwi_data($id_wallet_qiwi,'wallet');
			$name_payment = get_setting_data('name',$pay_id);
		}else
		{
			$wallet = get_setting_data('wallet',$pay_id);
			$name_payment = get_setting_data('name',$pay_id);
		}
		
		
		switch($name_payment)
		{
			case 'Bitcoin':
				$currency = get_currency_val(4);
				$all_sum = convert_sum($all_sum);
			break;
			default:
				$currency = get_currency_val(1);
		}
		
		$exmo_code = false;
		$inline_m = 0;
		switch($name_payment)
		{
			case 'Card':
				$inline_m = 1;
				$url_pay = "https://money.yandex.ru/transfer?receiver=$wallet&sum=$all_sum&successURL=&quickpay-back-url=&shop-host=&label=$id_zakaz&targets=%D0%9E%D0%BF%D0%BB%D0%B0%D1%82%D0%B0%20%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0%20%23$id_zakaz&comment=$id_zakaz&origin=form&selectedPaymentType=AC&destination=%D0%9E%D0%BF%D0%BB%D0%B0%D1%82%D0%B0%20%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0%20%23$id_zakaz&form-comment=$id_zakaz&short-dest=";
				$info_pay = "<b>Перевод на карту:</b>".' <a href="'.$url_pay.'">Перейти к оплате с карты</a>'."\n";
				break;
			case 'Yandex':
				$inline_m = 1;
				$url_pay = "https://money.yandex.ru/transfer?receiver=$wallet&sum=$all_sum&successURL=&quickpay-back-url=&shop-host=&label=$id_zakaz&targets=%D0%9E%D0%BF%D0%BB%D0%B0%D1%82%D0%B0%20%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0%20%23$id_zakaz&comment=$id_zakaz&origin=form&selectedPaymentType=PC&destination=%D0%9E%D0%BF%D0%BB%D0%B0%D1%82%D0%B0%20%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0%20%23$id_zakaz&form-comment=$id_zakaz&short-dest=";
				$info_pay = "<b>Перевод на $name_payment:</b>".' <a href="'.$url_pay.'">Перейти к оплате</a>'."\n";
				break;
			case 'Qiwi':
				$inline_m = 1;
				$url_pay = "https://qiwi.com/payment/form/99?extra[%27account%27]=$wallet&extra[%27comment%27]=$id_zakaz&amountInteger=$all_sum&amountFraction=0&currency=643&blocked[1]=account&blocked[2]=comment";
				$info_pay = "<b>Счет:</b> <code>$wallet</code>\n";
				break;
			case 'Bitcoin':
				$wallet = get_current('btc_address',$id_chat);
				$confirmations_all = get_setting_data('confirmations',4);
				$info_pay = "<b>Номер счета:</b> <code>$wallet</code>\n";
				$confirmations_ico = get_number(0);
				$confirmations_all_ico = get_number($confirmations_all);
				$inline_m = 0;
				$info_pay .= "\n♻️ <b>Статус:</b>  <code>Количество подтверждений:</code> <b>$confirmations_ico / $confirmations_all_ico</b>\n";
				break;
			case 'unitpay':
				$pub_key = get_setting_data('pub_key',8);
				$secretKey = get_setting_data('secret_key',8);
				$desc = "Пополнение баланса | ID:$id_zakaz";
				$account = $id_zakaz;
				$signature = getFormSignature($account, 'RUB', $desc, $all_sum, $secretKey);
				$url_pay = "https://unitpay.ru/pay/$pub_key?sum=$all_sum&account=$account&desc=$desc&signature=$signature&currency=RUB";
				$info_pay = "<b>Выполните оплату на $name_payment:</b>".' <a href="'.$url_pay.'">Перейти к оплате</a>'."\n\n";	
				$inline_m = 1;
				break;
			case 'Free-kassa':
				$merchant_id = get_setting_data('pub_key',9);
				$secret_word = get_setting_data('secret_key',9);				
				$sign = md5($merchant_id.':'.$all_sum.':'.$secret_word.':'.$id_zakaz);
				mysqli_query($db,"UPDATE `payment_users` SET `signature` = '$sign' WHERE `id` = '$id_zakaz'");
				$url_pay = "https://www.free-kassa.ru/merchant/cash.php?m=$merchant_id&oa=$all_sum&o=$id_zakaz&s=$sign&lang=ru&us_id_zakaz=$id_zakaz";
				$info_pay = "<b>Выполните оплату на $name_payment:</b>".' <a href="'.$url_pay.'">Перейти к оплате</a>'."\n\n";	
				$inline_m = 1;
		}
		
					
		$info = "📦 <b>Пополнение баланса</b>\n\n";
		$name_payment = strtoupper($name_payment);
		
		if($pay_id !== 4){$all_sum = number_format($all_sum, 0,'.','.'); }
		$info .= "<b>Сумма:</b> <code>$all_sum $currency</code>\n";
		$info .= $info_pay;
		if($pay_id == 1){ $info .= "<b>Примечание к платежу:</b> <code>$id_zakaz</code>"; }
		$info .= "\n\n❕ <i>При поступлении платежа, вы получите уведомление.</i>";
		
		if($inline_m == 1)
		{
			$buttons_inline['inline_keyboard'][0][0]['text'] = '💰 Пополнить';
			$buttons_inline['inline_keyboard'][0][0]['url'] = $url_pay;
			$buttons_inline['inline_keyboard'][0][1]['text'] = 'Отменить';
			$buttons_inline['inline_keyboard'][0][1]['callback_data'] = '/cancel_payment_balance';
			$markup = json_encode($buttons_inline);
		}elseif($inline_m == 0)
		{
			$buttons_inline['inline_keyboard'][0][0]['text'] = '❕ Отменить';
			$buttons_inline['inline_keyboard'][0][0]['callback_data'] = '/cancel_payment_balance';
			$markup = json_encode($buttons_inline);
		}elseif($inline_m == 3){ $markup = ''; }
				
		$message_id = get_current('message_id',$id_chat);
		$content = array(
			'chat_id' => $id_chat,
			'reply_markup' => $markup,
			'message_id'=>$message_id,
			'text' => $info,
			'parse_mode'=>'HTML',
			'disable_web_page_preview'=>true,
			'disable_notification'=>false,
		);

		$data = file_get_contents($url.'/editmessagetext?'.http_build_query($content));	
		set_current('z_step','wait_payment_balance',$id_chat);
		
		
	}
	
	if($cmd_id == '/cancel_payment_balance')
	{
		$id_zakaz = get_current('id_zakaz',$id_chat);
		if($id_zakaz < 1){ exit; }
		
		mysqli_query($db,"UPDATE `payment_users` SET `status` = '1' WHERE `id` = '$id_zakaz'");
		
		$r = mysqli_query($db,"SELECT `id`,`name_service` FROM `setting_payment` WHERE `active` = '0'");
		$x = 0;
		while($list = mysqli_fetch_assoc($r))
		{
			$buttons_inline['inline_keyboard'][$x][0]['text'] = $list['name_service'];
			$buttons_inline['inline_keyboard'][$x][0]['callback_data'] = '/payment_balance:'.$list['id'];
			$x++;
		
		}
		
		$buttons_inline['inline_keyboard'][$x][0]['text'] = "Назад";
		$buttons_inline['inline_keyboard'][$x][0]['callback_data'] = '/cancel_balance';
		
		$info = "➖ <b>Выберите споcоб пополнения:</b>";
		$message_id = get_current('message_id',$id_chat);
		$markup = json_encode($buttons_inline);
	
		$content = array(
				'chat_id' => $id_chat,
				'message_id'=>$message_id,
				'reply_markup' => $markup,
				'text' => $info,
				'parse_mode'=>'HTML',
				'disable_notification'=>true,
			);

		$data = file_get_contents($url."/editmessagetext?".http_build_query($content));	
		
		set_current('z_step','',$id_chat);
		
	}
	
	if($cmd_id == '/history_balance')
	{
		$r = mysqli_query($db,"SELECT `id`,`amount`,`status`,`date`,`pay_type`,`btc_amount` FROM `payment_users` WHERE `id_chat` = '$id_chat'");
		$all = mysqli_num_rows($r);
		if($all == 0)
		{
			$content = array(
				'callback_query_id' => $callback_query_id,
				'show_alert'=>false,
				'text' => '❗️ Операции отсуствуют',
			);

			$url_ = "https://api.telegram.org/bot$token/answerCallbackQuery?";
			file_get_contents($url_.http_build_query($content));
			exit;
		}
		$buttons_inline['inline_keyboard'][0][0]['text'] = "Назад";
		$buttons_inline['inline_keyboard'][0][0]['callback_data'] = '/cancel_balance';
		
		$status_x[0] = 'Ожидает';
		$status_x[1] = 'Отменен';
		$status_x[2] = 'Оплачен';
		
		$ico_x[0] = '⏳';
		$ico_x[1] = '❌';
		$ico_x[2] = '✔️';
		
		
		while($list = mysqli_fetch_assoc($r))
		{
			$amount = $list['amount'];
			if($list['pay_type'] == 4){ $amount = $list['btc_amount']; }
			$currency = get_currency_val($list['pay_type']);
			$date = date('d.m.Y',$list['date']);
			$status = $status_x[$list['status']];
			$ico = $ico_x[$list['status']];
			
			
			$mylist .= "$ico Сумма: <code>$amount $currency</code> | Стасус: <b>$status</b> | Дата: <code>$date</code>\n";
			
		}
		
		$info = "➖ <b>История операций:</b>\n\n$mylist";
		
		$message_id = get_current('message_id',$id_chat);
		$markup = json_encode($buttons_inline);
	
		$content = array(
				'chat_id' => $id_chat,
				'message_id'=>$message_id,
				'reply_markup' => $markup,
				'text' => $info,
				'parse_mode'=>'HTML',
				'disable_notification'=>true,
			);

		$data = file_get_contents($url."/editmessagetext?".http_build_query($content));	
	}

		# END CALLBACK
		
	}	

	$new = new_user($user,$username,$id_chat,$referer,$id_bot);
	#if($new == true){ exit; }
	static_add($id_chat);
	
	###################################################################
	
	###################################################################
	
	
	
	if(strstr($text,' '))
	{
		list($unicode,$text_,$n2,$n3,$n4) = explode(' ',$text);
		$unicode_ = base64_encode($unicode);
		$s = "SELECT `id` FROM `unicode` WHERE `u` = '$unicode_'";
		$un = mysqli_query($db,$s);
		$num_u = mysqli_num_rows($un);
		if($num_u > 0)
		{
			$arr_ = explode(' ',$text);
			for($w=1;$w<count($arr_);$w++)
			{  
				if(strlen($arr_[$w]) > 0){$st .= $arr_[$w].' '; }
			}
			$text = trim($st);
		}
	}
	#write('text:'.$text);
	
	if($text == '/qiwi_send')
	{
		$en_ref = get_setting('type_referer');
		if($en_ref == 1){ exit; }

		$r2 = mysqli_query($db,"SELECT `sum` FROM `orders_referers` WHERE `referer` = '$id_chat'");
		while($ref = mysqli_fetch_assoc($r2))
		{
			$ref_money = $ref_money + $ref['sum'];
		}
		
		if($ref_money < 100)
		{
			$info = "❗️ Сумма вывода должна быть больше <b>100 RUB</b>.";
			$content = array(
					'chat_id' => $id_chat,
					'text' => $info,
					'parse_mode'=>'HTML',
					'disable_notification'=>true,
				);

			$x = file_get_contents($url."/sendmessage?".http_build_query($content));
			exit;
		}
		
		set_current('z_step','get_sum',$id_chat);
		
		$content = array(
				'chat_id' => $id_chat,
				'text' => '❗️ Укажите сумму вывода.',
				'parse_mode'=>'HTML',
				'disable_web_page_preview'=>true,
				'disable_notification'=>false,
			);		
		$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
		exit;	
		
	}
	
	if(($text == 'Статистика') && ($id_chat == $id_chat_admin))
	{	
		$good_r = mysqli_query($db,"SELECT `id` FROM `Users` WHERE `lock` = '0' AND `id_bot` = '0'");
		$lock_r = mysqli_query($db,"SELECT `id` FROM `Users` WHERE `lock` = '1' AND `id_bot` = '0'");
		$all_r = mysqli_query($db,"SELECT `id` FROM `Users` WHERE  `id_bot` = '0'");
		
		$good = mysqli_num_rows($good_r);
		$lock = mysqli_num_rows($lock_r);
		$all = mysqli_num_rows($all_r);
		$date = date('Y.m.d h:i:s');
		$info = "📊 <b>СТАТИСТИКА ЗА $date:</b>\n\n➕ Подписано: <b>$good</b>\n➖ Отписано: <b>$lock</b>\n👁‍🗨 Всего: <b>$all</b>";
		$content = array(
				'chat_id' => $id_chat,
				'text' => $info,
				'parse_mode'=>'HTML',
				'disable_notification'=>true,
			);

		$x = file_get_contents($url."/sendmessage?".http_build_query($content));
		exit;
	}
		
/*
	if($text == 'Оформить')
	{
			$step_get_name = get_setting('step_get_name');
			$update_step_text = 0;
			if($step_get_name == 1)
			{
				$step_text = get_setting('step_text');
			
				$content = array(
					'chat_id' => $id_chat,
					'text' => $step_text,
					'parse_mode'=>'HTML',
					'disable_web_page_preview'=>true,
					'disable_notification'=>false,
				);

				$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));	
				$json = json_decode($data,true);
				$message_id  = $json['result']['message_id'];
				if($message_id > 0){ set_current('message_id',$message_id,$id_chat); }	
				set_current('z_step','step_text',$id_chat);	
				exit;
			}else
			{
				set_current('z_step','change_payment',$id_chat);
				$update_step_text = 1;
			}
	}
	*/	
	if($text == 'Отменить')
	{
		if($type_payment == 1){ exit; }
		set_current('z_step',0,$id_chat);
		set_current('limit',0,$id_chat);
		set_current('id_zakaz',0,$id_chat);
		
		$text = '/start';
		$cmd_id = 0;

		$message_id = get_current('message_id',$id_chat);
		if(($message_id > 1) )
		{
			$qur = array('message_id'=>$message_id,
						'chat_id'=>$id_chat,
						);
			$qur = '/deleteMessage?'.http_build_query($qur);
			file_get_contents($url.$qur);
			set_current('message_id',0,$id_chat);
		}	

		$id_zakaz = get_current('id_zakaz',$id_chat);
		$r1 = mysqli_query($db,"SELECT `id_item`,`count` FROM `a_zakaz_items` WHERE `id_zakaz` = '".$id_zakaz."'");
		while($items = mysqli_fetch_assoc($r1))
		{
			$count_all = get_item_param($items['id_item'],'count');
			$count = $count_all + $items['count'];
			mysqli_query($db,"UPDATE `catalog` SET `count` = '$count' WHERE `id` = '".$items['id_item']."'");
		}
		
	}
	
	$z_step = get_current('z_step',$id_chat);
	
	if($z_step == 'add_balance')
	{
		$min_sum_balance = get_setting('min_sum_balance');
		$sum = intval($text);
		if($sum < $min_sum_balance)
		{
			$buttons_inline['inline_keyboard'][0][0]['text'] = "Отмена";
			$buttons_inline['inline_keyboard'][0][0]['callback_data'] = '/cancel_balance';
		
			$markup = json_encode($buttons_inline);	
			$info = "❕ <b>Укажите сумму пополнения</b>\n\n❗️ Минимальная сумма пополнения: <b>$min_sum_balance RUB</b>.";
			$content = array(
					'chat_id' => $id_chat,
					'reply_markup' => $markup,
					'text' => $info,
					'parse_mode'=>'HTML',
					'disable_notification'=>true,
				);

			$data = file_get_contents($url."/sendmessage?".http_build_query($content));
			$json = json_decode($data,true);
			$message_id  = $json['result']['message_id'];
			if($message_id > 0){ set_current('message_id',$message_id,$id_chat); }	
			exit;
		}
		
		set_current('amount_add',$text,$id_chat);
		$r = mysqli_query($db,"SELECT `id`,`name_service` FROM `setting_payment` WHERE `active` = '0'");
		$x = 0;
		while($list = mysqli_fetch_assoc($r))
		{
			$buttons_inline['inline_keyboard'][$x][0]['text'] = $list['name_service'];
			$buttons_inline['inline_keyboard'][$x][0]['callback_data'] = '/payment_balance:'.$list['id'];
			$x++;
		
		}
		
		$buttons_inline['inline_keyboard'][$x][0]['text'] = "Отмена";
		$buttons_inline['inline_keyboard'][$x][0]['callback_data'] = '/cancel_balance';
		
		$info = "➖ <b>Выберите споcоб пополнения:</b>";
		$message_id = get_current('message_id',$id_chat);
		$markup = json_encode($buttons_inline);
	
		$content = array(
				'chat_id' => $id_chat,
				'reply_markup' => $markup,
				'text' => $info,
				'parse_mode'=>'HTML',
				'disable_notification'=>true,
			);

		$data = file_get_contents($url."/sendmessage?".http_build_query($content));
		$json = json_decode($data,true);
		$message_id  = $json['result']['message_id'];
		if($message_id > 0){ set_current('message_id',$message_id,$id_chat); }
		exit;
	}
	
	if($z_step == 'get_sum')
	{

		$sum = intval($text);
		if($sum < 1)
		{	
			$content = array(
				'chat_id' => $id_chat,
				'text' => '❗️ Укажите сумму вывода.',
				'parse_mode'=>'HTML',
				'disable_web_page_preview'=>true,
				'disable_notification'=>false,
			);		
			$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
			exit;	
		}
		
		if($sum < 100)
		{
			$info = "❗️ Сумма вывода должна быть больше <b>100 RUB</b>.";
			$content = array(
					'chat_id' => $id_chat,
					'text' => $info,
					'parse_mode'=>'HTML',
					'disable_notification'=>true,
				);

			$x = file_get_contents($url."/sendmessage?".http_build_query($content));
			exit;
		}
		
		#$st = 0;
		$r2 = mysqli_query($db,"SELECT `sum` FROM `orders_referers` WHERE `referer` = '$id_chat'");
		while($ref = mysqli_fetch_assoc($r2))
		{
			$ref_money = $ref_money + $ref['sum'];
		}
		
		if($sum > $ref_money)
		{	
			$content = array(
				'chat_id' => $id_chat,
				'text' => '❗️ Данной суммы у вас нет, укажите меньше.',
				'parse_mode'=>'HTML',
				'disable_web_page_preview'=>true,
				'disable_notification'=>false,
			);		
			$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
			exit;	
		}
		
		set_current('qiwi_sum',$text,$id_chat);
		set_current('z_step','get_wallet',$id_chat);
		
		$content = array(
				'chat_id' => $id_chat,
				'text' => 'ℹ️ Укажите ваш <b>Qiwi</b> счет,фомата <code>79XXXXXXXXX</code>',
				'parse_mode'=>'HTML',
				'disable_web_page_preview'=>true,
				'disable_notification'=>false,
			);		
		$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
		exit;
	}
	
	if($z_step == 'get_wallet')
	{
		if(strlen($text) < 9)
		{	
			$content = array(
				'chat_id' => $id_chat,
				'text' => 'ℹ️ Укажите ваш <b>Qiwi</b> счет,фомата <code>79XXXXXXXXX</code>',
				'parse_mode'=>'HTML',
				'disable_web_page_preview'=>true,
				'disable_notification'=>false,
			);		
			$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
			
			exit;	
		}
		set_current('qiwi_wallet',$text,$id_chat);
		
		$comment = "@$bot_username. Вывод средств";
		$qiwi_sum = get_current('qiwi_sum',$id_chat); 
		if(send_money($qiwi_sum,$comment,$text))
		{
			$content = array(
				'chat_id' => $id_chat,
				'text' => '✅ Сумма в размере<b>$qiwi_sum</b> переведена на ваш счет!',
				'parse_mode'=>'HTML',
				'disable_web_page_preview'=>true,
				'disable_notification'=>false,
			);		
			$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
		}else
			{
				$content = array(
					'chat_id' => $id_chat,
					'text' => '❗️ Ошибка при попытке перевода, попробуйте позже.',
					'parse_mode'=>'HTML',
					'disable_web_page_preview'=>true,
					'disable_notification'=>false,
				);		
				$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
				
			}
		set_current('z_step',0,$id_chat);
		exit;
	}
	
	if($z_step == 'step_text')
	{	if($type_payment == 1){ exit; }
		if((strlen($text) < 6) )
		{	
			$content = array(
				'chat_id' => $id_chat,
				'text' => 'Напишете больше 6 сивмолов!',
				'parse_mode'=>'HTML',
				'disable_web_page_preview'=>true,
				'disable_notification'=>false,
			);		
			$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
			
			exit;	
		}

		set_current('tmp_data',$text,$id_chat);
		#set_current('z_step','get_how',$id_chat);
		$z_step = 'step_text';
	}
		
	if(($z_step == 'step_text') or ($text == 'Оформить'))
	{		if($type_payment == 1){ exit; }
			$step_get_name = get_setting('step_get_name');
			$how = get_setting('how');
			$get_phone = get_setting('get_phone');
			
			if($text == 'Оформить'){ $z_step = 'step_text'; }

			if(($step_get_name == 1) && ($z_step == 'step_text') && ($text == 'Оформить'))
			{
				$step_text = get_setting('step_text');
			
				$content = array(
					'chat_id' => $id_chat,
					'text' => $step_text,
					'parse_mode'=>'HTML',
					'disable_web_page_preview'=>true,
					'disable_notification'=>false,
				);

				$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));		
				#if($how == 1){ $step_x = 'get_how'; } elseif($get_phone == 0) { $step_x = 'change_payment'; }
				
				#set_current('z_step',$step_x,$id_chat);
				set_current('z_step','step_text',$id_chat);
				exit;
			}
			
			if($how == 1)
			{
				$item_x = 0;
				$r1 = mysqli_query($db,"SELECT `id`,`name` FROM `how_delivery`");
				while($how = mysqli_fetch_assoc($r1))
				{
					$buttons_inline['inline_keyboard'][$item_x][0]['text'] = '✅ '.$how['name'];
					$buttons_inline['inline_keyboard'][$item_x][0]['callback_data'] = '/how_type:'.$how['id'];
					$item_x++;
				}
				
				$markup = json_encode($buttons_inline);
				
				if(($z_step == 'step_text')){ $action = 'sendmessage'; $message_id = ''; } 
				else 
				{ 
					$action = 'editmessagetext';
					$message_id = get_current('message_id',$id_chat); 
				} 
			
				$content = array(
					'chat_id' => $id_chat,
					'reply_markup' => $markup,
					'message_id'=>$message_id,
					'text' => '❕ <b>Выберите способ доставки</b>',
					'parse_mode'=>'HTML',
					'disable_web_page_preview'=>true,
					'disable_notification'=>false,
				);

				$data = file_get_contents($url.'/'.$action.'?'.http_build_query($content));
				$json = json_decode($data,true);
				$message_id  = $json['result']['message_id'];
				if($message_id > 0){ set_current('message_id',$message_id,$id_chat); }	
				
				set_current('z_step','get_how',$id_chat);	
			}elseif($get_phone == 1)
			{
				$button[0][0]['text'] = "\xF0\x9F\x93\x9E  Отправить номер";
				$button[0][0]['request_contact'] = true;
		
				$opt_btn = array('keyboard' => $button,
							'resize_keyboard' => true,
							'one_time_keyboard' => true
				);
				
				$markup = json_encode($opt_btn,true);
				
				$content = array(
					'chat_id' => $id_chat,
					'reply_markup' => $markup,
					'text' => '❕ <b>Укажите номер</b>',
					'parse_mode'=>'HTML',
					'disable_web_page_preview'=>true,
					'disable_notification'=>false,
				);
				
				$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));	
				set_current('z_step','get_phone',$id_chat);
			}else
			{
				set_current('z_step','change_payment',$id_chat);
				set_current('tmp_data',$text,$id_chat);
				$z_step = 'change_payment';
			}
	}


	if(($z_step == 'get_phone') && (strlen($text) > 0))
	{	if($type_payment == 1){ exit; }
		$tel = $text;
		if(strlen($tel) < 11)
		{
			$content = array(
				'chat_id' => $id_chat,
				'text' => '❗️ <b>Укажите номер в правильном формате.</b>',
				'parse_mode'=>'HTML',
				'resize_keyboard'=>true,
				'one_time_keyboard' => false,
			);
			file_get_contents($url."/sendmessage?".http_build_query($content));
			exit;
		}
		
		set_current('phone',$tel,$id_chat);
		set_current('z_step','change_payment',$id_chat);
		$z_step = 'change_payment';
	}

	if($z_step == 'change_payment')
	{	if($type_payment == 1){ exit; }
		$step_get_name = get_setting('step_get_name');
		
		$message_id = get_current('message_id',$id_chat);
		if(($message_id > 1) && ($step_get_name == 1))
		{
			$qur = array('message_id'=>$message_id,
						'chat_id'=>$id_chat,
						);
			$qur = '/deleteMessage?'.http_build_query($qur);
			file_get_contents($url.$qur);
		}		
		
		
		#if($step_get_name == 1)
		#{
			$button[0][0] = '➖ Отменить';
			$param = array('keyboard' => $button, 'resize_keyboard' => true, 'one_time_keyboard' => false);
			$encodedMarkup = json_encode($param);
			
			$content = array(
				'chat_id' => $id_chat,
				'reply_markup' => $encodedMarkup,
				'text' => 'ℹ️ <b>Создание заказа:</b>',
				'parse_mode'=>'HTML',
				'resize_keyboard'=>true,
				'one_time_keyboard' => false,
			);
			file_get_contents($url."/sendmessage?".http_build_query($content));			
		#}
		$r = mysqli_query($db,"SELECT `id`,`name_service` FROM `setting_payment` WHERE `active` = '0'");
		$x = 0;
		while($list = mysqli_fetch_assoc($r))
		{
			$buttons_inline['inline_keyboard'][$x][0]['text'] = '✅ '.$list['name_service'];
			$buttons_inline['inline_keyboard'][$x][0]['callback_data'] = '/payment:'.$list['id'];
			$x++;
		
		}
			$info = "➖ <b>Выберите споcоб оплаты</b>";
			$message_id = get_current('message_id',$id_chat);
			$markup = json_encode($buttons_inline);
			
			#if(($step_get_name == 2) && ($update_step_text == 0) && (empty($text))){ $action = 'editmessagetext'; } else { $action = 'sendmessage'; }
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

			$data = file_get_contents($url."/$action?".http_build_query($content));		
			$json = json_decode($data,true);
			$message_id  = $json['result']['message_id'];
			if($message_id > 0){ set_current('message_id',$message_id,$id_chat); }
			exit;	
		
	}


	if(($z_step == 'wait_payment') && (stristr($text,'EX-CODE_')))
	{
		if(strlen($text) < 51)
		{
			$info = '❗️ <b>EX-CODE</b> указан в неверном формате!';
			$content = array(
				'chat_id' => $id_chat,
				'text' => $info,
				'parse_mode'=>'HTML',
				'disable_web_page_preview'=>true,
				'disable_notification'=>false,
			);

			$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
			exit;
		}
		
		$id_zakaz = get_current('id_zakaz',$id_chat);
		$zak = mysqli_query($db,"SELECT `suma`,`discount` FROM `a_zakaz` WHERE `id` = '$id_zakaz'");			
		$ok_zak = mysqli_fetch_assoc($zak);
		$all_sum = $ok_zak['suma'];
		if($ok_zak['discount'] > 0){ $all_sum = $ok_zak['discount']; }
	
		$code = $text;
		$rec = api_query("excode_load", Array(
			"code"=>$code
		));
		
		$task_id_api = $rec['task_id'];
		$currency = $rec['currency'];
		$amount = $rec['amount'];
		$error = $rec['error'];

		if(($all_sum <> $amount) && (empty($error)) && ($task_id_api > 0) && ($currency == 'RUB'))
		{

			$info = "❗️ Техническая.ошибка, обратитесь к администратору магазина.";
			$content = array(
				'chat_id' => $id_chat,
				'text' => $info,
				'parse_mode'=>'HTML',
				'disable_web_page_preview'=>true,
				'disable_notification'=>false,
			);

			$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
			exit;
		}
	
		if(!empty($error))
		{
			if(stristr($error,'Error 40013')){ $info = '❗️ <b>Неверный код EX-CODE</b>'; }
				elseif(stristr($error,'Error 10104')){ $info = '❗️ <b>EX-CODE код недействителен.</b>'; }
					else { $info = '❗️ <b>Error</b>'; }
			
			$content = array(
				'chat_id' => $id_chat,
				'text' => $info,
				'parse_mode'=>'HTML',
				'disable_web_page_preview'=>true,
				'disable_notification'=>false,
			);

			$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
			exit;
		}
	/*
		if(($currency !== 'RUB') && ($task_id_api > 0))
		{
			$create = api_query("excode_create", Array(
				"currency"=>$currency,
				"amount"=>$amount
			));
	
			if($create['result'])
			{
				$code = $create['code'];
				
				$info = '❗️ Возврат <b>EX-CODE</b>\n\nВаш ex-code в валюте <b>$currency</b>, требуемая валюта <b>RUB</b>\n';
				$info .= "<b>ВАШ EX-CODE:</b> <code>$code</code>";
				$content = array(
					'chat_id' => $id_chat,
					'text' => $info,
					'parse_mode'=>'HTML',
					'disable_web_page_preview'=>true,
					'disable_notification'=>false,
				);

				$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
				exit;
			}else
			{
				$info = "❗️ Техническая.ошибка, обратитесь к администратору магазина.";
				$content = array(
					'chat_id' => $id_chat,
					'text' => $info,
					'parse_mode'=>'HTML',
					'disable_web_page_preview'=>true,
					'disable_notification'=>false,
				);

				$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
				exit;		
			}
		}
	*/
		if($rec['result'])
		{
			include('ex_code_pay.php');
		}
		
		exit;
	}
	
	
	if($z_step == 'wait_payment')
	{	if($type_payment == 1){ exit; }
		$message_id = get_current('message_id',$id_chat);
		if(($message_id > 1) )
		{
			$qur = array('message_id'=>$message_id,
						'chat_id'=>$id_chat,
						);
			$qur = '/deleteMessage?'.http_build_query($qur);
			file_get_contents($url.$qur);
		}
		
			$pay_id = get_current('pay_id',$id_chat);
			$pay_id = intval($pay_id);
			$name_payment = get_setting_data('name',$pay_id);
			$id_zakaz = get_current('id_zakaz',$id_chat);
			
			switch($name_payment)
			{
				case 'Bitcoin':
					$currency = get_currency_val(4);
				break;
				default:
					$currency = get_currency_val(1);
			}
			
			
			$r = mysqli_query($db,"SELECT `id_item`,`count`,`sum`,`all_sum`
								FROM `a_zakaz_items` WHERE `id_chat` = '$id_chat' AND `id_zakaz` = '$id_zakaz'");
			while($items = mysqli_fetch_assoc($r))
			{
				$item = $items['id_item'];
				$count = $items['count'];
				
				$price = $items['sum']; #get_item_param($item,'price');
				$price_all = $items['all_sum']; #$count * $price;
				$amount_all = $amount_all + $price_all;
				$name = get_item_param($item,'name');
				/*if($name_payment == 'Bitcoin')
				{
					$price = convert_sum($price);
					$price_all = convert_sum($price_all);
				}else{ $price_all = number_format($price_all, 2,'.','.'); }
				*/
				
				if($pay_id !== 4){ $price_all = number_format($price_all, 0,'.','.'); }
				
				$name = get_item_param($item,'name');
				$list_items .= "\n   <b>".$name."</b> - $count шт <b>x</b> ".$price." = <code>".$price_all." $currency.</code>\n<code> -  -  -  - -  -  -  -  -</code>";
				
			}
			#$sum = $all_sum;
			
			if($pay_id !== 4){ $sum = number_format($sum, 0,'.','.'); }

			
			$ds = mysqli_query($db,"SELECT `suma`,`discount`,`id_wallet_qiwi`  FROM `a_zakaz` WHERE `id` = '$id_zakaz'");
			$disc = mysqli_num_rows($ds);			
			$ok_d = mysqli_fetch_assoc($ds);
			$discount = $ok_d['discount'];
			$id_wallet_qiwi = $ok_d['id_wallet_qiwi'];
			
			#$wallet = get_setting_data('wallet',$pay_id);
			if($id_wallet_qiwi == 0)
			{
				$wallet = get_setting_data('wallet',$pay_id);
				#$name_payment = get_setting_data('name',$pay_id);
			}else
			{
				$wallet = get_multiple_qiwi_data($id_wallet_qiwi,'wallet');
				#$name_payment = get_setting_data('name',$pay_id);
			}
			
			$sum = $ok_d['suma'];
			if($discount > 0){ $sum = $discount; $price_all = $discount; }
			
			if($disc > 0)
			{
				#$discount = $ok_d['discount'];
				if($discount > 0){ $info_discount = "(вместо <b>$amount_all $currency</b>)"; }
			}
			
			/*
			if($name_payment == 'Bitcoin')
			{
				$sum = convert_sum($sum);
				$all_sum = convert_sum($all_sum);
			}
			*/
			
			#if($discount > 0){ $sum = $discount; }
			$inline_m = 0;
			$exmo_code = false;
			switch($name_payment)
			{
				case 'Card':
					$inline_m = 1;
					$url_pay = "https://money.yandex.ru/transfer?receiver=$wallet&sum=$sum&successURL=&quickpay-back-url=&shop-host=&label=$id_zakaz&targets=%D0%9E%D0%BF%D0%BB%D0%B0%D1%82%D0%B0%20%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0%20%23$id_zakaz&comment=$id_zakaz&origin=form&selectedPaymentType=AC&destination=%D0%9E%D0%BF%D0%BB%D0%B0%D1%82%D0%B0%20%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0%20%23$id_zakaz&form-comment=$id_zakaz&short-dest=";
					$info_pay = "<b>Перевод на карту:</b>".' <a href="'.$url_pay.'">Перейти к оплате с карты</a>'."\n\n";
					break;
				case 'Yandex':
					$inline_m = 1;
					$url_pay = "https://money.yandex.ru/transfer?receiver=$wallet&sum=$sum&successURL=&quickpay-back-url=&shop-host=&label=$id_zakaz&targets=%D0%9E%D0%BF%D0%BB%D0%B0%D1%82%D0%B0%20%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0%20%23$id_zakaz&comment=$id_zakaz&origin=form&selectedPaymentType=PC&destination=%D0%9E%D0%BF%D0%BB%D0%B0%D1%82%D0%B0%20%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0%20%23$id_zakaz&form-comment=$id_zakaz&short-dest=";
					$info_pay = "<b>Перевод на $name_payment:</b>".' <a href="'.$url_pay.'">Перейти к оплате</a>'."\n\n";
					break;
				case 'Qiwi':
					$inline_m = 1; # 1 enable
					$url_pay = "https://qiwi.com/payment/form/99?extra[%27account%27]=$wallet&extra[%27comment%27]=$id_zakaz&amountInteger=$sum&amountFraction=0&currency=643&blocked[1]=account&blocked[2]=comment";
					$info_pay = "<b>Номер счета:</b> <code>$wallet</code>\n";
					break;
				case 'unitpay':
					$pub_key = get_setting_data('pub_key',8);
					$secretKey = get_setting_data('secret_key',8);
					$desc = "Оплата заказа:$id_zakaz";
					$account = $id_zakaz;
					$signature = getFormSignature($account, 'RUB', $desc, $all_sum, $secretKey);
					$url_pay = "https://unitpay.ru/pay/$pub_key?sum=$sum&account=$account&desc=$desc&signature=$signature&currency=RUB";
					$info_pay = "<b>Выполните оплату на $name_payment:</b>".' <a href="'.$url_pay.'">Перейти к оплате</a>'."\n\n";	
					$inline_m = 1;
					break;
				case 'Bitcoin':
					$wallet = get_current('btc_address',$id_chat);
					$confirmations_all = get_setting_data('confirmations',4);
					$info_pay = "<b>Номер счета:</b> <code>$wallet</code>\n";
					$rx = mysqli_query($db,"SELECT `confirmations` FROM `a_zakaz` WHERE `id` = '$id_zakaz' ");
					$data_x = mysqli_fetch_assoc($rx);
					$conf = intval($data_x['confirmations']);
					#if($conf > 0){ $conf = $data_x['confirmations']; } else { $conf = 0; }
					$confirmations_ico = get_number($conf);
					$confirmations_all_ico = get_number($confirmations_all);
					if($conf > 0){ $inline_m = 3; } else { $inline_m = 0; }
					$info_pay .= "\n♻️ <b>Статус:</b>  <code>Количество подтверждений:</code> <b>$confirmations_ico / $confirmations_all_ico</b>\n";
					break;
				case 'Exmo-code':
					$exmo_code = true;
					$inline_m = 0;
					$info_pay = '';
					break;
				case 'Free-kassa':
					$merchant_id = get_setting_data('pub_key',9);
					$secret_word = get_setting_data('secret_key',9);
					$sign = md5($merchant_id.':'.$sum.':'.$secret_word.':'.$id_zakaz);
					mysqli_query($db,"UPDATE `a_zakaz` SET `signature` = '$sign' WHERE `id` = '$id_zakaz'");
					$url_pay = "https://www.free-kassa.ru/merchant/cash.php?m=$merchant_id&oa=$sum&o=$id_zakaz&s=$sign&lang=ru&us_id_zakaz=$id_zakaz";
					$info_pay = "<b>Выполните оплату на $name_payment:</b>".' <a href="'.$url_pay.'">Перейти к оплате</a>'."\n\n";	
					$inline_m = 1;
					break;
				default:
					$inline_m = 0;
					$info_pay = "<b>Номер счета:</b> <code>$wallet</code>\n";
			}
			
						
			$info = "📦 <b>Оплата заказа</b> <code>#$id_zakaz</code>\n\n";
			$info .= "➖ <b>Список покупок:</b> ➖";
			$info .= $list_items."\n\n";
			if(!$exmo_code){ $info .= "📝 <b>Реквизиты оплаты через $name_payment:</b>\n\n"; }
			else
				{ 
					$name_payment = strtoupper($name_payment);
					$info .= "🗞 <b>Способ оплаты:</b> <code>$name_payment</code>\n";
					$info .= "ℹ️ <b>Валюта:</b> <code>RUB</code>\n";
				}
							
			$info .= "💰 <b>Сумма для оплаты:</b> <code>$price_all $currency</code> $info_discount\n";
			$info .= $info_pay;
			#if(($pay_id !== 4) && ($pay_id !== 5) && (!$exmo_code)){ $info .= "<b>Примечание к платежу:</b> <code>$id_zakaz</code>"; }
			if($pay_id == 1){ $info .= "<b>Примечание к платежу:</b> <code>$id_zakaz</code>"; }
			if(!$exmo_code){ $info .= "\n❕ <i>Обработка платежа\Выдача покупки проходит в авто-режиме.</i>"; }
				else
					{  
						$info .= "\n📥 <b>Отправьте сюда Exmo-code на сумму</b> <code>$price_all $currency</code>.\n\n";
						#$info .= "\n❗️ <b>В случае если сумма exmo-кода не совпадет с указанной, вам будет выполнен возврат виде нового exmo-кода.</b> \n";
					}
			
			
			if($inline_m == 1)
			{
				$buttons_inline['inline_keyboard'][0][0]['text'] = '👉 Перейти к оплатите';
				$buttons_inline['inline_keyboard'][0][0]['url'] = $url_pay;
				$buttons_inline['inline_keyboard'][1][0]['text'] = '❕ Отменить заказ';
				$buttons_inline['inline_keyboard'][1][0]['callback_data'] = '/cancel_zakaz:'.$id_zakaz;
				$markup = json_encode($buttons_inline);
			}elseif($inline_m == 0)
			{
				$buttons_inline['inline_keyboard'][0][0]['text'] = '❕ Отменить заказ';
				$buttons_inline['inline_keyboard'][0][0]['callback_data'] = '/cancel_zakaz:'.$id_zakaz;
				$markup = json_encode($buttons_inline);
			}elseif($inline_m == 3){ $markup = ''; }
			
			
			$message_id = get_current('message_id',$id_chat);
			
			$content = array(
				'chat_id' => $id_chat,
				'reply_markup' => $markup,
				'text' => $info,
				'parse_mode'=>'HTML',
				'disable_web_page_preview'=>true,
				'disable_notification'=>false,
			);

			$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
			$json = json_decode($data,true);
			$message_id  = $json['result']['message_id'];
			if($message_id > 0){ set_current('message_id',$message_id,$id_chat); }
			exit;
	}
	

	$back = 0;
	if($text == 'Назад')
	{
		$back = 1;
		$this_cmd = get_current('cmd_id',$id_chat);
		set_current('limit',0,$id_chat);
		$cmd_id_ = get_pid_id($this_cmd);
		if($cmd_id_ > 0){ set_current('cmd_id',$cmd_id_,$id_chat); }
	}
	
	if((strlen($text) > 0) && ($text !== 'Назад'))
	{ 
		$cmd_id_ = get_pid($text);
		if($cmd_id_ > 0){  set_current('cmd_id',$cmd_id_,$id_chat);  }
	}

	
	if($cmd_id_ == 12)
	{
			$s = "SELECT `content` FROM `struktura` WHERE `id` = '$cmd_id_' AND `active` = '0'";
			$r1 = mysqli_query($db,$s);
			$data = mysqli_fetch_assoc($r1);
			$info = unicode_char($data['content']);
			
			$rx = mysqli_query($db,"SELECT `id` FROM `Users`");
			$all = mysqli_num_rows($rx);
			$info = str_replace('{users_all}',$all,$info);
			
			$content = array(
				'chat_id' => $id_chat,
				'text' => $info,
				'parse_mode'=>'HTML',
				'disable_notification'=>false,
			);
		
			file_get_contents($url."/sendmessage?".http_build_query($content));
			exit;
	}
			
		
	
	if($cmd_id_ == 1)
	{
		$content = get_content($cmd_id_);
		$markup = json_encode($buttons_inline);
		
		$r1 = mysqli_query($db,"SELECT `id` FROM `list_referer` WHERE `referer` = '$id_chat'");
		$ref_count = mysqli_num_rows($r1);
		
		$type_referer = get_setting('type_referer');
		if($type_referer == 2)
		{
			$sum = 0;
			$r2 = mysqli_query($db,"SELECT `sum` FROM `orders_referers` WHERE `referer` = '$id_chat'");
			while($ref = mysqli_fetch_assoc($r2))
			{
				$ref_money = $sum + $ref['sum'];
			}
			$ref_money = number_format($ref_money, 0,'.','.');
		}
		$gain_proc = get_setting('gain_proc');
		$discount = get_current('discount',$id_chat);
		
		
		
		$arr[0] = '{name}';
		$arr[1] = '{username}';
		$arr[2] = '{id_chat}';
		$arr[3] = '{all_referer}';
		$arr[6] = '{discount}';
		$arr[7] = '{ref_money}';
 		$arr[8] = '{gain_proc}';
		$arr[9] = '{day_sub}';
		$arr[10] = '{USER_BALANCE}';
		
		$arr2[0] = '<code>'.$user.'</code>';
		$arr2[1] = '<code>'.$username.'</code>';
		$arr2[2] = '<code>'.$id_chat.'</code>';
		$arr2[3] = '<code>'.$ref_count.'</code>';
		if($type_referer == 1){ $arr2[6] = '👥 <b>Скидка:</b> <code>'.$discount.'%</code>'; $arr2[7] = ''; }
			else{ $arr2[7] = '💴 <b>Заработано:</b> <code>'.$ref_money." RUB</code>"; $arr2[6] = ''; }
		$arr2[8] = '<code>'.$gain_proc.'%</code>';
		$arr2[9] = "<code>".$day_sub.'</code>';
		if($type_payment == 1)
		{
			$balance = get_current('balance',$id_chat);
			$balance = number_format($balance, 0,'.','.');
			$arr2[10] = "💰 <b>Мой баланс</b> <code>$balance RUB</code>";
			
			$buttons_inline['inline_keyboard'][0][0]['text'] = "➕ Пополнить баланс";
			$buttons_inline['inline_keyboard'][0][0]['callback_data'] = '/add_balance';
			$buttons_inline['inline_keyboard'][0][1]['text'] = "📃 История";
			$buttons_inline['inline_keyboard'][0][1]['callback_data'] = '/history_balance';
			$markup = json_encode($buttons_inline);
		}else { $arr2[10] = ''; $markup = ''; }
		
		$info = str_replace($arr,$arr2,$content);
	
		
		$content = array(
				'chat_id' => $id_chat,
				'reply_markup' => $markup,
				'text' => $info,
				'parse_mode'=>'HTML',
				'disable_notification'=>true,
			);
		
		$data = file_get_contents($url."/sendmessage?".http_build_query($content));
		$json = json_decode($data,true);
		$message_id  = $json['result']['message_id'];
		if($message_id > 0){ set_current('message_id',$message_id,$id_chat); }	
		exit;
	}	

	$ref_button = get_setting('ref_button');
	if($cmd_id_ == $ref_button)
	{
		#$id_bot = get_current('id_bot',$id_chat);write("id_bot:$id_bot");
		if($id_bot == 0){ $bot_username = get_setting('bot_username'); }
		else
		{
			$rx = mysqli_query($db,"SELECT `username` FROM `bot_list` WHERE `id_bot` = '$id_bot'");
			$data = mysqli_fetch_assoc($rx);
			$bot_username = $data['username'];
		}
		
		$ref_bonus = get_setting('ref_bonus');
		$ref_at_friend = get_setting('ref_at_friend');
		$ref_friend_5 = get_setting('ref_friend_5');
		$ref_friend_15 = get_setting('ref_friend_15');
		$ref_friend_30 = get_setting('ref_friend_30');
		$ref_friend_50 = get_setting('ref_friend_50');
		$ref_friend_100 = get_setting('ref_friend_100');

		$r = mysqli_query($db,"SELECT `id` FROM `list_referer` WHERE `referer` = '$id_chat'");
		$main_ref = mysqli_num_rows($r);
		$gain_proc = get_setting('gain_proc');
		
		$r2 = mysqli_query($db,"SELECT `id` FROM `orders_referers` WHERE `referer` = '$id_chat'");
		$client_ref = mysqli_num_rows($r2);
		
		$arr[0] = '{bot_username}';
		$arr[1] = '{ref_bonus}';
		$arr[2] = '{ref_at_friend}';
		$arr[3] = '{ref_friend_5}';
		$arr[4] = '{ref_friend_15}';
		$arr[5] = '{ref_friend_30}';
		$arr[6] = '{ref_friend_50}';
		$arr[7] = '{ref_friend_100}';
		$arr[8] = '{id_chat}';
		$arr[9] = '{main_ref}';
		$arr[10] = '{gain_proc}';
		$arr[11] = '{client_ref}';
		
		$arr1[0] = $bot_username;
		$arr1[1] = $ref_bonus;
		$arr1[2] = $ref_at_friend;
		$arr1[3] = $ref_friend_5;
		$arr1[4] = $ref_friend_15;
		$arr1[5] = $ref_friend_30;
		$arr1[6] = $ref_friend_50;
		$arr1[7] = $ref_friend_100;
		$arr1[8] = $id_chat;
		$arr1[9] = $main_ref;
		$arr1[10] = '<code>'.$gain_proc.'%</code>';
		$arr1[11] = $client_ref;
		
		$content = get_content($cmd_id_);
		$text = str_replace($arr,$arr1,$content);

	
		$content = array(
				'chat_id' => $id_chat,
				'text' => $text,
				'parse_mode'=>'HTML',
				'disable_notification'=>true,
			);
			
		file_get_contents($url."/sendmessage?".http_build_query($content));
		exit;
	}
			
		
		
	if($cmd_id_ == $basket_id)
	{		if($type_payment == 1){ exit; }
			$info = get_content($cmd_id_);
			$info = htmlspecialchars_decode($info);
			
			$q = 1;
			
			$r = mysqli_query($db,"SELECT `id_item`,`count` FROM `basket` WHERE `id_chat` = '$id_chat' AND `count` > 0");
			$num = mysqli_num_rows($r);
			if($num > 0)
			{
				$info .= "\n\n<code>У вас в корзине:</code>";
				while($items = mysqli_fetch_assoc($r))
				{
					$item = $items['id_item'];
					$count = $items['count'];
					
					$price = get_item_param($item,'price');
					$price_all = $count * $price;
					$all_sum = $all_sum + $price_all;
					
					$name = get_item_param($item,'name');
					$price_all = number_format($price_all, 0,'.','.');
					$info .= "\n".$q."). <b>".$name."</b> - $count шт <b>x</b> ".$price." = <code>".$price_all." RUB.</code>\n<code> -  -  -  - -  -  -  -  -</code>";
					
					$q++;
				}
				
				$all_sum = number_format($all_sum, 0,'.','.');
				$all_sum = $all_sum .' RUB.';
				
				$buttons_inline['inline_keyboard'][0][0]['text'] = "✅ Оформить заказ - $all_sum";
				$buttons_inline['inline_keyboard'][0][0]['callback_data'] = '/create_zakaz';
				$buttons_inline['inline_keyboard'][1][0]['text'] = "🛒 ". "Просмотр товара";
				$buttons_inline['inline_keyboard'][1][0]['switch_inline_query_current_chat'] = " Корзина";
				$buttons_inline['inline_keyboard'][2][0]['text'] = '❌ Очистить корзину';
				$buttons_inline['inline_keyboard'][2][0]['callback_data'] = '/clear_basket';

				$markup = json_encode($buttons_inline);		
			} else { $info = "\n\n❗️ <b>Корзина пуста, добавьте товар.</b>\n"; }
			
			
			
			$content = array(
				'chat_id' => $id_chat,
				'reply_markup' => $markup,
				'text' => $info,
				'parse_mode'=>'HTML',
				'disable_web_page_preview'=>true,
				'disable_notification'=>false,
			);		
		$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
		$json = json_decode($data,true);
		$message_id  = $json['result']['message_id'];
		if($message_id > 0){ set_current('message_id',$message_id,$id_chat); }
		exit;
	}
	
	

	
	if($text == '/start')
	{	
		$s = "SELECT `id`,`name` FROM `struktura` WHERE `pid` = '2' AND `active` = '0' ORDER BY `sid` ASC";
		$r = mysqli_query($db,$s);
		
		$item_x = 0;
		for($q=1;$q<10;$q++)
		{
			$s = "SELECT `id`,`name` FROM `struktura` WHERE `pid` = '2' AND `pos` = '$q' AND `active` = '0' ORDER BY `sid` ASC";
			$r = mysqli_query($db,$s);#write($s);
			$num = mysqli_num_rows($r);
			if($num == 0){ continue; }
			
		#	$id_city = get_current('id_city',$id_chat);
			$level = 0;
			while($ok = mysqli_fetch_assoc($r))
			{
				if(($type_payment == 1) && ($ok['id'] == $basket_id)){ continue; }
				$ico = get_char_unicode($ok['id']);
				if($ok['name'] == 'Назад'){ $ico = '⬅️ '; }				
			/*	
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
				
				if($ok['id'] == 692){ continue; }
			*/		
				$button[$item_x][$level] =  $ico.' '.$ok['name'];	
				$level++;

			}
			$item_x++;
		}
			
			if(($id_chat == $id_chat_admin))
			{
				#if(count($button[$item_x]) == 2){ $item_x--; } else { $item_x++; }
				$button[$item_x][0] =  '📊 Статистика';
			}
			
			$param = array('keyboard' => $button, 'resize_keyboard' => true, 'one_time_keyboard' => false);
			
			$salute = get_setting('salute');
			$salute = htmlspecialchars_decode($salute);
			
			$encodedMarkup = json_encode($param);
			$content = array(
				'chat_id' => $id_chat,
				'reply_markup' => $encodedMarkup,
				'text' => $salute,
				'parse_mode'=>'HTML',
				'resize_keyboard'=>true,
				'one_time_keyboard' => false,
			);
			file_get_contents($url."/sendmessage?".http_build_query($content));
			exit;
			
	} 


	
	$s = "SELECT `id` FROM `struktura` WHERE  `name` = '$text' AND `active` = '0'";
	$r1 = mysqli_query($db,$s);
	$exist_ = mysqli_num_rows($r1);
	
	$rx = mysqli_query($db,"SELECT `id1` FROM `cat_struktura` WHERE `id2` = '$cmd_id_' LIMIT 0,1");
	$ex_cat = mysqli_num_rows($rx);	
	#write("cmd_id_:$cmd_id_ | callback:$callback | exist_:$exist_ | ex_cat:$ex_cat | text:$text");
	if(($callback == 0) && ($exist_ == 1) && ($ex_cat == 0) or ($text == 'Назад')) 
	{
		$catalog_id = get_setting('catalog_id');

		
		$item_x = 0;
		for($q=1;$q<200;$q++)
		{
			$s = "SELECT `id`,`name` FROM `struktura` WHERE `pid` = '$cmd_id_' AND `pos` = '$q' AND `active` = '0' ORDER BY `sid` ASC";
			$r = mysqli_query($db,$s);
			$num = mysqli_num_rows($r);
			if($num == 0){ continue; }
		
			#$id_city = get_current('id_city',$id_chat);
			$level = 0;
			while($ok = mysqli_fetch_assoc($r))
			{
				if(($type_payment == 1) && ($ok['id'] == $basket_id)){ continue; }
				$ico = get_char_unicode($ok['id']);
				if($ok['name'] == 'Назад'){ $ico = '⬅️ '; }
				
						/*	
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
				
				if($ok['id'] == 692){ continue; }
			*/		
				$button[$item_x][$level] =  $ico.' '.$ok['name'];
				$level++;

			}
			$item_x++;
		}
		
		if(($id_chat == $id_chat_admin))
		{	
			if(count($button[$item_x]) == 1)
			{ 
				$button[$item_x][1] =  '📊 Статистика';
			}
			elseif(count($button[$item_x-1]) == 2)
			{
				$button[$item_x][0] =  '📊 Статистика';
			}
			
			$button[$item_x][0] =  '📊 Статистика';
		}
		
		if(intval($cmd_id_) !== 2){ $button[$item_x][0] =  '🔚 Назад'; }
		$param = array('keyboard' => $button, 'resize_keyboard' => true, 'one_time_keyboard' => false);
		

		$s = "SELECT `content` FROM `struktura` WHERE `id` = '$cmd_id_' AND `active` = '0'";
		$r1 = mysqli_query($db,$s);
		$data = mysqli_fetch_assoc($r1);
	
		$encodedMarkup = json_encode($param);
		if(empty($data['content']))
		{ $text = "<b>Menu</b>"; }
		else { $text = unicode_char($data['content']); }
		
		$content = array(
			'chat_id' => $id_chat,
			'reply_markup' => $encodedMarkup,
			'text' => $text,
			'parse_mode'=>'HTML',
			'resize_keyboard'=>true,
			'one_time_keyboard' => false,
		);
		file_get_contents($url."/sendmessage?".http_build_query($content));
	#	exit;
	}
	
	if($text == 'Еще 4')
	{ 	if($type_payment == 1){ exit; }
		$limit_ = get_current('limit',$id_chat);
		$limit_s = "LIMIT $limit_,4";
		$x = $limit_ + 4;
		$limit_s_next = "LIMIT $x,4";
		$cmd_id_ = get_current('cmd_id',$id_chat);
	} else { $limit_s = "LIMIT 0,4"; $limit_s_next = "LIMIT 4,4"; }
	/*
	if(stristr($text,'Товары('))
	{
		$id_city = get_current('id_city',$id_chat);
		if($id_city < 1){ exit; } else { $cmd_id_ = $id_city; }
	}
	*/
	
	$s = "SELECT `id1` FROM `cat_struktura` WHERE  `id2` = '$cmd_id_' $limit_s";
	$qur = mysqli_query($db,$s);
	$num = mysqli_num_rows($qur);
	if($num > 0 )
	{	
		$limit_ = get_current('limit',$id_chat);
		if($text !== 'Еще 4')
		{  
			set_current('limit',4,$id_chat); 
		} 
		else
		{ 
			$x = $limit_ + 4;
			set_current('limit',$x,$id_chat); 
			
		}
		
		
		$item_x = 0;
		for($q=1;$q<200;$q++)
		{
			$s = "SELECT `id`,`name` FROM `struktura` WHERE `pid` = '$cmd_id_' AND `pos` = '$q' AND `active` = '0' ORDER BY `sid` ASC";
			$r = mysqli_query($db,$s);
			$num = mysqli_num_rows($r);
			if($num == 0){ continue; }
		
			#$id_city = get_current('id_city',$id_chat);
			$level = 0;
			while($ok = mysqli_fetch_assoc($r))
			{
				$ico = get_char_unicode($ok['id']);
				if($ok['name'] == 'Назад'){ $ico = '⬅️ '; }
				
				$button[$item_x][$level] =  $ico.' '.$ok['name'];
				$level++;
			}
			$item_x++;
		}
		$button[$item_x][0] =  '🔚 Назад';
				
		$qur = mysqli_query($db,"SELECT `id1` FROM `cat_struktura` WHERE  `id2` = '$cmd_id_' $limit_s_next");
		$all_item = mysqli_num_rows($qur);
	
		if($all_item > 0)
		{
			$button[$item_x][0] =  '🔚 Назад';
			$button[$item_x][1] =  '👉 Еще 4';
		}
		
		
		$param = array('keyboard' => $button, 'resize_keyboard' => true, 'one_time_keyboard' => false);
		$markup = json_encode($param);
		
		$content = array(
			'chat_id' => $id_chat,
			'reply_markup' => $markup,
			'text' => '<b>Каталог товаров в разделе</b>',
			'parse_mode'=>'HTML',
			'resize_keyboard'=>true,
			'one_time_keyboard' => false,
		);
		
		file_get_contents($url."/sendmessage?".http_build_query($content));
	
	
		$r = mysqli_query($db,"SELECT `id1` FROM `cat_struktura` WHERE `id2` = '$cmd_id_' $limit_s");
		while($items = mysqli_fetch_assoc($r))
		{
			$item = $items['id1'];
			$ex_chk = mysqli_query($db,"SELECT `id`,`count` FROM `catalog` WHERE `id` = '$item' AND `active` = '1'");
			$exist_item = mysqli_num_rows($ex_chk);
			if($exist_item > 0)
			{
				$item_data = mysqli_fetch_assoc($ex_chk);
				
			}
			if($exist_item == 0){ continue; }
			
			$info = get_props($item,$id_chat);
			
			$rx = mysqli_query($db,"SELECT `count` FROM `basket` WHERE `id_chat`=  '$id_chat' AND `id_item` = '$item'");
			$is = mysqli_num_rows($rx);
			if($is > 0)
			{
				$dx = mysqli_fetch_assoc($rx);
				$added = "(".$dx['count'].")";
			}	else { $added = ''; }
		
			if($type_payment == 1)
			{
				$buttons_inline['inline_keyboard'][0][0]['text'] = "Купить";
				$buttons_inline['inline_keyboard'][0][0]['callback_data'] = "/pay_item:".$item;
			}else
			{
				$buttons_inline['inline_keyboard'][0][0]['text'] = "➕ ". "Добавить в корзину".$added;
				$buttons_inline['inline_keyboard'][0][0]['callback_data'] = "/add_basket:".$item;
			}
			
			$markup = json_encode($buttons_inline,true);
		/*	
			$content = array(
				'chat_id' => $id_chat,
				'reply_markup' => $markup,
				'text' => $info,
				'parse_mode'=>'HTML',
				'resize_keyboard'=>true,
				'one_time_keyboard' => false,
			);
		*/	
			
			$rx = mysqli_query($db,"SELECT `file`,`file_id` FROM `cat_photo` WHERE `id_cat` = '$item'");
			$num = mysqli_num_rows($rx);
			#if($num > 0)
			#{
				$pho = mysqli_fetch_assoc($rx);
				if($num > 0)
				{
					if((empty($pho['file_id'])) or ($mode == 'alt')){ $i = 'https://'.$_SERVER['SERVER_NAME'].'/img/catalog/'.$pho['file']; }
					elseif($mode !== 'alt') { $i = $pho['file_id']; }
				} else { $i = 'https://'.$_SERVER['SERVER_NAME'].'/img/catalog/no.jpg'; }
			
				$data = array('chat_id' => $id_chat,
							'reply_markup' => $markup,
							'photo' => $i,
							'caption' => $info,
							'parse_mode'=>'HTML',
							'disable_notification'=>false,
						);
				
			/*		
				$data = array('chat_id' => $id_chat,
							'photo' => $i,
							#'caption' => $info,
							'parse_mode'=>'HTML',
							'disable_notification'=>false,);
			*/
		
				$query = $url."/sendPhoto?".http_build_query($data);
				$json_photo = file_get_contents($query);
				$json_photo = json_decode($json_photo,true);
				$lost = count($json_photo['result']['photo']);
				$file_id = $json_photo['result']['photo'][$lost-1]['file_id'];
				#if(!empty($json_photo['result']['photo'][2])){ $file_id = $json_photo['result']['photo'][2]['file_id']; }
				#if(!empty($json_photo['result']['photo'][3])){ $file_id = $json_photo['result']['photo'][3]['file_id']; }	
				if(!empty($file_id) && ($mode !== 'alt'))
				{
					mysqli_query($db,"UPDATE `cat_photo` SET `file_id` = '$file_id' WHERE `id_cat` = '$item'");
				}
			#}

		#	$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));	
		#	$json = json_decode($data,true);
		#	$message_id  = $json['result']['message_id'];
			$message_id  = $json_photo['result']['message_id'];
			if($message_id > 0)
			{ 
				#mysqli_query($db,"INSERT INTO `message_id` (`message_id`,`id_chat`,`type`,`tmp_id`) 
				#								VALUES ('$message_id','$id_chat','1','$tmp_id')"); 
				
				if($type_payment == 1)
				{
					$buttons_inline['inline_keyboard'][0][0]['text'] = "Купить";
					$buttons_inline['inline_keyboard'][0][0]['callback_data'] = "/pay_item:".$item.':'.$message_id;
				}else
				{
					$buttons_inline['inline_keyboard'][0][0]['text'] = "➕ ". "Добавить в корзину".$added;
					$buttons_inline['inline_keyboard'][0][0]['callback_data'] = "/add_basket:".$item.':'.$message_id;
				}
				
				$markup = json_encode($buttons_inline,true);
				$data = array('chat_id' => $id_chat,
					'reply_markup' => $markup,
					'message_id'=>$message_id,
					'caption' => $info,
					'parse_mode'=>'HTML',
					'disable_notification'=>false,
				);
				
				$query = $url."/editMessageCaption?".http_build_query($data);
				$json_photo = file_get_contents($query);
			}
			
		
		}
		
		exit;
	}elseif($text == 'Еще 4')
	{
			$content = array(
				'chat_id' => $id_chat,
				'text' => '<b>❗️ Пусто, вернитесь назад.</b>',
				'parse_mode'=>'HTML',
				'resize_keyboard'=>true,
				'one_time_keyboard' => false,
			);
			
			file_get_contents($url."/sendmessage?".http_build_query($content));
			exit;
	}
	
	mysqli_close($db);
?>