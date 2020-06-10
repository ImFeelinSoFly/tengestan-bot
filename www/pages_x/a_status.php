
<style>
.info-box {
    display: block;
    min-height: 90px;
    background: url(https://www.toptal.com/designers/subtlepatterns/patterns/pinstriped_suit.png);
    border-radius: 2px;
    margin-bottom: 15px;
    -webkit-border-radius: 8px;
    -moz-border-radius: 8px;
    border-radius: 8px;
    color: #a2a2a2;
    border-bottom: 1px #777 solid;
}

	#sales-chart
	{
		background: url(/img/grey_wash_wall.png);
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;
	}
	
	.bg-aqua, .bg-green, .bg-yellow, .bg-red { background-color: #2d2d2d !important;color: #848484 !important; }
	.bg-aqua, .bg-green, .bg-yellow, .bg-red {background-color: #f5f5f5 !important;color: #848484 !important;border-right: 1px #dedede solid;}
</style>
		<br><br>
		
		
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
 <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
 <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
 
	<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js'></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>		  
	
			
	<div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><i class="fa fa-shopping-cart"></i> <?=get_count('a_zakaz','status','2');?></h3>
              <p>Продаж</p>
            </div>
   
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3> <i class="fa fa-flag-o"></i> <? echo get_count('a_zakaz');?></h3>
              <p>ВСЕГО ЗАКАЗОВ</p>
            </div>
        
           
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><i class="ion ion-person-add"></i> <?=get_count('Users');?> </h3>
              <p>Всего Подписчиков</p>
            </div>
           
          </div>
        </div>
		
		 <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
			<?
				$all_sum = 0;
				$r = mysqli_query($db,"SELECT `suma` FROM `a_zakaz` WHERE `status` = '2'");
				while($pay = mysqli_fetch_assoc($r))
				{
					$all_sum = $all_sum + $pay['suma'];
				}
				
				$all_sum = number_format($all_sum, 0,',',', ');
			?>
              <h3><i class="ion ion-person-add"></i> <?=$all_sum;?> RUB</h3>
              <p>Прибыль за все время</p>
            </div>
           
          </div>
        </div>

      </div>		
		

	  
		<br><br>
		
	<div class='row'>
	<div class='col-md-6'>
  <!-- DONUT CHART -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Статистика учета товара</h3>

          
            </div>
            <div class="box-body chart-responsive">
              <div class="chart" id="sales-chart" style="height: 300px; position: relative;"></div>
            </div>
          </div>
		</div>	
		
		 <div class="col-md-6">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Статистика посещений</h3>

            </div>
            <div class="box-body chart-responsive">
              <div class="chart" id="line-chart" style="height: 300px;"></div>
            </div>
     
          </div>
		</div>
		
		 <div class="col-md-6">
		 
		 </div>
	
		
		</div>	
			
			
	<script>
		  $(function () {
			"use strict";
		<?
			$pay = get_count('a_zakaz','status','2',1);
			$bad = get_count('a_zakaz','status','3',1);
			$time = get_count('a_zakaz','status','1',1);
			if($pay < 1){ $pay = 0; }
			if($bad < 1){ $bad = 0; }
			if($time < 1){ $time = 0; }
		?>
		  //DONUT CHART
			var donut = new Morris.Donut({
			  element: 'sales-chart',
			  resize: true,
			  colors: ["#3f92b7", "#f56954", "#00a65a"],
			  data: [
				{label: "В обработке", value: <?=$time;?>},
				{label: "Отменено заказов", value: <?=$bad;?>},
				{label: "Оплачено", value: <?=$pay;?>}
			  ],
			  hideHover: 'auto'
			});
	
		  
		 // LINE CHART
    var line = new Morris.Line({
      element: 'line-chart',
      resize: true,
      data: [
	<?php
		$date = date('Y.m.d');
		$s = "SELECT `date`,`id_chat` FROM `static_day` ";
		$r = mysqli_query($db,$s);
		$num = mysqli_num_rows($r);
		$q = 0;
		$param = array();
		while($ok = mysqli_fetch_assoc($r))
		{	
			$date_ = $ok['date'];
			$id_chat = $ok['id_chat'];
			$num_ =  get_count('static_day','date',$date_,1);
			$users =  get_count('static_day','id_chat',$id_chat,1);
			$param[$date_]['num'] = $num_;
			$param[$date_]['user'] = $users;
			$q++;
		
		}	
		foreach($param as $stat=>$val)
		{
			$arr .= "{y: '".$stat."', item1: ".$val['num'].", Посетителей: ".$users." },\n";
		}
			$arr = substr($arr,0,strlen($arr)-1);
	  ?>
		<?=$arr;?>

      ],
      xkey: 'y',
      ykeys: ['item1'],
      labels: ['Посетителей'],
      lineColors: ['#3c8dbc'],
      hideHover: 'auto'
    });  
		  
	
		  
  });
		  
		  
		    
		  
	</script>	
	

		
		