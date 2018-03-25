<table style="float:left;width:524px; margin:0 5px 5px 0;" cellpadding="0" cellspacing="0" class="serv_tab" >
<tr><th><? echo content('search_trans',$content_tn)?></th></tr>
<tr><td>
<form method="post" action="<? echo $host.$_SERVER['REQUEST_URI']?>">
	<div><input name="query" type="text" class="text_pole" size="150"
	value="<? echo (isset($_POST['query'])?$_POST['query']:'')?>"/>
	<input style="margin:-2px 10px;" type="submit" class="button" value="<? echo content('search')?>">
	<div style="clear:both;"></div></div>
	<div style="margin:5px 0 0 0;">
		<link href="<? echo $host?>/scripts/calendar.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="<? echo $host?>/scripts/calendar.js"></script>
		<div style="float:left; line-height:15px;">
		<?
		$lw1=date("d-m-Y",strtotime('previous week'));
		$lw2=date("d-m-Y",strtotime('last Monday'));
		$lm1=date("d-m-Y",strtotime('first day of previous month'));
		$lm2=date("d-m-Y",strtotime('last day of previous month'));
		$tw1=date("d-m-Y",strtotime('last Monday'));
		$tw2=date("d-m-Y",strtotime('next Monday'));
		$tm1=date("d-m-Y",strtotime('first day of this month'));
		$tm2=date("d-m-Y",strtotime('last day of this month'));
		?>
		<a href="javascript:settDate(<? echo "'$lw1','$lw2'";?>);"><? echo content('last_week');?></a> 
		<a href="javascript:settDate(<? echo "'$tw1','$tw2'";?>);"><? echo content('current_week');?></a> <br>
		<a href="javascript:settDate(<? echo "'$lm1','$lm2'";?>);"><? echo content('last_month');?></a>
		<a href="javascript:settDate(<? echo "'$tm1','$tm2'";?>);"><? echo content('this_mont');?></a>
		</div>
		<div style="float:left; margin:5px;"><? echo content('from');?></div>
		<input style="clear:none;" type="text" name="from" id="from" class="date" 
		value="<? echo (isset($_POST['from'])?$_POST['from']:'')?>">
		<div style="float:left; margin:5px;"> <? echo content('to');?></div>
		<input type="text" name="to" id="to" class="date" 
		value="<? echo (isset($_POST['to'])?$_POST['to']:'')?>">
		
	</div>
</form>
</td></tr>
</table>