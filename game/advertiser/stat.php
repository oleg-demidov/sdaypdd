<h3><? echo content('stat_campaigns',$content_adv);?></h3>
<?
include('../elements/adv_stat_table.php');
include('../elements/balans_table.php');
include('../elements/form_stat_adv.php');
include('../elements/gchart_adv.php');
include('../elements/adv_servers_tab.php');

?>
<?
	$quer="select (select city_name_ru from city_ where id=(select geo_city from servers where id=adv_stat.id_server)), count(*) from adv_stat where id_user=".$_SESSION['id']." and time>".(strtotime("first day of this month"))." group by (select geo_city from servers where id=adv_stat.id_server) order by count(*) limit 10";
	$piedata=$bd->sql_query($quer);
	$piedata=$bd->get_result($piedata,MYSQL_NUM);

?>
<div id="piechart" style="float:right;width:470px; margin:-15px 0 0 0;"></div>
<div style="clear:both; width:800px;"></div>
    <script type="text/javascript">
     /* google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);*/
      function drawChart2() {
	  var dgp=<? echo json_encode_gchart($piedata,0);?>;
        var data2 = google.visualization.arrayToDataTable(dgp);
	
        var options2 = {
		backgroundColor: '#666',
          title: 'Основная география'
        };
		
        var chart2 = new google.visualization.PieChart(document.getElementById('piechart'));
        chart2.draw(data2, options2);
      }
    </script>