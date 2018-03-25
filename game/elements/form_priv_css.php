<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
    	<table cellpadding="10" cellspacing="0" border="1" class="table_adv">
          <tr class="tr_top">
           <td colspan="2"><center>Настройка цен на отдельные привилегии</center></td>
          </tr>
          
       
         <?	if(is_array($buy[0]['flags']))$flags=$buy[0]['flags'];
		 	else $flags=unserialize($buy[0]['flags']);
		 ?>
               
         <tr>
         	<td rowspan="21">Флаги CS Sourse. Выберете для активации и введите цену за 1 день использования.</td>
            <td><input name="flag_a" type="checkbox" <? if($flags['flag_a'])echo'checked';?> /> a <input name="a" type="text" size="5" value="<? echo $flags['a'];?>" /> - Доступ к резервным слотам.</td>
         </tr>
         <tr >
         	<td><input name="flag_b" type="checkbox" <? if($flags['flag_b'])echo'checked';?> /> b <input name="b" type="text" size="5" value="<? echo $flags['b'];?>" /> - Базовый уровень доступа; обязателен для админов.</td>
		</tr>
          <tr >
         	<td><input name="flag_c" type="checkbox" <? if($flags['flag_c'])echo'checked';?> /> c <input name="c" type="text" size="5" value="<? echo $flags['c'];?>" /> - Кик игроков (выброс с сервера).</td>
		</tr>
          <tr >
         	<td><input name="flag_d" type="checkbox" <? if($flags['flag_d'])echo'checked';?> /> d <input name="d" type="text" size="5" value="<? echo $flags['d'];?>" /> - Бан игроков (запрещение доступа к серверу)</td>
		</tr>
          <tr >
         	<td><input name="flag_e" type="checkbox" <? if($flags['flag_e'])echo'checked';?> /> e <input name="e" type="text" size="5" value="<? echo $flags['e'];?>" /> - Снятие банов.</td>
		</tr>
          <tr >
         	<td><input name="flag_f" type="checkbox" <? if($flags['flag_f'])echo'checked';?> /> f <input name="f" type="text" size="5" value="<? echo $flags['f'];?>" /> - Убить или ранить игроков.</td>
		</tr>
          <tr >
         	<td><input name="flag_g" type="checkbox" <? if($flags['flag_g'])echo'checked';?> /> g <input name="g" type="text" size="5" value="<? echo $flags['g'];?>" /> - Смена карты</td>
		</tr>
          <tr >
         	<td><input name="flag_h" type="checkbox" <? if($flags['flag_h'])echo'checked';?> /> h <input name="h" type="text" size="5" value="<? echo $flags['h'];?>" /> - Изменение серверных переменных.</td>
		</tr>
          <tr >
         	<td><input name="flag_i" type="checkbox" <? if($flags['flag_i'])echo'checked';?> /> i <input name="i" type="text" size="5" value="<? echo $flags['i'];?>" /> - Выполнять произвольные конфиги на сервере.</td>
		</tr>
          <tr >
         	<td><input name="flag_j" type="checkbox" <? if($flags['flag_j'])echo'checked';?> /> j <input name="j" type="text" size="5" value="<? echo $flags['j'];?>" /> - Доп. действия с чатом.</td>
		</tr>
          <tr >
         	<td><input name="flag_k" type="checkbox" <? if($flags['flag_k'])echo'checked';?> /> k <input name="k" type="text" size="5" value="<? echo $flags['k'];?>" /> - Устраивать голосования и управлять ими.</td>
		</tr>
          <tr >
         	<td><input name="flag_l" type="checkbox" <? if($flags['flag_l'])echo'checked';?> /> l <input name="l" type="text" size="5" value="<? echo $flags['l'];?>" /> - Установка пароля на сервер.</td>
		</tr>
          <tr >
         	<td><input name="flag_m" type="checkbox" <? if($flags['flag_m'])echo'checked';?> /> m <input name="m" type="text" size="5" value="<? echo $flags['m'];?>" /> - Использование команд rcon.</td>
		</tr>
          <tr >
         	<td><input name="flag_n" type="checkbox" <? if($flags['flag_n'])echo'checked';?> /> n <input name="n" type="text" size="5" value="<? echo $flags['n'];?>" /> - Менять значение sv_cheats и выполнять другие читерские команды.</td>
		</tr>
        <tr >
        <td><input name="flag_o" type="checkbox" <? if($flags['flag_o'])echo'checked';?> /> o <input name="o" type="text" size="5" value="<? echo $flags['o'];?>" /> настраиваемый(для дополнительных плагинов)</td>
          </tr>
          <tr ><td><input name="flag_p" type="checkbox" <? if($flags['flag_p'])echo'checked';?>  /> p <input name="p" type="text" size="5" value="<? echo $flags['p'];?>" /> настраиваемый</td>
          </tr>
          <tr ><td><input name="flag_q" type="checkbox"  <? if($flags['flag_q'])echo'checked';?> /> q <input name="q" type="text" size="5" value="<? echo $flags['q'];?>" /> настраиваемый</td>
          </tr>
          <tr ><td><input name="flag_r" type="checkbox" <? if($flags['flag_r'])echo'checked';?> /> r <input name="r" type="text" size="5" value="<? echo $flags['r'];?>" /> настраиваемый</td>
          </tr>
          <tr ><td><input name="flag_s" type="checkbox" <? if($flags['flag_s'])echo'checked';?> /> s <input name="s" type="text" size="5" value="<? echo $flags['s'];?>" /> Выбор нестандартной модели(если загружена)</td>
          </tr>
          <tr ><td><input name="flag_t" type="checkbox" <? if($flags['flag_t'])echo'checked';?> /> t <input name="t" type="text" size="5" value="<? echo $flags['t'];?>" /> настраиваемый</td>
          </tr>
          <tr >
         	<td><input name="flag_z" type="checkbox" <? if($flags['flag_z'])echo'checked';?> /> z <input name="z" type="text" size="5" value="<? echo $flags['z'];?>" /> - Включает все флаги, перечисленные выше ( = полный доступ).</td>
		</tr>
                  <tr class="tr_grey">
          <td>Ограничить игроков по посещениям</td>
           <td><input name="limit_enter" type="text" size="5" value="<? echo $buy[0]['limit_enter'];?>" /> - колличесиво необходимых посещений сервера</td>
          </tr>
          <tr>
          <td>Включить автопродажу отдельных привилегий</td>
           <td ><input name="autobuy" type="checkbox" <? if($buy[0]['autobuy']=='on')echo'checked';?> /></td>
          </tr>
         <tr class="tr_grey">
           <td colspan="2"><center><input name="" type="submit" value="Сохранить"></center></td>
          </tr>

        </table>
    </form>