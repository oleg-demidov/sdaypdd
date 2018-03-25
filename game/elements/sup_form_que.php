<form class="sup_que" action="<? echo $host.$_SERVER['REQUEST_URI'];?>" method="post">
<h3><? echo content('new_que')?></h3>
<table>
<tr><td colspan="2">Категория <select name="type">
	<option value="tech_que"><? echo content('tech_que')?></option>
	<option value="money_que"><? echo content('money_que')?></option>
	</select>
</td></tr>
<tr><td><textarea style="width:350px;" name="text" class="text_pole" rows="5"></textarea></td>
<td><input style="margin:0 0 0 50px;" type="submit" class="button"></td></tr>
</table>
</form>