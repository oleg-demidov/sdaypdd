<div class="header_panel">Чат</div>
<div class="panel_block">
<div id="chatFrame">
<?
$datach = $bd->get_all("SELECT * FROM `chat` ORDER BY `time` DESC LIMIT 5");
foreach($datach as $post){
	echo'<div class="post_chat"><span class="name_chat">',$post['name'],'</span> <span class="time_chat">(',date('H:i',$post['time']),')</span> :<br> &nbsp;&nbsp;',$post['mess'],'</div>';
}
?>
</div>

<form style="margin-top:10px;"  id="chatForm"  method="get">
	<input type="text" id="nameval" name="name"  value="Имя"/>
	<textarea id="textar" name="mess">Сообщение (до 400 символов)</textarea>
   <input style="float:left;" type="button" class="button" value="Отправить" id="submit"/>
   <div style="float:left;margin: 10px;"><?php include('scr/captcha/form.php');  ?></div>
</form>

<script type="text/javascript">
var chatAct = $('#chatForm').attr('action');
var block = 0;
$('#textar, #nameval').bind('click', function(){
	$(this).val('').unbind('click');
	block++;
});
var interval;
function startChat(){
    interval= setInterval(function(){
            chatUpdate(chatAct + '?r=' + Math.random());
        }, 5000);
}
startChat();
$('#chatForm #submit').click(function(){
	if(block == 2 && checkCaptcha()){
            clearInterval(interval);
            chatUpdate(chatAct+'?name='+$('#chatForm #nameval').val()+'&mess='+$('#chatForm #textar').val());
            startChat();
        }
});

function chatUpdate(get){
	$.get( get, function( data ) {
	  $( "#chatFrame" ).empty().html( data );
	});
}


</script>
</div>
