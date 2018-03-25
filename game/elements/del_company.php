<table  border="0" align="center" width="500">
 <tr>
    <td colspan="3"><h3><? echo content('udalit_text');?></h3></td>
  </tr>
  <tr>
  <?
$urll=$host.'/advertiser/banner_creator/img_low.php?s=100&f=';
$urlf=$host.'/sprites/gifs/'.$data[0]['id'].'.gif';
  ?>
    <td rowspan="2"><img src="<? echo $urll,$urlf;?>" ></td>
    <td width="350" colspan="2" align="center"><? echo $data[0]['header']?></td>
  </tr>
  <tr>
    <td><a class="button" href="<?php echo $host.$_SERVER['REQUEST_URI'];?>&ok=1"><? echo content('remove');?></a></td>
    <td><a class="button" href="<? echo $host_lang;?>/advertiser/index.php?a=company"><? echo content('cancel');?></a></td>
  </tr>
</table>