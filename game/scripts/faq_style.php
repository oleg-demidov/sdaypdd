<script type="text/javascript">
$(document).ready(function(){
 $('.spoiler_links').click(function(){
  $(this).parent().children('div.spoiler_body').toggle('normal');
  return false;
 });
});
</script>
<style type="text/css">
	.spoiler_head{
		margin:3px;
	}
 .spoiler_body {display:none;}
 .spoiler_links {
 	color:#61D2FE;
 cursor:pointer;
 font-weight:bolder;
 }
 .spoiler_links:hover{
 color:#FFFF00;
 }
</style>