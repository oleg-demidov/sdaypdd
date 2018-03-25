<div id="xy"></div>
<link href="<? echo $host;?>/advertiser/banner_creator/b_creator.css" rel="stylesheet" type="text/css" />
<form method="post" id="data" class="form_create_banner" action="<? echo $host;?>/advertiser/banner_creator/create_banner.php">
<div id="area_parent" class="area_banner"><div id="area_banner" class="area_banner"></div></div>
<div id="img_tools" class="img_tools"></div>
<div id="load_div" class="load_div"></div>
<div id="scroll_div"  class="scroll_div"><div style="width:1px;" id="buttons" class="buts_add_image">

</div></div>
</form>
<?
$_SESSION['bf']=0;
if(file_exists(__DIR__.'/tmp/'.$_SESSION['id'].'0')){
	$sd=scandir(__DIR__.'/tmp');
	$fc=count($sd);
	for($i=0;$i<$fc;$i++){
		if(strpos($sd[$i],$_SESSION['id'])>-1)
			unlink(__DIR__.'/tmp/'.$sd[$i]);
	}
}
?>
<script language="JavaScript" type="text/javascript">
$(document).ready(initialization);
//$('.content').css('width','1045px');
var fileCount=0;
var data={
	images:[],
	width:512,
	height:256,
	sizes:[16,32,64,128,256,512,1024]};
var ulrFolder='http://<? echo $_SERVER['HTTP_HOST']?>/advertiser/banner_creator';
function initialization(){
	add_button();
	add_tools();
}
function add_button(){
	var el=document.getElementById("buttons");
	el.style.width=(parseInt(el.style.width.split('p')[0])+162)+'px';
	var button=create_button(data.images.length);
	el.appendChild(button);
	fileCount++;
}
function create_select(id,maxVal){
	var sel=document.createElement('select');
	sel.id=id;
	sel.style.margin='14px';
	sel.style.cssFloat='left';
	for(var i=0;i<data.sizes.length&&maxVal>=data.sizes[i];i++){
		var opt=document.createElement('option');
		opt.value=data.sizes[i];
		opt.innerHTML=data.sizes[i];
		sel.appendChild(opt);
	}
	sel.onchange=change_select;
	return sel;
}
function change_select(){
	if(this.value>data[this.id])
		if(!confirm('Warning. Some image to small for group this animate.\n Continue?')){
			this.selectedIndex = get_key(data.sizes,data[this.id]);
			return false;
		}
	data[this.id]=this.value;
	var rect=document.getElementById('block_img');
	rect.style[this.id]=this.value+'px';
}
function add_tools(){
	var tools=document.getElementById('img_tools');
	var sel=create_select('width',data.width);
	tools.appendChild(sel);
	sel=create_select('height',data.height);
	tools.appendChild(sel);
	add_rect();
	add_origins(0,0);
	add_delay();
	submitB=add_submit();
	tools.appendChild(submitB);
	//document.getElementById("select").selectedIndex = 0;
}
function add_submit(){
	var but=document.createElement('input');
	but.type='submit';
	but.className='button';
	but.value='Создать';
	but.style.margin='8px 8px';
	but.onclick=submit_data;
	return but;
}
function create_data_input(name,value){
	var s=document.createElement('input');
	s.type='text';
	s.name=name;
	s.value=value;
	s.style.visibility='hidden';
	return s;
}
function submit_data(){
	var form=document.getElementById('data');
	var width=create_data_input('width',data.width);
	form.appendChild(width);
	var height=create_data_input('height',data.height);
	form.appendChild(height);
	var c=create_data_input('count',data.images.length);
	form.appendChild(c);
	if(data.images.length>1){
		var d=create_data_input('delay',data.delay);
		form.appendChild(d);
	}
	var size_img=data.images.length;
	var el,x,y;
	for(var i=0;i<size_img;i++){
		el=create_data_input('name'+i,data.images[i].name);
		x=create_data_input('x'+i,data.images[i].x);
		y=create_data_input('y'+i,data.images[i].y);
		form.appendChild(el);
		form.appendChild(x);
		form.appendChild(y);
	}
	form.submit();
}
function add_delay(){
	var d=document.createElement('div');
	d.style.margin='7px';
	d.style.width='150px';
	d.style.cssFloat='left';
	d.style.color='black';
	var but=document.createElement('input');
	but.type='button';
	but.value='Play';
	but.className='button';
	but.style.margin='0 5px';
	but.onclick=function(){start_anim(but)};
	var id=document.createElement('input');
	id.type='text';
	id.style.width='50px';
	id.value=100;
	data.delay=100;
	id.onchange=function(){data.delay=parseInt(id.value);}
	d.appendChild(id);
	d.appendChild(but);
	var p=document.getElementById('img_tools');
	p.appendChild(d);
}
function start_anim(but){
	if(data.images.length<2)
		return;
	if(data.slide===undefined)
		data.slide=0;
	if(data.animArea===undefined)
		data.animArea=create_anim_area();
	var area=document.getElementById("area_parent");
	area.style.position='relative';
	area.appendChild(data.animArea);
	but.value='Stop';
	but.onclick=function(){stop_anim(but)};
	data.timer=setInterval(show_slide,data.delay);
}
function stop_anim(b){
	clearInterval(data.timer);
	var area=document.getElementById("area_parent");
	area.removeChild(data.animArea);
	b.value='Play';
	b.onclick=function(){start_anim(b)};
}
function create_anim_area(){
	var animArea=document.createElement('div');
	animArea.style.width=data.width+'px';
	animArea.style.height=data.height+'px';
	animArea.style.position='absolute';
	animArea.style.top='0px';
	animArea.style.left='0px';
	return animArea;
}
function show_slide(timer){
	if(data.slide==data.images.length)
		data.slide=0;
	data.animArea.style.backgroundImage='none';
	data.animArea.style.backgroundPosition=(0-data.images[data.slide].x)+'px '+(0-data.images[data.slide].y)+'px';
	data.animArea.style.backgroundImage="url('"+data.images[data.slide].src+"')";
	data.slide++;
}
function add_origins(x,y){
	if(!data.origins){
		data.origins=document.createElement('div');
		data.origins.style.margin='15px';
		data.origins.style.width='100px';
		data.origins.style.cssFloat='left';
		data.origins.style.color='black';
		var p=document.getElementById('img_tools');
		p.appendChild(data.origins);
	}
	data.origins.innerHTML='x:'+x+' y:'+y;
}
function add_rect(){
	var area=document.getElementById("area_parent");
	var rect=document.createElement('div');
	//rect.style.border='1px solid black';
	rect.id='block_img';
	data.rect=rect;
	rect.onmousedown = dragDown;
	rect.style.position='absolute';
	rect.style.top='0px';
	rect.style.left='0px';
	rect_size(data.width,data.height);
	area.appendChild(rect);
}
function get_key(arr,val){
	var size=arr.length;
	for(var i=0;i<size;i++){
		if(arr[i]==val)
			return i;
	}
}
function rect_size(w,h){
	data.rect.style.width=w+'px';
	data.rect.style.height=h+'px';
	document.getElementById("width").selectedIndex = get_key(data.sizes,w);
	document.getElementById("height").selectedIndex = get_key(data.sizes,h);
}
function rect_pos(x,y){
	if(x<0)
		x=0;
	if(y<0)
		y=0;
	if((parseInt(data.rect.style.width)+x)>data.images[data.loaded].width)
		x=data.images[data.loaded].width-parseInt(data.rect.style.width);
	if((parseInt(data.rect.style.height)+y)>data.images[data.loaded].height)
		y=data.images[data.loaded].height-parseInt(data.rect.style.height);
	data.rect.style.left=x+'px';
	data.rect.style.top=y+'px';
	data.rect.style.backgroundPosition=(0-x)+'px '+(0-y)+'px';
	//var xy=document.getElementById('xy');
	//xy.innerHTML=x+" : "+y+"; "+(0-x)+" : "+(0-y);
	change_origins(x,y);
}
function low_img_click(el){
	area_img(data.images[el].src);
	data.loaded=el;
	rect_pos(data.images[el].x,data.images[el].y);
	add_origins(data.images[el].x,data.images[el].y);
}
function ok_upload(name,w,h){
	var src=ulrFolder+'/tmp/'+name;
	data.loaded=data.images.length;
	load_img(name,src,w,h);
	change_gsize(w,h);
	area_img(src);
	add_button();
}
function get_close_size(s){
	var size=data.sizes.length;
	for(var i=size-1;i>-1;i--){
		if(data.sizes[i]<=s)
			return data.sizes[i];
	}
}
function change_gsize(w,h){
	if(data.width>w)
		data.width=get_close_size(w);
	if(data.height>h)
		data.height=get_close_size(h);
	rect_size(data.width,data.height);
}
function change_origins(x,y){
	add_origins(x,y);
	data.images[data.loaded].x=x;
	data.images[data.loaded].y=y;
}
function loading_start(src){
	var el=create_img(src);
	var loadel=document.getElementById("load_div");
	var img=document.createElement('img');
	img.src=ulrFolder+'/loader.gif';
	loadel.appendChild(img);
	el.onload=function(){loadel.removeChild(img);}
}
function create_img(src){
	var img=document.createElement('img');
	img.className="img_hi";
	img.src=src;
	return img;
}
function load_img(name,src,w,h){
	var imgObj={name:name,src:src,x:0,y:0,width:w,height:h};
	data.images.push(imgObj);
	loading_start(src);
}
function area_img(img){
	var area=document.getElementById("area_banner");
	area.style.backgroundImage="url('"+img+"')";
	$("#area_banner").css({ opacity: .2 });
	data.rect.style.backgroundImage="url('"+img+"')";
}
function setOpacity(el,value){
   el.style.opacity = value/10;
   el.style.filter = 'alpha(opacity=' + value*10 + ')';
}
function select_img(id_frame){
	alert(id_frame);
	/*if(!('el' in data.images[parseInt(id_frame.substr(3))]))
		return;
	area_img(data.images[parseInt(id_frame.substr(3))].el);*/
}
function create_button(name){
	var button=document.createElement('iframe');
	button.className='iframebutton';
	button.scrolling='no';
	var url=ulrFolder+'/upload_frame.php?b='+name;
	button.src=url;
	button.id="btn"+name;
	return button;
}
function dragDown(e){
  e = (e ? e : event);
  var top  = (isNaN(parseInt(this.style.top))  ? 0 : this.style.top);
  var left = (isNaN(parseInt(this.style.left)) ? 0 : this.style.left);
  var y = Math.abs(parseInt(top) - e.clientY);
  var x = Math.abs(parseInt(left) - e.clientX);

  var oldCursor = this.style.cursor;
  this.style.cursor = "move";

  var oldMousemove = document.onmousemove;
  var oldMouseup   = document.onmouseup;
  document.onmousemove = dragMakeMoveFunc(this, y, x);
  document.onmouseup   = dragMakeStopFunc(this, oldMousemove, oldMouseup, oldCursor);
}

function dragMakeMoveFunc(elem, y, x){
  return function(e){
    e = (e ? e : event);
	var newx=e.clientX - x;
	var newy=e.clientY - y;
	rect_pos(newx,newy);
  }
}

function dragMakeStopFunc(elem, oldMousemove, oldMouseup, oldCursor){
  return function(){
    document.onmousemove  = oldMousemove;
    document.onmouseup    = oldMouseup;
    elem.style.cursor     = oldCursor;
  }
}
</script>
