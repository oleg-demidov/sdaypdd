    <form action="<?php echo $host.$_SERVER['REQUEST_URI'];?>" method="post">
    <input name="dis"  type="text" id="dis" size="5" value="dis" style=" display:none;" >
	<h3><? echo content('extending_privileges',$content_sa);?></h3>
          </tr>
    	<table cellpadding="10" cellspacing="0" border="0" class="table_form" align="center" style="width:500px;">
          
          <tr>
          	<td rowspan="2"><img src="<?php echo $host;?>/images/user_admin.png"></td>
            <td>Email: <? if(isset($data['email']))echo $data['email'];?></td>
          </tr>
          <tr>
           <td><? echo content('nick');?>: <? if(isset($data['nik']))echo $data['nik'];?></td>
          </tr>
          <tr>
            <td colspan="2"><input name="prolong" class="text_pole" style="width:50px; float:none;"  type="text" id="prolong" size="5"> <? echo content('days',$content_sa);?></td>
          </tr>
          <tr>
            <td colspan="2">
             <input name="input" class="button" type="submit" value="<? echo content('extend',$content_sa);?>">
           </td>
          </tr>

        </table>
    </form>