	<form enctype="multipart/form-data" action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>&step=2" method="post">
	 <h3>Создать рекламную кампанию</h3>
    	<table cellpadding="10" cellspacing="0" border="0" class="table_form" >
    
         
           <tr>
            
          	<td><input name="name" class="text_pole" type="text" size="30" value="<? if(isset($data['name']))echo $data['name'];?>" /></td><td>Название</td>
          </tr>
          <tr >
          	
            <td><? 
			if(isset($data['id'])){
				echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/sprites/'.$data['id'].'.spr">';
				echo'<img src="http://'.$_SERVER['HTTP_HOST'].'/sprites/icons/'.$data['id'].'.gif"></a>';
			}else echo'<input type="file" name="sprite"> - <b>только gif</b>';	?></td>
			<td>Файл баннера</td>
          </tr>
          <tr>
          	
            <td>
            <input type="file" name="vtf">
            </td><td>Файл vtf  </td>
          </tr>
          <tr>
          	
            <td><input name="header" class="text_pole" type="text" size="30" value="<? if(isset($data['header']))echo $data['header'];?>"/></td><td>Заголовок</td>
          </tr>
          <tr>
          	
            <td><textarea class="text_pole" name="mess" cols="25" rows="5"><? if(isset($data['mess']))echo $data['mess'];?></textarea></td><td>Сообщение</td>
          </tr>
          <tr>
          	
            <td><input name="site" class="text_pole" type="text" size="30" value="<? if(isset($data['site']))echo $data['site'];?>"/></td><td>Ссылка</td>
          </tr>
          <tr>
          	
            <td><? include('../scripts/geo_country.php');?></td><td>География</td>
          </tr>
          <tr>
          	
            <td><input name="limit" class="text_pole" type="text" size="30" value="<? if(isset($data['limit']))echo $data['limit'];?>"/></td><td>Бюджет</td>
          </tr>
          <? include('special_serv.php')?>
         <tr>
           <td><input name="" class="button" type="submit" value="Далее"></td>
		   <td></td>
          </tr>

        </table>
    </form>

