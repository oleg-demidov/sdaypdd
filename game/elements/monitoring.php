<h1><? echo content('monitoring_server');?></h1>
<form id="form1" action="<?php echo $host_lang;?>/monitoring/index.php?<?php echo $_SERVER['QUERY_STRING'];?>" method="get">
<table id="table_monitor_search" height="50px" width="100%" >
	<? $all_yes=(!isset($_GET['cs16'])&&!isset($_GET['css'])&&!isset($_GET['csgo']))?true:false;?>
	<tr>
		<td>
			<label><input onchange="$('#form1').submit();" id="cs16" name="cs16" type="checkbox" <? if(isset($_GET['cs16'])||$all_yes)echo"checked"; ?>>cs16</label>
		</td>
		<td>
			<label><input onchange="$('#form1').submit();" id="css" name="css" type="checkbox" <? if(isset($_GET['css'])||$all_yes)echo"checked"; ?>>css</label>
		</td>
		<td>
			<label><input onchange="$('#form1').submit();" id="csgo" name="csgo" type="checkbox" <? if(isset($_GET['csgo'])||$all_yes)echo"checked"; ?>>csgo</label>
		</td>
		<td>
			<? echo content('show');?>:
			<select onchange="$('#form1').submit();" name="limit_line" size="1">
			<?
				for($i=10;$i<51;$i+=10){
					echo'<option ';
					if(isset($_GET['limit_line'])&&$_GET['limit_line']==$i)
						echo'selected="selected"';
					echo' value="'.$i.'">'.$i.'</option>';
				}
			?>
			</select>
		</td>
		<td>
			<input type="submit" value="<? echo content('search');?>" class="button">
			<input name="query" 
			value="<? if(isset($_GET['query'])&&$_GET['query']!='')echo $_GET['query'];?>" 
			style="float:right; margin:8px 10px 0 0; padding:4px;" type="text" size="50">
		</td>
	</tr>
</table>
</form>
<table class="serv_tab" cellpadding="0" cellspacing="0">
<tr>
	<th><? echo content('name');?></th>
	<th><? echo content('address');?></th>
	<th><? echo content('map');?></th>
	<th><? echo content('players');?></th>
	<th><? echo content('status');?></th>
</tr>
<?
$limitLine=10;
if(isset($_GET['limit_line']))
	$limitLine=$_GET['limit_line'];
$whereQ='';
$getType='';
if(!isset($_GET['cs16'])&&!isset($_GET['css'])&&!isset($_GET['csgo'])){
	$datag=array('cs16'=>'on','css'=>'on','csgo'=>'on');
}else{
	$datag=$_GET;
	unset($datag['query']);
	unset($datag['str']);
}
$i=0;
foreach($datag as $k=>$v){
	if($i)$whereQ.=" OR ";
	if($i)$getType.="&";
	$whereQ.="`type`='$k'";
	$getType.="$k=$v";
	$i++;
}
if(isset($_GET['str']))
	$limit=(($_GET['str']-1)*$limitLine).",$limitLine";
else
	$limit="$limitLine";
if(isset($_GET['query'])&&$_GET['query']!=''){
	$whereQ="(".$whereQ.") AND (MATCH (`name`) AGAINST ('".$_GET['query']."'))";
	$getType.="&query=".$_GET['query'];
}
$data=$bd->select('*','servers',$whereQ,'',$limit);
$sd=sizeof($data);
if($data){
	for($i=0;$i<$sd;$i++){
		echo'<tr ',(($i&1)?'':'class="nochet"'),'>';
		echo'<td><a href="',$host_lang;
		echo'/monitoring/index.php?server=',$data[$i]['id'],'">',$data[$i]['name'],'</a></td>';
		echo'<td>',$data[$i]['ip'],':',$data[$i]['port'],'</td>';
		echo'<td>',$data[$i]['map'],'</td>';
		echo'<td>',$data[$i]['players'],'/',$data[$i]['max_players'],'</td>';
		echo'<td>',$data[$i]['status'],'</td>';
		echo'</tr>';
	}
}
?>
</table>
<div class="buttons_str">
<?
$limitBtn=15;
$ahr='../monitoring/index.php?'.$getType;

if(!isset($_GET['str']))$numStr=1;
else $numStr=$_GET['str'];

$data=$bd->get_result($bd->sql_query("SELECT count(*) FROM `servers` WHERE ".$whereQ));
$countStr=ceil($data[0]['count(*)']/$limitLine);

if($countStr>1){
	if($numStr>=round($limitBtn/2)&&$limitBtn<$countStr){
		$start=$numStr-(round($limitBtn/2)-2);
		echo'<a style="margin-right:5px;" class="str_btn" href="'.$ahr.'&str=1">1</a>';
	}else $start=1;
	
	for($i=0;$i<$limitBtn&&($start<=$countStr);$i++){
		if($start!=$numStr)$id="no_ch_btn";
		else $id="ch_btn";
		echo'<a id="'.$id.'" class="str_btn" href="'.$ahr.'&str='.$start.'">'.$start.'</a>';
		$start++;
	}
	if($start<$countStr+1)
		echo'<a style="margin-left:5px;" class="str_btn" href="'.$ahr.'&str='.$countStr.'">'.$countStr.'</a>';
}
?>
</div>