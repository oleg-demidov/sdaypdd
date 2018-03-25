<form action="<?php echo $host.$_SERVER['REQUEST_URI'];?>" method="post">
<link href="<? echo $host?>/scripts/calendar.css" rel="stylesheet" type="text/css" /><div style="clear:both;"></div>
  <table style="margin-top:5px;" cellpadding="0"  cellspacing="0" border="0" class="serv_tab">
    <tr>
	<?
	$data=$bd->select('*','servers',array('id_user'=>$_SESSION['id']));
	$sds=sizeof($data);
	$sdata=array();
	for($i=0;$i<$sds;$i+=1)
		$sdata[$data[$i]['id']]=$data[$i]['name'];
		
	if(isset($_POST['from'])&&$_POST['from']!='') $from=strtotime($_POST['from']);
	else $from=strtotime('first day of this month');
	//echo ' from'.date('Y-m-d H:m:i',$from);
	
	if(isset($_POST['to'])&&$_POST['to']!='')	$to=strtotime($_POST['to'])+60*60*20;
	else $to=strtotime('last day of this month');
	//echo ' to'.date('Y-m-d H:m:i',$to);
	if(isset($_POST['server'])&&$_POST['server']!='')
		$server=$_POST['server'];
	else $server="all";
	?>
      <th colspan="3">
	  <? if($server=="all")echo content('all_servers',$content_sa);else echo $sdata[$server];?> 
	 (<? echo date("d.m.Y",$from),'-',date("d.m.Y",$to);?>)</th>
	 </tr>
   
      <tr>
	  <td>
        <label for="server"><? echo content('server');?>:</label>
       <select name="server" id="server">
        <option value="all"><? echo content('all');?></option>
         <?
	
	if($data){
		$s=sizeof($data);$i=0;
		while($i<$s){
			echo'<option value="'.$data[$i]['id'].'" ';
			if(isset($_POST['server'])&&($_POST['server']==$data[$i]['id']))echo'selected';
			echo'>'.$data[$i]['name'].'</option>';
			$i++;
		} 
	}
?>
      </select></td>

      <td>
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
	  <input style="clear:none;" type="text" name="from" id="from" class="date">
      
		<div style="float:left; margin:5px;"> <? echo content('to');?></div>
      <input type="text" name="to" id="to" class="date">
	  
	  </td>

      <td><input name="" class="button" type="submit" value="<? echo content('show');?>"></td>
    </tr>
 
	<tr><td colspan="3" style="padding:0px;" id="chart_div1">
</td>
</tr>

	<tr><td colspan="3" style="padding:0px;" id="chart_div2">
</td>
</tr>
</table>
</form>

<script type="text/javascript" src="<? echo $host?>/scripts/calendar.js"></script>