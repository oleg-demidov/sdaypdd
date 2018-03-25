<form action="<? echo "http://",$_SERVER['HTTP_HOST'],"/admin/index.php?a=post&",$_SERVER['QUERY_STRING'];?>" method="post" class="form_enter">
<h3>Редактировать страницу</h3>
<input type="hidden" name="id" value="<? if(isset($data['id']))echo $data['id'];?>"/> 
<table cellpadding="5" cellspacing="0"  align="center">
	<tr>
		<td><input name="title" class="text_pole" type="text" size="50" value="<? if(isset($data['title']))echo $data['title'];?>"/></td>
		<td>Title</td>
	</tr>
	<tr>
		<td><input name="header" class="text_pole" type="text" size="50" value="<? if(isset($data['header']))echo $data['header'];?>"/></td>
		<td>Заголовок</td>
	</tr>
	<tr>
		<td><input name="post" id="post" class="text_pole" type="checkbox" 
		<? if(isset($data['post']) && $data['post'] == 'on')echo 'checked="checked"';?>/></td>
		<td><label for="post">Опубликовать</label></td>
	</tr>
	<tr>
		<td><input name="menu" id="menu" class="text_pole" type="checkbox" 
		<? if(isset($data['menu']) && $data['menu'] == 'on')echo 'checked="checked"';?>/></td>
		<td><label for="menu">В меню</label></td>
	</tr>
	<tr>
		<td colspan="2"><textarea style="width:500px; height:300px;" name="text"><? if(isset($data['text']))echo $data['text'];?></textarea></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" value="Сохранить"/></td>
	</tr>
</table>
    </form>