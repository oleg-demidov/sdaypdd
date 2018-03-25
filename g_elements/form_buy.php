<form action="<? echo "http://",$_SERVER['HTTP_HOST'],"/robokassa/buy.php";?>" method="get" class="form_buy">
<h3>Продлить обучение</h3>
<table cellpadding="10" cellspacing="0" align="center">
 <tr>
  <td style="position:relative; width:6.5em;">
  <input name="days" id="days" class="text_pole" type="text" size="20" value="0"/>
  <div id="butt"><input id="pluss"  name="" class="button" type="button" value="+"><input id="minuss" name="" class="button" type="button" value="&ndash;"></div>
  </td>
  <td>Количество дней</td>
 </tr>
 <tr>
  <td><div id="summ">0 рублей</div></td>
  <td>Цена</td>
 </tr>
 <tr>
  <td colspan="2"><div id="eco">Экономия: 0</div></td>
 </tr>
 <tr>
  <td colspan="2">
     <input name="" class="button" type="submit" value="Оплатить">
  </td>
 </tr>
</table>
<script type="text/javascript">
	$('body').ready(function(){
		var summ;
		$('#pluss').click(function(){
			$('#days').val(parseInt($('#days').val(),10)+1);
			$('#days').trigger( "change" );
		});
		$('#minuss').click(function(){
			if($('#days').val()>0)
				$('#days').val(parseInt($('#days').val(),10)-1)
			$('#days').trigger( "change" );
		});
		$('#days').mouseleave(function(){$('#days').trigger( "change" );});
		$('#days').change( function(){
			var price = <? echo get_set('price')?>;
			var days = $('#days').val();
			summ = Math.ceil((days * price)/100 * ( 100 - 2*Math.sqrt(days)));
			var eco = days * price - summ;
			$('#eco').empty(); $('#eco').append(' экономия: '+eco);
			$('#summ').empty(); $('#summ').append(summ + ' рублей');
		});
		$('.form_buy').submit(function(){
			if(!summ)return false;
		});
	});
</script>
</form>