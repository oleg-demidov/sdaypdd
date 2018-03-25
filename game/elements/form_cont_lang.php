<form action="<? echo $host.$_SERVER['REQUEST_URI'];?>" method="post">
	<textarea name="value" class="text_pole" rows="10"><? echo $data[0]['value'];?></textarea>
	<input type="submit" class="button" value="Сохранить">
</form>
<div style="clear:both;"></div>