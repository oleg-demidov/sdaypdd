<form action="<? echo $host.$_SERVER['REQUEST_URI'];?>"  method="post">
	<table align="center" cellpadding="10" cellspacing="3">
	<tr><td><? echo content('description')?></td><td>
	<? 
	function pole($name,$c){ 
		return '<input type="text" name="'.$name.'" class="text_pole" size="50" value="'.$c.'"/>';
	}
	if(isset($_GET['id']))
		echo $data['desc'];
	else
		echo pole('desc','');?></td></tr>
	<tr><td>
	<? echo content('summ',$content_tn)?></td><td>
	<b><? if(isset($_GET['id']))echo $data['summ']; else echo pole('summ','');?></b>
	</td></tr>
	<tr><td><? echo content('sposob',$content_tn)?></td><td>
	<select name="sposob">
		<option value="webmoney">Webmoney (0.8%)</option>
		<option value="robokassa">Robokassa (5%)</option>
	</select>
	</td></tr>
	<tr><td colspan="2"><input type="submit" class="button" value="<? echo content('next');?>"/></td></tr>
	</table>
</form>

