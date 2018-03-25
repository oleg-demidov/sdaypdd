<form action="<? echo "http://",$_SERVER['HTTP_HOST'],"/admin/index.php?a=user&",$_SERVER['QUERY_STRING'];?>" method="post" class="form_enter">
<h3>Редактировать пользователя</h3>
<input type="hidden" name="id" value="<? if(isset($data['id']))echo $data['id'];?>"/> 
<table cellpadding="5" cellspacing="0"  align="center">
	<tr>
		<td><input name="name" class="text_pole" type="text" size="50" value="<? if(isset($data['name']))echo $data['name'];?>"/></td>
		<td>Имя</td>
	</tr>
	<tr>
		<td><input name="email" class="text_pole" type="text" size="50" value="<? if(isset($data['email']))echo $data['email'];?>"/></td>
		<td>Email</td>
	</tr>
	<tr>
		<td><input name="pass" class="text_pole" type="text" size="50" value="<? if(isset($data['pass']))echo $data['pass'];?>"/></td>
		<td>Пароль</td>
	</tr>
	<tr>
		<td><input name="donate" class="text_pole" type="text" size="50" value=""/> <? if(isset($data['donate']))echo date('d.m.y h:i', $data['donate']);?></td>
		<td>Включить на (дней)</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" value="Сохранить"/></td>
	</tr>
</table>
    </form>