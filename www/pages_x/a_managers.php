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
	.del_user{cursor:pointer;}
	.del_user:hover{color:red;}
	.dataTables_filter{display:none;}
	.sel_payment_list{cursor:pointer;}
	input[type="text"]{text-shadow: -1px 0px 8px rgba(247, 247, 247, 0.89);width: 108px;padding: 2px 5px;color: #312f2f;}
	.modal-header .close {margin-top: -40px;}
</style>

  <script>
  $(document).ready(function(){

    
   $( "#date1" ).datepicker();
   $( "#date2" ).datepicker();
  });
  </script>

	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	
	<div class="col-md-12">
	<div class="col-md-8">
		<h4>Операторы</h4><br>
	</div>
		<div class="col-md-4">
		<button type="button" class="btn btn-primary btn-lg btn_" data-toggle="modal" data-target="#add_manager">Добавить <i class="fa fa-plus " aria-hidden="true"></i></button>
		
		<button type="button" class="btn btn-primary btn-lg btn_" data-toggle="modal" data-target="#calculator_show">Калькулярор <i class="fa fa-calculator" aria-hidden="true"></i></button>
		</div>
	</div>
	<div class="col-md-12">
	
	
	
		<div class="col-md-8">
		

		
		</div><br><br>
		<input type='hidden' id='selected_col' value='0'>
		<table class="table table-bordered table-striped table_x">
		  <thead>
			<tr>
			  <th  scope="col" class='opt_col invisible_' style='width:16px;'>ID</th>
			  <th style='width:196px;' class='opt_col'>LOGIN</th>
			   <th style='width:196px;' class='opt_col'>PWD</th>
			  <th style='width:171px;' class='opt_col'>Товаров</th>
			   <th scope="col" class='opt_col invisible_' style='width: 103px;'>DATE</th>
			   <th class='opt_col' style='width:20px;'>#</th>
			</tr>
		  </thead>
		  <tbody id='result'>
		 <?
			$r = mysqli_query($db,"SELECT `id`,`rang`,`login`,`password`,`pwd_show`,`date` FROM `Accounts`");
			$all = mysqli_num_rows($r);
			while($ok = mysqli_fetch_assoc($r))
			{
				$date = date('Y.m.d',$ok['date']);
			
				$rx = mysqli_query($db,"SELECT `id` FROM `catalog` WHERE `user_id` = '".$ok['id']."'");
				$item_all = mysqli_num_rows($rx);
		 ?>
			<tr class='item_<?=$ok['id']?>'>
			  <td><label class='opt_col'><?=$ok['id'];?></label> </td>
			  <td style='color: #073a9a;'><?=$ok['login'];?></td>
			   <td ><?=$ok['pwd_show'];?></td>
			  <td><?=$item_all;?></td>
			   <td><span class="label label-primary"><?=$date;?></span></td>
			   <td><?if($ok['id'] > 1){?><i class="fa fa-trash del_user" aria-hidden="true" title='Удалить' value='<?=$ok['id'];?>'></i><?}?></td>
			</tr>
		  <?}?>
		  </tbody>
		</table>
		
		<div class="form-group">
			<label class='count_sub'><?=$all;?> Операторов</label>
		  </div>
	</div>
	
	

<!-- Modal -->
<div class="modal fade" id="add_manager" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" >Добавление оператора</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
		<div class="col-md-12">
		  <div class="form-group">
			<label class="control-label" >Login:</label>
			<input type='text' class="form-control" id='login_manager' style='width:100%;'>	
			<hr>
			<label class="control-label" ><b>Password:</b></label>
			<input type="password" class="form-control" id='pwd_manager'>
		
		  </div>
		
      </div>
      <div class="modal-footer">
	   <button type="button" class="btn btn-primary create_manager">Добавить</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
       
      </div>
    </div>
  </div>
</div>
</div>		
	

<!-- Modal -->
<div class="modal fade" id="calculator_show" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" style='width:60%;'>
    <div class="modal-content">
      <div class="modal-header" style='height:50px;padding:11px 1px;'>
	  <div class="col-md-12">
	  <div class="col-md-4">
        <h4 class="modal-title" >Калькулятор расчета оплаты</h4>
	</div>
	 <div class="col-md-8">
		<label>от</label>
		<input type='text' id='date1'>
		
		<label>до</label>
		<input type='text' id='date2'>
		<button id='search_payment'>Показать</button>
		<code id='money_x' style='display:none;'>1212344</code>
	</div>

	  </div>	
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
     </div>
      <div class="modal-body">
        
		<div class="col-md-12">
		<div class='col-md-4' style='padding:9px 0px;'>
		  <table class="table table-bordered table-striped table_x">
		  <thead>
			<tr>
			  <th style='width:196px;' class='opt_col'>LOGIN</th>
			   <th scope="col" class='opt_col invisible_' style='width:103px;'>DATE</th>
			</tr>
		  </thead>
		  <tbody id='result'>
		 <?
			$r = mysqli_query($db,"SELECT `id`,`login`,`date` FROM `Accounts`");
			$all = mysqli_num_rows($r);
			while($ok = mysqli_fetch_assoc($r))
			{
				$date = date('Y.m.d',$ok['date']);
		 ?>
			<tr class='item_<?=$ok['id']?> sel_payment_list' value='<?=$ok['id'];?>'>
			  <td style='color: #073a9a;'><?=$ok['login'];?></td>
			   <td><span class="label label-primary"><?=$date;?></span></td>
			</tr>
		  <?}?>
		  </tbody>
		</table>
		</div>
		
		<div class='col-md-8'>
		 <table class="table table-bordered table-striped table_x">
		  <thead>
			<tr>
				<th style='width:122px;' class='opt_col'>Login</th>
				<th scope="col" class='opt_col invisible_' style='width:291px;'>Товар</th>
				<th style='width:88px;' class='opt_col'>Кол-во</th>
				<th style='width:107px;' class='opt_col'>Сумма</th>
				<th style='width:115px;' class='opt_col'>Дата</th>
			</tr>
		  </thead>
		  <tbody id='list_payments'></tbody>
		  
		  </table>
		</div>
		
      </div>
      <div class="modal-footer">
	 
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
       
      </div>
    </div>
  </div>
</div>
</div>		