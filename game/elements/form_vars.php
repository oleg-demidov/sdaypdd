<form action="<?php echo $host.$_SERVER['REQUEST_URI'];?>" method="post">
<?
	//$bdObj=new BD($user_bd,$pass_bd);
	$data=$bd->select('*','variables');
	if($data){
	$s=sizeof($data);$i=0;
	echo'<table cellspacing="0"  class="serv_tab">';
	echo'<tr><th>Имя</th><th>Значение</th><th></th></tr>';
		while($i<$s){
			echo'<tr';
			if($i%2)echo' class="nochet" ';
			echo'><td>'.$data[$i]['name'].'</td>';
			echo'<td><input name="'.$data[$i]['name'].'" class="text_pole" type="text" value="'.$data[$i]['value'].'"></td>';
			echo'<td><a href="'.$host_lang.'/adminka/index.php?a=del_var&namev='.$data[$i]['name'].'">удалить</a></td></tr>';
			$i++;
		} 
	}
?>
</table><input name="" type="submit" class="button" value="Сохранить"><div style="clear:both;"></div>
</form>
<form action="<?php echo $host_lang;?>/adminka/index.php?a=add_var" method="post">
	<table width="200" cellspacing="0"  class="serv_tab">
  <tr>
    <th>Имя</th>
    <th>Значение</th>
    <th></th>
  </tr>
  <tr>
    <td><input name="name" class="text_pole" type="text" value=""></td>
    <td><input name="value" class="text_pole" type="text" value=""></td>
    <td><input name="" type="submit" class="button" value="Добавить"></td>
  </tr>
</table>

</form>