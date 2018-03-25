	<form enctype="multipart/form-data" action="<?php echo $host_lang.'/advertiser/index.php?a=company_sett';?>&id=<?php echo $id;?>" method="post">
	 <h3><? echo content('cust_banner',$content_adv);?></h3>
    	<table cellpadding="10" cellspacing="0" border="0" class="table_form" >
		<tr>
          	
            <td colspan="2"><img src="<?php echo $host;?>/sprites/gifs/<?php echo $id;?>.gif"></td>
          </tr>
		  <tr>
          	
            <td><input name="delay" class="text_pole" type="text" size="30" value="<? if(isset($data['delay'])&&$data['delay']!=0)echo (1000/$data['delay']);?>"/></td><td><? echo content('delay',$content_adv);?></td>
          </tr>
    <tr>
          	
            <td><input name="header" class="text_pole" type="text" size="30" value="<? if(isset($data['header']))echo $data['header'];?>"/></td><td><? echo content('title');?></td>
          </tr>
          <tr>
          	
            <td><textarea class="text_pole" name="mess" cols="25" rows="5"><? if(isset($data['mess']))echo $data['mess'];?></textarea></td><td><? echo content('message',$content_adv);?></td>
          </tr>
          <tr>
          	
            <td><input name="site" class="text_pole" type="text" size="30" value="<? if(isset($data['site']))echo $data['site'];?>"/></td><td><? echo content('link');?></td>
          </tr>
          <tr>
          	
            <td><? include('../scripts/geo_country.php');?></td><td><? echo content('geo');?></td>
          </tr>
          <tr>
          	
            <td><input name="budget" class="text_pole" type="text" size="30" value="<? if(isset($data['budget']))echo $data['budget'];?>"/></td><td><? echo content('budget');?></td>
          </tr>
         <tr>
           <td><input name="" class="button" type="submit" value="<? echo content('save');?>"></td>
		   <td></td>
          </tr>

        </table>
    </form>

