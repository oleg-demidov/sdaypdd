<form action="<? echo $host.$_SERVER['REQUEST_URI'];?>" method="post">
	<table border="0">
	<tr><td><textarea name="value0" class="text_pole" rows="10"><? if(isset($data[0]['value']))echo $data[0]['value'];?></textarea>
	</td></tr><tr><td><textarea name="value1" class="text_pole" rows="10"><? if(isset($data[1]['value']))echo $data[1]['value'];?></textarea>
	</td></tr><tr><td><textarea name="value2" class="text_pole" rows="10"><? if(isset($data[2]['value']))echo $data[2]['value'];?></textarea>
	</td></tr><tr><td><input type="submit" class="button" value="Сохранить">
	</td></tr></table>
</form>
<div style="clear:both;"></div>