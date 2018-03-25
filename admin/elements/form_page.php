<form action="<? echo "http://",$_SERVER['HTTP_HOST'],"/admin/index.php?a=post&",$_SERVER['QUERY_STRING'];?>" method="post" class="form_enter">
<h3>Редактировать страницу</h3>
<input type="hidden" name="id" value="<? if(isset($data['id']))echo $data['id'];?>"/> 
<table cellpadding="5" cellspacing="0"  align="center">
	<tr>
		<td><input name="title" class="text_pole" type="text" size="50" value="<? if(isset($data['title']))echo $data['title'];?>"/></td>
		<td>Title</td>
	</tr>
	<tr>
		<td><input name="header" class="text_pole" type="text" size="50" value="<? if(isset($data['header']))echo $data['header'];?>"/></td>
		<td>Заголовок</td>
	</tr>
	<tr>
		<td><textarea name="keywords"><? if(isset($data['keywords']))echo $data['keywords'];?></textarea></td><td>Keywords</td>
	</tr>
	<tr>
		<td><textarea name="description"><? if(isset($data['description']))echo $data['description'];?></textarea></td><td>Description</td>
	</tr>
	<tr>
		<td><select id="type" name="type">
			<option value="page" <? if(isset($data) &&$data['type']=='page')echo'selected';?>>Страница</option>
			<option value="razdel" <? if(isset($data) &&$data['type']=='razdel')echo'selected';?>>Раздел</option>
                        <option value="news" <? if(isset($data) &&$data['type']=='news')echo'selected';?>>Новость</option>
		</select></td>
		<script type="text/javascript">
			/*$('#type').change(function(){
				var val = $(this).val();
				$('.selinput').fadeOut('fast');
				$('#'+$(this).val()).fadeIn('fast');
			});*/
		</script>
		<td><label for="post">Опубликовать</label></td>
	</tr>
	<tr id="razdel" class="selinput" <? /*if((isset($data) && $data['type']=='page') || !isset($data))echo'style="display:none;"';*/?>">
		<td>Путь к скрипту <input name="path" class="text_pole" type="text" size="30" value="<? if(isset($data['path']))echo $data['path'];?>"/></td><td>a= <input name="name" class="text_pole" type="text" size="10" value="<? if(isset($data['name']))echo $data['name'];?>"/></td>
	</tr>
	<tr id="page" class="selinput" <? /*if(isset($data) && $data['type']=='razdel')echo'style="display:none;"';*/?>>
		<td colspan="2"><textarea style="width:500px; height:300px;" name="text"><? if(isset($data['text']))echo $data['text'];?></textarea></td>
	</tr>
	
	<tr>
		<td colspan="2"><textarea style="width:500px; height:300px;" name="seotext"><? if(isset($data['seotext']))echo $data['seotext'];?></textarea></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" value="Сохранить"/></td>
	</tr>
</table>
    </form>