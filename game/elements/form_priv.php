<form action="<?php echo $host.$_SERVER['REQUEST_URI'];?>" method="post">
    	<table cellpadding="5" cellspacing="0" border="0" class="table_form">
          <tr>
           <td colspan="5"><label><input name="auto_priv" type="checkbox" <? if($data['auto_priv']=='on')echo'checked';?> /> <? echo content('enable_auto_sales',$content_sa);?></label></td></tr>
          
		   </table>
		  <table cellpadding="0" cellspacing="0" border="0" class="serv_tab"> 
          <tr>
            <th><? echo content('name');?></th>
            <th><label><input id="all_c" type="checkbox" /> <? echo content('on_off');?></label></th>
            
            <th><? echo content('description');?></th><th><? echo content('price_per_day',$content_sa);?></th>
           </tr>
<?	

$sp=sizeof($arr_names);
for($i=0;$sp>$i;$i++){
	echo'<tr';
	if($i%2)echo' class="nochet" ';
	echo'><td>',$arr_names[$i],'</td>';
	echo'<td><input class="flag" name="',$arr_names[$i],'" type="checkbox"';
	if($data[$arr_names[$i]]=='on'){echo'checked';$strdis='';}
	echo' /></td><td><input name="',$arr_names[$i],'d" style="width:550px;" class="text_pole" type="text" id="',$arr_names[$i];
	echo'dp" size="5" value="';
	if(isset($data[$arr_names[$i].'d'])&&$data[$arr_names[$i].'d']!='')echo $data[$arr_names[$i].'d'];
	else echo content($defDesk[$arr_names[$i]],$content_sa);
	echo '" /></td><td><input id="',$arr_names[$i],'dp" name="',$arr_names[$i];
	echo 'p" type="text" style="width:30px;" class="text_pole" value="',$data[$arr_names[$i].'p'],'"/></td></tr>';
	
}
?>
           
         
         
        </table><input name="" type="submit" class="button" value="<? echo content('save');?>">
		<div style="clear:both;"></div>
<script type="text/javascript">
function check_all(){
	els=$(':checkbox[class="flag"]');
	if(!this.checked)
		els.removeAttr('checked');
	else els.attr('checked','checked');
	els.trigger('change');
}
els=$('input.flag');
$('#all_c').bind('change',check_all);
els.bind('change',dis);
els.trigger('change');
function dis(){
	els=$(':text[id="'+this.name+'dp"]');
	if(this.checked){
		els.removeAttr('disabled');}
	else els.attr('disabled','disabled');
}
</script>

    </form>