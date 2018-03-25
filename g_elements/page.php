<h1><? echo stripslashes($CONT->data['header'])?></h1>
<? $CONT->data['text'] = str_replace('<hr />', '', $CONT->data['text']) ?>
<div id="text"><? echo stripslashes($CONT->data['text'])?><div></div><div class="dateNews"><!--<? echo date ( 'j.m.Y', $CONT->data['data'] )?>--></div></div>
<? if($CONT->data['type'] == 'news') include('g_elements/vk_comments.php')?>