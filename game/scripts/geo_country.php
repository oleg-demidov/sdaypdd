<select name="geo_country" id="geo_country">
<option value="0"><? echo content('any');?></option>
<?
$geoC=(!isset($data['geo_country']))?1:$data['geo_country'];
echo 'Id_country'.$geoC;
$g_data=$bd->select('*','country_');
$sg=sizeof($g_data);
for($i=0;$i<$sg;$i++){
	echo'<option value="'.$g_data[$i]['id'].'"';
	if($geoC==$g_data[$i]['id'])echo' selected';
	echo'>'.$g_data[$i]['country_name_'.$lang].'</option>';
}
?>
</select> 
<select name="geo_city" id="geo_city">
<option value="0">Любой</option>
<?
$geoC=(!isset($data['geo_city']))?1:$data['geo_city'];
$g_data=$bd->select('*','city_',array('id'=>$geoC));
$sg=sizeof($g_data);
for($i=0;$i<$sg;$i++){
	echo'<option value="'.$g_data[$i]['id'].'" ';
	if($geoC==$g_data[$i]['id'])echo' selected';
	echo'>'.$g_data[$i]['city_name_'.$lang].'</option>';
}
mysql_select_db($bd_custom,$bd->connect);
?>
</select>
<script type="text/javascript">
	$("#geo_country").change(function(){
		if($("#geo_country").value==0){
			$('#geo_city').replaceWith(0);
			return;
		}
		urla='http://'+window.location.host+'/scripts/geo_city.php?country='+$(this).val();
		$.ajax({
			url:urla ,
			error:function (XMLHttpRequest, textStatus, errorThrown) {
				  alert('server error')
				},
			success: function(data) {
				$('#geo_city').replaceWith(data);
			}
		});
	});
	//$(document).ready(function () { $('#geo_country').trigger('change');});
	
</script>