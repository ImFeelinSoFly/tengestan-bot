
<style>	
	.table-bordered>tbody>tr>td{color: #424242;}
	.form-search{padding: 8px;}
	label{color: #adadad;}
	.count_sub{color:#444;}
	.sel_obj{font-size: 13px;}
	.opt_col{    color: #505050;font-size: 12px;}
	.search_text {width: 33%;}
	.all_exchange_{position:relative;left:4px;top:7px;}
	.label {color: #ffffff;font-size: 12px;}
	table.dataTable tbody th, table.dataTable tbody td {padding: 8px 1px;}
	.label-primary {background-color: #797979;}
	input[type=checkbox]{margin-left: 9px;}
	a{font-size: 12px;}
	

	.show_info_>tbody>tr:nth-of-type(odd){background-color:#44444438;;cursor:pointer;}
	.show_info_>tbody>tr:nth-of-type(odd):hover{background-color:#56565638;;cursor:pointer;}
	
	.show_info_>tbody>tr>td{padding: 10px 8px;line-height: 16px;border-bottom: 1px solid #5a5a5a;color:#d0d2d0;font-size: 14px;}
	.modal-title{color:#5d6165;}
	
	.table>thead>tr>th{color: #ffffff;}
	.btn {background: #464644f2;color: #d1d1d2;font-size: 10px;}
	.btn:hover{color:#e8e7e7;}
	.btn-default {text-shadow: 0 1px 0 #797878;}
	td{padding: 12px 5px;}
	table tr td label {color:#3c3b3b;font-size: 11px;font-weight: 600;}
	.select2-container--default .select2-search--inline .select2-search__field{width: 192px;}
	.label-primary {background-color: #505050e3;color: #e8e7e7;text-shadow: -1px 0px 8px rgba(107, 104, 104, 0.89);}
	.edit_item{cursor: pointer;color: #373738;font-size: 15px;}
	.edit_item:hover{color:red;}
	.modal-content{background-color: #f5f5f5;}
	.table_item_x tr{border-bottom:1px #c5c5c5 solid;}
	.photo_{height: 169px;width: 100%;background-size: cover;}
	.del_item_cat{cursor:pointer;}
	.del_item_cat:hover{color:red;}
	#content
	{
		height: 95px;
		background: #fff7b2;
		color: #404142;
		padding: 5px 6px;
		font-size: 12px;
		font-weight: 600;
		border: 1px #e8cb74 solid;
	}
</style>
  
	
	<div class="col-md-12">
		<h4>Выберите заказ для изменения</h4>
		
		<button type="button" class="btn btn-primary btn-lg btn_ new_item" style="float:right;;">Добавить <i class="fa fa-plus-square" aria-hidden="true"></i></i></button>
		
				<a href='/100/'>Показать 100 записей</a>
		<a href='/all/'>Показать все записи</a>
	</div>

	
	<div class="col-md-12">

	<?/*?>
	<div class="col-md-12 search">
		<form class="form-search">
		  <div class="input-append">
			<input type="text " class="span2 search-query search_text">
			<button type="button" class="btn btn-default">Поиск</button>

		  </div>
		</form>
	</div>
	<?*/?>
		<div class="col-md-8">
	
		  <?
		    $s_param = '';
			if($rang == 2){ $s_param = " WHERE `user_id` = '$user_id'"; }
			
			$limit = intval($i2);
			if($limit > 0){ $s_limit = "LIMIT 0,$limit"; } 
			elseif($i2 == 'all') { $s_limit = ''; }
				elseif(empty($i2)) { $s_limit = 'LIMIT 0,50'; }
			$r = mysqli_query($db,"SELECT `id`,`name`,`price`,`active`,`sales`,`user_id` FROM `catalog` $s_param ORDER BY `id` ASC $s_limit");
			$all_exchange_this = mysqli_num_rows($r);
			
			$rx = mysqli_query($db,"SELECT `id` FROM `catalog` $s_param");															
			$all_items = mysqli_num_rows($rx);
			
		  ?>
		
			
		</div><br><br>
		<input type='hidden' id='current_item' value='0'>
		<table class="table table-bordered table-striped table_x">
		  <thead>
			<tr>
		      <th scope="col" class='opt_col' style='width:15px;'>ID</th>
			  <th scope="col" style='width:24px;'>Active</th>
			  <th scope="col" style='width:15px;'></th>
			  <th scope="col" class='opt_col'>Товар</th>
			  <th scope="col" class='opt_col'>Цена</th>
			  <th scope="col" class='opt_col'>Группа</th>
			  <th scope="col" class='opt_col invisible_'>Статус</th>
			   <th scope="col" class='opt_col invisible_'>Создал</th>
			  <th scope="col" class='opt_col invisible_'></th>
			</tr>
		  </thead>
		  <tbody>
		  <?
			
			while($ok = mysqli_fetch_assoc($r))
			{
				if($ok['active'] == 1){ $active = 'checked'; } else { $active = ''; }
				$sect = get_section($ok['id']);
				if($sect == '/'){ $sect = ''; }
				
				$price = number_format($ok['price'], 2,'.','');
				
				$status_x[0] = 'Продаже';
				$status_x[1] = 'Продан';
				$rx = mysqli_query($db,"SELECT `login` FROM `Accounts` WHERE `id` = '".$ok['user_id']."'");
				$data_t = mysqli_fetch_assoc($rx);
				$login = $data_t['login'];
				
				
		  ?>
			<tr class=' item_<?=$ok['id'];?>' value='<?=$ok['id']?>' >
			 <td> <?=$ok['id'];?></td>
			 <td><label class='opt_col'><input type="checkbox" class='active_item' value='<?=$ok['id'];?>' <?=$active;?>></label></td>
			  <td>
			  <i class="fa fa-pencil-square-o edit_item" value='<?=$ok['id'];?>' aria-hidden="true"></i>
			  </td>
			  
			  <td><?=$ok['name'];?></td>
			  <td ><span class="label label-success"><?=$price;?></span></td>
			  <td ><?=$sect;?></td>
			  <td class='invisible_'><span class="label label-<?=$status_l[$ok['status']];?>"><span class="label label-primary"><?=$status_x[$ok['sales']];?></span></td>
			  <td ><?=$login;?></td>
			  <td class='invisible_'><i class="fa fa-trash del_item_cat" aria-hidden="true" title='Удалить' value='<?=$ok['id'];?>'></i></td>
			</tr>
		  <?}?>
		  </tbody>
		</table>
		
		<div class="form-group"><br>
			<label class='count_sub'>Всего: <?=$all_items;?> товаров.</label>
		  </div>
	</div>
	
	
	
<!-- Modal -->
<div class="modal fade" id="show_item_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" style='width:50%;'>
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title title_box" >Новый товар:</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
		<div class="col-md-12" style='padding-bottom:17px;'>
	
		<table border='0' class='table_item_x' style='width:100%;margin-top:18px;'>
			<tr>
				<th></th>
				<th></th>
			</tr>
			<form id='data_item'>
			<tr>
			<td><label class="control-label" >Активность:</label></td>
			<td>
				<label><input type='checkbox' id='active_item' class='active_item_x'> Активен</label>
			</td>
			</tr>
			
			
			<?
				$r = mysqli_query($db,"SELECT `name`,`alias`,`type` FROM `prop_list_catalog` WHERE `active` = '0' AND `private` = '0'");
				while($list = mysqli_fetch_assoc($r))
				{
					
			?>
			
				<tr id='col_<?=$list['alias']?>'>
				<td style='width:120px;'> <label class="control-label" ><?=$list['name'];?>:</label></td>
					<?if($list['type'] == 1){?>	
						<td><input type="text" class="form-control clear_x" id='<?=$list['alias']?>' name='item[<?=$list['alias']?>]'></td>
					<?}elseif($list['type'] == 2){?>	
						<td><textarea class="form-control clear_x" id='<?=$list['alias']?>' name='item[<?=$list['alias']?>]'></textarea></td>
					<?}
						elseif($list['type'] == 3){?>
							<td>
							<select class="form-control select2 associative_item_" name='item[amount_product]' style="width: 100%;">
							<option value='0' ></option>
							<?
								$rx = mysqli_query($db,"SELECT `id`,`name` FROM `product_value_units`");
								while($list_value = mysqli_fetch_assoc($rx))
								{
									?><option value='<?=$list_value['id'];?>'><?=$list_value['name'];?></option><?
									
								}
							?>
							</select>
							</td>
					<?}?>
					
				</tr>
				<?}?>
			<tr>	
			<td><label class="control-label" >Категория:</label></td>
			<td>
			<select class="form-control select2 cat_list" multiple="multiple"  name='cat_list[]' data-placeholder="Укажите категории" style="width: 100%;">
				
			<?
				$id_cat = get_setting('catalog_id');
				$r = mysqli_query($db,"SELECT `id`,`name` FROM `struktura` WHERE `active` = '0' AND `pid` = '$id_cat'");
				while($list = mysqli_fetch_assoc($r))
				{
					?><option value='<?=$list['id'];?>'><?=$list['name'];?></option><?
					
					$r1 = mysqli_query($db,"SELECT `id`,`name` FROM `struktura` WHERE `active` = '0' AND `pid` = '".$list['id']."'");
					
					while($list1 = mysqli_fetch_assoc($r1))
					{
						
						?><option value='<?=$list1['id'];?>'><?=$list1['name'];?></option><?
						
					$r2 = mysqli_query($db,"SELECT `id`,`name` FROM `struktura` WHERE `active` = '0' AND `pid` = '".$list1['id']."'");
					
					while($list2 = mysqli_fetch_assoc($r2))
					{
						?><option value='<?=$list2['id'];?>'><?=$list2['name'];?></option><?
						
						$r3 = mysqli_query($db,"SELECT `id`,`name` FROM `struktura` WHERE `active` = '0' AND `pid` = '".$list2['id']."'");
						
						while($list3 = mysqli_fetch_assoc($r3))
						{
							?><option value='<?=$list3['id'];?>'><?=$list3['name'];?></option><?
							
							$r4 = mysqli_query($db,"SELECT `id`,`name` FROM `struktura` WHERE `active` = '0' AND `pid` = '".$list3['id']."'");
							while($list4 = mysqli_fetch_assoc($r4))
							{
								
								?><option value='<?=$list4['id'];?>'><?=$list4['name'];?></option><?
							}	
						}	
					}	
						
					}
				}
				
				
					
			?>
			</select></td>
			</tr>
			<input type='hidden' name='tmp_photo' id='tmp_photo'>
			</form>	
			<tr>
			<td><label class="control-label" >Фото:</label></td>
			<td>
			<form  id='photo_item_'>
				
				<input type='hidden' name='act' value='upload_img'>
				<input type='file' class='form-control' id='photo_item_upload' name='photo' >
				<img class='photo_' >
			</form>
			
			</td>			
			</tr>
				
			
		</table>  
		  
		
      </div>
      <div class="modal-footer">
       
       <button type="button" class="btn btn-primary btn-lg btn_ add_item" style="float:right;;"><i class="fa fa-plus-square" aria-hidden="true"></i></i> Добавить</button>
	   <button type="button" class="btn btn-primary btn-lg btn_ save_item" style="float:right;;"><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить</button>
      </div>
    </div>
  </div>
</div>
</div>

<link rel="stylesheet" type="text/css" href="/css/alert.css">
<script type="text/javascript" src="/js/alert.js"></script>

 