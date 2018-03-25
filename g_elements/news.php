<h1><? if(isset($CONT->header))echo $CONT->header;?></h1>
<script>
    $( document ).ready(function() {
        $('#delNews').click(function(){
            var cod = $(this).attr('cod');
            var id = $(this).attr('que');
            $(this).parent().parent().remove();
            $.get('http://<? echo $hh;?>/scr/remove_new.php?id='+id+'&cod='+cod);
        });
    });
</script>
<?php
$del = 5;
$lim = isset($_GET['lim']) ? intval($_GET['lim']) : 0;
$datan = $bd->get_all("SELECT `pages`.* FROM `pages` WHERE `type`='news' ORDER BY `data` DESC  LIMIT ?,?", array(($lim),$del));
$sp = sizeof($datan);
$url_red = "http://".$_SERVER['HTTP_HOST']."/admin/index.php";
include('scr/remove_cod.php');
for($i=0;$i<$sp;$i++){
	echo'<div class="newsPost">';
        echo'<h2><a href="http://',$_SERVER['HTTP_HOST'],'/index.php?a=page&id=',$datan[$i]['id'],'">',$datan[$i]['title'],'</a></h2>';
        echo '<div class="newsText">',stripslashes(substr($datan[$i]['text'],0,  strpos($datan[$i]['text'], '<hr />'))),'</div>';
        echo '<div class="dateNews">',date ( 'j.m.Y', $datan[$i]['data'] );
        if ($_SESSION['user']['id'] == 1) {
            echo'<a href="http://' . $_SERVER['HTTP_HOST'] . '/index.php?a=add_new&id=', $datan[$i]['id'], '" class="redac">редактировать</a>';
            echo'<a que="', $datan[$i]['id'],'" cod="',$cod,'" id="delNews" class="redac">удалить</a>';
        }
    echo'</div></div>';
}

include('scr/navigator.php');
if($_SESSION['user']['id']==1)
    echo'<a href="http://',$hh,'/index.php?a=add_new" class="button">Добавить</a>';
$pageNav = new SimPageNav();
$cont = $bd->get_all("SELECT COUNT(`id`) AS `count` FROM `pages` WHERE `type`='news'");
echo $pageNav->getLinks( $cont[0]['count'], $del, $lim, 5, 'lim' );

