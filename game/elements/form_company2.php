	<form enctype="multipart/form-data" action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/advertiser/index.php?a=add_company';?>&step=2&id=<?php echo $id;?>&delay=<?php echo ($dSpr['delay'])?$dSpr['delay']:0;?>" method="post">
	 <h3>Настроить кампанию</h3>
    	<table cellpadding="10" cellspacing="0" border="0" class="table_form" >
		<tr>
          	
            <td colspan="2"><img src="../sprites/gifs/<?php echo $id;?>.gif"></td>
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
          	
            <td><input name="budget" class="text_pole" type="text" size="30" value="<? if(isset($data['budget']))echo $data['budget'];?>"/></td><td>Бюджет</td>
          </tr>
         <tr>
           <td><input name="" class="button" type="submit" value="Готово"></td>
		   <td></td>
          </tr>

        </table>
    </form>

