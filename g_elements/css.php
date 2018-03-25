<? header("Content-type: text/css"); ?> 
<? //include('css/all.css');?> 
@media (min-width:1000px){<? include('css/pc.css');?>} 
@media (min-width:501px) and (max-width:999px){<? include('css/tablet.css');?>} 
@media (min-width:501px){<? include('css/nomobile.css');?>} 
<? include('css/all.css');?>
@media (max-width:999px){<? include('css/nopc.css');?>} 
@media (max-width:500px){<? include('css/mobile.css');?>}
<?
	if(isset($_GET['a'])){
		$csski = array(
			'obuchenie'=>'obuchenie',
			'pdd'=>'pdd',
			'login'=>'login', 
			'reg'=>'login',
			'buy'=>'buy', 
			'race'=>'race',
			'signs'=>'pdd',
			'razm'=>'pdd',
			'free_test'=>'test',
			'eczamen'=>'test',
			'test'=>'test',
                        'news'=>'news',
                        'page'=>'news',
                        'questions'=>'que',
                        'que'=>'que',
                        'add_que'=>'que'
		);
		foreach($csski as $a=>$css){
			if($_GET['a'] == $a){
				include('css/'.$css.'.css');
			}
		}
	}
?>
