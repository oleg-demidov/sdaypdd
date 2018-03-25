<form action="<? echo "http://",$_SERVER['HTTP_HOST'],"/admin/index.php?a=post&",$_SERVER['QUERY_STRING'];?>" method="post" class="form_enter">
<h3>Редактировать пункт</h3>
<input type="hidden" name="id" value="<? if(isset($data['id']))echo $data['id'];?>"/> 
<table cellpadding="5" cellspacing="0"  align="center">
	<tr>
		<td><input name="punkt" class="text_pole" type="text" size="10" value="<? if(isset($data['punkt']))echo $data['punkt'];?>"/></td>
		<td>Пункт</td>
	</tr>
	<tr>
		<td><select name="category"><?
$categories = $bd->get_columns("SELECT * FROM `categories` ORDER BY `num`");
$sc=sizeof($categories['id']);
for($i = 0; $i < $sc; $i++){
	echo'<option value="', $categories['id'][$i],'" ';
	if(isset($data['category']) && $data['category'] == $categories['id'][$i])
		echo'selected="selected"';
	echo'>', $categories['num'][$i], ' ', $categories['category'][$i], '</option>';
}
?></select></td>
		<td>Категория</td>
	</tr>
	<tr>
		<td colspan="2"><textarea style="width:500px; height:300px;" name="text"><? if(isset($data['text']))echo $data['text'];?></textarea></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" value="Сохранить"/></td>
	</tr>
</table>
    </form>