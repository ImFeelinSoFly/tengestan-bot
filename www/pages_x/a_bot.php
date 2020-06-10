<style>	
	.add_{color: #38dc2f;}
	.edit_{color:#E4C14B; }
	.link_menu:hover{ text-decoration:none; }
	.group_menu{color: #107592;font-weight:600;}
	.group_menu:hover{ text-decoration:none; }
	.sorted_menu{width:47px;background: #fff;font-weight: 600;padding: 2px 5px;color: #8e8e8e;}
</style>
	
	
	<div class="col-md-8">
          <h4 >Меню</h4>
        
		<div class='menu'>
		 <table class="table">
		<thead>
		  <tr style='background:#70A7BC;color:#fff;'>
			<th style='min-width:119px;'>BOT</th>
			<th style='min-width:93px;'></th>
			
			<th style='min-width:40px;'>Sort</th>
		  </tr>
		</thead>
		<tbody>
		<?
			
		
			$r = mysqli_query($db,"SELECT `id`,`sid`,`name`,`alias`,`modal` FROM `a_menu` WHERE `active` = '0' ORDER BY `sid` ASC");
			while($menu = mysqli_fetch_assoc($r))
			{
		?>
		 <tr>
			<td>
			
			<a href='/<?=$menu['alias'];?>/' class='group_menu'><?=$menu['name'];?></a></td>
			<td><?if(!empty($menu['modal'])){?><a href='#' class='link_menu' data-toggle="modal" data-target="#<?=$menu['modal'];?>"><i class="fa fa-plus add_" aria-hidden="true"></i> Добавить</a><?}?></td>
			
			<td><input type='number' min='0' name='pos' class='sorted_menu' value='<?=$menu['sid'];?>' id_com='<?=$menu['id'];?>'></td>
		  </tr>
		<?}?>
		 
		 
		</tbody>
	  </table>
		</div>
		
        </div>