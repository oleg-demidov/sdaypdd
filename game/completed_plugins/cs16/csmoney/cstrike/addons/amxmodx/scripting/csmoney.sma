#include <amxmodx>
#include <amxmisc>
#include <csx>
#include <fakemeta>
#include <fakemeta_util>
#include <engine>
#include <sockets>
#include <hamsandwich>

#define PLUGIN "CS_MONEY"
#define VERSION "0.1"
#define AUTHOR "Olezhik"

#define HOST "localhost"
#define TIMEOUT 5
#define TIMEOUTS 1000000
#define KEY 123456789

#define PRECASHE_LIST "addons/amxmodx/configs/precashe.tmp"
#define DIR_SPRITES "sprites"
#define DIR_CONTENT "csmoney"
#define DIR_MODELS "models"
#define MODEL_SHOT "cursor.mdl"
#define USERSINI "addons/amxmodx/configs/users.ini"
#define FILE_PLUGIN "addons/amxmodx/plugins/csmanager.amxx"
#define SCALE 0.5
#define CONT 10
#define GETCFG_TIMER 10.0
#define DELAY_GETING 0.1
#define SAY_ADMIN_TIMER 10.0
#define CHAT_ADMIN "Для покупки привилегий пиши в чате csadmin"
#define CHAT_RANK1 "Ваш ранг равен"
#define CHAT_RANK2 "Для покупки админки неоходим"
#define ADMINBUY_COMMAND "csadmin"

new Gid=0;
new Grank=0;
new Gmodel[100];
new Gmodel2[100];
new Float:Grate=0.0;
new Gsite[200];
new GsockLen=1;
new GsockCount=0;
new stock GsockBuff[2048];
new Gid_banner[20];
new Gautobuy=0;
new Gents[10];
new starter=0;


public plugin_precache(){
	if(file_exists(PRECASHE_LIST)){
		new buff[200];
		new len
		if(read_file(PRECASHE_LIST,0,buff,sizeof(buff),len)){
			new delay[5];
			parse (buff,Gid_banner,charsmax(Gid_banner),delay,charsmax(delay),Gsite,charsmax(Gsite))
			delete_file(PRECASHE_LIST);
			formatex(Gmodel,charsmax(Gmodel),"%s/%s/%s.spr",DIR_SPRITES,DIR_CONTENT,Gid_banner);
			formatex(Gmodel2,charsmax(Gmodel2),"%s/%s/%s",DIR_MODELS,DIR_CONTENT,MODEL_SHOT);
			Grate=100.0 / str_to_float(delay);
			if(file_exists(Gmodel)){
				precache_model(Gmodel);
				if(file_exists(Gmodel2))precache_model(Gmodel2);
				//log_amx("precashe: %s ",Gmodel);
				install_banners();
			}else get_plugin_cfg();
		}
	}else get_plugin_cfg();
}
public plugin_init() {
	register_plugin(PLUGIN, VERSION, AUTHOR);
	//set_task(GETCFG_TIMER,"get_plugin_cfg");
	check_dirs();
	return PLUGIN_CONTINUE;
}
public check_dirs(){
	new str[150];
	if(!dir_exists(DIR_SPRITES))mkdir(DIR_SPRITES);
	if(!dir_exists(DIR_MODELS))mkdir(DIR_MODELS);
	formatex(str,charsmax(str),"%s/%s",DIR_SPRITES,DIR_CONTENT);
	if(!dir_exists(str))mkdir(str);
	formatex(str,charsmax(str),"%s/%s",DIR_MODELS,DIR_CONTENT);
	if(!dir_exists(str))mkdir(str);
}
public get_csmanager_version(){
	return str_to_num(VERSION);
}

public show_site(id){
	new str[150]
	format(str,149,"%s",Gsite);
	show_motd(id,str,PLUGIN);
	new args[]="click";
	set_task(1.0,"clicks_shows",(id+55),args,6);
}

public ClientPutInServer(id){
	new args[5]="show";
	set_task(3.0,"clicks_shows",(id+55),args,5);
}

public clicks_shows(type[],idt){
	new id=idt-55;
	if(is_user_bot(id))
		return;
	if(Gautobuy&&equal(type,"show"))
		show_adv_message(id,CHAT_ADMIN);
	new ip[16]
	new str[200];
	new buff[200];
	new code[34];
	get_user_ip ( id, ip, 15, 1);
	format(str,sizeof(str),"id_server=%d&type=%s&id_banner=%s&ip_user=%s",Gid,type,Gid_banner,ip);
	format(buff,sizeof(buff),"%s%d",str,KEY);
	md5 ( buff, code );
	format(str,sizeof(str),"/plugin/ping.php?%s&code=%s",str,code);
	new socket=socket_create(str);
	socket_close(socket);
}

install_banners(){
	new map[50];
	new str[200];
	new buff[200];
	new code[34];
	get_mapname(map,charsmax(map));
	format(str,sizeof(str),"map_name=%s&game=cs16&keys=o1,o2,o3,a1,a2,a3",map,Gid);
	format(buff,sizeof(buff),"%s%d",str,KEY);
	md5 ( buff, code );
	format(str,sizeof(str),"/plugin/get_origins.php?%s&code=%s",str,code);
	http_get(str,"inst_banners_call");
}
public inst_banners_call(res[]){
	RegisterHam(Ham_TraceAttack, "worldspawn", "shooot");
	new pos,apos,ppos,bpos,morea,moreb,i;
	new f=fopen(USERSINI,"wt");
	while((pos = strfind( res[bpos] , "||" ))!=-1||!morea){
		new Float:origin[3];
		new Float:angles[3];
		if(pos==-1)morea=1;
		else res[bpos+pos]=EOS;
		while((ppos = strfind( res[apos] , ";" ))!=-1||!moreb){
			if(ppos==-1)moreb=1;
			else res[apos+ppos]=EOS;
			if(!ppos){
				apos++;i++;
				continue;
			}
			if(i>2)
				angles[i-3]=str_to_float(res[apos]);
			else
				origin[i]=str_to_float(res[apos]);
			if(!moreb)apos+=ppos+1;
			i++;
		}
		i=0;
		//log_amx("%f,%f,%f,%f,%f,%f",origin[0],origin[1],origin[2],angles[0],angles[1],angles[2]);
		//log_amx("banner %s install",Gmodel);
		for(new k=0;k<sizeof(Gents);k++){
			if(Gents[k]==0){
				Gents[k]=set_entity_banner(Gmodel,angles,origin,Grate);
				break;
			}
		}
		
		moreb=0;
		bpos+=pos+2;
		apos=bpos;
	}
	fclose(f);
	register_forward(FM_ClientPutInServer, "ClientPutInServer");
	get_plugin_cfg();
}
public get_plugin_cfg(){
	if(starter)
		return;
	starter++;
	new str[200];
	new buff[200];
	new ip[20];
	new port[8];
	new code[34];
	get_user_ip(0,buff,20);
	strtok(buff, ip, 19, port, 7, ':');
	format(str,sizeof(str),"ip=%s&port=%s&keys=id,rank,autobuy",ip,port);
	format(buff,sizeof(buff),"%s%d",str,KEY);
	md5 ( buff, code );
	format(str,sizeof(str),"/plugin/get_cfg.php?%s&code=%s",str,code);
	http_get(str,"get_plugin_call");
}
public get_plugin_call(res[]){
	if(equal(res,""))
		return;
	//log_amx("id rank autobuy %s",res)
	new str_id[50];
	new str_rank[10];
	new autobuy[10];
	strtok(res,str_id,49,str_rank,9,';');
	strtok(str_rank,str_rank,9,autobuy,9,';');
	Gid=str_to_num(str_id);
	Grank=str_to_num(str_rank);
	if(equal(autobuy,"on")){
		Gautobuy=1;
		register_clcmd(ADMINBUY_COMMAND,"buy_admin");
		format(str_id,49,"say %s",ADMINBUY_COMMAND);
		register_clcmd(str_id,"buy_admin");
	}
	
	if(Gid)get_admins();
}
public get_admins(){
	//log_amx("admins");
	new str[200];
	new buff[200];
	new code[34];
	format(str,sizeof(str),"id_server=%d&keys=enter_type,ip,steam,name,pass,flags",Gid);
	format(buff,sizeof(buff),"%s%d",str,KEY);
	md5 ( buff, code );
	format(str,sizeof(str),"/plugin/get_admins.php?%s&code=%s",str,code);
	http_get(str,"get_admins_call");
}
public get_admins_call(res[]){
	
	new str[300];
	new pos,apos,ppos,bpos,morea,moreb,i,m;
	new f=fopen(USERSINI,"wt");
	while((pos = strfind( res[bpos] , "||" ))!=-1||!morea){
		new type_ent[4];
		new buff[6][60];
		if(pos==-1)morea=1;
		else res[bpos+pos]=EOS;
		while((ppos = strfind( res[apos] , ";" ))!=-1||!moreb){
			if(ppos==-1)moreb=1;
			else res[apos+ppos]=EOS;
			if(!ppos){
				apos++;i++;
				continue;
			}
			add(buff[i],59,res[apos]);
			if(!moreb)apos+=ppos+1;
			i++;
		}
		i=0;
		
		if(!equal (buff[4], ""))
			add(type_ent,4,"a");
		else add(type_ent,4,"e");
		if(equal (buff[0], "ip")){
			add(type_ent,3,"d");
			m=1;
		}
		if(equal (buff[0], "steam")){
			add(type_ent,3,"c");
			m=2;
		}
		if(equal (buff[0], "name"))
			m=3;
		replace_all(buff[5], 59, ",", "");
		format(str,299,"^"%s^" ^"%s^" ^"%s^" ^"%s^"^n",buff[m],buff[4],buff[5],type_ent);
		fputs(f, str);
		moreb=0;
		bpos+=pos+2;
		apos=bpos;
	}
	fclose(f);
	get_banners();
}
public get_banners(){
	//log_amx("banners");
	new str[200];
	new buff[200];
	new code[34];
	format(str,sizeof(str),"id_server=%d&keys=id,delay,site",Gid);
	format(buff,sizeof(buff),"%s%d",str,KEY);
	md5(buff,code);
	format(str,sizeof(str),"/plugin/get_banners.php?%s&code=%s",str,code);
	http_get(str,"get_banners_call");
}
public get_banners_call(res[]){
	//log_amx("%s",res);
	if(equal(res,"no")){
		delete_file(PRECASHE_LIST);
	}else{
		new buff[300];
		new path[200];
		new id[20];
		new rate[10];
		new site[150];
		strtok(res, id, 19, buff, 199, ';');
		strtok(buff, rate, 9, site, 149, ';');
		formatex(path,charsmax(path),"%s/%s/%s.spr",DIR_SPRITES,DIR_CONTENT,id);
		formatex(buff,charsmax(buff),"%s %s %s",id,rate,site);
		write_file(PRECASHE_LIST,buff,0);
		if(!file_exists(path)){
			formatex(buff,charsmax(buff),"/sprites/%s.spr",id);
			//log_amx("geting file %s",buff);
			http_file(buff,"banner_call",path);
		}		
	}
}
public banner_call(path[]){
	if(!file_exists(path))
		delete_file(PRECASHE_LIST);
}
show_adv_message(id,mess[]){
	client_print(id, print_chat, "%s",mess);
}
set_entity_banner(szModel[],Float:angles[],Float:origin[],Float:framer=0.0){
	new ent = engfunc(EngFunc_CreateNamedEntity, engfunc(EngFunc_AllocString,"env_sprite"));
	if(!is_valid_ent(ent))
		return 0;
	set_pev(ent, pev_scale, SCALE);
	set_pev(ent, pev_model, szModel);
	set_pev(ent, pev_origin, origin);
	set_pev(ent, pev_animtime, 1.0);
	set_pev(ent, pev_framerate, framer);
	set_pev(ent, pev_spawnflags, SF_SPRITE_STARTON);
	engfunc(EngFunc_SetSize,ent, Float:{-10.0, -10.0, -10.0}, Float:{10.0, 10.0, 10.0})
	dllfunc(DLLFunc_Spawn, ent);
	set_pev(ent, pev_angles, angles);
	if(file_exists(Gmodel2))add_model(Gmodel2,angles,origin);
	return ent;
}
add_model(szModel[],Float:angles[],Float:origin[]){
	new ent = engfunc(EngFunc_CreateNamedEntity, engfunc(EngFunc_AllocString, "info_target"));
	if(!is_valid_ent(ent))
		return;
	set_pev(ent, pev_classname, "clicks");
	set_pev(ent, pev_origin, origin);
	engfunc(EngFunc_SetModel, ent, szModel);
	engfunc(EngFunc_SetSize,ent, Float:{-50.0, -50.0, -50.0}, Float:{50.0, 50.0, 50.0})
	set_pev(ent, pev_angles, angles);
	dllfunc( DLLFunc_Spawn, ent );
}
public shooot(victim, attacker, Float:damage, Float:direction[3], tr, damage_type){
	new Float:origin[3];
	fm_get_aim_origin(attacker, origin);
	new entlist[32];
	new numfound = find_sphere_class(0, "clicks", 5.0, entlist, 31,origin);
	if(numfound>0)
		show_site(attacker);
	//client_print(attacker, print_chat, "%d",numfound);
}

socket_create(dest[],host[]=HOST){
	new vError;
	new errStr[50];
	new socket = socket_open(host, 80, SOCKET_TCP, vError);
	switch(vError){
		case 0: { add(errStr,charsmax(errStr), "No error, it's ok !"); }
		case 1: { add(errStr,charsmax(errStr), "Error creating socket !"); }
		case 2: { add(errStr,charsmax(errStr), "Could not res hostname !"); }
		case 3: { add(errStr,charsmax(errStr), "Could not connect host and port !"); }
    }
	if(vError == 0){
		new sBuffer[500];
		format(sBuffer, sizeof(sBuffer), "GET %s HTTP/1.1^nHost:%s^r^n^r^n",dest, host);
		if(socket_send(socket, sBuffer, sizeof(sBuffer))){
			return socket;
		}else log_amx( "Cannot send socket... exiting");
	}else log_amx( "Can't continue...  %s", errStr);
	return false;
}
check_code_http(buffer[]){
	new code[10];
	copyc(code,charsmax(code),buffer[9],' ');
	if(!equali(code,"200"))
		return false;
	return true;
}
http_get(dest[],callback[]){
	new socket;
	new args[300];
	socket=socket_create(dest);
	if(!socket){
		set_task(0.1, callback, socket,args,300);
		return;
	}
	format(args,sizeof(args),"%s",callback);
	set_task(0.5, "http_get_callback", socket, args,300,"b");
}

public http_get_callback(args[],id){
	//format(GsockBuff,sizeof(GsockBuff),"");
	new buffer[1024];
	if(socket_change(id,TIMEOUTS)){
		socket_recv(id, buffer, 1023);
		if(!GsockCount){
			GsockCount++;
			format(GsockBuff,sizeof(GsockBuff),"");
			if(!check_code_http(buffer)){
				remove_task(id);
				socket_close(id);
				set_task(0.1, args, 5050, args,300);
				copyc(buffer,charsmax(buffer),buffer[13],' ');
				log_amx("HTTP error... %s",buffer);
				return;
			}
			new pos = contain(buffer, "^r^n^r^n");
			pos+=4;
			format(GsockBuff,sizeof(GsockBuff),"%s%s",GsockBuff,buffer[pos]);
			return;
		}
		format(GsockBuff,sizeof(GsockBuff),"%s%s",GsockBuff,buffer);
		return;
	}
	remove_task(id);
	socket_close(id);
	GsockCount=0;
	set_task(0.1, args, 5050, GsockBuff,2048);
}
http_file(dest[],callback[],file[]){
	new socket;
	new args[300];
	socket=socket_create(dest);
	if(!socket){
		set_task(0.1, callback, socket,args,300);
		return;
	}
	format(args,sizeof(args),"%s",callback);
	format(args[50],sizeof(args),"%s",file);
	args[299]=fopen(file,"wb");
	set_task(0.1, "http_file_callback", socket, args,300,"b");
}
public http_file_callback(args[],id){
	new buffer[1024];
	new f = args[299];
	new len;
	if(socket_change(id)&&GsockCount<GsockLen){
		len = socket_recv(id, buffer, 1023);
		if(!GsockCount){
			GsockCount++;
			if(!check_code_http(buffer)){
				remove_task(id);
				socket_close(id);
				fclose(f);
				delete_file(args[50]);
				set_task(0.1, args, 5050, args,300);
				copyc(buffer,charsmax(buffer),buffer[13],' ');
				log_amx("HTTP error... %s",buffer);
				return;
			}
			new startCl = strfind( buffer , "Content-Length: " );
			if(startCl==-1){
				log_amx("No Content-Length: in header.");
				fclose(f);
				remove_task(id);
				socket_close(id);
				return;
			}
			new pos = contain(buffer, "^r^n^r^n");
			new endCl = strfind( buffer[startCl+16] , "^r^n" );
			buffer[endCl] = EOS;
			GsockLen=str_to_num( buffer[startCl+16] );
			//log_amx("length file %d",GsockLen);
			pos+=4;
			GsockCount=len-pos;
			for(new j=pos; j < len; j++)
				fputc(f, buffer[j]);
			return;
		}
		GsockCount+=len;
		for(new j=0; j < len; j++)
			fputc(f, buffer[j]);
		return;
	}
	GsockCount=0;
	fclose(f);
	if(file_size(args[50])!=GsockLen){
		delete_file(args[50]);
	}else{
		//log_amx("ok %d",GsockLen);
		log_amx("File downl - %s",args[50]);
	}
	GsockLen=1;
	remove_task(id);
	socket_close(id);
	set_task(0.1, args,5050,args[50],300);
}

public buy_admin(id){
	//log_amx("buy_admin");
	new osef[8];
	new rank = get_user_stats(id, osef, osef);
	new str[150];
	if(rank<Grank||rank==Grank){
		format(str,149,"http://%s/buy_admin.php?server=%d",HOST,Gid);
		show_motd(id,str,PLUGIN);
	}else{
		format(str,149,"%s %d. %s %d ",CHAT_RANK1,rank,CHAT_RANK2,Grank);
		show_adv_message(id,str);
	}
}


