    <form action="<?php echo $host.$_SERVER['REQUEST_URI'];?>" method="post"> 
	<h3><? echo content('new_server',$content_sa);?></h3>
          </tr>
    	<table cellpadding="10" cellspacing="0" border="0" class="table_form">
         
           <tr>
          	
            <td><input name="name" type="text"  class="text_pole" value="<? if(isset($_POST['name']))echo $_POST['name'];?>" /></td><td><? echo content('server_name');?></td>
          </tr>
          <tr class="tr_grey">
          	
            <td><input name="ip" type="text" class="text_pole" style="width:160px;" value="<? if(isset($_POST['ip']))echo $_POST['ip'];?>"/> <div class="delip">:</div> 
            <input name="port" type="text" class="text_pole" style="width:63px;" size="5" value="<? if(isset($_POST['port']))echo $_POST['port'];?>"/></td><td>Ip</td>
          </tr>
          <tr>
          	
            <td>
            <label><input name="type" type="radio" value="cs16" /> Counter Strike 1.6</label><br>
            <label><input name="type" type="radio" value="css" /> Counter Strike Sourse</label>
			<label><input name="type" type="radio" value="csgo" /> Counter Strike Global Offensive</label></td><td><? echo content('server_type',$content_sa);?></td>
          </tr>
              <tr>
          	
            <td><? include('../scripts/geo_country.php');?></td><td><? echo content('geo');?></td>
          </tr> 
         <tr class="tr_grey">
           <td><center><input name="" type="submit" class="button" ></center></td>
		   <td></td>
          </tr>

        </table>
    </form>