<form action="<? echo $host.$_SERVER['REQUEST_URI'];?>"  method="post">
	<table align="center" cellpadding="3" cellspacing="3">
	<tr><td>
	<? echo content('description')?></td><td>
	<input type="text" name="desc" class="text_pole" size="50" value="<? echo content('in',$content_tn);?>"/>
	</td></tr>
	<tr><td>
	<? echo content('summ',$content_tn)?></td><td>
	<input type="text"  style="float:none;" class="text_pole" name="summ" size="50" value=""/>
	</td></tr>
	<tr><td colspan="2"><input type="submit" class="button" value="<? echo content('next');?>"/></td></tr>
	</table>
</form>
