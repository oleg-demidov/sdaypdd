<script type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'];?>/scripts/jsapi.js"></script>
<script type="text/javascript">

<?	
function json_encode_gchart($data,$c=1){
	global $content_adv;
	$str='';
	if(!is_array($data))
		return "0";
	$s1=sizeof($data);
	if($c)$str.="[['".content('time')."','".content('display')."','".content('uni_pokazi')."','".content('hits')."','".content('uni_clicks')."']";
	else $str.="[['".content('city',$content_adv)."','".content('display')."']";
	for($a=0;$a<$s1;$a+=1){
		if(is_array($data[$a])){
			$str.=",[";
			if($c)$str.="new Date(".date('Y,m-1,d',$data[$a][0]).")";
			else{
				if($data[$a][0]=='')
					$sit=content('city_no',$content_adv);
				else $sit=$data[$a][0];
				$str.="'".$sit."'";
			}
			$s2=sizeof($data[$a]);
			for($b=1;$b<$s2;$b+=1)
				$str.=",".$data[$a][$b];
			$str.="]";
		}
	}
	$str.="]";
	return $str;
}

$qt="(`time`>'".$from."' AND `time`<'".$to."')";
$qt1="((`timeout`-60*60*24*(`days`-1))>'".$from."' AND (`timeout`-60*60*24*(`days`-1))<'".$to."')";
if($company=='all'){
	$servers=$bd->select(array('id'),'companies',array('id_user'=>$_SESSION['id']));
	$ss=sizeof($servers);
	//$datasNames=array();
	$qs='AND (';
	for($i=0;$i<$ss;$i++){
		$qs.="`id_banner`='".$servers[$i]['id']."'";
		$qs.=(($i+1)<$ss)?' OR ':'';
		//$datasNames[$servers[$i]['id']]=$servers[$i]['headers'];
	}
	$qs.=')';
}else $qs="AND `id_banner`='".$company."'";

$need=array('time','show','unishow','click','uniclick');
$data=$bd->select($need,"adv_stat",$qt.$qs,'','',MYSQL_NUM);
echo 'data1='.json_encode_gchart($data,1).';';

?>
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(function(){
		drawChart('chart_div',data1);
		drawChart2();
		});
function drawChart(div,dataGraph) {
	if(!dataGraph){
		$("#"+div).html("<h3><? echo content('no_data')?></h3>");
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

