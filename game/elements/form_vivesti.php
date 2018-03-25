<form action="<? echo $host.$_SERVER['REQUEST_URI'];?>"  method="post">
	<table align="center" cellpadding="5" cellspacing="3">
	<tr><td><? echo content('summ',$content_tn)?> <input type="text" style="float:none;" class="text_pole" name="sum" size="50"/>
	</td><td><? echo content('comment',$content_tn)?><br>
	  <textarea name="comment" class="text_pole" cols="45" rows="3"></textarea></td></tr>
	<tr><td><input type="submit" class="button" value="<? echo content('vivesti');?>"/></td></tr>
	</table>
</form>