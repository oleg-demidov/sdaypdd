<h1><? if(isset($CONT->header))echo $CONT->header;?></h1>
<script type='text/javascript' src='/scr/removerQ.js'></script>
<form method="post" class="searchForm" action="<? echo "http://",$_SERVER['HTTP_HOST'],"/index.php?a=add_que";?>">
    <input type="text" id="search_text" style="color: #666;" name="stext" value="Начинайте вводить вопрос">
    <input type="submit" class="button but_search_que"  value="Задать вопрос">
<script>
    $('#search_text').bind('focus',function(){
            $(this).val('').css('color','#333').unbind('focus');
            $('.searchForm').unbind('submit');
    });
    $('#search_text').bind('keyup',function(){
        $.get('http://<? echo $hh?>/scr/search_que.php?s='+$(this).val(),function(data){
            $('.resultsQue').empty().html(data);
        } );
    });
    $('.searchForm').bind('submit',function(){$('#search_text').val('');});
</script>

<script>
    $( document ).ready(function() {
        $('.removeQ').click(function(){
            var cod = $(this).attr('cod');
            var id = $(this).attr('que');
            $(this).parent().remove();
            $.get('http://<? echo $hh;?>/scr/remove_que.php?id='+id+'&cod='+cod);
        });
    });
</script>
</form>
<div class="resultsQue">
    
<?
$del = 10;
$lim = isset($_GET['lim']) ? intval($_GET['lim']) : 0;
$datan = $bd->get_all("SELECT `questions`.`id`, `questions`.`date`, `questions`.`header`, `users`.`name`, `social`.`first_name`, `social`.`avatar50`, `countq`.`ans` FROM `questions` LEFT JOIN `users` ON `users`.`id`=`questions`.`id_user` LEFT JOIN `social` ON `social`.`id_user`=`questions`.`id_user` LEFT JOIN (SELECT `id_que`, count(`id`) AS `ans` FROM `answers` GROUP BY `id_que`) AS `countq` ON `questions`.`id`=`countq`.`id_que` ORDER BY `questions`.`date` DESC  LIMIT ?,?", array(($lim),$del));
$sp = sizeof($datan);
$url_red = "http://".$_SERVER['HTTP_HOST']."/admin/index.php";
include('scr/remove_cod.php');
for($i=0;$i<$sp;$i++){
        if($datan[$i]['avatar50']=='')
            $datan[$i]['avatar50']='http://'.$_SERVER['HTTP_HOST'].'/images/def_ava50.jpg';
        if($datan[$i]['ans']==NULL)$datan[$i]['ans']=0;
        ?>
        <div class="newsPost">
        <?
      
            if(isset($_SESSION['user']['id'])&&$_SESSION['user']['id']==1){
                echo'<div class="removeQ" que="', $datan[$i]['id'],'" cod="',$cod,'">X</div>';
            }
        ?>
            <table cellpadding="0" class="visitor" style="margin: 0 10px 0 0;" align="left">
		<tr>
		 <td>
		  <img alt="<? echo $datan[$i]['name'];?>" src="<? echo $datan[$i]['avatar50']; ?>"/>
		 </td>
                </tr>
                <tr>
                    <td style="font-size: 0.7em;">
		 	<? if($datan[$i]['first_name'] == NULL)echo $datan[$i]['name'];
				else echo $datan[$i]['first_name'],'<br>',$datan[$i]['last_name']; ?>
		 </td>
                
		</tr>
		
	</table>
	<?
	echo'<h2><a href="http://',$_SERVER['HTTP_HOST'],'/index.php?a=que&id=',$datan[$i]['id'],'">',$datan[$i]['header'],'</a></h2>';
        echo '<div class="dateNews">';
        echo'<span';if(!$datan[$i]['ans'])echo ' style="color:#ccc;" ';echo'>ответов: ',$datan[$i]['ans'],'</span> &nbsp;&nbsp;&nbsp; ';
        echo date ( 'j.m.Y', $datan[$i]['date'] ),'</div></div>';
}

include('scr/navigator.php');
$pageNav = new SimPageNav();
$cont = $bd->get_all("SELECT COUNT(`id`) AS `count` FROM `questions`");
echo $pageNav->getLinks( $cont[0]['count'], $del, $lim, 5, 'lim' );
?>
</div>
