<style>	
	.table-striped tbody tr:nth-of-type(odd) {background-color: rgba(0, 0, 0, 0);}
	.table  .btn_filter
	{
		padding: 2px 5px;
		vertical-align: top;
		border-top: 1px solid #dee2e6;
		color:#6b6969;
		font-weight: 600;
		font-size: 13px;
		text-indent: 8px;
		cursor:pointer;
		text-shadow: -1px 0px 8px rgba(243, 243, 243, 0.89);
	}
	.table  .btn_filter:hover{color: #03A9F4; }
	.date_self{font-weight: 600;color: #272727;}
	.label {color: #f9f9f9;text-shadow: -1px 0px 8px rgba(68, 62, 62, 0.89);}
</style>
	<div class="col-md-12">

	
		<h3>Выберите выплату</h3>
		
		<div class="col-md-12 search">
		<form class="form-search">
		  <div class="input-append">
			<i class="fa fa-search" aria-hidden="true"></i>
			<input type="text " class="span2 search-query search_text">
			<button type="button" class="btn btn-default">Поиск</button>

		  </div>
		</form>
		</div><br><br>
		
		<table class="table table-bordered table-striped table_x">
		  <thead>
			<tr>
			  <th>ID</th>
			  <th scope="col" class='invisible_'>Пользователь</th>
			  <th scope="col">Сумма</th>
			  <th scope="col" class='invisible_'>Дата создания</th>
			   <th scope="col">Метод оплаты</th>
			  <th scope="col">Статус платежа</th>
			 
			</tr>
		  </thead>
		  <tbody id='result'>
		  <?	
			$status_x[0] = 'Ожидает';
			$status_x[1] = 'Отменен';
			$status_x[2] = 'Оплачен';
			
			$ico_x[0] = '⏳';
			$ico_x[1] = '❌';
			$ico_x[2] = '✔️';
			
			$class[0] = 'primary';
			$class[1] = 'danger';
			$class[2] = 'success';
		  
			$r = mysqli_query($db,"SELECT `id`,`amount`,`status`,`date`,`pay_type`,`btc_amount`,`id_chat` FROM `payment_users`");
			while($list = mysqli_fetch_assoc($r))
			{
				$amount = $list['amount'];
				if($list['pay_type'] == 4){ $amount = $list['btc_amount']; }
				$currency = get_currency_val($list['pay_type']);
				$date = date('d.m.Y H:i',$list['date']);
				$status = $ico_x[$list['status']].' '.$status_x[$list['status']];
				#$ico = $ico_x[$list['status']];
				$user = get_current('user',$list['id_chat']);
				$username = get_current('username',$list['id_chat']);
				if(!empty($username)){ $user = '<a href="https://t.me/'.$username.'" target="_blank">'.$user.'</a>'; }
				
				$payment_name = get_setting_data('name',$list['pay_type']);
				
		  ?>
			<tr>
			 <td><?=$list['id'];?></td>
			 <td class='invisible_'><?=$user;?></td>
			  <td><?=$amount.' '.$currency;?></td>
			  <td class='invisible_'><label class='label label-primary'><?=$date;?></label></td>
			    <td><label class='label label-primary'><?=$payment_name;?></label></td>
			   <td><label class='label label-<?=$class[$list['status']]?>'><?=$status;?></label></td>
			  
			</tr>
			<?}?>
			
		  </tbody>
		</table>
		
		
	</div>
	
	
	



	