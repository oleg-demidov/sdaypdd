<form action="<? echo $host.$_SERVER['REQUEST_URI'];?>" method="post">
<table><tr><td>
Ключ <input type="text" name="key">
</td></tr><tr><td>
Категория <input type="text" name="category">
</td></tr><tr><td>
Контент EN<textarea name="value_en" class="text_pole" rows="10"></textarea>
</td></tr><tr><td>
Контент RU<textarea name="value_ru" class="text_pole" rows="10"></textarea>
</td></tr><tr><td>
<input type="submit" class="button" value="Добавить">
</td></tr></table>
</form>