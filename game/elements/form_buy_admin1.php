<form action="<?php echo $host.$_SERVER['REQUEST_URI'].'&step=2&go=1';?>" method="post"><h3><? echo content('buy_admin_server',$content_buy);?> <span style="color:#000;"><? echo $data_server[0]['name'];?></span></h3>
          </tr>
    	<table cellpadding="10" cellspacing="0" border="0" class="table_adv" align="center">
          
          <tr><td colspan="2"><input name="email" class="text_pole" type="text" size="20" value="<? if(isset($dataForm['email']))echo $dataForm['email'];?>" /></td>
          	<td><? echo content('email');?> *</td>
            
          </tr>
          <tr><td colspan="2"><input name="ip" class="text_pole" type="text" size="20" value="<? if(isset($dataForm['ip']))echo $dataForm['ip'];?>" /></td>
          	<td><? echo content('ip');?></td>
            
          </tr>
          <tr><td colspan="2"><input name="steam" class="text_pole" type="text" size="20" value="<? if(isset($dataForm['steam']))echo $dataForm['steam'];?>" /></td>
          	<td><? echo content('steam_id');?></td>
            
          </tr>
          <tr><td colspan="2"><input name="name" class="text_pole" type="text" size="20" value="<? if(isset($dataForm['name']))echo $dataForm['name'];?>" /></td>
          	<td><? echo content('nick');?></td>
            
          </tr>
          <tr><td colspan="2"><input name="pass" class="text_pole" type="text" size="20" value="" /></td>
          	<td><? echo content('password');?></td>
            
          </tr>
          <tr><td colspan="2"><input name="age" class="text_pole" type="text" size="20" value="<? if(isset($dataForm['age']))echo $dataForm['age'];?>" /></td>
          	<td><? echo content('age');?></td>
            
          </tr>
          <tr class="tr_grey">
           <td colspan="3"><center><input name="" class="button" type="submit" value="<? echo content('next');?>"></center></td>
          </tr>

        </table>
    </form>