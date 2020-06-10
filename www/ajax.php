<?php
	header('Content-Type: text/html; charset=utf-8');


	include('functions.php');
	if(!$session){ exit; }
	$act = strip_tags($_POST['act']);
	preg_match("/^[a-zA-Z0-9\w]+/", $act,$actx);
	$act = $actx[0];
	
	$id = intval($_POST['id']);
	$type = intval($_POST['type']);
	
		
	if($rang == 2)
	{
		if(($act !== 'new_item') && ($act !== 'show_item_data') && ($act !== 'upload_img') && ($act !== 'del_item') && ($act !== 'set_active_item')){ exit; }
		
	}

	$id_ico = intval($_POST['id_ico']);
	if($act == 'set_ico')
	{
		$r = mysqli_query($db,"SELECT `id` FROM `unicode_struktura` WHERE `cmd_id` = '$id'");
		$num = mysqli_num_rows($r);
		if($num == 0)
		{
			mysqli_query($db,"INSERT INTO `unicode_struktura` (`cmd_id`,`id_unicode`) VALUES ('$id','$id_ico')");
		} else { mysqli_query($db,"UPDATE `unicode_struktura` SET `id_unicode` = '$id_ico' WHERE `cmd_id` = '$id'"); }
		echo json_encode(array('stat'=>1));
		
	}
	
	$name = strip_tags($_POST['name']);
	if($act == 'set_name_btn')
	{
		mysqli_query($db,"UPDATE `struktura` SET `name` = '$name' WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}

	$sid = intval($_POST['sid']);
	if($act == 'set_sort_btn')
	{
		mysqli_query($db,"UPDATE `struktura` SET `sid` = '$sid' WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}
	

	if($act == 'set_sort_btn_menu')
	{
		mysqli_query($db,"UPDATE `a_menu` SET `sid` = '$sid' WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}	
	
	$url = strip_tags($_POST['url']);
	if($act == 'set_inline_url') 
	{
		mysqli_query($db,"UPDATE `struktura` SET `url` = '$url' WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}		
	
	
	
	
	if($act == 'save_opt')
	{	
		foreach($_POST as $name=>$val)
		{	
			if($name == 'act'){ continue; }
			if($name == 'partners'){ $val = strip_tags($val); }

			$col .= "`$name` = '$val',";
		}
		$col = substr($col,0,strlen($col)-1);
			
		$s = "UPDATE `setting` SET $col WHERE `id` = '1'";
		if(mysqli_query($db,$s)){ $save = true; } else { $save = false; }
		echo json_encode(array('stat'=>1,'status'=>$save));
	}
	
	$bot_token = strip_tags($_POST['bot_token']);;
	$type_payment = intval($_POST['type_payment']);
	$chat_id_admin = intval($_POST['chat_id_admin']);
	$min_sum = intval($_POST['min_sum_balance']);
	if($act == 'save_opt_bot')
	{
		$max = intval($_POST['max_connections']);
		if(($max == 0) or ($max > 100)){ $max = 40; }
		$url = "https://".$_SERVER['SERVER_NAME']."/telegram/hook.php?db=".base;
		$u = "https://api.telegram.org/bot$bot_token/setWebhook?max_connections=$max&url=".$url;
		file_get_contents($u);
		
			
		$res = file_get_contents("https://api.telegram.org/bot".$bot_token."/getme");
		$json = json_decode($res);
		if($json->ok == 1)
		{
			$username_ = $json->result->username;
			$bot_name = $json->result->first_name;
			
			mysqli_query($db,"UPDATE `setting` SET `bot_username` = '$username_', `bot_name` = '$bot_name' WHERE `id` = '1'");
		}
		
		mysqli_query($db,"UPDATE `setting` SET `bot_token` = '$bot_token', `chat_id_admin` = '$chat_id_admin',
							`max_connections` = '$max',`type_payment` = '$type_payment', `min_sum_balance` = '$min_sum' WHERE `id` = '1'");
		echo json_encode(array('stat'=>1));		
	}
	
	$catalog_id = intval($_POST['catalog_id']);
	$basket_id = intval($_POST['basket_id']);
	$ref_button=  intval($_POST['ref_button']);
	if($act == 'data_setting2')
	{
		mysqli_query($db,"UPDATE `setting` SET `catalog_id` = '$catalog_id', `basket_id` = '$basket_id', 
						`ref_button` = '$ref_button' WHERE `id` = '1'");
		echo json_encode(array('stat'=>1));
	}
	
	
	
	if($act == 'operation_users')
	{
		$list = explode('|',$_POST['id']);
		if($type == 1)
		{
			foreach($list as $n=>$id)
			{	
				$r = mysqli_query($db,"SELECT `user` FROM `Users` WHERE `id` = '$id'");
				$rx = mysqli_fetch_assoc($r);
				$user = $rx['user'];
			
				if($id > 0){ mysqli_query($db,"DELETE FROM `Users` WHERE `id` = '$id'"); }
			}
		}
	
		echo json_encode(array('stat'=>1));
	}
	

	
	$message = $_POST['message'];
	$bonus = intval($_POST['bonus']);
	$inline_link = strip_tags($_POST['inline_link']);
	$run_time = strip_tags($_POST['run_time']);
	$inline_name = ($_POST['inline_name']);
	$type_d = intval($_POST['type_d']);
	if($act == 'create_delivery')
	{
		$type = $_FILES['file']['type'];
		if((!empty($type)) && ($type !== 'image/jpeg') && ($type !== 'image/png') && ($type !== 'image/gif')){ echo json_encode(array('stat'=>0));exit; }
		
		$img = '';
		$tmp = $_FILES['file']['tmp_name'];
		$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		$photo = rand(666,99999).".$ext";
		if(move_uploaded_file($tmp,$_SERVER['DOCUMENT_ROOT'].'/upload/delivery/'.$photo))
		{
			$img = $photo;
		}		
		
		if($type_d !== 2){
			$r1 = mysqli_query($db,"SELECT `id` FROM `Users` ");
			$max_users = mysqli_num_rows($r1);
		}else { $max_users = 1; }
		
		$run_time = str_replace('/','.',$run_time);
		$date = time();
		$message = unicode_html($message);
		$inline_name = unicode_html($inline_name);
		$s = "INSERT INTO `delivery_list` (`message`,`inline_link`,`date_run`,`inline_name`,`date`,`max_users`,`photo`,`type_d`)
				VALUES ('$message','$inline_link','$run_time','$inline_name','$date','$max_users','$img','$type_d')";
		mysqli_query($db,$s);
		echo json_encode(array('stat'=>1));
	}
	
	
	if($act == 'get_delivers')
	{
		
		$s = "SELECT `id`,`message`,`bonus`,`inline_link`,`date_run`,`date`,`inline_name`,`status`,`progress`,`max_users`
															FROM `delivery_list` ORDER BY `id` DESC";
		$r = mysqli_query($db,$s);
		$all = mysqli_num_rows($r);
			while($ok = mysqli_fetch_assoc($r))
			{
				$date = date('Y.m.d',$ok['date']);
				$progress = $ok['progress'];
				$max = $ok['max_users'];
			
				switch($ok['status'])
				{
					case 0:
						$info = 'Ожидает';$class_ = 'primary';$stop = '';break;
					case 1:	
						$info = "Идет рассылка ";$class_ = 'success';$stop = "<a class='label label-danger stop' value='".$ok['id']."' title='Stop'><i class='fa fa-stop-circle-o' aria-hidden='true'></i></a>";break;
					case 2:	
						$info = 'Выполнена';$class_ = 'success';$stop = '';break;
					case 6:	
						$info = 'Отменен';$class_ = 'danger';$stop = '';	
				}
				
				$run_delivery = '';
				if($ok['status'] == 1){ $run_delivery = "<span class='label label stat_x'>$progress/$max</span><div class='task_progressbar task_".$ok['id']."' value='".$ok['id']."'></div>"; }
	
				$code .= "<tr class='item_".$ok['id']."'>
				  <td class='invisible_'><label class='opt_col'><input type='checkbox' class='check_' value='".$ok['id']."'></label> </td>
				  <td class='invisible_' style='color: #073a9a;'>".unicode_char($ok['message'])."</td>
				  <td>".$ok['inline_link']."</td>
				  <td>".$ok['inline_name']."</td>
				   <td class='invisible_'><span class='label label-primary'>".$date."</span></td>
				   <td>$run_delivery<span class='label label-".$class_."'>".$info."</span>$stop</td>
				</tr>";
		}
		
			echo json_encode(array('stat'=>1,'code'=>$code,'all'=>$all));
	}
	
	if($act == 'operation_delivery')
	{
		$list = explode('|',$_POST['id']);
		if($type == 1)
		{
			foreach($list as $n=>$id)
			{	
				$id_ .= $id.',';
				if($id > 0){ mysqli_query($db,"DELETE FROM `delivery_list` WHERE `id` = '$id'"); }
			}
		}
		add_action_log('1','ID Рассылок:'.$id_,'Удаление расслыки');
		echo json_encode(array('stat'=>1));
	}		
	
	$name_btn = strip_tags($_POST['name_btn']);
	$url_btn = strip_tags($_POST['url_btn']);
	$info = ($_POST['content_btn']);
	$status = strip_tags($_POST['status']);
	$price = intval($_POST['price']);
	if($act == 'save_btn_money')
	{	
		if($status == 'on'){ $active = 0; } else { $active = 1; }
		#echo "UPDATE `struktura` SET `name` = '$name_btn', `content` = '$info', `active` = '$active' WHERE `id` = '10'";exit;
		mysqli_query($db,"UPDATE `struktura` SET `name` = '$name_btn', `content` = '$info', `active` = '$active',
								`url` = '$url_btn',`price` = '$price' WHERE `id` = '10'");
		echo json_encode(array('stat'=>1));
	}

	if($act == 'get_user_wallet')
	{
		$r = mysqli_query($db,"SELECT `tel` FROM `Users` WHERE `id_chat` = '$id'");
		$ok = mysqli_fetch_array($r);
		if(!empty($ok['tel'])){ $tel = $ok['tel']; } else { $tel = 0; }
		echo json_encode(array('stat'=>1,'tel'=>$tel));
	}
	
	$pwd = strip_tags($_POST['new_pwd']);
	$old_pwd = strip_tags($_POST['old_pwd']);
	if($act == 'change_pwd')
	{
		$hash = md5($pwd);
		$old_hash = md5($old_pwd);
		if(autorize($login_,$old_hash))
		{
			mysqli_query($db,"UPDATE `Accounts` SET `password` = '$hash' WHERE `id` = '$user_id'");
		} else { echo json_encode(array('stat'=>1,'change'=>false));exit; }
		
		echo json_encode(array('stat'=>1,'change'=>true));
	}
	
	

	
	$active = intval($_POST['active']);
	if($act == 'set_menu_active')
	{	if($active == 0){ $active = 1; } else { $active = 0; }
		mysqli_query($db,"UPDATE `struktura` SET `active` = '$active' WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}
	

	if($act == 'get_data_delivery')
	{
		$r = mysqli_query($db,"SELECT `progress`,`max_users` FROM `delivery_list` WHERE `id` = '$id'");
		$ok = mysqli_fetch_assoc($r);
		echo json_encode(array('stat'=>1,'this_'=>intval($ok['progress']),'max_users'=>intval($ok['max_users'])));
	}
	
	
	if($act == 'stop_delivery')
	{
		mysqli_query($db,"UPDATE `delivery_list` SET `status` = '6' WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}
	
	
	if($act == 'get_message_text')
	{
		$r = mysqli_query($db,"SELECT `content` FROM `struktura` WHERE `id` = '$id'");
		$ok = mysqli_fetch_assoc($r);
		$code = unicode_char($ok['content']);
		echo json_encode(array('stat'=>1,'msg'=>$code));
	}
	
	$content = $_POST['msg'];
	if($act == 'save_message_text')
	{
		$content = unicode_html($content);
		mysqli_query($db,"UPDATE `struktura` SET `content` = '$content' WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}
		
	$salute = htmlspecialchars($_POST['text']);
	if($act == 'set_salute_msg')
	{
		$content = unicode_html($salute);
		mysqli_query($db,"UPDATE `setting` SET `salute` = '$content' WHERE `id` = '1'");
		echo json_encode(array('stat'=>1));
	}
	
	
	if($act == 'del_strukture')
	{
		mysqli_query($db,"DELETE FROM `struktura` WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}
	
	$name = strip_tags($_POST['name']);
	$group = intval($_POST['group']);
	$ico = intval($_POST['ico']);
	if($act == 'new_btn_menu')
	{
		if(empty($name)){ exit; }
		mysqli_query($db,"INSERT INTO `struktura` (`name`,`pid`) VALUES ('$name','$group')");
		$id = mysqli_insert_id($db);
		if($ico > 0){ mysqli_query($db,"INSERT INTO `unicode_struktura` (`cmd_id`,`id_unicode`) VALUES ('$id','$ico')"); }
		
		
		echo json_encode(array('stat'=>1,'id'=>$id));
	}
	
	
	if($act == 'add_btn_bot')
	{
		if(empty($name)){ exit; }
		mysqli_query($db,"INSERT INTO `struktura` (`name`,`pid`) VALUES ('$name','2')");
		$id = mysqli_insert_id($db);
		if($ico > 0){ mysqli_query($db,"INSERT INTO `unicode_struktura` (`cmd_id`,`id_unicode`) VALUES ('$id','$ico')"); }
		
		
		echo json_encode(array('stat'=>1,'id'=>$id));
	}	

	$pos = intval($_POST['pos']);
	if($act == 'set_position_strukture')
	{
		mysqli_query($db,"UPDATE `struktura` SET `pos` = '$pos' WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}
	
	$name = strip_tags($_POST['name']);
	$alias = strip_tags($_POST['alias']);
	$type = intval($_POST['type']);
	if($act == 'add_prop_catalog')
	{
		if((empty($name)) or (empty($alias))){ exit; }
		mysqli_query($db,"INSERT INTO `prop_list_catalog` (`name`,`alias`,`type`) VALUES ('$name','$alias','$type');");
		mysqli_query($db,"ALTER TABLE `catalog` ADD `$alias` TEXT NOT NULL");
		echo json_encode(array('stat'=>1));
	}
	
	if($act == 'del_prop_cat')
	{
		mysqli_query($db,"DELETE FROM `prop_list_catalog` WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}
	
	$method = strip_tags($_POST['method']);
	$id_item = intval($_POST['id_item']);
	$tmp_photo = strip_tags($_POST['tmp_photo']);
	if($act == 'new_item')
	{
		foreach($_POST['item'] as $name=>$val)
		{
			$value = strip_tags($val);
			
		if($method == 1){ $col .= "`$name` = '$value',";}
		if($method == 2){ $col .= "`$name`,"; $value_ .= "'$value',"; }
			
		}
		
		
		if($method == 1)
		{
			$col = substr($col,0,strlen($col)-1);
			if($rang !== 1){ $s_param = "AND `user_id` = '$user_id'"; } else { $s_param = ''; }
			$s = "UPDATE `catalog` SET $col WHERE `id` = '$id_item' $s_param";
			mysqli_query($db,"UPDATE `catalog` SET $col WHERE `id` = '$id_item' $s_param");
		
			mysqli_query($db,"DELETE FROM `cat_struktura` WHERE `id1` = '$id_item'");
			foreach($_POST['cat_list'] as $cat)
			{
				$cat = intval($cat);
				mysqli_query($db,"INSERT INTO `cat_struktura` (`id1`,`id2`) VALUES ('$id_item','$cat')");
			}
			
			
			if(!empty($tmp_photo))
			{
				$rx = mysqli_query($db,"SELECT `id` FROM `cat_photo` WHERE `id_cat` = '$id_item'");
				$num = mysqli_num_rows($rx);
				if($num == 0){
					mysqli_query($db,"INSERT INTO `cat_photo` (`id_cat`,`file`) VALUES ('$id_item','$tmp_photo')");
				} else { mysqli_query($db,"UPDATE `cat_photo` SET `file` = '$tmp_photo', `file_id` = '' WHERE `id_cat` = '$id_item'"); }
			}
			
		}
		
		
		if($method == 2)
		{
			$col = substr($col,0,strlen($col)-1);
			$value_ = substr($value_,0,strlen($value_)-1);
			
			$s = "INSERT INTO `catalog` ($col,`user_id`) VALUES ($value_,'$user_id')";#echo $s;exit;
			$r = mysqli_query($db,$s);
			$id_cat = mysqli_insert_id($db);
			$id_item = $id_cat;
			
			if(!empty($tmp_photo))
			{
				mysqli_query($db,"INSERT INTO `cat_photo` (`id_cat`,`file`) VALUES ('$id_item','$tmp_photo')");
				
			}
			
			
			foreach($_POST['cat_list'] as $cat)
			{
				$cat = intval($cat);
				mysqli_query($db,"INSERT INTO `cat_struktura` (`id1`,`id2`) VALUES ('$id_cat','$cat')");
			}
		}
		
		echo json_encode(array('stat'=>1,'id_cat'=>$id_cat));
	}
	
	$col = strip_tags($_POST['col']);
	$val = strip_tags($_POST['val']);
	if($act == 'set_prop_item')
	{
		mysqli_query($db,"UPDATE `prop_list_catalog` SET `$col` = '$val' WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}
	
	$sid = intval($_POST['sid']);
	if($act == 'set_sort_prop')
	{
		mysqli_query($db,"UPDATE `prop_list_catalog` SET `sid` = '$sid' WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}
	
	$active = intval($_POST['active']);
	if($act == 'set_active_prop')
	{
		mysqli_query($db,"UPDATE `prop_list_catalog` SET `active` = '$active' WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}
	
	if($act == 'set_active_item')
	{
		mysqli_query($db,"UPDATE `catalog` SET `active` = '$active' WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}
	
	if($act == 'show_item_data')
	{
		if($rang !== 1){ $s_param = "AND `user_id` = '$user_id'"; } else { $s_param = ''; }
		$r = mysqli_query($db,"SELECT * FROM `catalog` WHERE `id` = '$id' $s_param"); # `id`,`name`,`price`,`active`,`sales`
		$item = mysqli_fetch_assoc($r);
		
		$r1 = mysqli_query($db,"SELECT `id`,`alias` FROM `prop_list_catalog` WHERE `active` = '0' ORDER BY `sid` ASC");
		while($list = mysqli_fetch_assoc($r1))
		{
			if((empty($item[$list['alias']])) && ($list['alias'] !== 'amount_product')){ continue; }
			$arr[$list['alias']] = $item[$list['alias']];
			$list_prop[] = $list['alias'];
		}
		
		
		$catalog_id = get_setting('catalog_id');
		$r = mysqli_query($db,"SELECT `id`,`name` FROM `struktura` WHERE `active` = '0' AND `pid` = '$catalog_id'");
		while($ok = mysqli_fetch_assoc($r))
		{
			$r2 = mysqli_query($db,"SELECT `id2` FROM `cat_struktura` WHERE `id1` = '$id' AND `id2` = '".$ok['id']."'");
			$num = mysqli_num_rows($r2);
			if($num > 0){ $sel = 'selected'; } else { $sel = ''; }
			$list_sel .= "<option value='".$ok['id']."' $sel>".$ok['name']."</option>";
			
			$r1 = mysqli_query($db,"SELECT `id`,`name` FROM `struktura` WHERE `active` = '0' AND `pid` = '".$ok['id']."'");
			while($list1 = mysqli_fetch_assoc($r1))
			{
				$r3 = mysqli_query($db,"SELECT `id2` FROM `cat_struktura` WHERE `id1` = '$id' AND `id2` = '".$list1['id']."'");
				$num = mysqli_num_rows($r3);
				if($num > 0){ $sel = 'selected'; } else { $sel = ''; }
				$list_sel .= "<option value='".$list1['id']."' $sel>".$list1['name']."</option>";
				
				$r2 = mysqli_query($db,"SELECT `id`,`name` FROM `struktura` WHERE `active` = '0' AND `pid` = '".$list1['id']."'");
				while($list2 = mysqli_fetch_assoc($r2))
				{
					$r4 = mysqli_query($db,"SELECT `id2` FROM `cat_struktura` WHERE `id1` = '$id' AND `id2` = '".$list2['id']."'");
					$num = mysqli_num_rows($r4);
					if($num > 0){ $sel = 'selected'; } else { $sel = ''; }
					$list_sel .= "<option value='".$list2['id']."' $sel>".$list2['name']."</option>";
					
					$r3 = mysqli_query($db,"SELECT `id`,`name` FROM `struktura` WHERE `active` = '0' AND `pid` = '".$list2['id']."'");
					while($list3 = mysqli_fetch_assoc($r3))
					{
						$r5 = mysqli_query($db,"SELECT `id2` FROM `cat_struktura` WHERE `id1` = '$id' AND `id2` = '".$list3['id']."'");
						$num = mysqli_num_rows($r5);
						if($num > 0){ $sel = 'selected'; } else { $sel = ''; }
						$list_sel .= "<option value='".$list3['id']."' $sel>".$list3['name']."</option>";
						
						$r4 = mysqli_query($db,"SELECT `id`,`name` FROM `struktura` WHERE `active` = '0' AND `pid` = '".$list3['id']."'");
						while($list4 = mysqli_fetch_assoc($r4))
						{
							$r5 = mysqli_query($db,"SELECT `id2` FROM `cat_struktura` WHERE `id1` = '$id' AND `id2` = '".$list4['id']."'");
							$num = mysqli_num_rows($r5);
							if($num > 0){ $sel = 'selected'; } else { $sel = ''; }
							$list_sel .= "<option value='".$list4['id']."' $sel>".$list4['name']."</option>";
						}
					}
				}
			}
		}		
		
		$active = intval($item['active']);
	
		$rx = mysqli_query($db,"SELECT `file` FROM `cat_photo` WHERE `id_cat` = '$id'");
		$num = mysqli_num_rows($rx);
		if($num > 0)
		{
			$pho = mysqli_fetch_assoc($rx);
			$i = $pho['file'];
		}
		
		echo json_encode(array('stat'=>1,'item_data'=>$arr,'list_prop'=>$list_prop,'list_sel'=>$list_sel,'active'=>$active,'i'=>$i));
	}
	
	
	
	
	if(($act == 'upload_img') )
	{	
		$ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
		if(empty($_FILES['photo']['tmp_name'])){ echo json_encode(array('stat'=>0,'code'=>'404')); exit; }
		
		global $db;
		$label = $_FILES['photo']['name'];
		$tmp = $_FILES['photo']['tmp_name'];
		
		$label = rand(66666,6666666);
		$label = $label.'.'.$ext;
		
		$dir = root_dir.'/img/catalog/'.$label;
		if(move_uploaded_file($tmp,$dir))
		{	
			$size = getimagesize($dir);
			if(($size[0] < 1) && ($size[1] < 1) && (!strpos($size['mine'],'image')))
			{
				{ unlink($dir); echo json_encode(array('stat'=>0,'code'=>'0')); exit; }
			}
	
			echo json_encode(array('stat'=>1,'photo'=>$label));
		}
	}
		
	
	if($act == 'del_item')
	{	
		if($rang !== 1){ $s_param = "AND `user_id` = '$user_id'"; } else { $s_param = ''; }
		$s = "SELECT `file` FROM `cat_photo` WHERE `id_cat` = '$id'";
		$rx = mysqli_query($db,$s);
		$ph = mysqli_fetch_assoc($rx);
		$i = root_dir."/img/catalog/".$ph['file'];
		if(file_exists($i)){ unlink($i); }
	
		mysqli_query($db,"DELETE FROM `cat_photo` WHERE `id_cat` = '$id'");
		mysqli_query($db,"DELETE FROM `catalog` WHERE `id` = '$id' $s_param");
		mysqli_query($db,"DELETE FROM `cat_struktura` WHERE `id1` = '$id'");
		
		echo json_encode(array('stat'=>1));
	}
	
	$api_key = strip_tags($_POST['api_key']);
	$wallet = strip_tags($_POST['wallet']);
	$active = intval($_POST['active']);
	if($act == 'save_opt_qiwi')
	{
		mysqli_query($db,"UPDATE `setting_payment` SET `api_key` = '$api_key', `wallet` = '$wallet', `active` = '$active' WHERE `id` = '1'");
		
		$url_x = 'https://'.$_SERVER['SERVER_NAME']."/payment_qiwi.php?login=$wallet";
		if(!reg_webhook($url_x)){ echo json_encode(array('stat'=>0));exit; }
		
		echo json_encode(array('stat'=>1));
	}
	
	$secret_key = strip_tags($_POST['secret_key']);
	$wallet = strip_tags($_POST['wallet']);
	$card = intval($_POST['card']);
	if($act == 'save_opt_yandex')
	{
		
		mysqli_query($db,"UPDATE `setting_payment` SET `active` = '$card', `wallet` = '$wallet' WHERE `id` = '3'");
		
		#mysqli_query($db,"UPDATE `setting_payment` SET `api_key` = '$secret_key', `active` = '$active',
		#														`wallet` = '$wallet' WHERE `id` = '2'");
		mysqli_query($db,"UPDATE `setting_payment` SET  `active` = '$active', `wallet` = '$wallet' WHERE `id` = '2'");
		#$url = 'https://'.$_SERVER['SERVER_NAME'].'/hook_yandex_pay.php?secret_key='.$secret_key;
		echo json_encode(array('stat'=>1));
	}	
	
	
	$confirmations = intval($_POST['confirmations']);
	$wallet = strip_tags($_POST['wallet']);
	$level_btc = strip_tags($_POST['level_btc']);
	if($act == 'save_opt_btc')
	{
		mysqli_query($db,"UPDATE `setting_payment` SET `confirmations` = '$confirmations', `active` = '$active',
											`wallet` = '$wallet', `level_btc` = '$level_btc' WHERE `id` = '4'");
		echo json_encode(array('stat'=>1));
	}	
	
	
	$secret_key = strip_tags($_POST['secret_key_exmo']);
	$api_key = strip_tags($_POST['api_key_exmo']);
	if($act == 'save_opt_exmo')
	{
		mysqli_query($db,"UPDATE `setting_payment` SET `api_key` = '$api_key', `secret_key` = '$secret_key',
																	`active` = '$active' WHERE `id` = '5'");
		echo json_encode(array('stat'=>1));
	}	
	
	
	$secret_key = strip_tags($_POST['secret_key']);
	$api_key = strip_tags($_POST['api_key']);
	$wallet = strip_tags($_POST['wallet']);
	if($act == 'save_opt_payment_btc')
	{
		mysqli_query($db,"UPDATE `setting_payment` SET `api_key` = '$api_key', `secret_key` = '$secret_key',
																	`wallet` = '$wallet' WHERE `id` = '6'");
		echo json_encode(array('stat'=>1));
	}	
	
	
	if($act == 'save_opt_payment_qiwi')
	{
		mysqli_query($db,"UPDATE `setting_payment` SET `api_key` = '$api_key', `wallet` = '$wallet' WHERE `id` = '7'");
		echo json_encode(array('stat'=>1));
	}	
	
	
	$secret_key = strip_tags($_POST['secret_key_unitpay']);
	$pub_key = strip_tags($_POST['pub_key_unitpay']);
	if($act == 'save_opt_unitpay')
	{
		mysqli_query($db,"UPDATE `setting_payment` SET `pub_key` = '$pub_key', `secret_key` = '$secret_key',
																	`active` = '$active' WHERE `id` = '8'");
		echo json_encode(array('stat'=>1));
	}		
	
	$secret_key = strip_tags($_POST['secret_key_freekassa']);
	$pub_key = strip_tags($_POST['merchant_id_freekassa']);
	if($act == 'save_opt_freekassa')
	{
		mysqli_query($db,"UPDATE `setting_payment` SET `pub_key` = '$pub_key', `secret_key` = '$secret_key',
																	`active` = '$active' WHERE `id` = '9'");
		echo json_encode(array('stat'=>1));
	}	
	
	$status = intval($_POST['status']);
	if($act == 'set_status_zakaz')
	{
		if($status == 2){ $buy = 1; } else { $buy = 0; }
		mysqli_query($db,"UPDATE `a_zakaz` SET `buy` = '$buy', `status` = '$status' WHERE `id` = '$id'");
		
		echo json_encode(array('stat'=>1));
	}
	
	if($act == 'del_zakaz')
	{
		mysqli_query($db,"DELETE FROM `a_zakaz` WHERE `id` = '$id'");
		mysqli_query($db,"DELETE FROM `a_zakaz_items` WHERE `id_zakaz` = '$id'");
		echo json_encode(array('stat'=>1));
	}
	
	$active = intval($_POST['active']);
	$query_info = strip_tags($_POST['query_info']);
	$how = intval($_POST['how_delivery']);
	$get_phone = intval($_POST['get_phone']);
	if($act == 'set_step')
	{
		mysqli_query($db,"UPDATE `setting` SET `step_get_name` = '$active', `step_text` = '$query_info', `how` = '$how',
																		`get_phone` = '$get_phone' WHERE `id` = '1'");
		echo json_encode(array('stat'=>1));
		
	}
	
	$type = intval($_POST['type']);
	if($act == 'set_type_referer')
	{
		mysqli_query($db,"UPDATE `setting` SET `type_referer` = '$type' WHERE `id` = '1'");
		echo json_encode(array('stat'=>1));
	}
	
	$proc = intval($_POST['proc']);
	if($act == 'save_gain')
	{
		mysqli_query($db,"UPDATE `setting` SET `gain_proc` = '$proc' WHERE `id` = '1'");
		echo json_encode(array('stat'=>1));
	}
	
	$login = strip_tags($_POST['login']);
	$pwd = strip_tags($_POST['pwd']);
	if($act == 'create_manager')
	{
		if($login == 'admin'){ exit; }
		if((empty($login)) or (empty($pwd))){ exit; }
		$time = time();
		$pwd_show = $pwd;
		$pwd = md5($pwd);
		mysqli_query($db,"INSERT INTO `Accounts` (`login`,`password`,`pwd_show`,`rang`,`date`) 
										VALUES ('$login','$pwd','$pwd_show','2','$time')");
		$id = mysqli_insert_id($db);
		
		$date = date('Y.m.d',$time);
		$tr = '<tr class="item_'.$id.'">
			  <td><label class="opt_col">'.$id.'</label> </td>
			  <td style="color: #073a9a;">'.$login.'</td>
			   <td>'.$pwd_show.'</td>
			  <td>0</td>
			  <td><span class="label label-primary">'.$date.'</span></td>
			  <td><i class="fa fa-trash del_user" aria-hidden="true" title="Удалить" value="'.$id.'"></i></td></tr>';
		
		echo json_encode(array('stat'=>1,'id'=>$id,'tr'=>$tr));
	}
	
	if($act == 'del_user')
	{
		mysqli_query($db,"DELETE FROM `Accounts` WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}
	
	
	$name = strip_tags($_POST['name']);
	$amount = strip_tags($_POST['amount']);
	if($act == 'add_price_object')
	{
		mysqli_query($db,"INSERT INTO `product_value_units` (`name`,`value`,`amount`) VALUES ('$name','1','$amount')");
		$id = mysqli_insert_id($db);
		echo json_encode(array('stat'=>1,'id'=>$id));
	}
	
	if($act == 'del_value')
	{
		mysqli_query($db,"DELETE FROM `product_value_units` WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}
	
	if($act == 'get_payment_list')
	{
		$r = mysqli_query($db,"SELECT `id`,`user_id`,`amount`,`date`,`count`,`item_id` FROM `sales_total` WHERE `user_id` = '$id'");
		while($list = mysqli_fetch_assoc($r))
		{
			#$date = date('Y.m.d',$list['date']);
			$login = get_current_users('login',$list['user_id']);
			$item_name = get_item_param($list['id'],'name');
			
			$tr .= "<tr><td>".$login."</td><td>".$item_name."</td><td>".$list['count']."</td><td>".$list['amount']." RUB</td><td>".$list['date']."</td></tr>";
		}
		
		echo json_encode(array('stat'=>1,'list'=>$tr));
	}
	
	$date1 = strip_tags($_POST['date1']);
	$date2 = strip_tags($_POST['date2']);
	if($act == 'search_payment')
	{
		$date1 = str_replace("/",'.',$date1);
		$date2 = str_replace("/",'.',$date2);
		$money_x = 0;
	
		$r = mysqli_query($db,"SELECT  `id`,`user_id`,`amount`,`date`,`count`,`item_id` FROM `sales_total` 
								WHERE `date` >= '$date1' AND `date` <= '$date2' AND `user_id` = '$id'");
		while($list = mysqli_fetch_assoc($r))
		{	
			#$date = date('Y.m.d',$list['date']);
			$login = get_current_users('login',$list['user_id']);
			$item_name = get_item_param($list['id'],'name');
			$money_x = $money_x + $list['amount'];
			$tr .= "<tr><td>".$login."</td><td>".$item_name."</td><td>".$list['count']."</td><td>".$list['amount']." RUB</td><td>".$list['date']."</td></tr>";
		}
		$btc = convert_sum($money_x);
		$money_x = $money_x." RUB.($btc BTC)";
		echo json_encode(array('stat'=>1,'list'=>$tr,'money_x'=>$money_x));		
		
	}
	
	$token_list = strip_tags($_POST['token_list']);
	if($act == 'save_token_list')
	{
		$list = explode("\n",$token_list);
		foreach($list as $token)
		{
			$r = mysqli_query($db,"SELECT `id_bot` FROM `bot_list` WHERE `token` = '$token'");
			$num = mysqli_num_rows($r);
			if($num > 0)
			{
				$data_x = mysqli_fetch_assoc($r);
				$id_bot = $data_x['id_bot'];
			}else { $id_bot = time() * rand(666,9999); }
			
			file_get_contents("https://api.telegram.org/bot$token/deleteWebhook");
			mysqli_query($db,"DELETE FROM `bot_list` WHERE `token` = '$token'");
			
			$url = "https://".$_SERVER['SERVER_NAME']."/telegram/hook.php?mode=alt:$id_bot";
			$u = "https://api.telegram.org/bot$token/setWebhook?url=".$url;
			file_get_contents($u);
			
			$res = file_get_contents("https://api.telegram.org/bot".$token."/getme");
			$json = json_decode($res);
			if($json->ok == 1)
			{
				$username = $json->result->username;
				$bot_name = $json->result->first_name;
				
				mysqli_query($db,"INSERT INTO `bot_list` (`webhook`,`token`,`botname`,`username`,`id_bot`,`active`)
														VALUES ('$u','$token','$bot_name','$username','$id_bot','1')");
				$id = mysqli_insert_id($db);
				$tr .= "<tr class='item$id'><td>".$id."</td><td>".$bot_name."</td><td>@".$username."</td><td><i class='fa fa-trash del_bot' aria-hidden='true' title='Удалить' value='".$id."'></i></td></tr>";
			}
			
			
		}
		
		mysqli_query($db,"UPDATE `setting` SET `token_list` = '$token_list' WHERE `id` = '1'");
		echo json_encode(array('stat'=>1,'list'=>$tr));
	}
	
	
	if($act == 'del_bot')
	{
		$r = mysqli_query($db,"SELECT `token`,`id_bot` FROM `bot_list` WHERE `id` = '$id'");
		$data = mysqli_fetch_assoc($r);
		$token = $data['token'];
		$id_bot = $data['id_bot'];
		
		file_get_contents("https://api.telegram.org/bot$token/deleteWebhook");
		mysqli_query($db,"DELETE FROM `bot_list` WHERE `id` = '$id'");
		mysqli_query($db,"DELETE FROM `Users` WHERE `id_bot` = '".$id_bot."'");
		
		$token_list = get_setting('token_list');
		$token_list = str_replace($token,'',$token_list);
		$token_list = trim($token_list);
		echo json_encode(array('stat'=>1,'tokens'=>$token_list));
	}
	
	$wallet = strip_tags($_POST['wallet']);
	$api_key = strip_tags($_POST['api_key']);
	$limit = strip_tags($_POST['limit']);
	if($act == 'add_qiwi_wallet')
	{	
		$balance = get_balance($api_key,$wallet);
		$url_x = 'https://'.$_SERVER['SERVER_NAME']."/payment_qiwi.php?login=$wallet";
		$hookId = add_webhook($api_key,$url_x);
		$s = "INSERT INTO `multiple_qiwi` (`wallet`,`api_key`,`limit`,`balance`,`status`,`hookId`) 
								 VALUES ('$wallet','$api_key','$limit','$balance','1','$hookId')";
		mysqli_query($db,$s);
		$id = mysqli_insert_id($db);
		
		echo json_encode(array('stat'=>1,'id'=>$id,'balance'=>$balance));
	}
	
	if($act == 'del_wallet')
	{
		del_webhook($id);
		
		mysqli_query($db,"DELETE FROM `multiple_qiwi` WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}
	
	$type = intval($_POST['type']);
	if($act == 'qiwi_change_method')
	{
		mysqli_query($db,"UPDATE `setting` SET `qiwi_method` = '$type' WHERE `id` = '1'");
		echo json_encode(array('stat'=>1));
	}
	

?>