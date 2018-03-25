<form action="<? echo $host.$_SERVER['REQUEST_URI'];?>" method="post">
<table><tr><td colspan="2">Категория <input type="text" name="category"></td></tr>
<tr><td>RU</td><td>EN</td></tr>
<tr><td>Value 0<br><textarea name="value0ru" class="text_pole" rows="10"></textarea></td>
<td>Value 0<br><textarea name="value0en" class="text_pole" rows="10"></textarea></td></tr>
<tr><td>Value 1<br><textarea name="value1ru" class="text_pole" rows="10"></textarea></td>
<td>Value 1<br><textarea name="value1en" class="text_pole" rows="10"></textarea></td></tr>
<tr><td>Value 2<br><textarea name="value2ru" class="text_pole" rows="10"></textarea></td>
<td>Value 2<br><textarea name="value2en" class="text_pole" rows="10"></textarea></td></tr><tr><td>
<input type="submit" class="button" value="Добавить">
</td></tr></table>
</form>