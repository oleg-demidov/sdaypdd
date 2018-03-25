<h1><?php if(isset($CONT->header))echo $CONT->header;?></h1>
<div>
<table cellpadding="5" cellspacing="0" class="stat_table" width="100%">
    <tr>
        <td></td>
        <td></td>
        <td>Количество гонок. Места<table width="100%"><tr><td>1-е</td><td>2-е</td><td>3-е</td><td>4-е</td></tr></table></td>
        <td>Всего гонок</td>
        <td>Последняя гонка</td>
    </tr>
<?php
function pluralForm($n, $form1, $form2, $form5) {
    /** Склонение существительных с числительными
 * @param int $n число
 * @param string $form1 Единственная форма: 1 секунда
 * @param string $form2 Двойственная форма: 2 секунды
 * @param string $form5 Множественная форма: 5 секунд
 * @return string Правильная форма
 */
    $n = abs($n) % 100;
    $n1 = $n % 10;
    if ($n > 10 && $n < 20) return $form5;
    if ($n1 > 1 && $n1 < 5) return $form2;
    if ($n1 == 1) return $form1;
    return $form5;
}
    $del = 10;
    $lim = isset($_GET['lim']) ? intval($_GET['lim']) : 0;
    $winners = $bd->get_all("SELECT  `users`.`name`,`social`.`first_name`,`social`.`last_name`, (`race_stat`.`p1` + `race_stat`.`p2` + `race_stat`.`p3` + `race_stat`.`p4`) AS `count`, `social`.`avatar50`,`race_stat`.`p1`, `race_stat`.`p2`, `race_stat`.`p3`, `race_stat`.`p4`, `race_stat`.`last_race` FROM `race_stat` LEFT JOIN `social` ON `social`.`id_user`=`race_stat`.`id_user` LEFT JOIN `users` ON `users`.`id`=`race_stat`.`id_user` ORDER BY `race_stat`.`p1` DESC, `race_stat`.`p2` DESC, `race_stat`.`p3` DESC, `race_stat`.`p4` DESC, `race_stat`.`last_race` DESC LIMIT ?,?", array(($lim),$del));
    $sw = sizeof($winners);
    $defava = 'http://'.$_SERVER['HTTP_HOST'].'/images/def_ava50.jpg';
    for($i=0;$i<$sw;$i++){
        if($winners[$i]['avatar50'] == NULL)
            $winners[$i]['avatar50'] = $defava;
?>
	
    <tr>
     <td>
            <?php $place = $lim + $i +1; 
            if($place<3){?><img src="/race/images/m<?php echo $place; ?>.png"><?php }
            else echo $place;
            ?>
     </td>
     <td>
      <img src="<?php echo $winners[$i]['avatar50']; ?>"/><br>
          <?php 
            if($winners[$i]['first_name'] == NULL)
                echo $winners[$i]['name'];
            else echo $winners[$i]['first_name'] + ' ' + $winners[$i]['last_name']; ?>
     </td>
     <td><table width="100%"><tr><td><?php echo $winners[$i]['p1']; ?></td>
                 <td><?php echo $winners[$i]['p2']; ?></td>
                 <td><?php echo $winners[$i]['p3']; ?></td>
                 <td><?php echo $winners[$i]['p4']; ?></td>
             </tr></table></td>
     <td>
            <?php echo $winners[$i]['count']?> 
     </td>
     <td><?php echo $winners[$i]['last_race']?></td>
    </tr>
		
	
	
<?php
	}
?></table>
</div>
<?php
include('scr/navigator.php');
if($_SESSION['user']['id']==1)
    echo'<a href="http://',$hh,'/index.php?a=add_new" class="button">Добавить</a>';
$pageNav = new SimPageNav();
$cont = $bd->get_all("SELECT COUNT(`id`) AS `count` FROM `race_stat`");
echo $pageNav->getLinks( $cont[0]['count'], $del, $lim, 5, 'lim' );