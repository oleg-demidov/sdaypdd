<link rel='stylesheet' href='<?php echo $host;?>/scripts/calendar.css' type='text/css'>
<script type="text/javascript" src="<?php echo $host;?>/scripts/calendar.js"></script>
    <form action="<?php echo $host.$_SERVER['REQUEST_URI'];?>" method="post">
	<h3><? echo content('time_sale_priv',$content_sa);?></h3>
    	<table cellpadding="10" cellspacing="0" border="0" class="table_form">
          <tr>
          	<td><label><input name="autobuy" type="checkbox" <? if(isset($data['autobuy'])&&$data['autobuy']=='on')echo'checked';?>/> <? echo content('auto_sale_priv',$content_sa);?></label></td>
			<td></td>
          </tr> 
		  <tr>
          	<td><input name="rank" class="text_pole" type="text" value="<? echo $data['rank'];?>" /> </td>
			<td><? echo content('required_rank',$content_sa);?></td>
          </tr>
          <tr>
          	<td colspan="2"><input type="radio" name="time_type" id="time" value="alltime" <? if($data['time_type']=='alltime')echo'checked';?> />
       	    <label for="time"><? echo content('always',$content_sa);?></label></td>
          </tr>
          <tr>
          	<td><input type="radio" name="time_type" id="time2" value="days" <? if($data['time_type']=='days')echo'checked';?>/>
       	    <label for="time2"><? echo content('by_day_of_week',$content_sa);?></label></td>
         
            <td >
	<label><input name="pn" type="checkbox" <? if(isset($flags['pn']))echo'checked';?> /> <? echo content('monday',$content_sa);?></label><br>
 	<label><input name="vt" type="checkbox" <? if(isset($flags['vt']))echo'checked';?> /> <? echo content('tuesday',$content_sa);?></label><br>
    <label><input name="sr" type="checkbox" <? if(isset($flags['sr']))echo'checked';?> /> <? echo content('wednesday',$content_sa);?></label><br>
    <label><input name="ch" type="checkbox" <? if(isset($flags['ch']))echo'checked';?> /> <? echo content('thursday',$content_sa);?></label><br>
    <label><input name="pt" type="checkbox" <? if(isset($flags['pt']))echo'checked';?> /> <? echo content('friday',$content_sa);?></label><br>
    <label><input name="sb" type="checkbox" <? if(isset($flags['sb']))echo'checked';?> /> <? echo content('saturday',$content_sa);?></label><br>
    <label><input name="vs" type="checkbox" <? if(isset($flags['vs']))echo'checked';?> /> <? echo content('sunday',$content_sa);?></label>
            </tr>
          <tr>
            <td colspan="2"><input type="radio" name="time_type" id="time3"  value="period" <? if($data['time_type']=='period')echo'checked';?>/>
            <label for="time3"><? echo content('period',$content_sa);?></label></td>
          </tr>
          <tr>
            <td><div style="float:left; margin:5px 10px;"><? echo content('from');?> </div>
              <input class="date" name="time_from" style="width:100px;" type="text" size="15" value="<?  echo $data['time_from'];?>" /></td>
            <td><div style="float:left; margin:5px 10px;"><? echo content('to');?> </div>
              <input class="date" name="time_to" style="width:100px;" type="text" size="15" value="<? echo $data['time_to'];?>" /></td>
          </tr>
         
          
               
         <tr class="tr_grey">
           <td><input name="" type="submit" class="button" value="<? echo content('save');?>"></td><td></td>
          </tr>

        </table>
    </form>
 
