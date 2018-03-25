<form action="<? echo "http://",$_SERVER['HTTP_HOST'],"/admin/index.php?a=settings&",$_SERVER['QUERY_STRING'];?>" method="post" class="form_enter">
<h3>Настройки</h3>
<table cellpadding="5" cellspacing="0"  align="center">
	<tr>
		<td><input name="home" class="text_pole" type="text" size="20" value="<? if(isset($data['home']))echo $data['home'];?>"/></td>
		<td>Домашняя</td>
	</tr>
	<tr>
		<td><input name="price" class="text_pole" type="text" size="20" value="<? if(isset($data['price']))echo $data['price'];?>"/></td>
		<td>Цена за день</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" value="Сохранить"/></td>
	</tr>
</table>
    </form>