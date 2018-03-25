    <form action="<?php echo $host.$_SERVER['REQUEST_URI'];?>" method="post">
    	<table cellpadding="10" cellspacing="0" border="0" style="height:100%;" class="table_form">
          <tr>
             <td><input class="text_pole" name="name" type="text" size="20" value="<? echo $data_server[0]['name'];?>" /></td>	
			<td><? echo content('server_name',$content_sa);?></td>
          </tr>
          <tr>
          	<td width="500px"><input name="ip" id="ip" class="text_pole" type="text" size="20" value="<? echo $data_server[0]['ip'];?>"/><div class="delip"> : </div> <input name="port" id="port" class="text_pole" type="text" size="20" value="<? echo $data_server[0]['port'];?>"/></td>
			<td><? echo content('ip_port',$content_sa);?></td>
          </tr>
          <tr>
          	<td>
            <label><input name="type" type="radio" value="cs16" <? if($data_server[0]['type']=='cs16') echo 'checked';?>/> Counter Strike 1.6</label><br>
            <label><input name="type" type="radio" value="css" <? if($data_server[0]['type']=='css') echo 'checked';?>/> Counter Strike Sourse</label><br>
			<label><input name="type" type="radio" value="csgo" <? if($data_server[0]['type']=='csgo') echo 'checked';?>/> Counter Strike Global Offensive</label></td>
			<td><? echo content('server_type',$content_sa);?></td>
          </tr>
              <tr>
          	<td height="20">
			<?
			$data=array();
			$data['geo_country']=$data_server[0]['geo_country'];
			$data['geo_city']=$data_server[0]['geo_city'];
			include('../scripts/geo_country.php');?></td>
			<td><? echo content('geo');?></td>
          </tr> 
		   
         <tr>
           <td><center><input name="" class="button" type="submit" value="<? echo content('save');?>"></center></td><td></td>
          </tr>

        </table>
    </form>