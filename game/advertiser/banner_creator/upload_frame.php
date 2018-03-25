<link href="b_creator.css" rel="stylesheet" type="text/css" />
<form name="upload" action="uploadfile.php?b=<? echo $_GET['b']?>" method="post" enctype="multipart/form-data">
<div class="uploadbutton">
<input type="file" name="file" />
</div>
<input type="submit"  style="visibility:hidden;"/>
</form>
<script language="JavaScript" type="text/javascript">
document.forms.upload.file.onchange=function(){document.forms.upload.submit();}
</script>