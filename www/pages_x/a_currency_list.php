<style>
	.del_currency{ cursor:pointer; }
	.del_currency:hover{ color:red; }
	.sel_item:hover{color:#d9534f;cursor:pointer;}
	.table-striped>tbody>tr:nth-of-type(odd):hover{color:#d9534f;cursor:pointer;}
</style>

	<div class="col-md-12">
	<div class="col-md-9">
		<h4>Список</h4><br>
	</div>
		<div class="col-md-3">
		<button type="button" class="btn btn-primary btn-lg btn_" data-toggle="modal" data-target="#new_currency">Добавить валюту <i class="fa fa-plus " aria-hidden="true"></i></button>
		</div>
	</div>
	<div class="col-md-12" style='padding-bottom:176px;'>
	
		<div class="col-md-6">
	  <div class="box box-danger">

            <div class="box-body">
              <div class="row">
				
					<table class="table table-bordered table-striped table_x">
					<thead>
					<tr>
						<th style='width: 35px;'>ID</th>
						<th style='width: 201px;'>Валюта</th>
						<th style='width: 201px;'>Курс</th>
						<th style='width: 100px;'></th>
					</tr>
					</thead>
					<tbody id='result'>					
					<?
						$s = "SELECT `id`,`name`,`value`,`active` FROM `currency_list` ";
						$r = mysqli_query($db,$s);
						while($ok = mysqli_fetch_assoc($r))
						{
							if($ok['active'] == 0){ $ch = 'checked'; } else { $ch = ''; }
					?>
						<tr class='sel_item item_<?=$ok['id'];?>' value='<?=$ok['id'];?>'>
							<td><?=$ok['id']?></td>
							<td><input type='checkbox' <?=$ch;?> class='chk_set_currency' value='<?=$ok['id'];?>'> <?=$ok['name'];?></td>
							<td><?=$ok['value'];?></td>
							<td>
								<a class='del_currency' value='<?=$ok['id'];?>'>X</a>
							</td>
						</tr> 
						<?}?>
						</tbody>
					</table>
				
              </div>
			 
			  
              </div>
            </div>
		
		</div>
		
		<div class="col-md-6">
		<div class="col-md-11">
		 <div class="form-group" style='padding-bottom:15px;'>
		 <form id='data_f_currency'>
			<input type='hidden' id='current_id' name='id' value='0'>
			<label class="control-label" >Валюта:</label>
			<input type="text" class="form-control" id='currency_name' name='currency_name'>
			<hr>
			<?/*?><label class="control-label" >Курс:</label>
			<input type="text" class="form-control" id='value' name='value'>
			<hr>
			<?*/?>
			<label class="control-label" >Доступные платежные системы на отправку:</label>
			<select class="form-control select2 payment_list_get" multiple="multiple" name='payment_list_get[]' data-placeholder="Укажите платежные системы" style="width: 100%;"></select>
		
		<hr>
			<label class="control-label" >Доступные платежные системы на прием средств:</label>
			<select class="form-control select2 payment_list_send" multiple="multiple" name='payment_list_send[]' data-placeholder="Укажите платежные системы" style="width: 100%;"></select>
		
		
		</form>
			<hr>
			<button type="button" class="btn btn-primary btn-lg btn_ save_currency_" >Сохранить</button>
			
		</div>
		</div>
		</div>
		
	
	</div>
	
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
  
  <script>
  $(document).ready(function(){
   
  $(".select2").select2({
      tags: true,
      tokenSeparators: [',', ' ']
    })
    
  });
  </script>
  
<style>
  .change_user_{ cursor:pointer; }
  .change_user_:hover{background: #ececec;}
  .select2-container--default .select2-selection--multiple .select2-selection__choice {background-color:#efedec;}
  .select2-container--default .select2-selection--multiple .select2-selection__choice{color: #828282;}
  .select2-container--default.select2-container--focus .select2-selection--multiple {border: solid #b7b7b7 1px;outline: 0;}
  .select2-container--default .select2-search--inline .select2-search__field{width: 223px;}
</style>	