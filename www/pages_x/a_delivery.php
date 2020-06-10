<style>	
	.table-bordered>tbody>tr>td{font-weight: 600;color: #424242;font-size: 12px;}
	.form-search{padding: 8px;}
	.label {color: #ffffff;text-shadow: -1px 0px 8px rgba(138, 37, 37, 0.89);}
	.count_sub{color:#444;}
	.sel_obj{font-size: 13px;}
	.opt_col{    color: #505050;font-size: 12px;}
	.search_text {width: 33%;}
	.sel_all{position:relative;top:7px;left:5px;}
	#message{width:100%;height:160px;}
	.stat_x{font-size: 11px;background: #5d6061;padding: 2px;top: -3px;position: relative;}
	.stop{float: right;margin-top: 5px;}
	.modal-content {background-color: #efefef;}
	.form-control {background-color: #f5f5f5;border: 1px solid #d2d2d2;}
</style>

	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	
	<div class="col-md-12">
	<div class="col-md-8">
		<h4>Выберите рассылку для изменения</h4><br>
	</div>
		<div class="col-md-4">
		<button type="button" class="btn btn-primary btn-lg btn_" data-toggle="modal" data-target="#add_delivery">Создать рассылку <i class="fa fa-plus " aria-hidden="true"></i></button>
		</div>
	</div>
	<div class="col-md-12">
	
	
	
		<div class="col-md-8">
		
		
		<form class="form-inline" role="form">
		 <div class="form-group">
			<label>Дейстие</label>
		  </div>
		
		  <div class="form-group" style='width:40%;'>
			
			<select class="form-control type_opetation select_list" style='width:100%;'>
				<option value='0'>--------</option>
				<option value='1'>Удалить</option>
			</select>
		  </div>
		  <div class="form-group">
			<button type="button" class="btn btn-default operation_delivery">Выполнить</button>
		  </div>
		  <?
			$s = "SELECT `id`,`message`,`bonus`,`inline_link`,`date_run`,`date`,`inline_name`,`status` 
															FROM `delivery_list` ORDER BY `id` DESC";
			$r = mysqli_query($db,$s);
			$all = mysqli_num_rows($r);
		  ?>
		   <div class="form-group">
			<label class='sel_obj'>Выбрано <span class='sel_list'>0</span> объектов из <?=$all;?></label>
		  </div>
		 
		</form>
		
		
		</div><br><br>
		<input type='hidden' id='selected_col' value='0'>
		<table class="table table-bordered table-striped table_x">
		  <thead>
			<tr>
			  <th scope="col" style='width:38px;' class='invisible_'><label class='opt_col'><input type="checkbox" class='sel_delivery'></label></th>
			  <th  scope="col" class='opt_col invisible_' style='width:55%;'>TEXT</th>
			  <th style='width:196px;' class='opt_col'>LINK</th>
			  <th style='width:171px;' class='opt_col'>NAME LINK</th>
			   <th scope="col" class='opt_col invisible_' style='width: 103px;'>DATE</th>
			   <th class='opt_col' style='width:490px;'>STATUS</th>
			</tr>
		  </thead>
		  <tbody id='result'>
		 <?
			
			while($ok = mysqli_fetch_assoc($r))
			{
				$date = date('Y.m.d',$ok['date']);
			
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
				
		 ?>
			<tr class='item_<?=$ok['id']?>'>
			  <td><label class='opt_col'><input type="checkbox" class='check_' value='<?=$ok['id'];?>'></label> </td>
			  <td style='color: #073a9a;'><?=$ok['message'];?></td>
			  <td><?=$ok['inline_link'];?></td>
			  <td><?=$ok['inline_name'];?></td>
			   <td><span class="label label-primary"><?=$date;?></span></td>
			   <td><span class="label label-<?=$class_;?>"><?=$info;?></span></td>
			</tr>
		  <?}?>
		  </tbody>
		</table>
		
		<div class="form-group">
			<label class='count_sub'><?=$all;?> Рассылок</label>
		  </div>
	</div>
	
	

