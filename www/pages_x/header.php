<div class='container'>



<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
		<span class='glyphicon glyphicon-globe'></span> <b>Магазин</b>
    </div>
    <ul class="nav navbar-nav">
	<?
		if($rang == 2){ $param = " AND `operator` = '1' "; } else { $param = ''; }
		$r = mysqli_query($db,"SELECT `id`,`sid`,`name`,`alias` FROM `a_menu` WHERE `active` = '0' $param ORDER BY `sid` ASC");
		while($menu = mysqli_fetch_assoc($r))
		{
			if($act == $menu['alias']){ $sel = 'active'; } else { $sel = ''; }
			if($menu['alias'] == 'orders')
			{
				$rx = mysqli_query($db,"SELECT `id` FROM `a_zakaz` WHERE `status` = '2'");
				$news = mysqli_num_rows($rx);
				$info = '<span class="label label-success" style="margin:0 3px;color:#fff;">+'.$news.'</span>';
			}elseif($menu['alias'] == 'payments')
			{
				$rx = mysqli_query($db,"SELECT `id` FROM `payment_users` WHERE `status` = '2'");
				$all = mysqli_num_rows($rx);
				$info = '<span class="label label-success" style="margin:0 3px;color:#fff;">+'.$all.'</span>';
			}else { $info = ''; }
	?>
      <li class="<?=$sel;?>"><a href="/<?=$menu['alias'];?>/"><?=$menu['name'].$info;?></a></li>
		<?}?>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href='#' class='link_head edit_pwd' data-toggle="modal" data-target="#edit_pwd"><span class="glyphicon glyphicon-lock"></span> Passwr</a></li>
      <li><a href='/logout/' class='link_head'><span class="glyphicon glyphicon-log-in"></span> Exit</a></li>
    </ul>
  </div>
</nav>

	<div class="col-md-12 breadcrumb_" >
		<ul class="breadcrumb">
		  <li><a href="/bot/">Bot</a> <span class="divider"> > </span></li>
		  <li class="active_bread"><?=$title;?></li>
		</ul>
		
		
	</div>
<div class='row row_x'>

