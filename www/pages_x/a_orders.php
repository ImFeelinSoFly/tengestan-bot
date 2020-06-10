<style>	
	table{width:100%;}
	.items_list{padding: 8px 11px;font-size: 11px;color: #585858;}
	.label{color: #ffffff;text-shadow: -1px 0px 8px rgba(107, 107, 107, 0.89);}
	.del_zakaz{cursor:pointer;}
	.del_zakaz:hover{color:red;}
	.table.dataTable thead th {color: #545454;padding: 5px 6px 6px;border-bottom: 1px solid #e0e0e0;background: #eaeaea;}
	.info_cli{font-size: 11px;color: #828282;}
	.count_z{font-size: 11px;color: #737373;}
	.limit_x{color:#6d6d6d;}
	.show{color: #2e6da4;font-weight: 600;}
	.vars{font-size: 13px;}
	table.dataTable tbody tr {background-color: #ececec;}
	.info_money{margin-left: 10px;font-size: 11px;background: #888;padding: 8px 13px;cursor: default;}
</style>

<script>
	$(document).ready(function(){
		
		var table = $('.table_').DataTable({
		  "paging": false,
		  "lengthChange": false,
		  "searching": false,
		  "ordering": true,
		  "info": false,
		  "autoWidth": true
		  
		});
		
	});

</script>

	<div class="col-md-12">
	<div class="col-md-10" style='padding:0;'>
	<?
		$rx = mysqli_query($db,"SELECT `id` FROM `a_zakaz` ");
		$all_orders = mysqli_num_rows($rx);
		if($i2 == 'all'){ $var1 = ''; $var2 = 'show'; } else {  $var1 = 'show'; $var2 = ''; }
	?>
		<h4>Заказов всего: <?=$all_orders;?></h4>
		

		
	</div>	
		<div class="col-md-2" style='padding-bottom:22px;'>
		<a href='/<?=$act?>/50/' class='vars limit_x <?=$var1;?>'><b>Показывать 20 заказов</b></a>
		<a href='/<?=$act;?>/all/' class='vars limit_x <?=$var2;?>'>Показывать все</a>
		</div>
		
	</div>

	<div class="col-md-12">
	
		<table class="table table-bordered table-striped table_x">
		  <thead>
			<tr>
		      <th scope="col" class='opt_col' style='width:36px;'>ID</th>
			  <th scope="col invisible_" style='width:300px;' class='invisible_'>Товар</th>
			   <th scope="col" style='width:190px;'>Покупатель</th>
			  <th scope="col" class='opt_col'>Сумма</th>
			  <th scope="col" class='opt_col' style='width:121px;'>Способ оплаты</th>
			  <th scope="col" class='opt_col' style='width:130px;'>Статус</th>
			  <th scope="col" class='opt_col invisible_'>Дата</th>
			  <th scope="col" class='opt_col invisible_'></th>
			</tr>
		  </thead>
		  <tbody>
		  <? 
			if($i2 !== 'all'){ $limit_s = 'LIMIT 0,20'; } elseif($i2 == 'all') { $limit_s = ''; }
			$rx = mysqli_query($db,"SELECT `id`,`id_chat`,`status`,`suma`,`pay_type`,`info_client`,
											`date`,`how_method`,`phone` FROM `a_zakaz`  $limit_s");
			$all_orders = mysqli_num_rows($rx);
			$query_info = get_setting('step_text');
			while($ok = mysqli_fetch_assoc($rx))
			{
				$id_zakaz = $ok['id'];
				$step_text = get_setting('step_text');
				$info_client = $ok['info_client'];
				$user = get_current('user',$ok['id_chat']);
				#$phone = get_current('phone',$ok['id_chat']);
				#$how_method = get_current('how_method',$ok['id_chat']);
				$how_method = $ok['how_method'];
				$phone = $ok['phone'];
				$username = get_current('username',$ok['id_chat']);
				
				if(!empty($username)){ $user = '<a href="https://t.me/'.$username.'" target="_blank">'.$user.'</a>'; }
				if($ok['pay_type'] == 4){ $currency = get_currency_val($ok['pay_type']); } else { $currency = 'RUB'; }
				#$price = number_format($ok['price'], 2,'.','');
				
				$status_x[1] = 'Продаже';
				$status_x[2] = 'Продан';
				
				$date = date('d.m.Y',$ok['date']);
				$summa =  $ok['suma'];
				
				$rx1 = mysqli_query($db,"SELECT `name` FROM `how_delivery` WHERE `id` = '$how_method'");
				$how_ok = mysqli_fetch_assoc($rx1);
				$how_name = $how_ok['name'];
				
				switch($ok['pay_type'])
				{
					case 2:
						$all_sum_yandex = $all_sum_yandex + $summa; break;
					case 1:
						$all_sum_qiwi = $all_sum_qiwi + $summa; break;
					case 3:
						$all_sum_card = $all_sum_card + $summa; break;
					case 4:
						$all_sum_btc = $all_sum_btc + $summa;break;
					case 5:
						$all_sum_ex_code = $all_sum_ex_code + $summa;break;
					case 6: $all_amount = $all_amount + $summa;
				}
				
				switch($ok['status'])
				{
					case 1: $status1 = 'selected';$status2 = '';$status3 = '';$dis = '';break;
					case 2: $status1 = '';$status2 = 'selected';$status3 = '';$dis = 'disabled';break;
					case 3: $status1 = '';$status2 = '';$status3 = 'selected';$dis = 'disabled';
				}
				
				 $invite_link = $ok['invite_link'];
		  ?>
			<tr class=' item_<?=$ok['id'];?>' value='<?=$ok['id']?>' >
			 <td> <?=$ok['id'];?></td>
			 <td class='invisible_'>
				<table border='0' class='table table-bordered table-striped table_ items_list'>
				 <thead>
					<tr>
						<th>Название</th>
						<th>Цена</th>
						<th>Кол-во</th>
					</tr>
				 </thead>
					<tbody>
				<?
				$r = mysqli_query($db,"SELECT `id`,`id_item`,`count`,`sum` FROM `a_zakaz_items` WHERE `id_zakaz` = '$id_zakaz'");
				while($items = mysqli_fetch_assoc($r))
				{
					$item = $items['id_item'];
					
					$s = "SELECT `name` FROM `catalog` WHERE `id` = '$item'";
					$r1 = mysqli_query($db,$s);
					$data = mysqli_fetch_assoc($r1);
					
					$payment_name = get_setting_data('name',$ok['pay_type']);
					
					
					
				?>
					<tr>
						<td style='padding:2px 9px;border-bottom:1px #e8e8e8 solid;'><?=$data['name'];?></td>
						<td style='padding:2px 9px;border-bottom:1px #e8e8e8 solid;'><?=$items['sum'].' '.$currency;?></td>
						<td style='padding:2px 9px;border-bottom:1px #e8e8e8 solid;'><?=$items['count'];?>.шт</td>
					</tr>
				<?}
				
				#$all_sum = number_format($all_sum, 2,'.','');
				?>
				</tbody>
				</table>
			 </td>
			  <td><span class='info_cli'>
						<?='<b>Покупатель: </b>'.$user.'<br>';
						if(!empty($info_client)){ echo '<b>'.$query_info.':</b> '.$info_client; }
						if(!empty($phone)){ echo '<br><b>Номер:</b> '.$phone; }
						if(!empty($how_name)){ echo '<br><b>Тип доставки:</b> '.$how_name; }
						if(!empty($invite_link)){ echo "<b>Invite Link Cnannel:</b> <a href='$invite_link' target='_blank'>View</a></span>"; }
						?>
						
				</span></td>
			 <td><?=$summa .' '.$currency;?></td>
			  <td><span class="label label-success"><?=$payment_name;?></span></td>
			  
			  <td>
			  <?
			#	if($ok['status'] == 3){ $dis = 'disabled'; } else { $dis = ''; }
			  ?>
			  <select class="form-control select_list change_status" id_x='<?=$ok['id'];?>'  style="width: 100%;" <?=$dis;?>>
				<option value='1' <?=$status1;?>>В обработке</option>
				<option value='2' <?=$status2;?>>Оплачен</option>
				<option value='3' <?=$status3;?>>Отменен</option>
			  </select>
			  
			  </td>
			  <td class='invisible_'><span class="label label-success"><?=$date;?></span></td>
			  <td class='invisible_'><i class="fa fa-trash del_zakaz" aria-hidden="true" id_x='<?=$ok['id'];?>'></i></td>
			</tr>
		  <?}
		 $all_suma = number_format($all_suma, 2,'.','');
		  ?>
		  </tbody>
		</table>
		
		<div class="form-group"><br>
			<label class='count_z'>Всего: <?=$all_orders;?> заказов.</label><br><br>
			<?
				$all_sum_yandex = number_format($all_sum_yandex, 0,'.','.');
				$all_sum_qiwi = number_format($all_sum_qiwi, 0,'.','.');
				$all_sum_card = number_format($all_sum_card, 0,'.','.');
				$all_sum_ex_code = number_format($all_sum_ex_code, 0,'.','.');
				$all_amount = number_format($all_amount, 0,'.','.');
			?>
			<label class='count_z'>Всего по типам расчета:</label><br>
			<span class="label label-success info_money">Yandex money: <?=$all_sum_yandex.' RUB';?></span>
			<span class="label label-success info_money">Qiwi: <?=$all_sum_qiwi.' RUB';?></span>
			<span class="label label-success info_money">Visa\MastecCard: <?=$all_sum_card.' RUB';?></span>
			<span class="label label-success info_money">Bitcoin: <?=$all_sum_btc.' btc';?></span>
			<span class="label label-success info_money">Ex-code: <?=$all_sum_ex_code.' RUB';?></span>
			<span class="label label-success info_money">Баланс юзера: <?=$all_amount.' RUB';?></span>
			
			<br>

		  </div>
	</div>
	