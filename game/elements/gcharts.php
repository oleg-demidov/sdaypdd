<script type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'];?>/scripts/jsapi.js"></script>
<script type="text/javascript">

<?	
function json_encode_gchart($data,$coun){
	global $content_sa;
	$str='';
	if(!is_array($data))
		return "0";
	$s1=sizeof($data);
	if($coun==1)
		$str.="[['".content('time')."','".content('display')."','".content('uni_pokazi')."','".content('hits')."','".content('uni_clicks')."']";
	else
		$str.="[['".content('time')."','".content('admins',$content_sa)."']";
	for($a=0;$a<$s1;$a+=1){
		if(is_array($data[$a])){
			$str.=",[";
			$str.="new Date(".date('Y,n-1,j',$data[$a][0]).")";
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
$qs='';
if(isset($_POST['server'])&&$_POST['server']=='all'){
	$servers=$bd->select('*','servers',array('id_user'=>$_SESSION['id']));
	$ss=sizeof($servers);
	$datasNames=array();
	$qs.='AND (';
	for($i=0;$i<$ss;$i++){
		$qs.="`id_server`='".$servers[$i]['id']."'";
		$qs.=(($i+1)<$ss)?' OR ':'';
		$datasNames[$servers[$i]['id']]=$servers[$i]['name'];
	}
	$qs.=')';
}else $qs.=(isset($_POST['server']))?"AND `id_server`='".$_POST['server']."'":'';

$qq="SELECT UNIX_TIMESTAMP(FROM_UNIXTIME(`timeout`-60*60*24*(`days`-1),'%Y%m%d')), COUNT(*) FROM `admins` where ".$qt1.$qs." GROUP BY UNIX_TIMESTAMP(FROM_UNIXTIME(`timeout`-60*60*24*(`days`-1),'%Y%m%d'))";
//echo " ",$qq," ";
$data=$bd->sql_query($qq);
$data=$bd->get_result($data,MYSQL_NUM);
///print_r($data);
echo 'data1='.json_encode_gchart($data,2).';';

$need=array('time','show','unishow','click','uniclick');
$data=$bd->select($need,"adv_stat",$qt.$qs,'','',MYSQL_NUM);
echo 'data2='.json_encode_gchart($data,1).';';

?>
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(function(){
		drawChart('chart_div1',data1);
		drawChart('chart_div2',data2);});
function drawChart(div,dataGraph) {
	if(!dataGraph){
		$("#"+div).html("<h3><? echo content('no_data'); ?></h3>");
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

