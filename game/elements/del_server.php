<table  border="0" align="center" width="500">
 <tr>
    <td colspan="2"><h3><? echo content('udalit_text');?></h3></td>
  </tr>
  <tr>
    <td rowspan="3"><img src="<? echo $host;?>/images/server.png" ></td>
    <td width="350" colspan="2"><? echo content('name').': '.$data[0]['name']?></td>
  </tr>
  <tr>
    <td colspan="2"><? echo ' IP '.$data[0]['ip']?></td>
  </tr>
  <tr>
    <td><a class="button" href="<?php echo $host_lang.$_SERVER['REQUEST_URI'];?>&ok=1"><? echo content('remove');?></a></td>
    <td><a  class="button" href="<? echo $host_lang;?>/server_admin/index.php?a=my_servers"><? echo content('cancel');?></a></td>
  </tr>
</table>
