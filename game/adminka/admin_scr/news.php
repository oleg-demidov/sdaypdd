<table cellpadding="0"  cellspacing="0" class="serv_tab">
<?
$lim=(isset($_GET['limit']))?$_GET['limit']:0;
$data=$bd->select('*','news','','',$lim.',30');
echo'<tr><th>Дата</th><th>Заголовок</th><th></th></tr>';
$s=sizeof($data);$i=0;
		while($i<$s){
			echo'<tr';
			if($i%2)echo' class="nochet" ';
			echo'><td>'.date('d-m-Y',$data[$i]['date']).'</td>';
			echo'<td>'.$data[$i]['header'].'</td>';echo'<td><a href="'.$host_lang.'/adminka/index.php?a=del_news&id='.$data[$i]['id'].'">удалить</a></td></tr>';
			$i++;
		} 
?>
</table>
<a href="<? echo $host_lang;?>/adminka/index.php?a=news&limit=<? echo ($lim+30);?>">след</a>
<br><a class="button" href="<? echo $host_lang;?>/adminka/index.php?a=add_news">Добавить новость</a><div style="clear:both;"></div>