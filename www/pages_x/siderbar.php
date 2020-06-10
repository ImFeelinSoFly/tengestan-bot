<div class="col-md-3 sidebar_log" style='background:#ececec;'>
        
          <ul class="list-group mb-3">
		  
		   <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h4>Последние действия</h4>
              </div>
            
            </li>
			<br><p style='padding-left:10px;'><b>Мои действия</b></p>
			 <?
				$r = mysqli_query($db,"SELECT `type`,`content`,`info` FROM `losg_action` ORDER BY `id` DESC LIMIT 0,15");
				while($ok = mysqli_fetch_assoc($r))
				{
					if($ok['type'] == 1){ $type_ = 'plus add_'; } else { $type_ = 'trash'; }
			 ?>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
			 <div>
               <a  class='lost_event'><i class="fa fa-<?=$type_;?>" aria-hidden="true"></i> <?=$ok['content'];?></a><br>
                <small class="text-muted"><?=$ok['info'];?></small>
              </div>
            </li>
			<?}?>
		  
		   
          </ul>

         
        </div>
		
<style>		
	.justify-content-between{background: #ececec;}
	.lost_event{word-wrap: break-word;word-break: break-all;  }
	.lost_event:hover{ text-decoration:none; }
	
	.list-group-item {
		position: relative;
		display: block;
		padding: 5px 2px;
		margin-bottom: -1px;
		border: 1px solid rgba(210, 210, 210, 0.125);
	}
	
</style>