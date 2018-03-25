<?
$server=$bd->select('*','servers',array('id'=>$_GET['server']));
?>
<h1><? echo content('monitoring_server');?></h1>
<table  class="serv_tab" cellpadding="0" cellspacing="0"  style="width:420px; float:left;" >
<tr>
	<th>
		<? echo content('information');?>
	</th>
</tr>
<tr>
<td> 
	<div style="margin:7px;"><? echo content('name');?>: &nbsp; <b><? echo $server[0]['name'];?></b></div>
	<div style="margin:7px;"><? echo content('address');?>: &nbsp; <b><? echo $server[0]['ip'],':',$server[0]['port'];?></b></div>
	<div style="margin:7px;"><? echo content('ping');?>: &nbsp; <b>
	<? if($server[0]['ping'])echo $server[0]['ping'];
		else echo content('unknown');?></b></div>
	<? $types=array('cs16'=>'Counter Strike 1.6',
					'css'=>'Counter Strike Source',
					'csgo'=>'Counter Strike Global Offensive');?>
	<div style="margin:7px;"><? echo content('server_type')?>: &nbsp; <b><? echo $types[$server[0]['type']];?></b></div>
	<div style="margin:7px;"><? echo content('players');?>: &nbsp; 
	<b><? echo $server[0]['players'],'/',$server[0]['max_players'];?></b></div>
	<? $status=array('on'=>content('on'),
					'off'=>content('off'));?>
	<div style="margin:7px;"><? echo content('status');?>: &nbsp; <b><? echo $status[$server[0]['status']];?></b></div>
	<? 
	$city=$bd->select('city_name_ru','city_',array('id'=>$server[0]['geo_city']));
	$country=$bd->select('country_name_ru','country_',array('id'=>$server[0]['geo_country']));
	?>
	<div style="margin:7px;"><? echo content('geo');?>: &nbsp; <b>
	<? if($country)echo $country[0]['country_name_ru'];
		else echo content('unknown');
		if($city)echo '/',$city[0]['city_name_ru'];
	?></b></div>
	<div style="margin:7px;"><? echo content('map');?>: &nbsp; <b>
	<? if($server[0]['map'])echo $server[0]['map'];
	else echo content('unknown');?></b></div>
	<div style="margin:7px;"><? echo content('last_update');?>: &nbsp; <b>
	<? if($server[0]['last_monitor'])echo date('G:h',$server[0]['last_monitor']);
	else echo content('unknown');?></b></div>
</td>
</tr>
</table>
<table  class="serv_tab" cellpadding="0" cellspacing="0" style="width:540px;float:left; margin:0 10px;" >
<tr>
	<th>
		<? echo content('stat');?>
	</th>
</tr>
<tr>
	<td style="padding:0;">
		<div id="chart_div"></div>
	</td>
</tr>
</table>
<script type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'];?>/scripts/jsapi.js"></script>
<script type="text/javascript">
<?
?>
data=<? 
$data=$bd->select(array('time','players'),'monitoring',array('id_server'=>$server[0]['id'],'type'=>'day'),'ORDER BY `time`', 30);
//echo $bd->error;
$data2=$bd->select('time`,SUM(`players`) AS `sp','monitoring',array('id_server'=>$server[0]['id'],'type'=>'hour'));
$sd=sizeof($data);
if($data||$data2){
	if($data2){
		$data[$sd-1]['players']+=$data2[0]['sp'];
	}
}

if($data){
	echo"[['".content('time')."','".content('visitors')."']";
	for($i=0;$i<$sd;$i++){
		echo",[new Date(",date('Y,n-1,j',$data[$i]['time']),"),",$data[$i]['players'];
		echo']';
	}
	echo'];';
}else echo '0;';
?>
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(function(){
		drawChart('chart_div',data);});
function drawChart(div,dataGraph) {
	if(!dataGraph){
		$("#"+div).html("<h3><? echo content('no_data');?></h3>");
		return;
	}
	var data = google.visualization.arrayToDataTable(dataGraph);
	var options = {
		title: '',
		pointSize:5,
		backgroundColor: '#555',
		color:'white',
		crosshair:{color:'black'},
		hAxis:{textStyle:{color:'white'},
			gridlines:{color:'#666', count: 5}},
		vAxis:{textStyle:{color:'white'},
			gridlines:{color:'#3A3A3A', count: 5}}
	};
	var chart = new google.visualization.LineChart(document.getElementById(div));
	chart.draw(data, options);
}
</script>
<div style="clear:both;"></div>