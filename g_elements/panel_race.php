<div class="header_panel">Статистика</div>
<div class="panel_block">
<table cellpadding="2" class="visitor" width="100%">
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
    $winners = $bd->get_all("SELECT `users`.`name`,`social`.`first_name`, (`race_stat`.`p1` + `race_stat`.`p2` + `race_stat`.`p3` + `race_stat`.`p4`) AS `count`, `social`.`avatar50`,`race_stat`.`p1`, `race_stat`.`p2`, `race_stat`.`p3`, `race_stat`.`p4`, `race_stat`.`last_race` FROM `race_stat` LEFT JOIN `social` ON `social`.`id_user`=`race_stat`.`id_user` LEFT JOIN `users` ON `users`.`id`=`race_stat`.`id_user` ORDER BY `race_stat`.`p1` DESC, `race_stat`.`p2` DESC, `race_stat`.`p3` DESC, `race_stat`.`p4` DESC, `race_stat`.`last_race` DESC LIMIT 5");
    $sw = sizeof($winners);
    $defava = 'http://'.$_SERVER['HTTP_HOST'].'/images/def_ava50.jpg';
    for($i=0;$i<$sw;$i++){
        if($winners[$i]['avatar50'] == NULL)
            $winners[$i]['avatar50'] = $defava;
?>
	
    <tr>
     <td>
            <?php echo ($i+1); ?>
     </td>
     <td>
      <img src="<?php echo $winners[$i]['avatar50']; ?>"/>
     </td>
     <td><?php 
            if($winners[$i]['first_name'] == NULL)
                echo $winners[$i]['name'];
            else echo $winners[$i]['first_name']; ?>
     </td>
     <!--<td>
            <?php echo $winners[$i]['count']?> <span style="font-size:0.6em;"><?php echo pluralForm($winners[$i]['count'],'гонка', 'гонки', 'гонок')?></span>
     </td>-->
     <td><?php if($i<3){?><img src="/race/images/m<?php echo ($i+1); ?>.png"><?php }?></td>
    </tr>
		
	
	
<?php
	}
?></table>
    <a href="/index.php?a=race_stat">Полная статистика</a>
</div>