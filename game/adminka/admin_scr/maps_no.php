<h3>Нет координат для карт</h3>
<table cellpadding="0"  cellspacing="0" class="serv_tab">
<tr><th>Игра</th><th>Карта</th><th></th></tr>
<?
$data=$bd->select('*','maps_no');
if($data){
	for($i=0;$i<sizeof($data);$i++){
		echo'<tr';
		if($i%2)echo' class="nochet" ';
		echo'><td>';
		echo $data[$i]['game'];
		echo'</td><td>';
		echo $data[$i]['map_name'];
		echo'</td><td><a href="',$host_lang,'/adminka/index.php?a=del_map_no&id=',$data[$i]['id'],'">Удалить</a>';
		echo'</td></tr>';
	}
}
?>
</table>