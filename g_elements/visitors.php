<div class="header_panel">Кто на сайте</div>
<div class="panel_block">
	<div class="visitors">
<?php
//echo $_SESSION['user']['id'];
    if(isset($_SESSION['user']['id'])){
        if(isset($_SESSION['user']['gost'])){
            set_visit_gost();
        }else{
            set_visit_user($_SESSION['user']['id']);
            //echo $bd->error;
        }
    }
    $visitors = get_visitors($_SESSION['user']['id']);
    //print_r($visitors);
    $su = sizeof($visitors);
    if(!$visitors)$su = 0;
    $defava = 'http://'.$_SERVER['HTTP_HOST'].'/images/def_ava50.jpg';
    for($i=0; $i<$su; $i++){
            if($visitors[$i]['avatar50'] == NULL)
                    $visitors[$i]['avatar50'] = $defava;
?>
	<table cellpadding="2" class="visitor">
		<tr>
		 <td>
		  <img alt="<? echo $visitors[$i]['name'];?>" src="<? echo $visitors[$i]['avatar50']; ?>"/>
		 </td>
                 <td><a href="<?php echo get_profile_link($visitors[$i])?>">
		 	<?php if($visitors[$i]['first_name'] == NULL)echo $visitors[$i]['name'];
				else echo $visitors[$i]['first_name'],'<br>',$visitors[$i]['last_name']; ?>
                     </a>
		 </td>
		</tr>
		
	</table>
	
<?php
	}
if($visitors)echo'Всего: ',$visitors[0]['all'],'<br>';

echo 'Гостей:', get_gosts();?>
</div></div>