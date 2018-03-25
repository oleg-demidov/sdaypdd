
<?

$shows=0;
$clicks=0;
$uni_shows=0;
if($data){
	$sd=sizeof($data);
	for($i=0;$i<$sd;$i++){
		$shows+=$data[$i][1];
		$clicks+=$data[$i][3];
		$uni_shows+=$data[$i][2];
}}
//$datagraph=dataSql2data(&$data,'id_server');
?>