<?
    if(isset($_POST['text'])){
        $data = $_POST;
	$data['date'] = time();
        $data['id_user'] = $_SESSION['user']['id'];
        $data['id_que'] = $_GET['id'];
	$bd->insert_on_update('answers', $data);
    }
    if(isset($_GET['id']))$id = $_GET['id'];
    $dataQuest = $bd->get_row('SELECT * FROM `questions` WHERE `id`=?',array($id));
    $dataAns = $bd->get_all('SELECT `answers`.`text`, `answers`.`id`, `answers`.`date` , `users`.`name` , `social`.`first_name` , `social`.`avatar50` FROM `answers` LEFT JOIN `users` ON `users`.`id` = `answers`.`id_user` LEFT JOIN `social` ON `social`.`id_user` = `answers`.`id_user` WHERE `answers`.`id_que` =?',array($id));
?>
<h1><? echo stripslashes($dataQuest['header'])?></h1>
<div id="text"><p><? echo stripslashes($dataQuest['text'])?></p><div></div><div class="dateNews"><span>ответов: <?echo sizeof($dataAns)?></span> &nbsp;&nbsp; <? echo date ( 'j.m.Y', $dataQuest['date'] )?></div></div>
<script>
    $( document ).ready(function() {
        $('.removeQ').click(function(){
            var cod = $(this).attr('cod');
            var id = $(this).attr('que');
            $(this).parent().remove();
            $.get('http://<? echo $hh;?>/scr/remove_ans.php?id='+id+'&cod='+cod);
        });
    });
</script>
    <? 
include('scr/remove_cod.php');
for($i=0; $i<sizeof($dataAns); $i++){
        if($dataAns[$i]['avatar50']=='')
            $dataAns[$i]['avatar50']='http://'.$_SERVER['HTTP_HOST'].'/images/def_ava50.jpg';
        if($dataAns[$i]['ans']==NULL)$dataAns[$i]['ans']=0;
        ?>
        <div class="answer">
        <?
      
            if(isset($_SESSION['user']['id'])&&$_SESSION['user']['id']==1){
                echo'<div class="removeQ" que="', $dataAns[$i]['id'],'" cod="',$cod,'">X</div>';
            }
        ?>
            <table cellpadding="0" class="visitor" style="margin: 0 10px 0 0;" align="left">
		<tr>
		 <td>
		  <img alt="<? echo $dataAns[$i]['name'];?>" src="<? echo $dataAns[$i]['avatar50']; ?>"/>
		 </td>
                </tr>
                <tr>
                    <td style="font-size: 0.7em; text-align: center;">
		 	<? if($dataAns[$i]['first_name'] == NULL)echo $dataAns[$i]['name'];
				else echo $dataAns[$i]['first_name'],'<br>',$dataAns[$i]['last_name']; ?>
		 </td>
                
		</tr>
		
	</table>
	<p><? echo $dataAns[$i]['text']?></p>
        <div class="dateNews"><? echo date ( 'j.m.Y', $dataAns[$i]['date'] )?></div></div>
        <?
}
if(isset($_SESSION['user']['id'])){?>
<form class="answer" action="http://<? echo $_SERVER['HTTP_HOST']?>/index.php?a=que&id=<? echo $id?>" method="post">
            <table cellpadding="0" class="visitor" style="margin: 0 10px 0 0;" align="left">
		<tr>
		 <td>
		  <img alt="<? echo $_SESSION['user']['name'];?>" src="<? echo $_SESSION['user']['avatar50']; ?>"/>
		 </td>
                </tr>
                <tr>
                <td style="font-size: 0.7em; text-align: center;">
		<? if($_SESSION['user']['first_name'] == NULL)echo $_SESSION['user']['name'];
                    else echo $_SESSION['user']['first_name'],'<br>',$_SESSION['user']['last_name']; ?>
		</td>
                </tr>		
	</table>
    <textarea class="ansTextarea" name="text" style="width: 480px;"></textarea>
    <input type="submit" value="Ответить" class="button" style="float: right;">
</form>
<?}
