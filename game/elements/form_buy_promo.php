<form action="<?php echo $host.$_SERVER['REQUEST_URI'];?>" method="post"><h3><? echo content('activ_promo',$content_buy); ?></h3>
          </tr>
    	<table cellpadding="10" cellspacing="0" border="0" class="table_adv" align="center">
        <tr>
		 <td><input name="promocode" class="text_pole" type="text" size="20" value="" /></td>
        <td><center><input name="" class="button" type="submit" value="<? echo content('activ'); ?>"></center></td>
          </tr>

        </table>
    </form>