<a class="button" href="<? echo $host_lang?>/adminka/index.php?a=add_content">Добавить</a>
<table cellpadding="0"  cellspacing="0" class="serv_tab">
<tr><th>time</th><th>Категория <select name="category" id="mysel" onChange="my_sel()">
<option></option>

	<?
		$cats=$bd->select('category','content',''," GROUP BY `category`");
		if($cats){
			$sl=sizeof($cats);
			for($i=0;$sl>$i;$i++){
				echo'<option';
				if(isset($_GET['cat'])&&$_GET['cat']==$cats[$i]['category'])
					echo' selected="selected" ';
				echo' value="',$cats[$i]['category'],'">',$cats[$i]['category'],'</option>';
			}
		
		}
	?>
</select>
<script language="javascript" type="text/javascript">
function my_sel(){
	sel=document.getElementById("mysel");
	window.location='<? echo $host_lang;?>/adminka/index.php?a=content&cat='+sel.options[sel.selectedIndex].value;
}
</script>
</th><th></th><th>Языки</th></tr>
<?
if(isset($_GET['cat'])){
	$where="`category`='".$_GET['cat']."'";
	$uri='&cat='.$_GET['cat'];
}else{
	$where='';
	$uri='';
}
if(isset($_GET['start']))
	$start=$_GET['start'];
else $start=0;
$data=$bd->select('*','content',$where,'',$start.','.($start+30));
$langs=$bd->select('value','variables',array('name'=>'langs'));
$langs=explode(',',$langs[0]['value']);
$sl=sizeof($langs);
if($data){
	$sd=sizeof($data);
	for($i=0;$i<$sd;$i++){
		echo'<tr';
		if($i%2)echo' class="nochet" ';
		echo'><td>',date('d F Y',$data[$i]['time']),'</td>';
		echo'<td>',$data[$i]['category'],'</td>';
		echo'<td><a href="',$host_lang,'/adminka/index.php?a=del_cont&id=',$data[$i]['id'],'">удалить</a></td>';
		echo'<td>';
		for($l=0;$sl>$l;$l++){
			echo'<a class="button" style="margin:-5px 5px;" href="',$host_lang,'/adminka/index.php?a=cont_cfg&id=',$data[$i]['id'],'&l=',$langs[$l],'">',$langs[$l],'</a>';
		}
		echo'</td></tr>';
	}
}
?>
</table>
<a href="<? echo $host_lang;?>/adminka/index.php?a=content<? echo $uri?>&start=<? echo ($start+30);?>">след.</a>