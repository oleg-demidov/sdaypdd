#include <sourcemod>
#include <sdktools>
#include <sdkhooks>
#include <socket>
#include <dbi>

#define HOST "localhost"
#define MOTDPHP "/plugin/motd.php?href="
#define KEY 123456789

#define ADMINS_PATH "addons/sourcemod/configs/admins_simple.ini"
#define DIR_SPR "sprites"
#define DIR_BANNERS "csmoney"
#define PRECASHE_FILE "addons/sourcemod/configs/adv_precashe.cfg"
#define TITLE_MOTD "Administrator rights to buy"
#define MODEL_CURSOR "cursor"

#define VERSION 1


new Gid_banner;
new String:Grate_banner[5];
new String:Gsite_banner[200];
new Gadv_used;
new Gid;
new Grank;
new GidClient;
new String:Gsprite[150];
new Gents[10];
new Float:Gorigins[6][3];
new Float:Gangles[6][3];
new Gi=0;

new GsocketData[10];

enum{
	s_host,
	s_url,
	s_cfile,
	s_call,
	s_file,
	s_rec,
	s_path,
	s_csize,
	s_fr,
	s_error
}

public Plugin:myinfo = 
{
    name = "Cs manager",
    author = "Olezhik",
    description = "Ad",
    version = "VERSION",
    url = "cs-manager.ru"
}


public OnMapStart(){
	if(precache_models())
		CreateTimer(5.0,get_origins_http);
	else LogMessage("No precache");
}
public OnPluginStart(){
	new Handle:ch=FindConVar("sv_hibernate_when_empty");
	if(ch!=INVALID_HANDLE)
		SetConVarInt(ch, 0);
	RegConsoleCmd("adminka", buy_priveleges, "Try to buy priveleges.");
	RegConsoleCmd("say adminka", buy_priveleges, "Try to buy priveleges.");
	AddCommandListener(Command_Say, "say");
	check_dir();
	get_plugin_cfg();
}
 
public check_dir(){
	if(!DirExists("materials"))CreateDirectory("materials",511);
	new String:dir[150];
	FormatEx(dir,149,"materials/%s",DIR_SPR);
	if(!DirExists(dir))CreateDirectory(dir,511);
	FormatEx(dir,149,"%s/%s",dir,DIR_BANNERS);
	if(!DirExists(dir))CreateDirectory(dir,511);
		
}

 
public Action:Command_Say(client, const String:command[], argc){
	new String:text[192];
	if (GetCmdArgString(text, sizeof(text)) < 1)
		return;
	strcopy(text, 8, text[1]);
	if(StrEqual(text,"adminka"))
		buy_priveleges(client, argc);
}
public Action:buy_priveleges(client, args){
	check_rank(client);
}
public check_rank(client){
	GidClient=client;
	SQL_TConnect(sql_resume,"storage-local", check_rank_sql);
}
public sql_resume(Handle:owner, Handle:hndl, const String:error[], any:callback){
	if (sql_result_check(hndl,error)){
		new String:Gquery[250];
		new String:name[250];
		GetClientName(GidClient, name, 250);
		FormatEx(Gquery,250,"SELECT `rank` FROM `players` WHERE `name`='%s'",name);
		SQL_TQuery(hndl, callback, Gquery);
	}
}
public check_rank_sql(Handle:owner, Handle:query, const String:error[], any:data){
	if (!sql_result_check(query,error))
		return;
	SQL_FetchRow(query);
	new rank=SQL_FetchInt(query, 0);
	if(rank>Grank){
		PrintToChat(GidClient,"Your rank is more than the allowed (%d)",Grank);
		return;
	}
	new String:str[128];
	Format(str,sizeof(str),"http://%s/buy_admin.php?server=%d",HOST,Gid);
	DoUrlVisible(GidClient, str);
} 
sql_result_check(Handle:hndl, const String:error[]){
	if (hndl == INVALID_HANDLE){
		LogError("Database failure: %s", error);
		return 0;
	}else return 1;
}

public DoUrlVisible(client, String:url[]){
	new String:href[200];
	Format(href,sizeof(href),"http://%s%s%s",HOST,MOTDPHP,url);
	ShowMOTDPanel(client, "Title",href, MOTDPANEL_TYPE_URL);
}
public get_csmanager_version(){
	return VERSION;
}
public OnClientPutInServer(client){
	if(Gid_banner)
		clicks_shows(client,"show");
}
clicks_shows(client,String:type[]){
	if(IsFakeClient(client))
		return;
	new String:ip[16]
	new String:str[200];
	new String:buff[200];
	new String:code[34];
	GetClientIP(client, ip, sizeof(ip));
	Format(str,sizeof(str),"id_server=%d&ip_user=%s&type=%s&id_banner=%d",Gid,ip,type,Gid_banner);
	Format(buff,sizeof(buff),"%s%d",str,KEY);
	MD5String ( buff, code ,33);
	Format(str,sizeof(str),"plugin/ping.php?%s&code=%s",str,code);
	http(str,"show_banner");
}
public Action:show_banner(Handle:t,Handle:pack){
	ResetPack(pack);
	new String:res[200];
	ReadPackString(pack, res, sizeof(res));
	LogMessage(res);
}
public get_plugin_cfg(){
	LogMessage("get_cfg");
	new String:str[200];
	new String:buff[200];
	new String:ip[20];
	new String:code[34];
	new pieces[4];
	new longip = GetConVarInt(FindConVar("hostip"));
	new port = GetConVarInt(FindConVar("hostport"));
	pieces[0] = (longip >> 24) & 0x000000FF;
	pieces[1] = (longip >> 16) & 0x000000FF;
	pieces[2] = (longip >> 8) & 0x000000FF;
	pieces[3] = longip & 0x000000FF;
	FormatEx(ip, sizeof(ip), "%d.%d.%d.%d", pieces[0], pieces[1], pieces[2], pieces[3]);
	FormatEx(str,sizeof(str),"ip=%s&port=%d&keys=id,rank,adv_used",ip,port);
	Format(buff,sizeof(buff),"%s%d",str,KEY);
	MD5String ( buff, code ,33);
	Format(str,sizeof(str),"plugin/get_cfg.php?%s&code=%s",str,code);
	LogMessage(str);
	http(str,"get_plugin_call");
}
public Action:get_plugin_call(Handle:t,Handle:pack){
	ResetPack(pack);
	new String:res[2048];
	ReadPackString(pack, res, sizeof(res));
	if(StrEqual(res,"")){
		LogMessage("Your css server is not in the database.");
		return;
	}
	new String:buffers[3][50];
	ExplodeString(res, ";", buffers, 3,49);
	Gid=StringToInt(buffers[0]);
	Grank=StringToInt(buffers[1]);
	if(StrEqual(buffers[2],"on"))
		Gadv_used=1;
	//LogMessage("id:%d rank:%d",Gid,Grank)
	get_admins();
}
public get_admins(){
	LogMessage("admins");
	new String:str[200];
	new String:buff[200];
	new String:code[34];
	FormatEx(str,sizeof(str),"id_server=%d&keys=enter_type,ip,steam,name,pass,flags",Gid);
	FormatEx(buff,sizeof(buff),"%s%d",str,KEY);
	MD5String ( buff, code ,33);
	Format(str,sizeof(str),"/plugin/get_admins.php?%s&code=%s",str,code);
	LogMessage(str);
	http(str,"get_admins_call");
}
public Action:get_admins_call(Handle:t,Handle:pack){
	ResetPack(pack);
	new String:res[2048];
	ReadPackString(pack, res, sizeof(res));
	LogMessage("admns: %s",res);
	if(StrEqual(res,"")){
		LogMessage("Admins for this server is not in the database.");
		if(Gadv_used)get_banner();
		return;
	}
	new Handle:f=OpenFile(ADMINS_PATH,"wt");
	new String:admins[50][200];
	new String:buffers[6][50];
	new String:add[120];
	new num_admins=ExplodeString(res, "||", admins, 50,199);
	if(num_admins){
		new n=0;
		for(new i=0;i<num_admins;i++){
			ExplodeString(admins[i], ";", buffers, 6,49);
			if(StrEqual(buffers[0],"ip"))n=1;
			if(StrEqual(buffers[0],"steam"))n=2;
			if(StrEqual(buffers[0],"name"))n=3;
			if(n==1)FormatEx(buffers[n],50,"!%s",buffers[n]);
			FormatEx(add,120,"\"%s\"",buffers[n]);
			ReplaceString(buffers[5], 49, ",","");
			FormatEx(add,120,"%s \"%s\"",add,buffers[5]);
			if(!StrEqual(buffers[4],""))
				FormatEx(add,120,"%s \"%s\"",add,buffers[4]);//pass
			WriteFileLine(f,"%s",add);
		}
	}
	CloseHandle(f);
	if(Gadv_used)get_banner();
}
public get_banner(){
	new String:str[200];
	new String:buff[200];
	new String:code[34];
	Format(str,sizeof(str),"id_server=%d&keys=id,delay,site",Gid);
	Format(buff,sizeof(buff),"%s%d",str,KEY);
	MD5String ( buff, code ,33);
	Format(str,sizeof(str),"plugin/get_banners.php?%s&code=%s",str,code);
	http(str,"get_banner_call");
}
public Action:get_banner_call(Handle:t,Handle:pack){
	ResetPack(pack);
	new String:res[500];
	ReadPackString(pack, res, sizeof(res));
	if(StrEqual(res,"no")){
		LogMessage("No banners.");
		return;
	}
	new String:banner[3][100];
	new String:path[200];
	new String:url[200];
	ExplodeString(res, ";", banner, 3,99);
	
	new Handle:f=OpenFile(PRECASHE_FILE,"wt");
	WriteFileLine(f, "%s;%s;%s",banner[0],banner[1],banner[2]);
	CloseHandle(f);
	new String:dir[150];
	Format(dir,149,"materials/%s/%s",DIR_SPR,DIR_BANNERS);
	Format(path,sizeof(path),"%s/%s.vmt",dir,banner[0]);
	if(!FileExists(path)){
		new Handle:vmt=OpenFile(path,"wt");
		Format(res,sizeof(res),"\"Sprite\"\n{\n\"$basetexture\" %s/%s/%s\n\"$spriteorientation\" \"oriented\"\n\"$surfaceprop\" \"glass\"\n",DIR_SPR,DIR_BANNERS,banner[0]);
		Format(res,sizeof(res),"%s\"Proxies\"\n{\n\"AnimatedTexture\"\n{\n\"animatedTextureVar\" \"$basetexture\"\n\"animatedTextureFrameNumVar\" \"$frame\"\n\"animatedTextureFrameRate\" \"%s\"\n}\n}\n} ",res,banner[1]);
		WriteFileString(vmt, res,false);
		CloseHandle(vmt);
	}
	FormatEx(path,sizeof(path),"%s/%s.vtf",dir,banner[0]);
	if(!FileExists(path)){
		FormatEx(url,sizeof(url),"sprites/%s.vtf",banner[0]);
		LogMessage("download %s",url);
		http(url,"banner_check",path);
	}
}
public Action:banner_check(Handle:t,Handle:pack){
	ResetPack(pack);
	new String:res[200];
	new sf=ReadPackCell(pack);
	ReadPackString(pack, res, sizeof(res));
	LogMessage(res);
	if(FileSize(res)!=sf){
		DeleteFile(res);
		DeleteFile(PRECASHE_FILE);
		ReplaceString(res, sizeof(res), "vtf", "vmt");
		DeleteFile(res);
	}
}
public Action:get_origins_http(Handle:t){
	new String:str[200];
	new String:buff[200];
	new String:code[34];
	new String:mn[50];
	GetCurrentMap(mn, sizeof(mn));
	Format(str,sizeof(str),"map_name=%s&game=csgo&keys=o1,o2,o3,a1,a2,a3",mn);
	Format(buff,sizeof(buff),"%s%d",str,KEY);
	MD5String ( buff, code ,33);
	Format(str,sizeof(str),"plugin/get_origins.php?%s&code=%s",str,code);
	http(str,"get_origins_call");
}
public Action:get_origins_call(Handle:t,Handle:pack){
	ResetPack(pack);
	new String:res[2048];
	ReadPackString(pack, res, sizeof(res));
	if(StrEqual(res,"")){
		LogMessage("Origins for this map is not in the database.");
		return;
	}
	new String:data[6][50];
	new String:origins[6][50];
	//new Float:origin[3];
	//new Float:angles[3];
	ExplodeString(res, "||", data, 6,49);
	for(new i=0;!StrEqual(data[i],"")&&i<6;i++){
		ExplodeString(data[i], ";", origins, 6,49);
		Gorigins[i][0]=StringToFloat(origins[0]);
		Gorigins[i][1]=StringToFloat(origins[1]);
		Gorigins[i][2]=StringToFloat(origins[2]);
		Gangles[i][0]=StringToFloat(origins[3]);
		Gangles[i][1]=StringToFloat(origins[4]);
		Gangles[i][2]=StringToFloat(origins[5]);
		Gi++;
	}
	if(Gi>0){
		HookEvent("round_start", Event_RoundStart);
		install_banners();
	}
}
public Action:Event_RoundStart(Handle:event, const String:name[], bool:dontBroadcast)
{
install_banners();
return Plugin_Continue;
}
install_banners(){
	for(new i=0;i<Gi;i++){
		Gents[i]=create_sprite(Gorigins[i],Gangles[i],Grate_banner);
	}
}
precache_models(){
	if(!FileExists(PRECASHE_FILE))
		return false;
	new String:buff[200];
	new String:id_rate[3][150];
	new Handle:f=OpenFile(PRECASHE_FILE,"rt");
	ReadFileString(f, buff, sizeof(buff));
	ExplodeString(buff, ";", id_rate, 3, 149);
	CloseHandle(f);
	DeleteFile(PRECASHE_FILE);
	Gid_banner=StringToInt(id_rate[0]);
	Format(Grate_banner,sizeof(Grate_banner),"%s",id_rate[1]);
	Format(Gsite_banner,sizeof(Gsite_banner),"%s",id_rate[2]);
	LogMessage("%d;%s;%s",Gid_banner,Grate_banner,Gsite_banner);
	new String:dir[150];
	Format(dir,149,"materials/%s/%s",DIR_SPR,DIR_BANNERS);
	Format(buff,sizeof(buff),"%s/%s.vtf",dir,id_rate[0]);
	precache_model(buff);
	Format(Gsprite,sizeof(Gsprite),"%s/%s.vmt",dir,id_rate[0]);
	precache_model(Gsprite);
	Format(buff,sizeof(buff),"models/%s/%s.mdl",MODEL_CURSOR,MODEL_CURSOR);
	precache_model(buff);
	Format(buff,sizeof(buff),"models/%s/%s.dx80.vtx",MODEL_CURSOR,MODEL_CURSOR);
	precache_model(buff);
	Format(buff,sizeof(buff),"models/%s/%s.dx90.vtx",MODEL_CURSOR,MODEL_CURSOR);
	precache_model(buff);
	Format(buff,sizeof(buff),"models/%s/%s.vvd",MODEL_CURSOR,MODEL_CURSOR);
	precache_model(buff);
	Format(buff,sizeof(buff),"models/%s/%s.sw.vtx",MODEL_CURSOR,MODEL_CURSOR);
	precache_model(buff);
	Format(buff,sizeof(buff),"materials/models/%s/%s.vmt",MODEL_CURSOR,MODEL_CURSOR);
	precache_model(buff);
	Format(buff,sizeof(buff),"materials/models/%s/%s.vtf",MODEL_CURSOR,MODEL_CURSOR);
	precache_model(buff);
	return true;
}
precache_model(String:file[]){
	if(!FileExists(file))
		return false;
	PrecacheModel(file);
	if(!IsModelPrecached(file))
		return false;
	AddFileToDownloadsTable(file);
	LogMessage("Precache %s",file);
	return true;
}
create_sprite(Float:pos[3],Float:angle[3],String:rate[]){
	create_entity(pos,angle);
	//LogMessage("%f %f %f , %f %f %f",pos[0],pos[1],pos[2],angle[0],angle[1],angle[2])
	new sprite=CreateEntityByName("env_sprite_oriented");
	DispatchKeyValue(sprite, "classname", "env_sprite_oriented");
	DispatchKeyValueVector(sprite, "origin", pos);
	DispatchKeyValueVector(sprite, "angles", angle);
	DispatchKeyValue(sprite, "spawnflags", "1");
	DispatchKeyValue(sprite, "scale", "0.5");
	DispatchKeyValue(sprite, "rendermode", "0");
	DispatchKeyValue(sprite, "Radius", "256");
	DispatchKeyValue(sprite, "framerate", rate);
	//DispatchKeyValue(sprite, "rendercolor", "0 255 0");
	//DispatchKeyValue(sprite, "renderamt", "255");
	DispatchKeyValue(sprite, "model", Gsprite);
	DispatchSpawn(sprite);
	AcceptEntityInput(sprite, "ShowSprite");
	LogMessage("sprite %d",sprite);
	return sprite;
}
public create_entity(Float:pos[3],Float:angle[3]){
	new Float:angles[3];
	angles[0]=angle[0];
	angles[2]=angle[2];
	angles[1]=angle[1]+90.0;
	new ent=CreateEntityByName("prop_dynamic_override");
	DispatchKeyValue(ent, "classname", "cursor");
	new String:model[150];
	Format(model,sizeof(model),"models/%s/%s.mdl",MODEL_CURSOR,MODEL_CURSOR);
	DispatchKeyValue(ent, "model", model);
	DispatchKeyValueVector(ent, "origin", pos);
	DispatchKeyValueVector(ent, "angles", angles);
	SDKHook(ent, SDKHook_TraceAttack, _RH_TraceAttack);
	DispatchKeyValueFloat(ent, "solid", 2.0);
	DispatchSpawn(ent);
	return ent;
}
public Action:_RH_TraceAttack(victim, &attacker, &inflictor, &Float:damage, &damagetype, &ammotype, hitbox, hitgroup)
{
	clicks_shows(attacker,"click");
	DoUrlVisible(attacker, Gsite_banner);
	return Plugin_Continue;
} 
http(String:url[],String:callback[],String:path[]="",String:host[]=HOST) {
	new Handle:data=CreateArray(2048);
	GsocketData[s_path]=PushArrayString(data,path);
	GsocketData[s_url]=PushArrayString(data,url);
	GsocketData[s_host]=PushArrayString(data,host);
	GsocketData[s_call]=PushArrayString(data,callback);
	GsocketData[s_fr]=PushArrayCell(data,0);
	GsocketData[s_error]=PushArrayCell(data,0);
	if(StrEqual(path,"")){
		GsocketData[s_cfile]=PushArrayCell(data,0);
	}else{
		GsocketData[s_file]=PushArrayCell(data,OpenFile(path,"wb"));
		GsocketData[s_cfile]=PushArrayCell(data,1);
	}
	new Handle:socket = SocketCreate(SOCKET_TCP, OnSocketError);
	SocketSetArg(socket, data);
	SocketConnect(socket, on_socket_connect, on_socket_receive, on_socket_disconnected, host, 80);
}
public on_socket_connect(Handle:socket, any:data) {
	new String:requestStr[500];
	new String:url[250];
	new String:host[50]
	GetArrayString(data, GsocketData[s_url], url, sizeof(url));
	GetArrayString(data, GsocketData[s_host], host, sizeof(host));
	Format(requestStr, sizeof(requestStr), "GET /%s HTTP/1.0\r\nHost: %s\r\nConnection: close\r\n\r\n", url, host);
	LogMessage(requestStr);
	SocketSend(socket, requestStr);
}
public on_socket_receive(Handle:socket, String:receiveData[], const dataSize, any:data) {
	//PrintToServer("RECIVE %d",dataSize);
	//PrintToServer("DATA \r\n %s\r\n",receiveData);
	new er=StrContains(receiveData,"404");
	if(er<15&&er>5){
		GsocketData[s_error]=PushArrayCell(data,404);
		LogMessage("Server is not available");
		return;
	}	
	new cf=GetArrayCell(data, GsocketData[s_cfile]);
	new startCont=0;
	new fr=GetArrayCell(data, GsocketData[s_fr]);
	if(!fr){
		new String:contLen[50];
		new startcl=StrContains(receiveData,"Content-Length: ");
		sub_str(receiveData,contLen,sizeof(contLen),startcl+16,49);
		SplitString(contLen, "\r\n", contLen, sizeof(contLen));
		startCont=StrContains(receiveData,"\r\n\r\n")+4;
		GsocketData[s_csize]=PushArrayCell(data,StringToInt(contLen));
		//LogMessage("len:%s",contLen);
		SetArrayCell(data, GsocketData[s_fr],1);
	}
	if(cf){
		new Handle:f=GetArrayCell(data, GsocketData[s_file]);
		for(new i=startCont;i<dataSize;i++){
			WriteFileCell(f, receiveData[i], 1);
		}
	}else{
		new String:content[2048];
		sub_str(receiveData,content,sizeof(content),startCont,dataSize-startCont);
		GsocketData[s_rec]=PushArrayString(data,content);
	}
}
public on_socket_disconnected(Handle:socket, any:data) {
	new er=GetArrayCell(data, GsocketData[s_error]);
	if(er>0) return;
	new cf=GetArrayCell(data, GsocketData[s_cfile]);
	new String:callback[100];
	GetArrayString(data, GsocketData[s_call], callback, sizeof(callback));
	new Timer:func=GetFunctionByName(GetMyHandle(),callback);
	if(cf){
		new Handle:f=GetArrayCell(data, GsocketData[s_file]);
		CloseHandle(f);
		new String:path[250];
		GetArrayString(data, GsocketData[s_path], path, sizeof(path));
		new cs=GetArrayCell(data, GsocketData[s_csize]);
		new Handle:pack;
		CreateDataTimer(0.1, func, pack);
		WritePackCell(pack, cs);
		WritePackString(pack, path);
	}else{
		new String:content[2048];
		GetArrayString(data, GsocketData[s_rec], content, sizeof(content));
		new Handle:pack;
		CreateDataTimer(0.1, func, pack);
		WritePackString(pack, content);
	}
	ClearArray(data);
	CloseHandle(socket);
}
public OnSocketError(Handle:socket, const errorType, const errorNum, any:data) {
	LogError("socket error %d (errno %d)", errorType, errorNum);
	new Handle:f=GetArrayCell(data, GsocketData[s_file]);
	CloseHandle(f);
	ClearArray(data);
	CloseHandle(socket);
}

sub_str(String:str[],String:sub[],sublen,start,len){
	//LogMessage("str:%s sub:%s",str,sub);
	new i;
	for(i=0;i<len;i++){
		if(i==sublen) break;
		sub[i]=str[start+i];
	}
	sub[i+1]=0;
}
stock MD5String(const String:str[], String:output[], maxlen)
{
    decl x[2];
    decl buf[4];
    decl input[64];
    new i, ii;
    
    new len = strlen(str);
    
    // MD5Init
    x[0] = x[1] = 0;
    buf[0] = 0x67452301;
    buf[1] = 0xefcdab89;
    buf[2] = 0x98badcfe;
    buf[3] = 0x10325476;
    
    // MD5Update
    new in[16];

    in[14] = x[0];
    in[15] = x[1];
    
    new mdi = (x[0] >>> 3) & 0x3F;
    
    if ((x[0] + (len << 3)) < x[0])
    {
        x[1] += 1;
    }
    
    x[0] += len << 3;
    x[1] += len >>> 29;
    
    new c = 0;
    while (len--)
    {
        input[mdi] = str[c];
        mdi += 1;
        c += 1;
        
        if (mdi == 0x40)
        {
            for (i = 0, ii = 0; i < 16; ++i, ii += 4)
            {
                in[i] = (input[ii + 3] << 24) | (input[ii + 2] << 16) | (input[ii + 1] << 8) | input[ii];
            }
            // Transform
            MD5Transform(buf, in);
            
            mdi = 0;
        }
    }
    
    // MD5Final
    new padding[64] = {
        0x80, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
        0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
        0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
        0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
        0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
        0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
        0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
        0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00
    };
    new inx[16];
    inx[14] = x[0];
    inx[15] = x[1];
    
    mdi = (x[0] >>> 3) & 0x3F;
    
    len = (mdi < 56) ? (56 - mdi) : (120 - mdi);
    in[14] = x[0];
    in[15] = x[1];
    
    mdi = (x[0] >>> 3) & 0x3F;
    
    if ((x[0] + (len << 3)) < x[0])
    {
        x[1] += 1;
    }
    
    x[0] += len << 3;
    x[1] += len >>> 29;
    
    c = 0;
    while (len--)
    {
        input[mdi] = padding[c];
        mdi += 1;
        c += 1;
        
        if (mdi == 0x40)
        {
            for (i = 0, ii = 0; i < 16; ++i, ii += 4)
            {
                in[i] = (input[ii + 3] << 24) | (input[ii + 2] << 16) | (input[ii + 1] << 8) | input[ii];
            }
            // Transform
            MD5Transform(buf, in);
            
            mdi = 0;
        }
    }
    
    for (i = 0, ii = 0; i < 14; ++i, ii += 4)
    {
        inx[i] = (input[ii + 3] << 24) | (input[ii + 2] << 16) | (input[ii + 1] << 8) | input[ii];
    }
    MD5Transform(buf, inx);
    
    new digest[16];
    for (i = 0, ii = 0; i < 4; ++i, ii += 4)
    {
        digest[ii] = (buf[i]) & 0xFF;
        digest[ii + 1] = (buf[i] >>> 8) & 0xFF;
        digest[ii + 2] = (buf[i] >>> 16) & 0xFF;
        digest[ii + 3] = (buf[i] >>> 24) & 0xFF;
    }
    
    FormatEx(output, maxlen, "%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x",
        digest[0], digest[1], digest[2], digest[3], digest[4], digest[5], digest[6], digest[7],
        digest[8], digest[9], digest[10], digest[11], digest[12], digest[13], digest[14], digest[15]);
}

stock MD5Transform_FF(&a, &b, &c, &d, x, s, ac)
{
    a += (((b) & (c)) | ((~b) & (d))) + x + ac;
    a = (((a) << (s)) | ((a) >>> (32-(s))));
    a += b;
}

stock MD5Transform_GG(&a, &b, &c, &d, x, s, ac)
{
    a += (((b) & (d)) | ((c) & (~d))) + x + ac;
    a = (((a) << (s)) | ((a) >>> (32-(s))));
    a += b;
}

stock MD5Transform_HH(&a, &b, &c, &d, x, s, ac)
{
    a += ((b) ^ (c) ^ (d)) + x + ac;
    a = (((a) << (s)) | ((a) >>> (32-(s))));
    a += b;
}

stock MD5Transform_II(&a, &b, &c, &d, x, s, ac)
{
    a += ((c) ^ ((b) | (~d))) + x + ac;
    a = (((a) << (s)) | ((a) >>> (32-(s))));
    a += b;
}

stock MD5Transform(buf[], in[])
{
    new a = buf[0];
    new b = buf[1];
    new c = buf[2];
    new d = buf[3];
    
    MD5Transform_FF(a, b, c, d, in[0], 7, 0xd76aa478);
    MD5Transform_FF(d, a, b, c, in[1], 12, 0xe8c7b756);
    MD5Transform_FF(c, d, a, b, in[2], 17, 0x242070db);
    MD5Transform_FF(b, c, d, a, in[3], 22, 0xc1bdceee);
    MD5Transform_FF(a, b, c, d, in[4], 7, 0xf57c0faf);
    MD5Transform_FF(d, a, b, c, in[5], 12, 0x4787c62a);
    MD5Transform_FF(c, d, a, b, in[6], 17, 0xa8304613);
    MD5Transform_FF(b, c, d, a, in[7], 22, 0xfd469501);
    MD5Transform_FF(a, b, c, d, in[8], 7, 0x698098d8);
    MD5Transform_FF(d, a, b, c, in[9], 12, 0x8b44f7af);
    MD5Transform_FF(c, d, a, b, in[10], 17, 0xffff5bb1);
    MD5Transform_FF(b, c, d, a, in[11], 22, 0x895cd7be);
    MD5Transform_FF(a, b, c, d, in[12], 7, 0x6b901122);
    MD5Transform_FF(d, a, b, c, in[13], 12, 0xfd987193);
    MD5Transform_FF(c, d, a, b, in[14], 17, 0xa679438e);
    MD5Transform_FF(b, c, d, a, in[15], 22, 0x49b40821);
    
    MD5Transform_GG(a, b, c, d, in[1], 5, 0xf61e2562);
    MD5Transform_GG(d, a, b, c, in[6], 9, 0xc040b340);
    MD5Transform_GG(c, d, a, b, in[11], 14, 0x265e5a51);
    MD5Transform_GG(b, c, d, a, in[0], 20, 0xe9b6c7aa);
    MD5Transform_GG(a, b, c, d, in[5], 5, 0xd62f105d);
    MD5Transform_GG(d, a, b, c, in[10], 9, 0x02441453);
    MD5Transform_GG(c, d, a, b, in[15], 14, 0xd8a1e681);
    MD5Transform_GG(b, c, d, a, in[4], 20, 0xe7d3fbc8);
    MD5Transform_GG(a, b, c, d, in[9], 5, 0x21e1cde6);
    MD5Transform_GG(d, a, b, c, in[14], 9, 0xc33707d6);
    MD5Transform_GG(c, d, a, b, in[3], 14, 0xf4d50d87);
    MD5Transform_GG(b, c, d, a, in[8], 20, 0x455a14ed);
    MD5Transform_GG(a, b, c, d, in[13], 5, 0xa9e3e905);
    MD5Transform_GG(d, a, b, c, in[2], 9, 0xfcefa3f8);
    MD5Transform_GG(c, d, a, b, in[7], 14, 0x676f02d9);
    MD5Transform_GG(b, c, d, a, in[12], 20, 0x8d2a4c8a);
    
    MD5Transform_HH(a, b, c, d, in[5], 4, 0xfffa3942);
    MD5Transform_HH(d, a, b, c, in[8], 11, 0x8771f681);
    MD5Transform_HH(c, d, a, b, in[11], 16, 0x6d9d6122);
    MD5Transform_HH(b, c, d, a, in[14], 23, 0xfde5380c);
    MD5Transform_HH(a, b, c, d, in[1], 4, 0xa4beea44);
    MD5Transform_HH(d, a, b, c, in[4], 11, 0x4bdecfa9);
    MD5Transform_HH(c, d, a, b, in[7], 16, 0xf6bb4b60);
    MD5Transform_HH(b, c, d, a, in[10], 23, 0xbebfbc70);
    MD5Transform_HH(a, b, c, d, in[13], 4, 0x289b7ec6);
    MD5Transform_HH(d, a, b, c, in[0], 11, 0xeaa127fa);
    MD5Transform_HH(c, d, a, b, in[3], 16, 0xd4ef3085);
    MD5Transform_HH(b, c, d, a, in[6], 23, 0x04881d05);
    MD5Transform_HH(a, b, c, d, in[9], 4, 0xd9d4d039);
    MD5Transform_HH(d, a, b, c, in[12], 11, 0xe6db99e5);
    MD5Transform_HH(c, d, a, b, in[15], 16, 0x1fa27cf8);
    MD5Transform_HH(b, c, d, a, in[2], 23, 0xc4ac5665);

    MD5Transform_II(a, b, c, d, in[0], 6, 0xf4292244);
    MD5Transform_II(d, a, b, c, in[7], 10, 0x432aff97);
    MD5Transform_II(c, d, a, b, in[14], 15, 0xab9423a7);
    MD5Transform_II(b, c, d, a, in[5], 21, 0xfc93a039);
    MD5Transform_II(a, b, c, d, in[12], 6, 0x655b59c3);
    MD5Transform_II(d, a, b, c, in[3], 10, 0x8f0ccc92);
    MD5Transform_II(c, d, a, b, in[10], 15, 0xffeff47d);
    MD5Transform_II(b, c, d, a, in[1], 21, 0x85845dd1);
    MD5Transform_II(a, b, c, d, in[8], 6, 0x6fa87e4f);
    MD5Transform_II(d, a, b, c, in[15], 10, 0xfe2ce6e0);
    MD5Transform_II(c, d, a, b, in[6], 15, 0xa3014314);
    MD5Transform_II(b, c, d, a, in[13], 21, 0x4e0811a1);
    MD5Transform_II(a, b, c, d, in[4], 6, 0xf7537e82);
    MD5Transform_II(d, a, b, c, in[11], 10, 0xbd3af235);
    MD5Transform_II(c, d, a, b, in[2], 15, 0x2ad7d2bb);
    MD5Transform_II(b, c, d, a, in[9], 21, 0xeb86d391);
    
    buf[0] += a;
    buf[1] += b;
    buf[2] += c;
    buf[3] += d;
}  