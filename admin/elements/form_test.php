<form action="<? echo "http://",$_SERVER['HTTP_HOST'],"/admin/index.php?a=test";?>" method="post" class="form_enter">
<h3>Редактировать вопрос</h3>
<input type="hidden" name="id" value="<? if(isset($data['id']))echo $data['id'];?>"/> 
<table cellpadding="5" cellspacing="0"  align="center">
	<tr>
		<td><input name="number" class="text_pole" type="text" size="10" value="<? if(isset($data['number']))echo $data['number'];?>"/></td>
		<td>Номер</td>
		<td rowspan="2"><? if(isset($data['img']))
		echo'<img src="http://',$_SERVER['HTTP_HOST'],'/tests/images/s',$data['img'],'.jpg" />';?></td>
	</tr>
	<tr>
		<td><input name="bilet" class="text_pole" type="text" size="10" value="<? if(isset($data['bilet']))echo $data['bilet'];?>"/></td>
		<td>Билет</td>
	</tr>
	<tr>
		<td colspan="2"><select name="id_category"><?
$categories = $bd->get_columns("SELECT * FROM `categories` ORDER BY `num`");
$sc=sizeof($categories['id']);
for($i = 0; $i < $sc; $i++){
	echo'<option value="', $categories['id'][$i],'" ';
	if(isset($data['id_category']) && $data['id_category'] == $categories['id'][$i])
		echo'selected="selected"';
	echo'>',$categories['num'][$i],' ', $categories['category'][$i], '</option>';
}
?></select></td>
		<td>Категория</td>
	</tr>
	<tr>
		<td colspan="3">Вопрос<br><textarea style="width:500px; height:100px;" name="value"><? if(isset($data['value']))echo $data['value'];?></textarea></td>
	</tr>
	<tr>
		<td colspan="3">Ответ1<br><textarea style="width:500px; height:50px;" name="ans1"><? if(isset($data['ans1']))echo $data['ans1'];?></textarea></td>
	</tr>
	<tr>
	<tr>
		<td colspan="3">Ответ2<br><textarea style="width:500px; height:50px;" name="ans2"><? if(isset($data['ans2']))echo $data['ans2'];?></textarea></td>
	</tr>
	<tr>
	<tr>
		<td colspan="3">Ответ3<br><textarea style="width:500px; height:50px;" name="ans3"><? if(isset($data['ans3']))echo $data['ans3'];?></textarea></td>
	</tr>
	<tr>
	<tr>
		<td colspan="3">Ответ4<br><textarea style="width:500px; height:50px;" name="ans4"><? if(isset($data['ans4']))echo $data['ans4'];?></textarea></td>
	</tr>
		<tr>
		<td colspan="3">Ответ5<br><textarea style="width:500px; height:50px;" name="ans5"><? if(isset($data['ans5']))echo $data['ans5'];?></textarea></td>
	</tr>
	<tr>
		<td  colspan="2"><input name="right" class="text_pole" type="text" size="10" value="<? if(isset($data['right']))echo $data['right'];?>"/></td>
		<td>Верный</td>
	</tr>
	<tr>
	<tr>
		<td colspan="3">Коммент<br><textarea style="width:500px; height:100px;" name="comm"><? if(isset($data['comm']))echo stripslashes ($data['comm']);?></textarea></td>
	</tr>
	<tr>
		<td colspan="3"><input type="submit" value="Сохранить"/></td>
	</tr>
</table>
    </form>