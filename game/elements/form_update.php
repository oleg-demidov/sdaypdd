	<form enctype="multipart/form-data" action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
	 <h3>Загрузить файл в базу</h3>
   
    <table cellpadding="10" cellspacing="0"  class="table_form" >
         
           <tr>
                  <td>Версия</td>
          	<td><input name="version" class="text_pole" type="text" size="3" value="" /></td>
          </tr>
          <tr >
          	<td>Файл</td>
            <td><input type="file" name="file"></td>
          </tr>
          <tr>
          	<td>Тип</td>
            <td>
            <label><input name="type" type="radio" value="cs16" />Couter Strike 1.6</label><br>
            <label><input name="type" type="radio" value="css" />Couter Strike Source</label><br>
            <label><input name="type" type="radio" value="csgo" />Couter Strike GO</label></td>
          </tr>
           <tr>
           <td colspan="2"><input name="" class="button" type="submit" value="Отправить"></td>
          </tr>
        </table>
       
<?
	$result=$bd->sql_query("SELECT distinct(name),id,type,exp FROM `files`");
	$result=$bd->get_result($result);
	if($result){
		echo'<table cellpadding="0" cellspacing="0" class="serv_tab" >
		<tr class="tr_top"><th colspan="4"><center>Файлы</center></th></tr>';
		$sr=sizeof($result);
		for($i=0;$sr>$i;$i++){
			echo"<tr";
			if($i%2)echo' class="nochet" ';
			echo"><td>",$result[$i]['name'],"</td>";
			echo"<td>",$result[$i]['exp'],"</td>";
			echo"<td>",$result[$i]['type'],"</td>";
			echo'<td><a href="http://',$_SERVER['HTTP_HOST'],$_SERVER['REQUEST_URI'],"&del=",$result[$i]['id'],'">удалить</a></td></tr>';
		}
		echo"</table>";
	}
?>
    </form>
