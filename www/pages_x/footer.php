</div>
	<footer class="my-5 pt-5 text-muted text-center text-small">
       <p class="mb-1">© Bot shop v6 | 2018-<?=date('Y');?></p>
      </footer>
</body>


  

<!-- Modal -->
<div class="modal fade" id="add_delivery" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" >Создать рассылку</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
		<div class="col-md-12">
		<form id="data_dalivery" class='modal_'>
		<input type='hidden' name='act' value='create_delivery'>
		  <div class="form-group">
			<label class="control-label" >*Text: <code>{name_full} {name_user} {username}</code></label>
			<textarea  class="form-control" name='message' style='height:174px;'></textarea>		
		
			<hr>
			<label class="control-label" ><b>*Тип рассылки:</b></label>
			<select name='type_d' class="form-control">
				<option value='0'>Всем</option>
				<option value='1'>Тем, кто ни пригласил ни одного реферала</option>
				<option value='2'>Тестовая</option>
			</select>
			
			<hr>
			<label class="control-label" ><b><i class="fa fa-picture-o" aria-hidden="true"></i> Фото:</b></label>
			<input type="file" class="form-control" name='file'>
			
			<hr>
			<label class="control-label" ><b>Link:</b></label>
			<input type="text" class="form-control" name='inline_link' placeholder='http://t.me.ru/channel'>
			
			<hr>
			<label class="control-label" >Name link:</label>
			<input type="text" class="form-control" name='inline_name' value='➡ Перейти'>
			
			<hr>
			<label class="control-label" ><b><i class="fa fa-clock-o" aria-hidden="true"></i> *Запуск рассылки:</b></label>
			<input type="text"  class="datepicker form-control run_time" name='run_time'>
			
		</form>	
		  </div>
		
      </div>
      <div class="modal-footer">
	   <button type="button" class="btn btn-primary create_delivery"><i class="fa fa-plus-square" aria-hidden="true"></i></i> Создать рассылку</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
       
      </div>
    </div>
  </div>
</div>
</div>		
	
			

<!-- Modal -->
<div class="modal fade" id="edit_pwd" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Смена пароля</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
		<div class="col-md-12">
		<form id="pwd_ch" class='modal_'>
		<input type='hidden' name='act' value='change_pwd'>
		  <div class="form-group">
			<label class="control-label" >Старый пароль:</label>
			<input type="password" class="form-control" name='old_pwd' id='old_pwd'>			
			<hr>
			<label class="control-label" ><b>Новый пароль:</b></label>
			<input type="password" class="form-control" name='new_pwd' id='new_pwd'>

		</form>	
		  </div>
		
      </div>
      <div class="modal-footer">
	   <label class="label label-primary result_pwd" style='float:left;font-size:15px;display:none;color:#fff;'></label>
	   <button type="button" class="btn btn-primary change_pwd">Изменить</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
       
      </div>
    </div>
  </div>
</div>
</div>



	
			