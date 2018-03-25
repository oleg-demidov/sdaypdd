	<form enctype="multipart/form-data" action="<?php echo $host_lang.'/advertiser/index.php?a=add_company';?>&step=3&delay=<?php echo (isset($_GET['delay']))?$_GET['delay']:'';?>" method="post">
    	<table cellpadding="10" cellspacing="0" border="0" class="table_form" >
		<tr>
          	
            <td colspan="2">
<img src="<? echo $host;?>/advertiser/banner_creator/tmp/<? echo $_SESSION['id']; ?>.gif" id="animate_img"/>
<input type="text" name="delay" value="<? echo (isset($_GET['delay']))?$_GET['delay']:'';?>" style="visibility:hidden;">
			</td>
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

