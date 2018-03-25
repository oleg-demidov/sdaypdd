<form action="<? echo "http://",$_SERVER['HTTP_HOST'],"/admin/index.php?a=sign&",$_SERVER['QUERY_STRING'];?>" method="post" class="form_enter" enctype="multipart/form-data">
<h3>Редактировать знак</h3>
<input type="hidden" name="id" value="<? if(isset($data['id']))echo $data['id'];?>"/> 
<table cellpadding="5" cellspacing="0"  align="center">
	<tr>
		<td><input name="num" class="text_pole" type="text" size="10" value="<? if(isset($data['num']))echo $data['num'];?>"/></td>
		<td>Номер</td>
	</tr>
	<tr>
		<td><input name="type" class="text_pole" type="text" size="10" value="<? if(isset($data['type']))echo $data['type'];?>"/></td>
		<td>тип</td>
	</tr>
	<tr>
		<td><select name="id_category"><?
$categories = $bd->get_columns("SELECT * FROM `cat_signs` ORDER BY `category`");
$sc=sizeof($categories['id']);
for($i = 0; $i < $sc; $i++){
	echo'<option value="', $categories['id'][$i],'" ';
	if(isset($data['id_category']) && $data['id_category'] == $categories['id'][$i])
		echo'selected="selected"';
	echo'>', $categories['category'][$i], '</option>';
}
?></select></td>
		<td>Категория</td>
	</tr>
	
<?
if(!isset($data['id']))echo'<tr><td colspan="2"><input type="file" name="sign"/></td></tr>';
else echo'<tr><td colspan="2"><img src="http://',$_SERVER['HTTP_HOST'],'/signs/large',$data['id'],'.png"/><img src="http://',$_SERVER['HTTP_HOST'],'/signs/medium',$data['id'],'.png"/><img src="http://',$_SERVER['HTTP_HOST'],'/signs/small',$data['id'],'.png"/></td></tr>';
?>
	<tr>
		<td><input name="name" class="text_pole" type="text" size="80" value="<? if(isset($data['name']))echo $data['name'];?>"/></td>
		<td>Имя</td>
	</tr>
		<td colspan="2"><textarea style="width:500px; height:300px;" name="text"><? if(isset($data['text']))echo $data['text'];?></textarea></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" value="Сохранить"/></td>
	</tr>
</table>
    </form>