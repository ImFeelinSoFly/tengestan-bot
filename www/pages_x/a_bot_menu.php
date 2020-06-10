<style>	
	.add_{color: #38dc2f;}
	.edit_{color:#E4C14B; }
	.link_menu:hover{ text-decoration:none; }
	.group_menu{color: #107592;font-weight:600;}
	.group_menu:hover{ text-decoration:none; }
	.sorted{width:47px;background: #fff;font-weight: 600;padding: 2px 5px;color: #8e8e8e;}
	.btn_name{display:inline-block;width:87%;}
	.edit_message{cursor:pointer;padding:4px;position:relative;top:-1px;color:#FF5722;font-size: 18px;}
	.edit_message:hover{color:#6d6b6b;}
	textarea.form-control{margin: 12px 2px 0px 0px;height: 213px;width: 564px;}
	.btn_{float:none;background:#333333;padding:4px 8px;font-size:10px;text-transform:uppercase;position:relative;left: 5px;}	
	#salute{width:100%;height:112px;margin-bottom:16px;background: #eaeaea;}
	.del_item{cursor:pointer;}
	.del_item:hover{color:red;}
	.new_element{color:#777;cursor:pointer;}
	.map_x{color: #82898e;}
	.table_m{width: 100%;margin-bottom: 18px;margin-top: 12px; }
	.new_button{width:155px;float:right;margin-top:-29px;font-size:13px;}
</style>
	
	
	<div class="col-md-12">
	<input type='hidden' id='current_id' value='0'>
	
          <h4>Меню бота</h4>
        <button type="button" class="btn btn-primary btn-lg btn_ new_button" >Новая кнопка <i class="fa fa-plus-circle" aria-hidden="true"></i></button>
		<div class='menu'>
		<div class="col-md-12">
		
		<label class="control-label" >Сообщение привествия:</label>
		<?
			$msg = get_setting('salute');
			$msg = htmlspecialchars_decode($msg);
		?>
			<textarea class="form-control" id='salute' ><?=$msg;?></textarea>
		</div>
		 <table class="table">
		<thead>
		  <tr style='background:#70A7BC;color:#fff;'>
			<th style='width:80px;'>Icon</th>
			<th style='min-width:40px;'>Name</th>
			<th style='min-width:40px;'>Sort</th>
			<th style='min-width:40px;width:104px;'>Позиция</th>
			<th style='min-width:40px;'>#</th>
			
			<th style='min-width:40px;'></th>
		  </tr>
		</thead>
		<tbody>
		<?
			
			$r = mysqli_query($db,"SELECT `id`,`sid`,`name`,`active`,`type`,`pos` FROM `struktura` WHERE  `pid` = '2' ORDER BY `sid` ASC");
			while($menu = mysqli_fetch_assoc($r))
			{
				$id = $menu['id'];
			#	$active = $ok['active'];
				$r2 = mysqli_query($db,"SELECT `id_unicode` FROM `unicode_struktura` WHERE `cmd_id` = '$id'");
				$is = mysqli_num_rows($r2);
				if($is > 0)
				{
					$uni = mysqli_fetch_assoc($r2);							
					$self_ico_id = intval($uni['id_unicode']);
				} else { $self_ico_id = 0; }
				
				$r1 = mysqli_query($db,"SELECT `id`,`u` FROM `unicode`");
				$options = "<option value='0' >-</option>";
				while($ico = mysqli_fetch_assoc($r1))
				{
					$ico_id = intval($ico['id']);
					
					if(($self_ico_id == $ico_id) && ($self_ico_id > 0)){ $sel_ = 'selected'; } else { $sel_ = ''; }
					$options .= "<option value='".$ico['id']."' $sel_>".base64_decode($ico['u'])."</option>";
				}
				
				if($menu['active'] == 0){ $chk = 'checked'; } else { $chk = ''; }
				
				$options_pos = "<option value='none' $sel>-</option>";
				for($q=1;$q<8;$q++)
				{
					if($q == $menu['pos']){ $sel = 'selected'; } else { $sel = ''; }
					$options_pos .= "<option value='$q' $sel>Ряд $q</option>";
				}
				
		?>
		 <tr class='del_ tr<?=$menu['id'];?>' >
			<td>
			<select class='select_list change_ico' name='unicode' style='width:100%;' value='<?=$menu['id'];?>'><?=$options;?></select>
			</td>
			<td> 
			<?if($menu['type'] == 1){ $width = 'style="width:87%;"'; $width_val = '30%'; } else { $width = ''; $width_val = '77%'; }
			?>
				<input type="text" class="form-control btn_name set_btn_bot name<?=$id;?>" <?=$width;?> id_com='<?=$menu['id'];?>' value='<?=$menu['name'];?>'>
			<i class="fa fa-plus-square new_element" aria-hidden="true" value='<?=$id;?>'></i>	
			</td>
			<td><input type='number' min='0' name='pos' class='sorted' id_com='<?=$menu['id'];?>' value='<?=$menu['sid'];?>' ></td>
					
			<td>
					<select class='select_list position_btn'  style='width:100%;' value='<?=$menu['id'];?>' ><?=$options_pos;?></select>
			</td>
			<td>
			<a class='edit_message msg<?=$menu['id'];?>' value='<?=$menu['id'];?>' data-toggle="modal" data-target="#edit_msg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
			<input type='checkbox' class='active_chk ' value='<?=$menu['id'];?>' <?=$chk;?>>
			</td>
			
			<td>
					<i class='fa fa-trash del_item' title='Удалить' aria-hidden='true' value='<?=$menu['id'];?>'></i>
			</td>
		  </tr>
			<?
				
				$rx = mysqli_query($db,"SELECT `id`,`sid`,`name`,`active`,`pos` FROM `struktura` 
															WHERE `pid` = '$id' ORDER BY `sid` ASC");
				$num = mysqli_num_rows($rx);
				if($num > 0)
				{
				while($menu1 = mysqli_fetch_assoc($rx))
				{
					$id = $menu1['id'];
					if($menu1['active'] == 0){ $chk1 = 'checked'; } else { $chk1 = ''; }
					
					if($id == 14){ $dis = 'disabled'; } else { $dis = ''; }
					
					$r3 = mysqli_query($db,"SELECT `id_unicode` FROM `unicode_struktura` WHERE `cmd_id` = '$id'");
					$is = mysqli_num_rows($r3);
					if($is > 0)
					{
						$uni1 = mysqli_fetch_assoc($r3);							
						$self_ico_id = intval($uni1['id_unicode']);
					} else { $self_ico_id = 0; }
					
					$r11 = mysqli_query($db,"SELECT `id`,`u` FROM `unicode`");
					$options = "<option value='0' >-</option>";
					while($ico1 = mysqli_fetch_assoc($r11))
					{
						$ico_id = intval($ico1['id']);
						
						if(($self_ico_id == $ico_id) && ($self_ico_id > 0)){ $sel_ = 'selected'; } else { $sel_ = ''; }
					#	echo $self_ico_id .'=='. $ico['id'].' | '.$sel_."\n";
						$options .= "<option value='".$ico1['id']."' $sel_>".base64_decode($ico1['u'])."</option>";
					}
					
					$options_pos1 = "<option value='none' $sel>-</option>";
					for($q=1;$q<8;$q++)
					{
						if($q == $menu1['pos']){ $sel = 'selected'; } else { $sel = ''; }
						$options_pos1 .= "<option value='$q' $sel>Ряд $q</option>";
					}
					?>
					 <tr class='del_ tr<?=$menu1['id'];?>' style='background:#cacaca47;'>
					<td>
					<select class='select_list change_ico' name='unicode' style='width:100%;' value='<?=$menu1['id'];?>' ><?=$options;?></select>
					</td>
					<td>
						<input type="text" class="form-control btn_name set_btn_bot name<?=$menu1['id'];?>" style='margin-left:20px;width:<?=$width_val;?>;' id_com='<?=$menu1['id'];?>' value='<?=$menu1['name'];?>' >
						<i class="fa fa-plus-square new_element" aria-hidden="true" value='<?=$menu1['id'];?>'></i>
					</td>
					<td><input type='number' min='0' name='pos' class='sorted' id_com='<?=$menu1['id'];?>' value='<?=$menu1['sid'];?>' ></td>
					<td>
						<select class='select_list position_btn'  style='width:100%;' value='<?=$menu1['id'];?>' ><?=$options_pos1;?></select>
					</td>
					
					<td>					
					<a class='edit_message msg<?=$menu1['id'];?>' value='<?=$menu1['id'];?>' data-toggle="modal" data-target="#edit_msg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
					
					<input type='checkbox' class='active_chk' value='<?=$menu1['id'];?>' <?=$chk1;?>>
					</td>
					
					<td>
					<i class='fa fa-trash del_item' title='Удалить' aria-hidden='true' value='<?=$menu1['id'];?>'></i>
					</td>
					
					</tr>
				<?
				$rx1 = mysqli_query($db,"SELECT `id`,`sid`,`name`,`active`,`pos` FROM `struktura` 
															WHERE `pid` = '".$menu1['id']."' ORDER BY `sid` ASC");
				$num = mysqli_num_rows($rx1);
				if($num > 0)
				{
				while($menu2 = mysqli_fetch_assoc($rx1))
				{
					$id = $menu2['id'];
					if($menu2['active'] == 0){ $chk1 = 'checked'; } else { $chk1 = ''; }
					
					if($id == 14){ $dis = 'disabled'; } else { $dis = ''; }
					
					$r3 = mysqli_query($db,"SELECT `id_unicode` FROM `unicode_struktura` WHERE `cmd_id` = '$id'");
					$is = mysqli_num_rows($r3);
					if($is > 0)
					{
						$uni1 = mysqli_fetch_assoc($r3);							
						$self_ico_id = intval($uni1['id_unicode']);
					} else { $self_ico_id = 0; }
					
					$r11 = mysqli_query($db,"SELECT `id`,`u` FROM `unicode`");
					$options = "<option value='0' >-</option>";
					while($ico1 = mysqli_fetch_assoc($r11))
					{
						$ico_id = intval($ico1['id']);
						
						if(($self_ico_id == $ico_id) && ($self_ico_id > 0)){ $sel_ = 'selected'; } else { $sel_ = ''; }
						$options .= "<option value='".$ico1['id']."' $sel_>".base64_decode($ico1['u'])."</option>";
					}
					$options_pos2 = "<option value='none' $sel>-</option>";
					for($q=1;$q<8;$q++)
					{
						if($q == $menu2['pos']){ $sel = 'selected'; } else { $sel = ''; }
						$options_pos2 .= "<option value='$q' $sel>Ряд $q</option>";
					}
					?>
					 <tr class='del_ tr<?=$menu2['id'];?>' style='background:#d4cece8a;'>
					<td>
					<select class='select_list change_ico' name='unicode' style='width:100%;' value='<?=$menu2['id'];?>' ><?=$options;?></select>
					</td>
					<td>
						<input type="text" class="form-control btn_name set_btn_bot name<?=$menu2['id'];?>" style='margin-left:54px;width:<?=$width_val;?>;' id_com='<?=$menu2['id'];?>' value='<?=$menu2['name'];?>' >
						<i class="fa fa-plus-square new_element" aria-hidden="true" value='<?=$menu2['id'];?>'></i>
					</td>
					<td><input type='number' min='0' name='pos' class='sorted' id_com='<?=$menu2['id'];?>' value='<?=$menu2['sid'];?>' ></td>
					<td>
						<select class='select_list position_btn'  style='width:100%;' value='<?=$menu2['id'];?>' ><?=$options_pos2;?></select>
					</td>
					
					<td>					
					<a class='edit_message msg<?=$menu1['id'];?>' value='<?=$menu2['id'];?>' data-toggle="modal" data-target="#edit_msg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
					
					<input type='checkbox' class='active_chk' value='<?=$menu2['id'];?>' <?=$chk1;?>>
					</td>
					
					<td>
					<i class='fa fa-trash del_item' title='Удалить' aria-hidden='true' value='<?=$menu2['id'];?>'></i>
					</td>
					
					</tr>
					
				<?
				$rx2 = mysqli_query($db,"SELECT `id`,`sid`,`name`,`active`,`pos` FROM `struktura` 
															WHERE `pid` = '".$menu2['id']."' ORDER BY `sid` ASC");
				$num = mysqli_num_rows($rx2);
				if($num > 0)
				{
				while($menu3 = mysqli_fetch_assoc($rx2))
				{
					$id = $menu3['id'];
					if($menu3['active'] == 0){ $chk1 = 'checked'; } else { $chk1 = ''; }
					
					if($id == 14){ $dis = 'disabled'; } else { $dis = ''; }
					
					$r3 = mysqli_query($db,"SELECT `id_unicode` FROM `unicode_struktura` WHERE `cmd_id` = '$id'");
					$is = mysqli_num_rows($r3);
					if($is > 0)
					{
						$uni1 = mysqli_fetch_assoc($r3);							
						$self_ico_id = intval($uni1['id_unicode']);
					} else { $self_ico_id = 0; }
					
					$r11 = mysqli_query($db,"SELECT `id`,`u` FROM `unicode`");
					$options = "<option value='0' >-</option>";
					while($ico1 = mysqli_fetch_assoc($r11))
					{
						$ico_id = intval($ico1['id']);
						
						if(($self_ico_id == $ico_id) && ($self_ico_id > 0)){ $sel_ = 'selected'; } else { $sel_ = ''; }
						$options .= "<option value='".$ico1['id']."' $sel_>".base64_decode($ico1['u'])."</option>";
					}
					$options_pos2 = "<option value='none' $sel>-</option>";
					for($q=1;$q<8;$q++)
					{
						if($q == $menu3['pos']){ $sel = 'selected'; } else { $sel = ''; }
						$options_pos2 .= "<option value='$q' $sel>Ряд $q</option>";
					}
					?>
					 <tr class='del_ tr<?=$menu3['id'];?>' style='background:#d4cece8a;'>
					<td>
					<select class='select_list change_ico' name='unicode' style='width:100%;' value='<?=$menu2['id'];?>' ><?=$options;?></select>
					</td>
					<td>
						<input type="text" class="form-control btn_name set_btn_bot name<?=$menu3['id'];?>" style='margin-left:71px;width:74%;' id_com='<?=$menu3['id'];?>' value='<?=$menu3['name'];?>' >
						<i class="fa fa-plus-square new_element" aria-hidden="true" value='<?=$menu3['id'];?>'></i>
					</td>
					<td><input type='number' min='0' name='pos' class='sorted' id_com='<?=$menu3['id'];?>' value='<?=$menu3['sid'];?>' ></td>
					<td>
						<select class='select_list position_btn'  style='width:100%;' value='<?=$menu3['id'];?>' ><?=$options_pos2;?></select>
					</td>
					
					<td>					
					<a class='edit_message msg<?=$menu2['id'];?>' value='<?=$menu3['id'];?>' data-toggle="modal" data-target="#edit_msg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
					
					<input type='checkbox' class='active_chk' value='<?=$menu3['id'];?>' <?=$chk1;?>>
					</td>
					
					<td>
					<i class='fa fa-trash del_item' title='Удалить' aria-hidden='true' value='<?=$menu3['id'];?>'></i>
					</td>
					
					</tr>
					
				<?
				$rx3 = mysqli_query($db,"SELECT `id`,`sid`,`name`,`active`,`pos` FROM `struktura` 
															WHERE `pid` = '".$menu3['id']."' ORDER BY `sid` ASC");
				$num = mysqli_num_rows($rx3);
				if($num > 0)
				{
				while($menu4 = mysqli_fetch_assoc($rx3))
				{
					$id = $menu4['id'];
					if($menu4['active'] == 0){ $chk1 = 'checked'; } else { $chk1 = ''; }
					
					if($id == 14){ $dis = 'disabled'; } else { $dis = ''; }
					
					$r4 = mysqli_query($db,"SELECT `id_unicode` FROM `unicode_struktura` WHERE `cmd_id` = '$id'");
					$is = mysqli_num_rows($r3);
					if($is > 0)
					{
						$uni1 = mysqli_fetch_assoc($r4);							
						$self_ico_id = intval($uni1['id_unicode']);
					} else { $self_ico_id = 0; }
					
					$r11 = mysqli_query($db,"SELECT `id`,`u` FROM `unicode`");
					$options = "<option value='0' >-</option>";
					while($ico1 = mysqli_fetch_assoc($r11))
					{
						$ico_id = intval($ico1['id']);
						
						if(($self_ico_id == $ico_id) && ($self_ico_id > 0)){ $sel_ = 'selected'; } else { $sel_ = ''; }
						$options .= "<option value='".$ico1['id']."' $sel_>".base64_decode($ico1['u'])."</option>";
					}
					$options_pos2 = "<option value='none' $sel>-</option>";
					for($q=1;$q<8;$q++)
					{
						if($q == $menu4['pos']){ $sel = 'selected'; } else { $sel = ''; }
						$options_pos2 .= "<option value='$q' $sel>Ряд $q</option>";
					}
					?>
					 <tr class='del_ tr<?=$menu4['id'];?>' style='background:#b5b5b58a;'>
					<td>
					<select class='select_list change_ico' name='unicode' style='width:100%;' value='<?=$menu3['id'];?>' ><?=$options;?></select>
					</td>
					<td>
						<input type="text" class="form-control btn_name set_btn_bot name<?=$menu4['id'];?>" style='margin-left:87px;width:71%;' id_com='<?=$menu4['id'];?>' value='<?=$menu4['name'];?>' >
						<i class="fa fa-plus-square new_element" aria-hidden="true" value='<?=$menu4['id'];?>'></i>
					</td>
					<td><input type='number' min='0' name='pos' class='sorted' id_com='<?=$menu4['id'];?>' value='<?=$menu4['sid'];?>' ></td>
					<td>
						<select class='select_list position_btn'  style='width:100%;' value='<?=$menu4['id'];?>' ><?=$options_pos2;?></select>
					</td>
					
					<td>					
					<a class='edit_message msg<?=$menu3['id'];?>' value='<?=$menu4['id'];?>' data-toggle="modal" data-target="#edit_msg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
					
					<input type='checkbox' class='active_chk' value='<?=$menu4['id'];?>' <?=$chk1;?>>
					</td>
					
					<td>
					<i class='fa fa-trash del_item' title='Удалить' aria-hidden='true' value='<?=$menu4['id'];?>'></i>
					</td>
					
					</tr>
					
				<?
				$rx4 = mysqli_query($db,"SELECT `id`,`sid`,`name`,`active`,`pos` FROM `struktura` 
															WHERE `pid` = '".$menu4['id']."' ORDER BY `sid` ASC");
				$num = mysqli_num_rows($rx4);
				if($num > 0)
				{
				while($menu5 = mysqli_fetch_assoc($rx4))
				{
					$id = $menu5['id'];
					if($menu5['active'] == 0){ $chk1 = 'checked'; } else { $chk1 = ''; }
					
					if($id == 14){ $dis = 'disabled'; } else { $dis = ''; }
					
					$r5 = mysqli_query($db,"SELECT `id_unicode` FROM `unicode_struktura` WHERE `cmd_id` = '$id'");
					$is = mysqli_num_rows($r3);
					if($is > 0)
					{
						$uni1 = mysqli_fetch_assoc($r5);							
						$self_ico_id = intval($uni1['id_unicode']);
					} else { $self_ico_id = 0; }
					
					$r11 = mysqli_query($db,"SELECT `id`,`u` FROM `unicode`");
					$options = "<option value='0' >-</option>";
					while($ico1 = mysqli_fetch_assoc($r11))
					{
						$ico_id = intval($ico1['id']);
						
						if(($self_ico_id == $ico_id) && ($self_ico_id > 0)){ $sel_ = 'selected'; } else { $sel_ = ''; }
						$options .= "<option value='".$ico1['id']."' $sel_>".base64_decode($ico1['u'])."</option>";
					}
					$options_pos2 = "<option value='none' $sel>-</option>";
					for($q=1;$q<8;$q++)
					{
						if($q == $menu5['pos']){ $sel = 'selected'; } else { $sel = ''; }
						$options_pos2 .= "<option value='$q' $sel>Ряд $q</option>";
					}
					?>
					 <tr class='del_ tr<?=$menu5['id'];?>' style='background:#a0a0a08a;'>
					<td>
					<select class='select_list change_ico' name='unicode' style='width:100%;' value='<?=$menu5['id'];?>' ><?=$options;?></select>
					</td>
					<td>
						<input type="text" class="form-control btn_name set_btn_bot name<?=$menu5['id'];?>" style='margin-left:95px;width:71%;' id_com='<?=$menu5['id'];?>' value='<?=$menu5['name'];?>' >
						<i class="fa fa-plus-square new_element" aria-hidden="true" value='<?=$menu5['id'];?>'></i>
					</td>
					<td><input type='number' min='0' name='pos' class='sorted' id_com='<?=$menu5['id'];?>' value='<?=$menu5['sid'];?>' ></td>
					<td>
						<select class='select_list position_btn'  style='width:100%;' value='<?=$menu5['id'];?>' ><?=$options_pos2;?></select>
					</td>
					
					<td>					
					<a class='edit_message msg<?=$menu4['id'];?>' value='<?=$menu5['id'];?>' data-toggle="modal" data-target="#edit_msg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
					
					<input type='checkbox' class='active_chk' value='<?=$menu5['id'];?>' <?=$chk1;?>>
					</td>
					
					<td>
					<i class='fa fa-trash del_item' title='Удалить' aria-hidden='true' value='<?=$menu5['id'];?>'></i>
					</td>
					
					</tr>
					<?
					
					
				}
				}
							
					
					
				}
				}
							
					
					
					
				}
				}
									
								
					
					
					
				}
				}
				
		?>
			<?}
				}
				}?>
		 
		 
		</tbody>
	  </table>
		</div>
		
        </div>
		
		

<div class="modal fade" id="edit_msg" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" >Содержимое сообщения</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type='hidden' value='0' id='current_id'>
		<div class="col-md-12">
		  <div class="form-group">
			<div id='data_text'></div>
			
		  </div>
		
      </div>
      <div class="modal-footer">
	   <button type="button" class="btn btn-primary save_msg_text">Сохранить</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
       
      </div>
    </div>
  </div>
</div>
</div>
		

<!-- Modal -->
<div class="modal fade" id="new_element" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" >Добавить пункт меню</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
		<div class="col-md-12">
	
		<table border='0' class='table_m'>
			<tr>
				<th>Ico</th>
				<th>Название кнопки:</th>
			</tr>
			
			<tr>
				<td> <select class='select_list id_ico' name='unicode' style='width:100%;' value='<?=$menu['id'];?>'><?=$options;?></select></td>
				<td><input type="text" class="form-control name_btn" id='name' placeholder='Test'></td>
			</tr>
			
		</table>
	
		  <label class='map_x'></label>
		
      </div>
      <div class="modal-footer">
	  <button type="button" class="btn btn-primary btn-lg btn_" data-dismiss="modal">Закрыть</button>
	  <button type="button" class="btn btn-primary btn-lg btn_ add_button" >Добавить</button>
	  
		 <a href='/<?=$act;?>/' class="btn btn-primary btn-lg btn_ btc_refresh" style='display:none;'>Готово</a>
       
      </div>
    </div>
  </div>
</div>
</div>
 		
		
		

<!-- Modal -->
<div class="modal fade" id="new_button_show" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" >Добавить новую кнопку меню</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
		<div class="col-md-12">
	
		<table border='0' class='table_m'>
			<tr>
				<th>Ico</th>
				<th>Название кнопки:</th>
			</tr>
			
			<tr>
				<td> <select class='select_list id_ico_x' name='unicode' style='width:100%;' value='<?=$menu['id'];?>'><?=$options;?></select></td>
				<td><input type="text" class="form-control name_btn_x"  placeholder='Test'></td>
			</tr>
		</table>
	
      </div>
      <div class="modal-footer">
	  <button type="button" class="btn btn-primary btn-lg btn_" data-dismiss="modal">Закрыть</button>
	  <button type="button" class="btn btn-primary btn-lg btn_ new_button_bot" >Добавить</button>
	  
		 <a href='/<?=$act;?>/' class="btn btn-primary btn-lg btn_ btc_refresh_x" style='display:none;'>Готово</a>
       
      </div>
    </div>
  </div>
</div>
</div>
 				