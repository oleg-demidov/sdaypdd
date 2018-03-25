<form action="<? echo "http://",$_SERVER['HTTP_HOST'],"/admin/index.php?a=timg&",$_SERVER['QUERY_STRING'];?>" method="post" class="form_enter" enctype="multipart/form-data">
<h3>Редактировать изображение</h3>
<input type="hidden" name="id" value="<? if(isset($data['id']))echo $data['id'];?>"/> 
<table cellpadding="5" cellspacing="0"  align="center">
	<tr>
		<td><input name="number" class="text_pole" type="text" size="10" value="<? if(isset($data['number']))echo $data['number'];?>"/></td>
		<td>Номер вопроса</td>
	</tr>
	<tr>
		<td><input name="vid" class="text_pole" type="text" size="10" value="<? if(isset($data['vid']))echo $data['vid'];?>"/></td>
		<td>Разновидность</td>
	</tr>

	
<?
if(!isset($data['id']))echo'<tr><td colspan="2"><input type="file" name="image"/></td></tr>';
else echo'<tr><td colspan="2"><img src="http://',$_SERVER['HTTP_HOST'],'/tests/images/',$data['id'],'.jpg"/></td></tr>';
?>

	<tr>
		<td colspan="2"><input type="submit" value="Сохранить"/></td>
	</tr>
</table>
    </form>