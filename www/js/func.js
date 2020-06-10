$(document).ready(function(){
	
    var table = $('.table_x').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": false
	  
    });
	
	$('.search_text').on( 'keyup', function () {
		table.search( this.value ).draw();
	} );
	
	$('.method_pay').on('change',function(){
		
		var st = parseInt($(this).attr('value'));
		
		if(st == 1)
		{
			$('.date_box').hide();
		}else { $('.date_box').show(); }
		
	});
	
	$('.change_ico').on('change',function(){
		var id = parseInt($(this).attr('value'));
		var ico = $(this).val();
		
		var data_ = new Object();
			data_['act'] = 'set_ico';
			data_['id_ico'] = ico;
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
							
							}
					  }
				});
		
	});
	
	
	 $(".set_btn_bot").keyup(function() {
		 var id = parseInt($(this).attr('id_com'));
		 var name = $(this).val();
		
		var data_ = new Object();
			data_['act'] = 'set_name_btn';
			data_['name'] = name;
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
							
							}
					  }
				});
		
		 
	 });

	 
	 
	 $(".sorted").keyup(function() {
		 var id = parseInt($(this).attr('id_com'));
		 var sid = $(this).val();
		
		var data_ = new Object();
			data_['act'] = 'set_sort_btn';
			data_['sid'] = sid;
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
							
							}
					  }
				});
		
	 });
	 
	  $(".sorted").on('change',function() {
		 var id = parseInt($(this).attr('id_com'));
		 var sid = $(this).val();
		
		var data_ = new Object();
			data_['act'] = 'set_sort_btn';
			data_['sid'] = sid;
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
							
							}
					  }
				});
		
		 
	 });
	 
	 
	 
	 
	 $(".sorted_menu").keyup(function() {
		 var id = parseInt($(this).attr('id_com'));
		 var sid = $(this).val();
		
		var data_ = new Object();
			data_['act'] = 'set_sort_btn_menu';
			data_['sid'] = sid;
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
							
							}
					  }
				});
		
	 });
	 
	  $(".sorted_menu").on('change',function() {
		 var id = parseInt($(this).attr('id_com'));
		 var sid = $(this).val();
		
		var data_ = new Object();
			data_['act'] = 'set_sort_btn_menu';
			data_['sid'] = sid;
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
							
							}
					  }
				});
		
		 
	 });	 
	 
	

	

  $( ".task_progressbar" ).progressbar({
      value: 0,
    });

	 
	 
	$('.save_opt').on('click',function(){
		
		var data_ = $('#data_setting').serialize();
			data_ = 'act=save_opt&'+data_;
		
		$('.save_opt').prop('disabled',true);
		
		$.ajax({
			  type: "POST",
			  url: '/ajax.php',
			  data: data_,
			  dataType:"text",
			  success: function(data)
			  { 
					var card = JSON.parse(data);
					if(card.stat == 1)
					{						
						$('.save_opt').prop('disabled',false);
					}
			  }
		});
		
	});	
	 
	 
	 
	$('.save_opt_bot').on('click',function(){
		
		var data_ = $('#data_setting_bot').serialize();
			data_ = 'act=save_opt_bot&'+data_;
		
		$('.save_opt_bot').prop('disabled',true);
		
		$.ajax({
			  type: "POST",
			  url: '/ajax.php',
			  data: data_,
			  dataType:"text",
			  success: function(data)
			  { 
					var card = JSON.parse(data);
					if(card.stat == 1)
					{						
						$('.save_opt_bot').prop('disabled',false);
					}
			  }
		});
		
	});	
	 
	 
 
	$('.save_opt2').on('click',function(){
		
		var data_ = $('#data_setting2').serialize();
			data_ = 'act=data_setting2&'+data_;
		
		$('.save_opt2').prop('disabled',true);
		
		$.ajax({
			  type: "POST",
			  url: '/ajax.php',
			  data: data_,
			  dataType:"text",
			  success: function(data)
			  { 
					var card = JSON.parse(data);
					if(card.stat == 1)
					{						
						$('.save_opt2').prop('disabled',false);
					}
			  }
		});
		
	});		 
	 
	 
	
	$('.check_all').on('click',function(){
		var status = $(this).prop('checked');
		$('.check_').prop('checked',status);
	});
	 
	 
	$('.run_task').on('click',function(){
		var type = $('.type_opetation').val();
		
		var list = '';
		$('.check_').each(function(){
			var id = $(this).attr('value');
			if($(this).prop('checked'))
			{
				list = list+'|'+id;
				if(type == 1){ $('.item_'+id).remove(); }
			}
		});
		
		var data_ = new Object();
			data_['act'] = 'opetation_sub';
			data_['id'] = list;
			data_['type'] = type;

		$.ajax({
			  type: "POST",
			  url: '/ajax.php',
			  data: data_,
			  dataType:"text",
			  success: function(data)
			  { 
					var card = JSON.parse(data);
					if(card.stat == 1)
					{						
					
					}
			  }
		});
	});	
	
	
	
 

	
	$('.all_users_').on('click',function(){
		var status = $(this).prop('checked'); 
		$('.check_').prop('checked',status);
		
		
		var count = 0;;
		$('.check_').each(function(){
			var id = $(this).attr('value');
			if($(this).prop('checked'))
			{
				count++;
			} else { count = 0; }
		});
		
		$('.sel_list').text(count);
	});
	


	$('.all_exchange_').on('click',function(){
		var status = $(this).prop('checked'); 
		$('.check_').prop('checked',status);
		
		
		var count = 0;;
		$('.check_').each(function(){
			var id = $(this).attr('value');
			if($(this).prop('checked'))
			{
				count++;
			} else { count = 0; }
		});
		
		$('.sel_list').text(count);
	});
	
	
 
	$('.operation_users').on('click',function(){
		var type = $('.operation_list').val();
		
		var list = '';
		$('.check_').each(function(){
			var id = $(this).attr('value');
			if($(this).prop('checked'))
			{
				list = list+'|'+id;
				if(type == 1){ $('.item_'+id).remove(); }
			} 
		});
		

		var data_ = new Object();
			data_['act'] = 'operation_users';
			data_['id'] = list;
			data_['type'] = type;

		$.ajax({
			  type: "POST",
			  url: '/ajax.php',
			  data: data_,
			  dataType:"text",
			  success: function(data)
			  { 
					var card = JSON.parse(data);
					if(card.stat == 1)
					{						
					
					}
			  }
		});
	});	
	

	$('body').on('click','.check_',function(){
		
		var count = parseInt($('.sel_list').text()); 
		if($(this).prop('checked')){ count++; } else { count--; }
		$('.sel_list').text(count);
		if(count > 0){ $('#selected_col').attr('value',1); } else { $('#selected_col').attr('value',0); }

	});
	
	$('.run_operation').on('click',function(){
		var type = $('.type_opetation').val();
		
		var list = '';
		$('.check_').each(function(){
			var id = $(this).attr('value');
			if($(this).prop('checked'))
			{
				list = list+'|'+id;
				if(type == 1){ $('.item_'+id).remove(); }
			}
		});
		
		var data_ = new Object();
			data_['act'] = 'operation_apps';
			data_['id'] = list;
			data_['type'] = type;

		$.ajax({
			  type: "POST",
			  url: '/ajax.php',
			  data: data_,
			  dataType:"text",
			  success: function(data)
			  { 
					var card = JSON.parse(data);
					if(card.stat == 1)
					{						
					
					}
			  }
		});
	});	
	
	

	 
  
	$('.create_delivery').on('click',function(){
		var data_ = new FormData($('#data_dalivery')[0]);

		$.ajax({
			  type: "POST",
			  url: '/ajax.php',
			  data: data_,
			  dataType:"text",
			  processData: false, 
			  contentType: false, 
			  success: function(data)
			  { 
					var card = JSON.parse(data);
					if(card.stat == 1)
					{						
						$('#add_delivery').modal('hide');
						
					}
			  }
		});
		
	
	});	
		 
		 
	$('body').on('click','.sel_delivery',function(){
		var status = $(this).prop('checked'); 
		$('.check_').prop('checked',status);

		
		var count = 0;;
		var x = 0;
		$('.check_').each(function(){
			var id = $(this).attr('value');
			if($(this).prop('checked'))
			{
				count++;
				x++;
			} else { count = 0;x--; }
		});
		
		if(x > 0){ $('#selected_col').attr('value',1); } else { $('#selected_col').attr('value',0); } 
		$('.sel_list').text(count);
		
	});		 
	
	
	
	

	$('body').on('click','.operation_delivery',function(){
		var type = $('.type_opetation').val();
		if(type < 1){ return false; }
		
		var list = '';
		$('.check_').each(function(){
			var id = $(this).attr('value');
			
			if($(this).prop('checked'))
			{
				list = list+'|'+id;
				if(type == 1){ $('.item_'+id).remove(); }
			}
		});
		
		$('.sel_obj').text('0');;
		
		var data_ = new Object();
			data_['act'] = 'operation_delivery';
			data_['id'] = list;
			data_['type'] = type;

		$.ajax({
			  type: "POST",
			  url: '/ajax.php',
			  data: data_,
			  dataType:"text",
			  success: function(data)
			  { 
					var card = JSON.parse(data);
					if(card.stat == 1)
					{						
					
					}
			  }
		});
	});	
		
	
	 function update_progressbar()
	 {
		 if($('#page_x').attr('value') == 'delivery')	
		 {
			
			$('.task_progressbar').each(function(){
				var id_task = parseInt($(this).attr('value'));
				
				var data_ = new Object();
				data_['act'] = 'get_data_delivery';
				data_['id'] = id_task;
			
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								
								if(card.this_ < 1){ return false; }
							
								 $( ".task_"+id_task).progressbar({
								  value: card.this_,
								  max: card.max_users,
								});
							}
					  }
				});
				
			});
				
				
			
					
		 }
		
	 }
	 
	 //setInterval(update_progressbar, 2000);	 		
	 
	 function update_delivers()
	 {
		 if($('#page_x').attr('value') == 'delivery')	
		 {
			 var sel = parseInt($('#selected_col').attr('value'));
		
			 if(sel > 0){ return false; }
			 
			var data_ = new Object();
				data_['act'] = 'get_delivers';
				data_['type'] = 'all';
			
					$.ajax({
						  type: "POST",
						  url: '/ajax.php',
						  data: data_,
						  dataType:"text",
						  success: function(data)
						  { 
								var card = JSON.parse(data);
								if(card.stat == 1)
								{
									$('#result').empty();
									$('#result').html(card.code);
									$('.count_sub').text(card.all+' Рассылок');
												
										$('.task_progressbar').each(function(){
											var id_task = parseInt($(this).attr('value'));
											
											var data_ = new Object();
											data_['act'] = 'get_data_delivery';
											data_['id'] = id_task;
										
											$.ajax({
												  type: "POST",
												  url: '/ajax.php',
												  data: data_,
												  dataType:"text",
												  success: function(data)
												  { 
														var card = JSON.parse(data);
														if(card.stat == 1)
														{
															
															if(card.this_ < 1){ return false; }
														
															 $( ".task_"+id_task).progressbar({
															  value: card.this_,
															  max: card.max_users,
															});
														}
												  }
											});
											
										});
										
								}
						  }
					});
					
		 }
		
	 }
	 
	 setInterval(update_delivers, 6000);	

	 
 
	$('body').on('click','.stop',function(){
		
		var id = parseInt($(this).attr('value'));
											
		var data_ = new Object();
		data_['act'] = 'stop_delivery';
		data_['id'] = id;
		
		$.ajax({
			  type: "POST",
			  url: '/ajax.php',
			  data: data_,
			  dataType:"text",
			  success: function(data)
			  { 
					var card = JSON.parse(data);
					if(card.stat == 1)
					{						
						
					}
			  }
		});
		
	});	
	 	
		
	

	
	
	
	$('.list_users').on('change',function(){
		$('.wallet_pay').val('');
		$('.sum_pay').val('');
		
		var user_id = parseInt($(this).val());
		var sum = parseInt($('.list_users option:selected').attr('sum'));
		
		var data_ = new Object();
			data_['act'] = 'get_user_wallet';
			data_['id'] = user_id;
	
		$.ajax({
			  type: "POST",
			  url: '/ajax.php',
			  data: data_,
			  dataType:"text",
			  success: function(data)
			  { 
					var card = JSON.parse(data);
					if(card.stat == 1)
					{						
						if(card.tel > 0){ $('.wallet_pay').val(card.tel); }
						if(sum > 0){ $('.sum_pay').val(sum); }
					}
			  }
		});
		
	});	
	
	
	$('.edit_pwd').on('click',function(){
		$('.result_pwd').hide();
		
	});

	$('.change_pwd').on('click',function(){
		if($('#old_pwd').val() == ''){ return false; }
		var data_ = $('#pwd_ch').serialize();
		
		$.ajax({
			  type: "POST",
			  url: '/ajax.php',
			  data: data_,
			  dataType:"text",
			  success: function(data)
			  { 
					var card = JSON.parse(data);
					if(card.stat == 1)
					{		
						$('.result_pwd').show();
						if(card.change){ $('.result_pwd').text('Пароль изменен!'); }
						else { $('.result_pwd').text('Укажите старый пароль!'); }
					}
			  }
		});
		
	});	
	 		
	


	$('.active_chk').on('click',function(){
	
		var active = 0;
		var id = $(this).attr('value');
		if($(this).prop('checked')){ active = 1; }
			
		var data_ = new Object();
			data_['act'] = 'set_menu_active';
			data_['id'] = id;
			data_['active'] = active;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								
							}
					  }
				});
	});

		
		
	
	 $("body").on('click','.edit_message',function() {
		 var id = parseInt($(this).attr('value'));
		
		if((id < 1) ){ return false; }
		$('#current_id').attr('value',id);
		$('.message_text').text('');
		
		var data_ = new Object();
			data_['act'] = 'get_message_text';
			data_['id'] = id;
			data_['msg'] = $('.message_text').text();;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('#data_text').empty();
								$('#data_text').html('<textarea class="form-control message_text" name="message" >'+card.msg+'</textarea>');
								
								$('#edit_msg').modal('show'); 
							}
					  }
				});
		
	 });
		
			
	
	 $("body").on('click','.save_msg_text',function() {
		 var id = parseInt($('#current_id').attr('value'));
		
		if((id < 1) ){ return false; }
		
		var data_ = new Object();
			data_['act'] = 'save_message_text';
			data_['id'] = id;
			data_['msg'] = $('.message_text').val();;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('#edit_msg').modal('hide'); 
							}
					  }
				});
		
	 });
			
	 
	 
	 

	 
	$('.save_change').on('click',function(){
		
		var data_ = $('#data_f').serialize();
			data_ = data_+'&act=save_change';

			$.ajax({
				  type: "POST",
				  url: '/ajax.php',
				  data: data_,
				  dataType:"text",
				  success: function(data)
				  { 
						card = JSON.parse(data);
						if(card.stat == 1)
						{
							$('#new_change').hide();	
							//$('#new_change').dialog('close');	
						}
				  }
			});
	});
	
	$('body').on('click','.active_set',function(){
		var id = $(this).attr('value');
		var st = 0;
		if($(this).prop('checked')){ st = 1; }
		
		var data_ = new Object();
			data_['act'] = 'set_active_exchange';
			data_['id'] = id;
			data_['active'] = st;
		
			$.ajax({
				  type: "POST",
				  url: '/ajax.php',
				  data: data_,
				  dataType:"text",
				  success: function(data)
				  { 
						card = JSON.parse(data);
						if(card.stat == 1)
						{
							
						}
				  }
			});
		
	});
		

	
	
	
	 $(".set_inline_url").keyup(function() {
		 var id = parseInt($(this).attr('id_com'));
		 var url = $(this).val();
		
		var data_ = new Object();
			data_['act'] = 'set_inline_url';
			data_['url'] = url;
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
							
							}
					  }
				});
		
		 
	 });	
	

	$('.new_inline_').on('click',function(){ 
		$('#new_inline').show();
	});

	$('.create_inline_btc').on('click',function(){
		

		var data_ = new Object();
			data_['act'] = 'create_inline';
			data_['name'] = $('#name_inline').val();
			data_['url'] = $('#url_inline').val();

			$.ajax({
				  type: "POST",
				  url: '/ajax.php',
				  data: data_,
				  dataType:"text",
				  success: function(data)
				  { 
						card = JSON.parse(data);
						if(card.stat == 1)
						{
							$('#new_inline').hide();
							location.href = '/bot_menu/';

						}else { alert('Ошибка укажите URL'); }
				  }
			});
	});
		


	 $("#salute").keyup(function(){
		
		var data_ = new Object();
			data_['act'] = 'set_salute_msg';
			data_['text'] = $(this).val();
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
							
							}
					  }
				});
		
	 });	
	 
 


	 $('body').on('click','.del_item',function(e){

		var id = parseInt($(this).attr('value'));
		$('.tr'+id).css('background','#ffd5d5');
	
		var data_ = new Object();
			data_['act'] = 'del_strukture';
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.tr'+id).remove();
								
							}
					  }
				});		
		
	 });
	 
	 
	 
	 $('.new_element').on('click',function(){
		 var id = parseInt($(this).attr('value'));
		 $('#current_id').attr('value',id);
		 $('.map_x').html('<span style="color:#555;">Позиция:</span> '+$('.name'+id).val()+' / ololo /');
		 $('#new_element').modal('show');
		 
	 });
	 
	 
	 $('.new_button').on('click',function(){
		 $('#new_button_show').modal('show');
		 
	 });	 
	 
	 
	 $(".name_btn").keyup(function(){
		 var id = parseInt($('#current_id').attr('value'));
		 $('.map_x').html('<span style="color:#555;">Позиция:</span> '+$('.name'+id).val()+' / '+$(this).val()+' /');
	 });
	 
	 
	 
	 

	 $('body').on('click','.add_button',function(){

		var current = $('#current_id').attr('value');
		var data_ = new Object();
			data_['act'] = 'new_btn_menu';
			data_['name'] = $('.name_btn').val();
			data_['ico'] = $('.id_ico').val();
			data_['group'] = current;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.name_btn').val('');
								$('.id_ico').prop('selectedIndex',0);
								
								if(card.id > 0){ $('.btc_refresh').show(); } else { $('.btc_refresh').hide(); }
							}
					  }
				});		
		
	 });
	 	 
	 
	 
	 
	 $('body').on('click','.new_button_bot',function(){

		var current = $('#current_id').attr('value');
		var data_ = new Object();
			data_['act'] = 'add_btn_bot';
			data_['name'] = $('.name_btn_x').val();
			data_['ico'] = $('.id_ico_x').val();
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.name_btn').val('');
								$('.id_ico_x').prop('selectedIndex',0);
								
								if(card.id > 0){ $('.btc_refresh_x').show(); } else { $('.btc_refresh_x').hide(); }
							}
					  }
				});		
		
	 });
	 	 
	 
	 
	 $('body').on('change','.position_btn',function(){

		var id = $(this).attr('value');
		var data_ = new Object();
			data_['act'] = 'set_position_strukture';
			data_['pos'] = $(this).val();
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
						
					  }
				});		
		
	 });
	 		 
	 $('body').on('click','.add_prop',function(){

		var id = $(this).attr('value');
		var data_ = new Object();
			data_['act'] = 'add_prop_catalog';
			data_['name'] = $('#name_prop').val();
			data_['alias'] = $('#alias_prop').val();
			data_['type'] = $('#type_prop').val();
			
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
						
					  }
				});		
		
	 });
	


	 $('body').on('click','.del_prop_',function(e){

		var id = parseInt($(this).attr('value'));
		$('.tr'+id).css('background','#ffd5d5');
	
		var data_ = new Object();
			data_['act'] = 'del_prop_cat';
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.prop'+id).remove();
								
							}
					  }
				});		
		
	 });
	 

	
	 $('.new_item').on('click',function(){
		$('.add_item').show();
		$('.save_item').hide();
		$('.title_box').html;('<i class="fa fa-plus-circle" aria-hidden="true"></i> Добавить товар:');
		$('.clear_x').val('');
		$('.photo_').css('background','none');
		$('.active_item_x').prop('checked',false);
		
		$('#content').prop('disabled',false);
		$('#content').css('background','#fff7b2'); 
		
		$('#count').prop('disabled',false);
		$('#count').css('background','#fdfdfd');
		$('#channel_url').val('');
		$('#show_item_box').modal('show');
		 
		 
	 });
	 	
	
	

	 $('body').on('click','.add_item',function(){
		var name = $('input[name="item[name]"]').val();
		var price = $('input[name="item[price]"]').val();
		var content = $('#content').val();
		var msg = '';
		

		if(name == ''){ msg = "Укажите название товара!<br>"; }
		if(price == ''){ msg = msg+"Укажите стоимость товара!<br>"; }
		if(content == ''){ msg = msg+"Укажите продаваемый товар!"; }
		
		if(msg !==''){ alert(msg);return false; }
	
		var active = 0;
		if($('#active_item').prop('checked')){ active = 1; }
		var data_ = $('#data_item').serialize();
			data_ = data_+'&act=new_item&method=2&item[active]='+active;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								 $('#show_item_box').modal('hide');
								 if(card.id_cat > 0){ alert("Добавлено - ID:"+card.id_cat); }
							}
					  }
				});		
		
	 });
	 
	 $('#content').on('keyup',function(){
		var content = $('#content').val();
		
		lines = content.split(/\r|\r\n|\n/);
		if(content == ''){ lines = 0; }
		$('#count').val(lines.length);		
		 
	 });
	 
	 
	 $('body').on('click','.save_item',function(){

		var active = 0;
		var id_item = parseInt($('#current_item').attr('value'));
		if($('#active_item').prop('checked')){ active = 1; }
		var data_ = $('#data_item').serialize();
			data_ = data_+'&act=new_item&method=1&item[active]='+active+'&id_item='+id_item;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								 $('#show_item_box').modal('hide');
							}
					  }
				});		
		
	 });
	 	 
		 
		 
	$('body').on('change','#photo_item_upload',function(){
		
		$('#upload_photo').show('666');
		var data_f = new FormData($('#photo_item_')[0]);   

			$.ajax({
				  type: "POST",
				  url: '/ajax.php',
				  data: data_f,
				  dataType:"text",
				  processData: false,  // tell jQuery not to process the data
				  contentType: false,  // tell jQuery not to set contentType				  
				  success: function(data)
				  { 
					 card=JSON.parse(data);
					 var stat = card.stat;
					if(stat == 1)
					 {	
						if(card.photo !=='')
						{
							$('.photo_').css('background','url("/img/catalog/'+card.photo+'") no-repeat');
							$('.photo_').css('background-size','contain');
							$('#tmp_photo').attr('value',card.photo);
						}
					  }
					  
					  if(card.stat == 0)
					  { 
						if(card.code == 2){ $('.exits_macr').show();  }
						if(card.code == 0){ alert('Укажите изображение!'); }
						
					  }
					  
					  $('#upload_photo').hide();
				      
				  }
			});	
		
	});
			 
		 
	 

	 $('body').on('click','.edit_item',function(e){
		var id = parseInt($(this).attr('value'));
		$('#current_item').attr('value',id);
		
		$('.add_item').hide();
		$('.save_item').show();
		$('.title_box').html('<i class="fa fa-pencil-square" aria-hidden="true"></i> Редактировать товар:');
		
		var data_ = new Object();
			data_['act'] = 'show_item_data';
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var chk = false;
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.clear_x').val('');
								for(var q=0;q<card.list_prop.length;q++)
								{
									var alias = card.list_prop[q];
									if(alias == 'amount_product')
									{
										var val = parseInt(card.item_data[alias]);
										$('.associative_item_').val(val).trigger('change');
									}else { $('#'+alias).val(card.item_data[alias]); }
								}
								
								
								if(card.active > 0){ chk = true; }
								$('#active_item').prop('checked',chk);
								
								if(card.list_sel !== ''){ $('.cat_list').html(card.list_sel); }
								if(card.i !== '')
								{ 
									$('.photo_').css('background','url("/img/catalog/'+card.i+'") no-repeat');
									$('.photo_').css('background-size','contain');
								}
								 $('#show_item_box').modal('show');
							}
					  }
				});		
		
	 });
	 		 
	
	
	 $(".set_prop").keyup(function(){
		
		var id = parseInt($(this).attr('id_x'));
		
		var data_ = new Object();
			data_['act'] = 'set_prop_item';
			data_['col'] = $(this).attr('col');
			data_['val'] = $(this).val();
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
						
					  }
				});
		
	 });	
	 
	
 
	 $(".sort_prop").keyup(function() {
		 var id = parseInt($(this).attr('id_x'));
		 var sid = $(this).val();
		
		var data_ = new Object();
			data_['act'] = 'set_sort_prop';
			data_['sid'] = sid;
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
							
							}
					  }
				});
		
	 });
	 
	  $(".sort_prop").on('change',function() {
		 var id = parseInt($(this).attr('id_x'));
		 var sid = $(this).val();
		
		var data_ = new Object();
			data_['act'] = 'set_sort_prop';
			data_['sid'] = sid;
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
							
							}
					  }
				});
		
		 
	 });
	 
	$('.active_prop').on('click',function(){
		 var id = parseInt($(this).attr('value'));
		var active = 1;
		if($(this).prop('checked')){ active = 0; }
		
		var data_ = new Object();
			data_['act'] = 'set_active_prop';
			data_['active'] = active;
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
							
							}
					  }
				});		
	});
	 

	 
	$('.active_item').on('click',function(){
		var id = parseInt($(this).attr('value'));
		var active = 0;
		if($(this).prop('checked')){ active = 1; }
		
		var data_ = new Object();
			data_['act'] = 'set_active_item';
			data_['active'] = active;
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
							
							}
					  }
				});		
	});	 
	 
 
	$('.del_item_cat').on('click',function(){
		var id = parseInt($(this).attr('value'));

		
		var data_ = new Object();
			data_['act'] = 'del_item';
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.item_'+id).remove();
							}
					  }
				});		
	});	 	




 
	$('.save_opt_qiwi').on('click',function(){
		$('.save_opt_qiwi').prop('disabled',true);
		
		var active = 1
		if($('#active_qiwi').prop('checked')){ active = 0; }
		
		var data_ = new Object();
			data_['act'] = 'save_opt_qiwi';
			data_['api_key'] = $('#api_key_qiwi').val();
			data_['wallet'] = $('#api_wallet_qiwi').val();
			data_['active'] = active;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 0)
							{
								alert('Ошибка некоректные данные!');
							}
							
							$('.save_opt_qiwi').prop('disabled',false);
					  }
				});		
	});	 
	 


	$('.save_opt_yandex').on('click',function(){
		$('.save_opt_yandex').prop('disabled',true);
		
		var active = 1;
		var card = 1;
		if($('#active_yandex').prop('checked')){ active = 0; }
		if($('#get_payment_yandex').prop('checked')){ card = 0; }
		
		var data_ = new Object();
			data_['act'] = 'save_opt_yandex';
			//data_['secret_key'] = $('#secret_key').val();
			data_['wallet'] = $('#wallet').val();
			data_['active'] = active;
			data_['card'] = card;
			
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								//if(card.url !== ''){ $('#url_hook').val(card.url); }
								$('.save_opt_yandex').prop('disabled',false);
							}
					  }
				});		
	});	 
	 
	 
	 

	$('.save_opt_btc').on('click',function(){
		$('.save_opt_btc').prop('disabled',true);
		
		var active = 1
		if($('#active_bitcoin').prop('checked')){ active = 0; }
		
		var data_ = new Object();
			data_['act'] = 'save_opt_btc';
			data_['confirmations'] = $('#confirmations').val();
			data_['wallet'] = $('#btc_address').val();
			data_['level_btc'] = $('#level_btc').val();
			data_['active'] = active;
			
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.save_opt_btc').prop('disabled',false);
							}
					  }
				});		
	});	 	 
	 

	 
	$('.save_opt_exmo').on('click',function(){
		$(this).prop('disabled',true);
		
		var active = 1
		if($('#active_exmo').prop('checked')){ active = 0; }
		
		var data_ = new Object();
			data_['act'] = 'save_opt_exmo';
			data_['secret_key_exmo'] = $('#secret_key_exmo').val();
			data_['api_key_exmo'] = $('#api_key_exmo').val();
			data_['active'] = active;
			
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.save_opt_exmo').prop('disabled',false);
							}
					  }
				});		
	});	 	 
	 
	 
 
	$('.save_opt_payment_btc').on('click',function(){
		$(this).prop('disabled',true);

		
		var data_ = new Object();
			data_['act'] = 'save_opt_payment_btc';
			data_['secret_key'] = $('#secret_key_payment').val();
			data_['api_key'] = $('#api_key_payment').val();
			data_['wallet'] = $('#wallet_payment').val();
			
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.save_opt_payment_btc').prop('disabled',false);
							}
					  }
				});		
	});	 	 
	 	 
	 
	$('.save_opt_payment_qiwi').on('click',function(){
		$(this).prop('disabled',true);

		
		var data_ = new Object();
			data_['act'] = 'save_opt_payment_qiwi';
			data_['api_key'] = $('#api_key_payment_qiwi').val();
			data_['wallet'] = $('#api_wallet_payment_qiwi').val();
			
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.save_opt_payment_qiwi').prop('disabled',false);
							}
					  }
				});		
	});	 	 
		 
	 
	 
	$('.save_opt_unitpay').on('click',function(){
		$(this).prop('disabled',true);
		
		var active = 1
		if($('#active_unitpay').prop('checked')){ active = 0; }
		
		var data_ = new Object();
			data_['act'] = 'save_opt_unitpay';
			data_['secret_key_unitpay'] = $('#secret_key_unitpay').val();
			data_['pub_key_unitpay'] = $('#pub_key_unitpay').val();
			data_['active'] = active;
			
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.save_opt_unitpay').prop('disabled',false);
							}
					  }
				});		
	});	 	 
	 	 
		 
 
	$('.save_opt_freekassa').on('click',function(){
		$(this).prop('disabled',true);
		
		var active = 1
		if($('#active_freekassa').prop('checked')){ active = 0; }
		
		var data_ = new Object();
			data_['act'] = 'save_opt_freekassa';
			data_['secret_key_freekassa'] = $('#secret_key_freekassa').val();
			data_['merchant_id_freekassa'] = $('#merchant_id_freekassa').val();
			data_['active'] = active;
			
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.save_opt_freekassa').prop('disabled',false);
							}
					  }
				});		
	});	 	 
	 	 
	 
		 
	 

	$('.change_status').on('change',function(){
		var id = $(this).attr('id_x');
		var status = $(this).val();

		
		var data_ = new Object();
			data_['act'] = 'set_status_zakaz';
			data_['id'] = id;
			data_['status'] = status;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								
							}
					  }
				});		
	});	 	

	
	$('.del_zakaz').on('click',function(){
		var id = parseInt($(this).attr('id_x'));

		var data_ = new Object();
			data_['act'] = 'del_zakaz';
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.item_'+id).remove();
							}
					  }
				});		
	});	 	
	
	
	$('.save_opt_step').on('click',function(){
		var active = 2;
		if($('#qery_name').prop('checked')){ active = 1; }
		
		var active_how = 2;
		if($('#how_delivery').prop('checked')){ active_how = 1; }
		
		var active_tel = 2;
		if($('#get_phone').prop('checked')){ active_tel = 1; }
		
		$('.save_opt_step').prop('disabled',true);
		
		var data_ = new Object();
			data_['act'] = 'set_step';
			data_['active'] = active;
			data_['query_info'] = $('#query_info').val();
			data_['how_delivery'] = active_how;
			data_['get_phone'] = active_tel;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.save_opt_step').prop('disabled',false);
							}
					  }
				});		
	});	 


	$('.is_type_referer').on('change',function(){
		var type = parseInt($(this).attr('value'));
		if(type < 1){ return false; }
		
		var data_ = new Object();
			data_['act'] = 'set_type_referer';
			data_['type'] = type;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  {
						  
					  }
				});		
	});
	
	
	

	$('.save_gain').on('click',function(){
		var proc = parseInt($('#gain_proc').val());

		var data_ = new Object();
			data_['act'] = 'save_gain';
			data_['proc'] = proc;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
						
					  }
				});		
	});	 	
		
		
	
	$('.create_manager').on('click',function(){

		var data_ = new Object();
			data_['act'] = 'create_manager';
			data_['login'] = $('#login_manager').val();
			data_['pwd'] = $('#pwd_manager').val();
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								if(card.id > 0){ $('#result').append(card.tr); }
								
								$('#login_manager').val('');
								$('#pwd_manager').val('');
								$('#add_manager').modal('hide');
							}
					  }
				});		
	});	 	
			
		
	$('body').on('click','.del_user',function(){
		var id = parseInt($(this).attr('value'));

		var data_ = new Object();
			data_['act'] = 'del_user';
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.item_'+id).remove();
							}
					  }
				});		
	});	 	
			
		
		
	$('body').on('click','.add_price_object',function(){

		if(($('#object_product').val() == '') | ($('#amount_object').val() == '')){ return false; }
	
		var data_ = new Object();
			data_['act'] = 'add_price_object';
			data_['name'] = $('#object_product').val();
			data_['amount'] = $('#amount_object').val();
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								var tr = '<tr class="item_'+card.id+'"><td>'+$('#object_product').val()+'</td><td>1</td><td>'+$('#amount_object').val()+'</td><td><a class="del_value" value="'+card.id+'">X</a></td></tr>';
								$('#result').append(tr);
								
								$('#object_product').val('');
								$('#amount_object').val('');
							}
					  }
				});		
	});	 	
		


	
	$('body').on('click','.del_value',function(){
		var id = parseInt($(this).attr('value'));

		var data_ = new Object();
			data_['act'] = 'del_value';
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.item_'+id).remove();
							}
					  }
				});		
	});	 	
		
		
	$('.sel_payment_list').on('click',function(){
		var id = parseInt($(this).attr('value'));
		
		$('.sel_payment_list').removeClass('sel_payment_active');
		$('.sel_payment_list').css('background','#fff');
		$(this).addClass('sel_payment_active');
		$(this).css('background','#03A9F4');

		var data_ = new Object();
			data_['act'] = 'get_payment_list';
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('#list_payments').html(card.list);
							}
					  }
				});		

	});	
	
	
	$('#search_payment').on('click',function(){
		var id = parseInt($('.sel_payment_active').attr('value'));

		var data_ = new Object();
			data_['act'] = 'search_payment';
			data_['id'] = id;
			data_['date1'] = $('#date1').val();
			data_['date2'] = $('#date2').val();
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('#list_payments').html(card.list);
								if(card.money_x !==''){ $('#money_x').show(); $('#money_x').text(card.money_x); }
									else { $('#money_x').hide(); }
							}
					  }
				});		

	});		

	
	
	
	$('body').on('click','.save_token_list',function(){
		var id = parseInt($(this).attr('value'));
		$('.save_token_list').text('Подключение ботов..');
		$('.save_token_list').prop('disabled',true);
		
		var data_ = new Object();
			data_['act'] = 'save_token_list';
			data_['token_list'] = $('#token_list').val();
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('#list_bot').html(card.list);
								$('.save_token_list').text('Сохранить');
								$('.save_token_list').prop('disabled',false);
							}
					  }
				});		
	});	 	
		
	
	
	$('.add_qiwi_wallet').on('click',function(){
		
		var data_ = new Object();
			data_['act'] = 'add_qiwi_wallet';
			data_['wallet'] = $('#wallet_qiwi_').val();
			data_['api_key'] = $('#api_key_qiwi_').val();
			data_['limit'] = $('#limit_qiwi_').val();
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								if(card.id > 0)
								{
									var tr = "<tr class='w_item"+card.id+"'><td>"+$('#wallet_qiwi_').val()+"</td><td>"+card.balance+"</td><td>Активен</td><td>"+$('#limit_qiwi_').val()+"</td><td><span class='del_wallet' value='"+card.id+"'>X</span></td></tr>";
									$('#result_qiwi').append(tr);
									
									$('#wallet_qiwi_').val('');
									$('#api_key_qiwi_').val('');
								}
							}
					  }
				});		

	});		

	$('body').on('click','.del_wallet',function(){
		var id = parseInt($(this).attr('value'));

		var data_ = new Object();
			data_['act'] = 'del_wallet';
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.w_item'+id).remove();
							}
					  }
				});		
	});	
	
	
	
	$('body').on('change','.qiwi_method',function(){
		var type = parseInt($(this).attr('value'));

		var data_ = new Object();
			data_['act'] = 'qiwi_change_method';
			data_['type'] = type;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { }
				});		
	});	
	
	$('.type_payment').on('change',function(){
		var type = parseInt($(this).val());
		
		if(type == 1)
		{ 
			$('.basket_id').prop('disabled',true);
			$('.info_type_payment').text("Юзер пополняет свой баланс внутри бота, и через этот баланс оплачивает товары.");
			$('#box_min_sum_balance').show();
		}else 
		{ 
			$('.basket_id').prop('disabled',false);
			$('.info_type_payment').text('Юзер добавляет товары в корзину, создает заказ и оплачивает его.');
			$('#box_min_sum_balance').hide();
		}
		
	});
	

	$('body').on('click','.del_bot',function(){
		var id = parseInt($(this).attr('value'));

		var data_ = new Object();
			data_['act'] = 'del_bot';
			data_['id'] = id;
		
				$.ajax({
					  type: "POST",
					  url: '/ajax.php',
					  data: data_,
					  dataType:"text",
					  success: function(data)
					  { 
							var card = JSON.parse(data);
							if(card.stat == 1)
							{
								$('.item'+id).remove();
								$('#token_list').val(card.tokens);
							}
					  }
				});		
	});	 	
			


		
});

