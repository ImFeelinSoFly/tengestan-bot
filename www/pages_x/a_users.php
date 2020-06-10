<script>	

$(document).ready(function(){

    var table = $('.table_x1').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": false
	  
    });
	
	$('.search_text').on( 'keyup', function () {
		table.search( this.value ).draw();
	} );
});
</script>


  
	<div class="col-md-12">


	</div>
	<div class="col-md-12">
		<a href='/users/100/'>Показать 100 пользователей</a>
		<a href='/users/all/'>Показать всех пользователей</a>
	<div class="col-md-12 search">
		<form class="form-search">
		  <div class="input-append">
			<input type="text " class="span2 search-query search_text">
			<button type="button" class="btn btn-default">Поиск</button>

		  </div>
		</form>
	</div>
	
		<div class="col-md-8">
		
		
		
		<form class="form-inline" role="form" style='padding-bottom:16px;'>
		 <div class="form-group">
			<label>Дейстие</label>
		  </div>
		
		  <div class="form-group" style='width:40%;'>
			
			<select class="form-control select_list operation_list" style='width:100%;'>
				<option value='0'>--------</option>
				<option value='1'>Удалить</option>
			</select>
		  </div>
		  <div class="form-group">
			<button type="button" class="btn btn-default operation_users">Выполнить</button>
		  </div>
		  <?
			$limit = intval($i2);
			if($limit > 0){ $s_limit = "LIMIT 0,$limit"; } 
			elseif($i2 == 'all') { $s_limit = ''; }
				elseif(empty($i2)) { $s_limit = 'LIMIT 0,50'; }
			$r = mysqli_query($db,"SELECT `id`,`username`,`user`,`date`,`id_chat`,`count_msg`,`discount`,`balance` 
																		FROM `Users` WHERE `id_bot` = '0' ORDER BY `date` ASC  $s_limit ");
			$all_users_this = mysqli_num_rows($r);
			
			$rx = mysqli_query($db,"SELECT `id` FROM `Users` WHERE `id_bot` = '0'");															
			$all_users = mysqli_num_rows($rx);
			
		  ?>
		   <div class="form-group">
			<label class='sel_obj'>Выбрано <span class='sel_list'>0</span> объектов из <?=$all_users_this;?>. Всего: <?=$all_users;?></label>
		  </div>
		 
		</form>
		
		
		</div><br><br>
		<input type='hidden' id='user_view' value='0'>
		<table class="table table-bordered table-striped table_x1">
		  <thead>
			<tr>
			  <th style='width:27px;'><label ><input type="checkbox" class='all_users_'> </label></th>
			  <th scope="col" class='opt_col' style='width:100px;'>CHAT_ID</th>
			  <th scope="col" class='opt_col'>USERNAME</th>
			  <th scope="col" class='opt_col invisible_'>Имя</th>
			  <th scope="col" class='opt_col ' style='width:100px;'>Баланс</th>
			  <th scope="col" class='opt_col invisible_'>REFERER</th>
			   <th scope="col" class='opt_col invisible_'>Всего реферов</th>
			   <th scope="col" class='opt_col'>Всего сообщений</th>
			   <th scope="col" class='opt_col'>Скидка</th>
			   <th scope="col" class='opt_col'>Дата</th>
			</tr>
		  </thead>
		  <tbody>
		  <?
			
			while($ok = mysqli_fetch_assoc($r))
			{
				
				$r1 = mysqli_query($db,"SELECT `id` FROM `list_referer` WHERE `referer` = '".$ok['id_chat']."'");
				$ref = mysqli_num_rows($r1);
				$username = '';
				if($ok['username']){ $username = "<a href='https://t.me/".$ok['username']."'>".$ok['username']."</a>"; } 
				$date = date('m.d.Y',$ok['date']);
				if($ok['discount'] > 0){ $discount = $ok['discount'].'%'; } else { $discount = '-'; }
		  ?>
			<tr class='item_<?=$ok['id'];?>'>
			  <td><label class='opt_col'><input type="checkbox" class='check_' value='<?=$ok['id'];?>'></label> </td>
			  <td><?=$ok['id_chat'];?></td>
			  <td><?=$username;?></td>
			  <td class='invisible_'><?=$ok['user'];?></td>
			  <td><?=$ok['balance'];?></td>
			  <td class='invisible_'><?=$ok['referer'];?></td>
			   <td class='invisible_'><?=$ref;?></td>
			   <td><?=$ok['count_msg'];?></td>
				<td><?=$discount;?></td>
			   <td><?=$date;?></td>
			</tr>
		  <?}?>
		  </tbody>
		</table>
		
		<div class="form-group">
			<label class='count_sub'>Всего: <?=$all_users;?> Пользвателей</label>
		  </div>
	</div>
	
	

	
<style>	
	.table-bordered>tbody>tr>td{font-weight:300;color:#696969;font-size:13px;}
	.form-search{padding: 8px;}
	label{color: #adadad;}
	.count_sub{color:#444;}
	.sel_obj{font-size:13px;}
	.opt_col{color:#505050;font-size:12px;}
	.search_text {width:33%;}
	.all_users_{position:relative;left:4px;top:7px;}
	.btn{font-size:11px;padding: 5px 15px;color:#cacaca;}
	.info_req{color:#e0dbd4;font-size:12px;}
	.status{float: left;color: #fff;background: #56575a;font-size: 11px;}
	.btn-primary{border-color: #bfbfbf;}
	.modal-content{background: url(https://steamuserimages-a.akamaihd.net/ugc/780658586478544288/3437DCCA5FCBCA7A7FA08362D5C84AA903F87F52/) no-repeat top;color: #fff;}
	.form-control {background-color: #48484857;border: 1px solid #5f5d5a;}
	input[type="text"] {text-shadow: -1px 0px 8px rgba(39, 36, 36, 0.89);color: #aba0a0;}
	.level_user_self{width:100%;color:#d2d2d2;}
	.select2-container--default .select2-selection--single {background-color: #48484857;}
	.select2-container--default .select2-selection--single .select2-selection__rendered {color: #bfbbb5;line-height: 25px;}
	.level_user_self option{    background: #292828;color: #c5bbbb;}
	.label-status{color:#dedede;text-shadow: -1px 0px 8px rgba(62, 58, 58, 0.89);background-color:#505050;font-size:11px;padding:4px 6px;}
</style>