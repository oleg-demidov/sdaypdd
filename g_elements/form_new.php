<form action="http://<? echo $hh; ?>/index.php?a=add_new" method="post" class="form_enter">
<input type="hidden" name="id" value="<? if(isset($data['id']))echo $data['id'];?>"/> 
    <script src="http://<? echo $hh?>/ckeditor/ckeditor.js"></script>
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
	<!--<tr>
		<td><textarea id="editor1" style="width:95%; height:150px;" name="seotext"><? if(isset($data['seotext']))echo $data['seotext'];?></textarea></td>
                <td>Коротко</td>
        </tr>-->
	<tr>
	<tr>
            <td colspan="2"><textarea id="editor2" style="width:100%; height:300px;" name="text"><? if(isset($data['text']))echo $data['text'];?></textarea></td>
        </tr>
	 <script>
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                //CKEDITOR.replace( 'editor1' );
                CKEDITOR.replace( 'editor2' );
                
         </script>
	<tr>
		<td colspan="2"><input type="submit" value="Сохранить"/></td>
	</tr>
</table>
    </form>