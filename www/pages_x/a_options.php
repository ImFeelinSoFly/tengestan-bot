

	<div class="col-md-12">
	
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#home">Общие</a></li>
		<li ><a data-toggle="tab" href="#refers">Партнерка</a></li>
		<li><a data-toggle="tab" href="#menu1">Настройка платежных систем</a></li>
		<li ><a data-toggle="tab" href="#prop_list">Свойства товара</a></li>
		<li ><a data-toggle="tab" href="#order_step">Этапы оформления заказа</a></li>
		<li ><a data-toggle="tab" href="#bot_manager">Зеркала бота</a></li>
	</ul>
  
  		
			
	
  <div class="tab-content">
  

  
    <div id="home" class="tab-pane fade in active">
      <h3>Общие настройки</h3>
		
		<?
			$s = "SELECT `qiwi_token`,`bot_token`,`max_connections`,`chat_id_admin`,`type_payment`,`min_sum_balance` FROM `setting` WHERE `id` = '1'";
			$r = mysqli_query($db,$s);
			$data_x = mysqli_fetch_assoc($r);
			$type_payment = $data_x['type_payment'];
			
			$type_pay[1] = 'Юзер пополняет свой баланс внутри бота, и через этот баланс оплачивает товары.';
			$type_pay[2] = 'Юзер добавляет товары в корзину, создает заказ и оплачивает его.';
		?>

	 
		<div class="col-md-5">
		<form id="data_setting_bot">	 
			
			<label class="control-label" >TOKEN BOT:</label>
			<input type="password" class="form-control" name='bot_token' value='<?=$data_x['bot_token'];?>'>
		
			<label class="control-label" >Тип оплаты:</label>
			<select class="form-control select2 type_payment"  name='type_payment' data-placeholder="Выберите тип приема оплаты" style="width: 100%;">
				<option value='1' <?if($type_payment == 1){ echo 'selected';}?>>Оплата товаров через Внутренний баланс пользователя</option>
				<option value='2' <?if($type_payment == 2){ echo 'selected';}?>>Оплата заказа созданного через Корзину</option>
			</select>
			<label class='label label-primary info_type_payment'><?=$type_pay[$type_payment];?></label>
			<span id='box_min_sum_balance' <?if($type_payment == 2){ echo 'style="display:none;"';}?>>
				<label class="control-label" >Мин сумма пополнения:</label>
				<input type="text" class="form-control" name='min_sum_balance' value='<?=$data_x['min_sum_balance'];?>' placeholder='100' style='width:100%;'>
			</span>	
			<br><br>
			
			<label class="control-label" >Одновременных обращений к боту[1-100]:</label>
			<input type="text" class="form-control" name='max_connections' value='<?=$data_x['max_connections'];?>' placeholder='min 35' style='width:100%;'>
			
			<hr>
			<label class="control-label"><b>CHAT_ID ADMIN:</b></label>
			<select class="form-control select2 cat_list"  name='chat_id_admin' data-placeholder="Укажите админа" style="width: 100%;">
			<?
				$r = mysqli_query($db,"SELECT `id_chat`,`user` FROM `Users` WHERE `id_bot` = '0'");
				while($users = mysqli_fetch_assoc($r))
				{
					if($users['id_chat'] == $data_x['chat_id_admin']){ $sel = 'selected'; } else { $sel = ''; }
					?><option value='<?=$users['id_chat'];?>' <?=$sel;?>><?=$users['user'];?></option><?
				}
			?>
			</select>
		   		
		</form>
		<br>
		<button type="button" class="btn btn-primary btn-lg btn_ save_opt_bot" style='width:100%;'>Сохранить <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
		</div>
	
	
	<div class="col-md-5">
		<form id="data_setting2">	 
			
			<label class="control-label" >Раздел каталога:</label>
			<select class="form-control select2 "  name='catalog_id' data-placeholder="Укажите каталог" style="width: 100%;">
			<?
				$id_cat = get_setting('catalog_id');
				$r = mysqli_query($db,"SELECT `id`,`name` FROM `struktura` WHERE `active` = '0' AND `pid` = '2'");
				while($list = mysqli_fetch_assoc($r))
				{
					if($list['id'] == $id_cat){ $sel = 'selected'; } else { $sel = ''; }
					?><option value='<?=$list['id'];?>' <?=$sel;?>><?=$list['name'];?></option><?
					
					$r1 = mysqli_query($db,"SELECT `id`,`name` FROM `struktura` WHERE `active` = '0' AND `pid` = '".$list['id']."'");
					while($list1 = mysqli_fetch_assoc($r1))
					{
						if($list1['id'] == $id_cat){ $sel = 'selected'; } else { $sel = ''; }
						?><option value='<?=$list1['id'];?>' <?=$sel;?>><?=$list1['name'];?></option><?
					}
				}
			?>
			</select>
			
			
			<label class="control-label" >Раздел корзины:</label>
			<select class="form-control select2 basket_id"  name='basket_id' data-placeholder="Укажите каталог" style="width: 100%;"
			<?if($type_payment == 1){ echo 'disabled'; }?>>
			<?
				$basket_id = get_setting('basket_id');
				$r = mysqli_query($db,"SELECT `id`,`name` FROM `struktura` WHERE `active` = '0' AND `pid` = '2'");
				while($list = mysqli_fetch_assoc($r))
				{
					if($list['id'] == $basket_id){ $sel = 'selected'; } else { $sel = ''; }
					?><option value='<?=$list['id'];?>' <?=$sel;?>><?=$list['name'];?></option><?
					
					$r1 = mysqli_query($db,"SELECT `id`,`name` FROM `struktura` WHERE `active` = '0' AND `pid` = '".$list['id']."'");
					while($list1 = mysqli_fetch_assoc($r1))
					{
						if($list1['id'] == $basket_id){ $sel = 'selected'; } else { $sel = ''; }
						?><option value='<?=$list1['id'];?>' <?=$sel;?>><?=$list1['name'];?></option><?
					}
				}
			?>
			</select>			
			
			<label class="control-label" >Кнопка: Пригласить друга:</label>
			<select class="form-control select2 "  name='ref_button' data-placeholder="Укажите каталог" style="width: 100%;">
			<?
				$ref_button = get_setting('ref_button');
				$r = mysqli_query($db,"SELECT `id`,`name` FROM `struktura` WHERE `active` = '0' AND `pid` = '2'");
				while($list = mysqli_fetch_assoc($r))
				{
					if($list['id'] == $ref_button){ $sel = 'selected'; } else { $sel = ''; }
					?><option value='<?=$list['id'];?>' <?=$sel;?>><?=$list['name'];?></option><?
					
					$r1 = mysqli_query($db,"SELECT `id`,`name` FROM `struktura` WHERE `active` = '0' AND `pid` = '".$list['id']."'");
					while($list1 = mysqli_fetch_assoc($r1))
					{
						if($list1['id'] == $ref_button){ $sel = 'selected'; } else { $sel = ''; }
						?><option value='<?=$list1['id'];?>' <?=$sel;?>><?=$list1['name'];?></option><?
					}
				}
			?>
			</select>
		   		
		</form>
		<br>
		<button type="button" class="btn btn-primary btn-lg btn_ save_opt2" style='width:100%;'>Сохранить <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
		</div>	
	 
    </div>
	
	
   <div id="refers" class="tab-pane fade in ">
      <h3>Партнерка</h3>
		<?
			$s = "SELECT `ref_bonus`,`ref_at_friend`,`ref_friend_5`,`ref_friend_15`,`ref_friend_30`,`ref_friend_50`,
							`ref_friend_100`,`type_referer`,`gain_proc` FROM `setting` WHERE `id` = '1'";
			$r = mysqli_query($db,$s);
			$ok = mysqli_fetch_assoc($r);
			
			switch($ok['type_referer'])
			{
				case 1:
					$type_ref1 = 'checked'; $type_ref2 = '';break;
				case 2:
					$type_ref1 = ''; $type_ref2 = 'checked';break;
				default:
					$type_ref1 = 'checked'; $type_ref2 = '';
			}
		?>	
	
	<div class="col-md-4" >
	<label class='type_referer'><input type='radio' class='is_type_referer' value='1' name='type_ref' <?=$type_ref1;?>> Скидка на одну покупку</label><br><br>
	<form id="data_setting">
		
		  <div class="form-group" style='padding-bottom:15px;'>
			
			<label class="control-label" >Скидка % за приглашенного друга Вашим рефералом:</label>
			<input type="text" class="form-control" name='ref_at_friend' value='<?=$ok['ref_at_friend'];?>'>
			<hr>
			<label class="control-label" >Скидка % за 5 приглашенных друзей:</label>
			<input type="text" class="form-control" name='ref_friend_5' value='<?=$ok['ref_friend_5'];?>'>
			<hr>
			<label class="control-label" >Скидка % за 15 приглашенных друзей:</label>
			<input type="text" class="form-control" name='ref_friend_15' value='<?=$ok['ref_friend_15'];?>'>
			<hr>
			<label class="control-label" >Скидка % за 30 приглашенных друзей:</label>
			<input type="text" class="form-control" name='ref_friend_30' value='<?=$ok['ref_friend_30'];?>'>
			<hr>
			<label class="control-label" >Скидка % за 50 приглашенных друзей:</label>
			<input type="text" class="form-control" name='ref_friend_50' value='<?=$ok['ref_friend_50'];?>'>
		   <hr>
		   <label class="control-label" >Скидка % за 100 приглашенных друзей:</label>
			<input type="text" class="form-control" name='ref_friend_100' value='<?=$ok['ref_friend_100'];?>'>
			
		   <br>
		 		
		</div>
		
	   <div class="col-md-12" style='padding-bottom: 45px;'>
			<button type="button" class="btn btn-primary btn-lg btn_ save_opt" >Сохранить <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
		</div>
		  </form> 
	</div>	
	
	<div class="col-md-5">
	<label class='type_referer'><input type='radio' class='is_type_referer' value='2' name='type_ref' <?=$type_ref2;?>> Заработок на рефералах</label><br>
	<br>
		<label class="control-label" ><b>% Отчислений рефералу</b>, от суммы покупки приведенного им клиента:</label>
			<input type="text" class="form-control" id='gain_proc' value='<?=$ok['gain_proc'];?>'>
			<hr>
			<button type="button" class="btn btn-primary btn-lg btn_ save_gain" >Сохранить <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
	</div>
 
		  
  </div>
  	
  <div id="order_step" class="tab-pane fade in ">
      <h4>Что запросить при оформлении заказа(только для режима Корзины)</h4><br>
	  <div class="col-md-12">
		<div class="col-md-6">
			
				<div class="col-md-9">
				<?
					$step_get_name = get_setting('step_get_name');
					$query_info = get_setting('step_text');
					$how = get_setting('how');
					$get_phone = get_setting('get_phone');
					if($step_get_name == 1){$chk = 'checked'; } else { $chk = ''; }
					if($how == 1){$chk_how = 'checked'; } else { $chk_how = ''; }
					if($get_phone == 1){$chk_tel = 'checked'; } else { $chk_tel = ''; }
				?>
					<label class="control-label" >Произвольный вопрос:</label><br>
					<label class="control-label" > <input type="checkbox"  id='qery_name' <?=$chk;?>> Вкл </label>
					<input type='text' id='query_info' value='<?=$query_info;?>'>
					<br><br>
					
					<label class="control-label" >Способ доставки:</label><br>
					<label class="control-label" > <input type="checkbox"  id='how_delivery' <?=$chk_how;?>> Вкл </label>
					<table border='0' class='table table-bordered table-striped table_ items_list'>
					 <thead>
						<tr>
							<th>Способ доставки</th>
							<th>Активен</th>
						</tr>
					 </thead>
						<tbody>
						<?
							$r = mysqli_query($db,"SELECT `id`,`name` FROM `how_delivery`");
							while($how = mysqli_fetch_assoc($r))
							{
						?>
							<tr>
								<td><?=$how['name'];?></td>
								<td>Active</td>
							</tr>
							<?}?>
						</tbody>
					</table>
					
					<label class="control-label" >Запросить номер телефона:</label><br>
					<label class="control-label" > <input type="checkbox"  id='get_phone' <?=$chk_tel;?>> Вкл </label>
					
				</div>
			
			
				<button type="button" class="btn btn-primary btn-lg btn_ save_opt_step" style='width:70%;margin-top:20px;'>Сохранить <i class="fa fa-floppy-o" aria-hidden="true"></i></button>	
		</div>

	</div>
 </div>	
	
    <div id="menu1" class="tab-pane fade">
	 <h5>Платежные системы.</h5><br>
	 
	 
	<ul class="nav nav-tabs">
		<?/*?><li class="active"><a data-toggle="tab" href="#coin_box">Криптовалю́ты</a></li><?*/?>
		<li class="active"><a data-toggle="tab" href="#qiwi_box">QIWI</a></li>
		<li ><a data-toggle="tab" href="#yandex_box">Yandex money</a></li>
		<li ><a data-toggle="tab" href="#bitcoin_box">Bitcoin</a></li>
		<li ><a data-toggle="tab" href="#exmo_code">Exmo-code</a></li>
		<li ><a data-toggle="tab" href="#unitpay">Unitpay</a></li>
		<li ><a data-toggle="tab" href="#freekassa">FreeKassa</a></li>
		<li ><a data-toggle="tab" href="#payment_box">Выплаты</a></li>
	</ul>	
	
  <div class="tab-content">
  <?/*?>
  	<div id="coin_box" class="tab-pane fade in active" style='padding-bottom: 42px;'>
	<br>
	<div class="col-md-12" >
		<h1>В разработке</h1><br>
		<div class="col-md-2" >
		<?for($q=0;$q<14;$q++){?>
			<label>Криптовалю́та</label><br>
			<span class="label "><label class='info_coin'><input type='checkbox' name='bitcoin'> CoinX</label></span>		
		<?}?>
		</div>
		
		
		<div class="col-md-10">
		<?for($q=0;$q<14;$q++){?>
		<div class="col-md-5">
			<label class='info_key'>Public key:</label>
			<input type='password' class='form-control key' name='bitcoin' value='<?=$data_['secret_key'];?>' placeholder='Public key'>
		</div>
		<div class="col-md-5">
			<label class='info_key'>Private key:</label>
			<input type='password' class='form-control key' name='bitcoin' value='<?=$data_['secret_key'];?>' placeholder='Public key'>
		</div>	
		<?}?>
		</div>
	

	</div>	
		

	</div>
  <?*/?>
  
	<div id="qiwi_box" class="tab-pane fade in active">
	<?
		$qiwi_method = get_setting('qiwi_method');
		if($qiwi_method == 1){ $method1 = 'checked';$method2 = ''; } else { $method1 = '';$method2 = 'checked'; }
	?>

	<div class="col-md-4" >
			<br><label class='change_method'>
					<input type='radio' name='qiwi_method' class='qiwi_method' value='1' <?=$method1;?>> Прием оплаты с 1 кошелька </label>
			<br><h4>Настройка - Qiwi</h4><hr>
			<?
				$rx = mysqli_query($db,"SELECT `api_key`,`wallet`,`active` FROM `setting_payment` WHERE `id` = '1'");
				$data_ = mysqli_fetch_assoc($rx);
				if($data_['active'] == 0){ $chk = 'checked'; } else { $chk = ''; }
			?>
			<label><input type='checkbox' id='active_qiwi' <?=$chk;?>> Активен:</label><br>
			<label>Qiwi API token:</label>
			<input type='text' class='form-control' id='api_key_qiwi' value='<?=$data_['api_key'];?>' placeholder='Укажите api key qiwi'>
			<label>Qiwi Wallet:</label>
			<input type='text' class='form-control' id='api_wallet_qiwi' value='<?=$data_['wallet'];?>' placeholder='Укажите wallet qiwi'>
		<br><br>
		<button type="button" class="btn btn-primary btn-lg btn_ save_opt_qiwi" style="width:100%;">Сохранить <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
	  </div>  
	  		  
	<div class="col-md-7" >
		<br><label class='change_method'>
			<input type='radio' name='qiwi_method' class='qiwi_method' value='2' <?=$method2;?>> Прием оплаты с разных кошельков </label>
		<br><h4>Multiple Qiwi</h4><hr>
		
		<div class="col-md-12" >
		
		<div class="col-md-3" >
			<label>Wallet:</label>
			<input type='text' id='wallet_qiwi_' style='width:100%;' placeholder='79XXXXXXXXX'>
			
		</div>
		
		<div class="col-md-3" >
			<label>Api key:</label>
			<input type='text' id='api_key_qiwi_'  style='width:100%;'>
		</div>
		
		<div class="col-md-2" >
			<label>Лимит:</label>
			<input type='number' id='limit_qiwi_'  style='width:100%;padding:3px;' value='20000'>
		</div>
		
		<div class="col-md-3" style='padding-bottom:26px;'><br>
			<button id='add_wallet' class='btn btn-primary btn-lg btn_ add_qiwi_wallet'>Добавить</button>
		</div>
		
		<br>
		
			<table class='table table_x'>
			<thead>
				<tr>
					<th>Wallet</th>
					<th>Balance</th>
					<th>Status</th>
					<th>Limit</th>
					<th>#</th>
				</tr>
			</thead>
			
			<tbody id='result_qiwi'>
				<?
					$r = mysqli_query($db,"SELECT `id`,`wallet`,`api_key`,`limit`,`balance`,`status` FROM `multiple_qiwi`");
					while($list = mysqli_fetch_assoc($r))
					{
						
						switch($list['status'])
						{
							case 1:$active_x = 'Активен';break;
							case 2:$active_x = 'Заморожен';break;
							case 3:$active_x = 'Невалид';
						}
				?>
					<tr class='w_item<?=$list['id'];?>'>
						<td><?=$list['wallet'];?></td>
						<td><?=$list['balance'];?></td>
						<td><?=$active_x;?></td>
						<td><?=$list['limit'];?></td>
						<td><span class='del_wallet' value='<?=$list['id'];?>'>X</span></td>
					</tr>
					<?}?>
			</tbody>
			
			</table>
		</div>

	</div>
		</div>	


	<div id="yandex_box" class="tab-pane fade in">
		  <br><h4>Настройка - Yandex money</h4>
		
	  <div class="col-md-6">
		  <div class="form-group">
			
			<?
				$rx = mysqli_query($db,"SELECT `api_key`,`wallet`,`active` FROM `setting_payment` WHERE `id` = '2'");
				$data_ = mysqli_fetch_assoc($rx);
				if($data_['active'] == 0){ $chk = 'checked'; } else { $chk = ''; }
				
				$rx1 = mysqli_query($db,"SELECT `active` FROM `setting_payment` WHERE `id` = '3' ");
				#$is_card = mysqli_num_rows($rx);echo $is_card;
				$is_card = mysqli_fetch_assoc($rx1);
				if($is_card['active'] == 0){ $chk_card = 'checked'; } else { $chk_card = ''; }
			?>			
			<hr><label><input type='checkbox' id='active_yandex' <?=$chk;?>> Активен:</label><br>
			
			<label><input type='checkbox' id='get_payment_yandex' <?=$chk_card;?>> Прием оплаты с карты</label><br><br>
			
			<label>Wallet:</label>
			<input type='text' class='form-control' id='wallet' value='<?=$data_['wallet'];?>' placeholder='Wallet'>
			<?/*?>
			<label>secret key:</label>
			<input type='text' class='form-control' id='secret_key' value='<?=$data_['api_key'];?>' placeholder='secret key'>
			<?*/?>
			
			<label><b>URL notification</b>(указать <a href="https://money.yandex.ru/settings?_openstat=template%3Bipulldown%3Bsettings&w=other" target='_blank'>тут</a> в Сбор денег\Уведомления):</label><br>
			<input type='text' class='form-control' id='url_hook' value='https://<?=$_SERVER['SERVER_NAME'];?>/payment_yandex.php' readonly>
				<br>
					<button type="button" class="btn btn-primary btn-lg btn_ save_opt_yandex" style="width:100%;">Сохранить <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
			
		  </div>
		
	 </div>		
	</div>
	
	

	<div id="bitcoin_box" class="tab-pane fade in">
		  <br><h4>Настройка - Bitcoin</h4>
		
	  <div class="col-md-6">
		  <div class="form-group">
		<?
			$rx = mysqli_query($db,"SELECT `api_key`,`wallet`,`active` FROM `setting_payment` WHERE `id` = '4'");
			$data_ = mysqli_fetch_assoc($rx);
			if($data_['active'] == 0){ $chk = 'checked'; } else { $chk = ''; }
		?>			
		<hr><label><input type='checkbox' id='active_bitcoin' <?=$chk;?>> Активен:</label><br>
		 
	<?
		$rx = mysqli_query($db,"SELECT `id`,`confirmations`,`wallet`,`level_btc` FROM `setting_payment` WHERE `id` = '4'");
		$ok = mysqli_fetch_assoc($rx);
		
		$sel = 'selected';
		switch($ok['level_btc'])
		{
			case 'low': $opt1 = $sel; $opt2 = ''; $opt3 = '';break;
			case 'medium': $opt1 = ''; $opt2 = $sel; $opt3 = '';break;
			case 'high': $opt1 = ''; $opt2 = ''; $opt3 = $sel;
		}
		
		switch($ok['confirmations'])
		{
			case 1: $conf1 = $sel; $conf2 = ''; $conf3 = '';break;
			case 2: $conf1 = ''; $conf2 = $sel; $conf3 = '';break;
			case 3: $conf1 = ''; $conf2 = ''; $conf3 = $sel;
		}
	
	?>
		<div class="col-md-10">
		 <div class="form-group">
			<label class="control-label" >Fee level:</label>
			<select class="form-control select2 cat_list"  id='level_btc' data-placeholder="Fee level" style="width: 100%;">
				<option value="low" <?=$opt1;?>>low</option>
				<option value="medium" <?=$opt2;?>>medium</option>
				<option value="high" <?=$opt3;?>>high</option>
			</select>
		
			<label class="control-label" >Confirmations:</label>
			<select class="form-control select2 cat_list"  id='confirmations' data-placeholder="Fee level" style="width: 100%;">
				<option value='1' <?=$conf1;?>>1 Confirmations</option>
				<option value='2' <?=$conf2;?>>2 Confirmations</option>
				<option value='3' <?=$conf3;?>>3 Confirmations</option>
			</select>
			
			
			<label class="control-label" >BTC Wallet:</label>
			<input type="text" class="form-control" id='btc_address' style='width:100%;' value='<?=$ok['wallet'];?>' >
		
	
			<br>
			<button type="button" class="btn btn-primary btn-lg btn_ save_opt_btc" style="width:100%;">Сохранить <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
		</div>
		</div>	
		  </div>
		
	 </div>		
	</div>	
		
	<div id="exmo_code" class="tab-pane fade in">
		  <br><h4>Настройка - Exmo code</h4>
		
	  <div class="col-md-6">
		  <div class="form-group">
			
			<?
				$rx = mysqli_query($db,"SELECT `api_key`,`secret_key`,`active` FROM `setting_payment` WHERE `id` = '5'");
				$data_ = mysqli_fetch_assoc($rx);
				if($data_['active'] == 0){ $chk = 'checked'; } else { $chk = ''; }
			?>			
			<hr><label><input type='checkbox' id='active_exmo' <?=$chk;?>> Активен:</label><br>
			
			<label>secret key:</label>
			<input type='text' class='form-control' id='secret_key_exmo' value='<?=$data_['secret_key'];?>' placeholder='secret key'>
			<label>Api key:</label>
			<input type='text' class='form-control' id='api_key_exmo' value='<?=$data_['api_key'];?>' placeholder='Api key'>
			
			<br>
			<button type="button" class="btn btn-primary btn-lg btn_ save_opt_exmo" style="width:100%;">Сохранить <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
			
		  </div>
		
	 </div>		
	</div>		
		
	
	<div id="unitpay" class="tab-pane fade in">
		  <br><h4>Настройка - Unitpay</h4>
		
	  <div class="col-md-6">
		  <div class="form-group">
			
			<?
				$rx = mysqli_query($db,"SELECT `pub_key`,`secret_key`,`active` FROM `setting_payment` WHERE `id` = '8'");
				$data_ = mysqli_fetch_assoc($rx);
				if($data_['active'] == 0){ $chk = 'checked'; } else { $chk = ''; }
			?>			
			<hr><label><input type='checkbox' id='active_unitpay' <?=$chk;?>> Активен:</label><br>
			
			<label>secret key:</label>
			<input type='text' class='form-control' id='secret_key_unitpay' value='<?=$data_['secret_key'];?>' placeholder='secret key'>
			<label>Publick key:</label>
			<input type='text' class='form-control' id='pub_key_unitpay' value='<?=$data_['pub_key'];?>' placeholder='Api key'>
			
			<label><b>URL notification</b>:</label><br>
			<input type='text' class='form-control' value='https://<?=$_SERVER['SERVER_NAME'];?>/payment_unitpay.php' readonly>
			
			<br>
			<button type="button" class="btn btn-primary btn-lg btn_ save_opt_unitpay" style="width:100%;">Сохранить <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
			
		  </div>
		
	 </div>		
	</div>		
	
	
	<div id="freekassa" class="tab-pane fade in">
		  <br><h4>Настройка - Free-kassa</h4>
		
	  <div class="col-md-6">
		  <div class="form-group">
			
			<?
				$rx = mysqli_query($db,"SELECT `pub_key`,`secret_key`,`active` FROM `setting_payment` WHERE `id` = '9'");
				$data_ = mysqli_fetch_assoc($rx);
				if($data_['active'] == 0){ $chk = 'checked'; } else { $chk = ''; }
			?>			
			<hr><label><input type='checkbox' id='active_freekassa' <?=$chk;?>> Активен:</label><br>
			
			<label>Merchant id:</label>
			<input type='text' class='form-control' id='merchant_id_freekassa' value='<?=$data_['pub_key'];?>' placeholder='Merchant id'>
			<label>Secret word:</label>
			<input type='text' class='form-control' id='secret_key_freekassa' value='<?=$data_['secret_key'];?>' placeholder='secret word'>
			
			<label><b>URL оповещения:</b>:</label><br>
			<input type='text' class='form-control' value='https://<?=$_SERVER['SERVER_NAME'];?>/payment_freekassa.php' readonly>
			<br>
			<button type="button" class="btn btn-primary btn-lg btn_ save_opt_freekassa" style="width:100%;">Сохранить <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
			
		  </div>
		
	 </div>		
	</div>			
	
	
	<div id="payment_box" class="tab-pane fade in">
		  <br><h4>Настройка функции Выплат</h4>
	<?/*?>	
	  <div class="col-md-6"><hr>
	  <h4>BTC</h4><hr>
		  <div class="form-group">
			
			<?
				$rx = mysqli_query($db,"SELECT `api_key`,`secret_key`,`wallet` FROM `setting_payment` WHERE `id` = '6'");
				$data_ = mysqli_fetch_assoc($rx);
				
			?>		
			<label>Secret PIN:</label>
			<input type='text' class='form-control' id='secret_key_payment' value='<?=$data_['secret_key'];?>' placeholder='secret key'>
			<label>Api key:</label>
			<input type='text' class='form-control' id='api_key_payment' value='<?=$data_['api_key'];?>' placeholder='Api key'>
			<label>Btc address:</label>
			<input type='text' class='form-control' id='wallet_payment' value='<?=$data_['wallet'];?>' placeholder='Api key'>
			<br>
			<button type="button" class="btn btn-primary btn-lg btn_ save_opt_payment_btc" style="width:100%;">Сохранить <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
			
		  </div>
	 </div>
	 <?*/?>
	  <div class="col-md-6"><hr>
	  <h4>QIWI</h4><hr>
		  <div class="form-group">
			
			<?
				$rx = mysqli_query($db,"SELECT `api_key`,`wallet` FROM `setting_payment` WHERE `id` = '7'");
				$data_t = mysqli_fetch_assoc($rx);
				
			?>		
			<label>Qiwi API token:</label>
			<input type='text' class='form-control' id='api_key_payment_qiwi' value='<?=$data_t['api_key'];?>' placeholder='Укажите api key qiwi'>
			<label>Qiwi Wallet:</label>
			<input type='text' class='form-control' id='api_wallet_payment_qiwi' value='<?=$data_t['wallet'];?>' placeholder='Укажите wallet qiwi'>
			<br>
			<button type="button" class="btn btn-primary btn-lg btn_ save_opt_payment_qiwi" style="width:100%;">Сохранить <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
			
		  </div>
	 </div>
	 	 
	 
	</div>			
	
	</div>	
	
  
    </div>
	
	
	 
	 <div id="prop_list" class="tab-pane fade">
	 <h4>Свойства товара.</h4>

	  <div class="col-md-12" style='margin-bottom:32px;'>
	  <div class="col-md-3">
		<label>Название</label> <input type='text' class='form-control' id='name_prop'>
		<label>Alias</label> <input type='text' class='form-control' id='alias_prop'>
		<label>Тип</label>
		<select class="form-control select2" id='type_prop' style='width:100%;'>
			<option value='1'>Строка</option>
			<option value='2'>Список</option>
		</select><br>
		<button type="button" class="btn btn-primary btn-lg btn_ add_prop" style="width:45%;margin-top:11px;">Добавить </button>
	</div>
	</div>
	
	  <div class="col-md-8">
			<input type='hidden' id='current_level' value='0'>
			 <table class="table table-dark">
			  <thead >
				<tr>
				 
				  <th scope="col">Название</th>
				  <th scope="col">Alias</th>
				   <th scope="col" style='width:96px;'>Sort</th>
				   <th scope="col">Тип</th>
				  <th scope="col">Активность</th>
				  <th>#</th>
				</tr>
			  </thead>
			  <tbody id='level_list'>
			  <?
				$sys['name'] = true;
				$sys['count'] = true;
				$sys['price'] = true;
				$sys['description'] = true;
			  
				$r = mysqli_query($db,"SELECT `id`,`name`,`alias`,`sid`,`active`,`type` FROM `prop_list_catalog`
														WHERE `private` = '0' ORDER BY `sid` ASC");
				while($list = mysqli_fetch_assoc($r))
				{
					if($list['id'] == 10){ continue; }
					if($list['active'] == 0){ $active = 'checked'; } else { $active = ''; }
					if($list['type'] == 1){ $info = 'Строка'; }elseif($list['type'] == 2){ $info = 'Список'; }
				
			  ?>
				<tr class='prop<?=$list['id'];?>' >
				  
				  <td><input type='text' class='set_prop' value='<?=$list['name'];?>' col='name' id_x='<?=$list['id'];?>'></td>
				  <td><input type='text' class='set_prop' value='<?=$list['alias'];?>' col='alias' id_x='<?=$list['id'];?>'></td>
				  <td><input type='number' class='sort_prop' value='<?=$list['sid'];?>' id_x='<?=$list['id'];?>'></td>
				  <td><?=$info;?></td>
				  <td><label> <input type='checkbox' class='active_prop' value='<?=$list['id'];?>' <?=$active;?>> Показывать</label></td>
				  <td><?if(!$sys[$list['alias']]){?>
						<i class="fa fa-trash del_prop_" value='<?=$list['id'];?>' aria-hidden="true" title='В небытие?'></i>
					<?}?>
					</td>
				</tr>
				<?}?>
			  </tbody>
			</table>
			  
	  </div>
	 

	 </div>

	<div id="bot_manager" class="tab-pane fade in">
	  <h4>Менеджер ботов - Зеркала основного бота.</h4>
		
	  <div class="col-md-6"><hr>

		  <div class="col-md-12">
		  <?
			$token_list = get_setting('token_list');
		  ?>
			<label>Token list:</label>
			<textarea id='token_list'><?=$token_list;?></textarea>
			
			<button type="button" class="btn btn-primary btn-lg btn_ save_token_list" >Сохранить </button>
		 </div>
	 </div>
	 
	  <div class="col-md-6"><hr>

	  <table class="table table-bordered table-striped table_x">
		  <thead>
			<tr>
			  <th scope="col" class='invisible_'>ID</th>
			   <th scope="col">Bot</th>
			  <th scope="col">Username</th>
			  <th scope="col">#</th>
			</tr>
		  </thead>
		  <tbody id='list_bot'>
		  <?
			$rx = mysqli_query($db,"SELECT `id`,`botname`,`username` FROM `bot_list`");
			while($list = mysqli_fetch_assoc($rx))
			{
				?>
				<tr class='item<?=$list['id'];?>'>
				<td><?=$list['id'];?></td>
				<td><?=$list['botname'];?></td>
				<td>@<?=$list['username'];?></td>
				<td><i class="fa fa-trash del_bot" aria-hidden="true" title="Удалить" value="<?=$list['id'];?>"></i></td>
				</tr>
				<?
				
			}
		  ?>
		  
		  </tbody>
		  </table>
	  </div>
	
	</div>			
		
 
   
  </div>
		
		
		
	</div>
	
	

<!-- Modal -->
<div class="modal fade" id="new_rang" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" >Добавить новый статус пользователя</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
		<div class="col-md-12">
	

		  <div class="form-group">
			<label class="control-label" >Новый статус:</label>
			<input type="text" class="form-control" name='name_rang' id='name_rang' placeholder='напр Гость'>
		
		  </div>
		
      </div>
      <div class="modal-footer"><br>
	   <button type="button" class="btn btn-primary new_rang_">Добавить</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
       
      </div>
    </div>
  </div>
</div>
</div>
 
	
	
	
<style>	
	.table-striped tbody tr:nth-of-type(odd) {background-color: rgba(0, 0, 0, 0);}
	.table  .btn_filter
	{
		padding: 2px 5px;
		vertical-align: top;
		border-top: 1px solid #dee2e6;
		color: #8c8c8c;
		font-weight: 600;
		font-size: 13px;
		text-indent: 8px;
		cursor:pointer;
	}
	.table  .btn_filter:hover{color: #03A9F4; }
	.sel_obj{font-size: 13px;}
	.opt_col{    color: #505050;font-size: 12px;}
	label {
		color: #6f6e6e;
		font-weight: 300;
		font-size: 17px;
	}
	textarea{width: 100%;height:160px;padding:4px 8px;color: #7d7878;}
	label {color: #6b6b6b;font-weight: 300;font-size: 13px;}
	.info_{color:#868484;font-weight: 300;font-size:12px;}
	.col-md-5{border-right: 1px #e8e7e7 solid;}
	.del_prop_{ cursor:pointer; }
	.del_prop_:hover{ color:red;}
	.active_rang{background: #333333;color: #afaca8;}
	.right{padding-right: 9px;}
	
	.table>tbody>tr>td {word-break: normal;}
	input[type="text"] {font-weight:300;color:#575a5d;padding:4px 7px;height:30px;font-size:12px;width: 82%;}
	.sort_prop{ width: 49px;padding: 1px 5px;color: #716b6b;}
	.info_coin {
		color: #efefef;
		text-shadow: -1px 0px 8px rgba(93, 92, 92, 0.89);
		background-color: #585454;
		padding:4.59px 38px;
		cursor: default;
		border-radius: 5px;
		-moz-border-radius: 5px;
		-webkit-border-radius: 4px;
	}
	
	.info_key {color: #82817f;font-weight: 300;font-size: 11px;text-transform: uppercase;}	
	.key{background-color: #f1f1f1;border: 1px solid #e2e2e2;color: #545252;}
	.save_opt_step{width:39%;float:left;}
	.type_referer{font-size: 17px;padding-left: 17px;color: #bd2121;}
	button{width:100%;}
	.del_value{cursor:pointer;}
	.del_value:hover{color:red;}
	.dataTables_filter{display:none;}
	.del_wallet{color:red;font-size:12px;}
	.del_wallet:hover{cursor:pointer;}
	.del_bot{font-size:12px;}
	.del_bot:hover{cursor:pointer;color:red;}
	.change_method{background:#ece9e9;padding: 5px 8px;color:#606061;font-weight:800;font-size:12px;width:100%;}
	.info_type_payment{color:#fff;background:#333;font-size:10px;}
	
</style>