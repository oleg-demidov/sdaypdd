var IA = {
    razdel: 0,
    url_scr: '/scr/get.php',
    url_file: '/scr/sqlfile.php',
    url_reply: 'https://vk.com/wall',
    catimgid:0,
    cats:[],
    kandidat:0,
    vars:0,
    limitImgSize: 50,
    scope:0,
    url:'',
    scopes:{
        grey:2,
        more:1,
        addmarker:1,
        delmarker:3,
        addcat:3,
        remove_black:2,
        basket:2,
        tobasket:2
    },
    scopeNames:[
        'Гость',
        'Участник',
        'Модератор',
        'Администратор'
    ],
    user:{},
    users:{  },
    groups:{},
    agents: 0,
    height: 200,
    counts:{black:0, grey:0},
    nowlist:'black',
    init: function(vars){
        IA.users["0"] = {
            first_name:'Аноним',
            last_name:'',
            photo_50:   kango.io.getResourceUrl('res/no_photo.png')
        };
        IA.vars = vars;
        IA.url_scr = kango.getExtensionInfo().urlapp + IA.url_scr;
        IA.url_file = kango.getExtensionInfo().urlapp + IA.url_file;
        IA.limitImgSize = kango.getExtensionInfo().catImglimit;
        //console.log(IA.vars);
        IA.updateCounts(function(){
            IA.getUserScope(IA.acceptScope);
        });
        $('#butt').click(function(){IA.addMarker()});
        $('#agblack').click(function(){ IA.getList('gblack'); });
        $('#ablack').click(function(){ IA.getList('black'); });
        $('#acats').click(function(){IA.showCats()});
    },
    acceptScope: function(){
        //console.log('accept scope ',IA.scopes[IA.scope]);
        if(IA.scope > 1){
            $('#agrey').show().click(function(){ IA.getList('grey'); });
            $('#abasket').show().click(function(){ IA.getList('basket'); });
            if(IA.scope > 2){
                $('#addcatbut').show().click(function(){ IA.addCat(); });
                $('#redcatbut').show().click(function(){ 
                    IA.addCat($('#cats select').val()); 
                });
                $('#remcatbut').show().click(function(){ 
                    IA.removeCat($('#cats select').val()); 
                });
            }
            IA.checkMore(function(){IA.getList('grey');});
        }else{
            $('#agrey').remove();
            $('#abasket').remove();
            IA.checkMore(function(){IA.getList('black');})
        }
        $('#spiski').show();
    },
    checkScopes: function(need){
        if(IA.scopes[need] > IA.scope){
            //console.log('нет доступа');
            return false;
        }
        return true;
    },
    getList: function(list){
        $('#results').html('');
        if(!IA.checkScopes(list)){
            return false;
        }    
        IA.agents = 0;
        IA.nowlist = list;
        $('#spiski a').removeAttr( 'style' );
        if(list == 'poisk')
            return true;
        $('#a'+list).css('color','#666');
        IA.getAgents(list,0,10);
    },
    showCats: function(id){
        IA.updateCats($('#cats select'), function(sel){
            var cat = $('#cats').css('display','block');
            cat.find('.haddform div').unbind('click').bind('click', function(){cat.css('display','none');});

            var change_sel = function(sel){
                IA.selChange(sel);
                IA.getJSON({a:'cat_desc', cat:sel.val() },function(data){
                    $('#comm_cat').html(data[0].comm);
                });
            }
            cat.find('select').unbind('change').bind('change', function(){
                change_sel($(this));
                
            });
            if(id === undefined){
                id = IA.cats[0].id
            }
            cat.find('select').val(id).trigger('change');
            //IA.updateCats($('#cats select'), change_sel);
        });
    },
    addCat: function(id){
        if(!IA.checkScopes('addcat')){
            return false;
        }
        //console.log('addcat', id)
        $('#cattext').val('');
        var upl = IA.uploadImgCat();
        var addcat = $('#addcat');
        var init = function(id){
            if(id !== undefined){
                IA.getJSON({a: 'cat_desc', cat: id}, function(data){
                    //kango.//console.log(data)
                    tinymce.activeEditor.setContent(data[0].comm);
                    IA.catimgid = data[0].id_file;
                    addcat.find('#catname').val(data[0].name);
                    addcat.find('#promptzone').css('background-image','url('+IA.url_file+'?id='+data[0].id_file+')');
                });
            }else{
                addcat.find('#catname').val('');
                $('#promptzone').css('background-image','');
            }
        }

        tinymce.init({
            selector: '#cattext',
            toolbar: "insertfile undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | anchor insertdatetime", 
            language: "ru_RU",
            plugins: "image paste textcolor code anchor autolink insertdatetime layer link textpattern",
            link_list: [
                {title: 'My page 1', value: 'http://www.rusnod.ru'}
            ],
            image_advtab: true,
            paste_as_text: true,
            insertdatetime_formats: ["%Y.%m.%d", "%H:%M"],
            setup: function(editor) {
                editor.on('init', function(e) {
                    init(id);
                });
            }

        });

        
        addcat.find('.haddform div').unbind('click').bind('click', function(){ 
            //CKEDITOR.instances.cattext.destroy();
            tinymce.activeEditor.remove();
            addcat.find('#promptzone').empty();
            addcat.css('display','none'); 
            upl.remove();
        });
        addcat.show();
        $('#cats').hide();
        
        addcat.find('#addcbut').unbind('click').bind('click', function(){ 
            var text = tinymce.activeEditor.getContent();
            tinymce.activeEditor.remove();
           // CKEDITOR.instances.cattext.destroy();
            addcat.find('#promptzone').empty();
            var name = addcat.find('#catname').val();
            var opt = {a:'addcat', name: name, comm: text, id_file: IA.catimgid}
            if(id !== undefined){opt.id = id;}
            IA.getJSON(opt, function(data){
                IA.updateCounts(function(){
                    IA.updateCats($('#cats select'),function(){
                        addcat.css('display','none');
                    });
                });
            });
        });
    },
    removeCat: function(id){
        if(!IA.checkScopes('addmarker')){
            return false;
        }
        var ncat; 
        IA.cats.map(function(cat){if(cat.id == id) ncat = cat;});
        if(!window.confirm('Вы действительно хотите\r\nудалить категорию '+ncat.name))
            return false;
        IA.getJSON( {a:'removecat',id:id}, function(data){
            //console.log(data);
        });
    },
    updateCounts:function(call){
        //console.log('upd Scope');
        IA.getJSON({a:'counts'},function(data){
            $('#agrey span').html(' ('+data[0].marker_c+')');
            $('#ablack span').html(' ('+data[0].agent_c+')');
            $('#agblack span').html(' ('+data[0].group_c+')');
            $('#abasket span').html(' ('+data[0].basket_c+')');
            IA.counts.black = data[0].agent_c;
            IA.counts.basket = data[0].basket_c;
            IA.counts.grey = data[0].marker_c;
            $('#acats span').html(' ('+data[0].cat_c+')');
            IA.scope = data[0].scope;
            kango.storage.setItem('scope',data[0].scope);
            if(data[0].scope > 1){
                kango.ui.browserButton.setBadgeValue(data[0].marker_c);
            }
            kango.storage.setItem('counts',data[0]);
            call();
        });
        
    },
    getUserScope: function(call){
        IA.api("users.get", { fields:"photo_50,domain" }, function(udata){
            IA.user = udata[0];
            call();
        });
    },
    createAgent: function(data){
        //console.log('agent',data)
        var agent = $(document.createElement('div'))
            .attr('class','agent')
            .append($('#shablon').html());
        if(data.agent < 0){
            var ia = IA.groups[(data.agent*-1)];
            ia.first_name = ia.name;
            ia.last_name = '';
            ia.domain = ia.screen_name;
            ia.domain = ia.screen_name;
            ia.id = ia.gid * -1;
        }else{
            var ia = IA.users[data.agent];
            ia.id = ia.uid;
        }
            agent.find('.avatar').css("background-image","url("+ia.photo_50+")");
        agent.find('.avatar img').attr("src",data.agent.photo_200);
        if(data.markers !== undefined){
            var markers = agent.find('.markers');
            //markers.append( IA.getUserA(IA.users[data.markers[0]]) );
            //if(data.markers.length>1)
                markers.append( "<span> "+ data.markers.length+'</span>');
        }
        agent.find('.user_name').append( IA.getUserA(ia) );
        if(data.admin){ 
            agent.find('.adm_name').append( IA.getUserA(IA.users[data.admin]) );
        }
        if(IA.scope > 1){
            var close = agent.find('.close');
            if(IA.nowlist == 'grey'){
                close.append($('<a title="Подтвердить" href="#">&#xe816;</a>').click(function(){IA.markerForm(ia, 2);}));
            }
            if(IA.nowlist == 'basket'){
                close.append($('<a title="Восстановить" href="#">&#xe816;</a>').click(function(){IA.markerForm(ia, 2);}));
                if(IA.scope > 2)close.append($('<a title="Удалить" href="#">&#xe810;</a>').click(function(){IA.remove(ia.id);}));
                
            }else{
                close.append($('<a title="В корзину" href="#">&#xe810;</a>').click(function(){IA.toBasket(ia.id);}));
            }
            
        }
        if(data.ftime !== undefined) agent.find('.adm_name').after( ' <span class="date"> - '+data.ftime+'</span>' );
        if(IA.nowlist != 'grey' && IA.nowlist != 'basket'){
            agent.find('.agent_cat a').click(function(){IA.showCats(data.catid)})
                .html(data.catname).before('<img class="minimg" src="'+IA.createImg(data.id_file)+'"/> ');
        }
        agent.find('.more a').bind('click',function(){IA.getMore(data.agent, agent);});
        $('#results').append(agent);
        IA.agents++;
    },
    createImg: function(key){
        return kango.storage.getItem('file'+key);
    },
    createAgents:function(data){
        for(i=0;data.length>i; i++){
            IA.createAgent(data[i]); 
        }
        if(IA.counts[IA.nowlist] > IA.agents) {
            if(IA.nowlist != 'poisk'){
                $('#results').append($('<a class="morea" href="#">еще</a>').click(function(){
                    $(this).remove();
                    IA.getAgents(IA.nowlist,IA.agents,10);
                }));
            }
        }
    },
    getMore: function(agent, obj){
        IA.getJSON({a:'desc',agent:agent},function(data){ IA.callMore(data,obj) });
    },
    callMore:function(data,obj){
        var ids = '';
        for(i=0; data.length>i; i++){
            ids += data[i].id_user;
            if(data.length>i+1)ids += ', ';
        }
        IA.api("users.get", {user_ids:ids, fields:"photo_50,photo_200,domain"}, function(udata){
            for(i=0; udata.length>i; i++){
                IA.users[udata[i].uid.toString(10)] = udata[i];
            }
            //data.users = udata;
            IA.createMarkers(data,obj);
        });
    },
    createMarkers: function(data,obj){
        var desc = obj.find('.desc');
        var marker = $($('#shablon3').html());
        desc.append(marker);
        for(i=0;data.length>i;i++ ){
            IA.createMarker(data[i], marker, data.length);
        }
        marker.css('display','table');
        desc.slideDown(function(){
            obj.find('.more a').addClass('morebut2').unbind('click').bind('click',function(){
                desc.slideToggle('slow',function(){
                    obj.find('.more a').toggleClass('morebut2');
                });
            });
        });
    },
    createMarker: function(data,mark, l){
        //console.log('marker',data)
        if(data.anonim == '1'){
            data.id_user = 0;
        }
        mark.find('#mShablon').remove();
        var marker;
        mark.append(marker = $('<tr>'+$('#mShablon').html()+'</tr>'));
        if(data.wall != 0){
            marker.find('.reply').append(' <a href="'+IA.url_reply+data.wall+'_'+data.post+'?reply='+data.reply+'" target="_blank">'+data.ftime+'</a>');
        }
        marker.find('.avatartd').append($('<img/>').attr("src", IA.users[data.id_user].photo_50).css('width','30px'));
        marker.find('.marker_cat a').click(function(){IA.showCats(data.cat)}).html(data.name);
        
        marker.find('.user_name').append( IA.getUserA(IA.users[data.id_user]) );
        if(data.comm){
            var comm;
            marker.after(comm = $('<tr style="display:none;"><td colspan="6">'+data.comm+'</td></tr>'));
            marker.find('.comment').append($('<a href="#">показать</a>').click(function(){
                comm.slideToggle();
                return false;
            }));
        }
        if(IA.checkScopes('delmarker')){
            marker.find('.delmarker a').css('visibility', 'visible').click(function(){

                if(l > 1){
                    IA.delMarker(data.id, marker);
                    l--;
                }else{
                    IA.remove(data.id_subj);
                }
                return false;})
        }
        
    },
    delMarker: function(id, el){
        //console.log('delMarker',id);
        IA.getJSON({a:'delmarker',id:id},function(data){
            el.css('background','red').fadeOut(500);
            IA.updateCounts(function(){});
        });
    },
    addMarker: function(url){
        var domain;
        if(url === undefined){
            var url = $('#pole').val();
            if(!url) return false;
            var arr = url.split('/');
            domain = arr[arr.length - 1];
        }
        //console.log(url)
        IA.api("users.get", {user_ids:domain, fields:"photo_50,photo_200,domain"},function(udata){
            IA.checkUserInList(udata[0]);
        },function(error){
            if(error.error_code != 113)
                return;
            IA.api("groups.getById", {group_id:domain, fields:"start_date,description"}, function(gdata){
                IA.checkUserInList(gdata[0]);
            });
        });
    },
    checkUserInList: function(user){
        if(user.uid !== undefined){
            user.id = user.uid;
        }else{
            user.id = user.gid*-1;
        }
        IA.getJSON({user:user.id, a:'user'},function(data){
            if(data.length){ 
                $('#results').html('');
                if(IA.getList('poisk'))
                    IA.addUserInfo(data, function(){IA.createAgents(data)});
            }else{
                IA.notif('Не найдено');
               //IA.markerForm(user, 1); 
            }
        });
    },
    checkMore:function(call){
        kango.browser.tabs.getCurrent(function(tab) {
            var url = tab.getUrl().split("#");
            var gets = $.querystring(url[1]);
            //kango.console.log(gets)
            if(gets.moreid !== undefined){
                IA.showAgentMore(gets.moreid);
                return;
            }
            if(gets.domain !== undefined){
                IA.addMarker(gets.domain);
                return;
            }
            call();
            
        });
    },
    showAgentMore: function(id){
        IA.getJSON({user:id, a:'user'},function(data){
            if(data.length){ 
                $('#results').html('');
                if(IA.getList('poisk'))
                    IA.addUserInfo(data, function(){
                        IA.createAgents(data);
                        $('#results').find('.more a').trigger('click');
                    });
            }
        });
    },
    markerForm: function(data, type ){
        if(!IA.checkScopes('addmarker')){
            return false;
        }
        //console.log('form add ',data);
        IA.updateCats($('#addform select'),function(sel){
            sel.unbind('change').bind('change',function(){
                IA.selChange(this);
            });
            IA.selChange(sel);
        });
        IA.kandidat = data.id;
        var form = $('#addform');
        form.find('.avatar')
                .css("background-image","")
                .css("background-image","url("+data.photo_50+")");
        if(data.type == 'page'){
            data.first_name = data.name;
            data.last_name = '';
            data.domain = data.screen_name;
            //data.id = data.id*-1;
        }
        form.find('.user_name').html('').append( IA.getUserA(data) );
        form.show(); 
        form.find('.haddform div').click(function(){form.hide();});
        var but = form.find('#addtolist');
        but.unbind('click');
        if(type == 1){
            but.bind('click',IA.addToList);
        }
        if(type == 2){
            but.bind('click',function(){ IA.addToBlackList(data.id); });
        }
    },
    addToBlackList: function(id){
        var comm = $('#commadd').val();
        var cat = $('#catselect').val();
        //console.log(cat);
        if(cat == 0) return false;
        if(id>0){ var name = IA.users[id.toString()].first_name+' '+IA.users[id.toString()].last_name;
        }else{var name = IA.groups[(id*-1).toString()].name;}
        IA.get({a:'toblack', agent:id, comm:comm, cat:cat},function(res){
            //console.log(res);
            IA.notif(name+' добавлен в черный список');
            $('#addform').css('display','none');
            IA.updateCounts(function(){ IA.getList('grey'); });
        });
    },
    removeFromBlack: function(id){
        if(!IA.checkScopes('remove_black')){
            return false;
        }
        IA.getJSON({a:"remove_black",id: id},function(data){
            IA.updateCounts(function(){
                IA.getList('black');
            });
        });
    },
    remove: function(id){
        if(!IA.checkScopes('remove_black')){
            return false;
        }
        if(!window.confirm('Вы действительно хотите удалить\r\n'+IA.getApiData(id).name + id))
            return false;
        IA.getJSON({a:"remove",id: id},function(data){
            IA.notif(IA.getApiData(id).name+' удален');
            IA.updateCounts(function(){
                IA.getList(IA.nowlist);
            });
        });
    },
    removeFromBasket: function(id){
        if(!IA.checkScopes('tobasket')){
            return false;
        }
        IA.get({a:"frombasket",id: id, admin: IA.user.id},function(data){
            IA.updateCounts(function(){
                IA.getList(IA.nowlist);
            });
        });
    },
    toBasket: function(id){
        if(!IA.checkScopes('tobasket')){
            return false;
        }
        if(id>0){ var name = IA.users[id.toString()].first_name+' '+IA.users[id.toString()].last_name;
        }else{var name = IA.groups[(id*-1).toString()].name;}
        if(!window.confirm('Вы действительно хотите переместить в корзину\r\n'+name))
            return false;
        IA.get({a:"tobasket",id: id, admin: IA.user.uid},function(data){
            IA.notif(name+' перемещен в корзину');
            IA.updateCounts(function(){
                IA.getList(IA.nowlist);
            });
        });
    },
    addToList: function(){
        var comm = $('#commadd').val();
        var cat = $('#catselect').val();
        //console.log(cat)
        if(cat == 0) return false;
        IA.getJSON({a:'addmarker', id_subj:IA.kandidat, comm:comm, cat:cat},function(data){
            //console.log(data);
            $('#addform').css('display','none');
            IA.updateCounts(function(){ IA.notif('Ваша отметка добавлена.');IA.getList('grey'); });
        });
    },
    notif: function(mess){
        $('#notif').html(mess).slideDown('slow');
        setTimeout(function(){$('#notif').html(mess).slideUp('slow');},3000);
    },
    updateCats: function(sel, call){
        IA.getJSON({a:'cats'},function(data){
            if(!data) return;
            IA.cats = data;
            sel.html('').css('padding-left',IA.limitImgSize+'px').css('height', IA.limitImgSize+'px');
            sel.find('option').css('padding-left',IA.limitImgSize+'px').css('height', IA.limitImgSize+'px');
            for(i=0; data.length>i; i++){
               sel.append($('<option value="'+data[i].id+'">'+data[i].name+'</option>')
                       .css('background-image','url('+IA.url_file+'?id='+data[i].id_file+')'));
            }
            if(call !== undefined)call(sel);
        });
    },
    selChange: function(sel){
        var val = $(sel).find('option:selected').css('background-image');
        //console.log(val)
        $(sel).css('background-image',val);
    },
    getUserA: function(data){
        return '<a target="_blank" href="https://vk.com/'+data.domain +'">'+data.first_name+' '+data.last_name+'</a>';
    },
    getAgents: function(sp, from, to){
        IA.getJSON({a:sp,from:from,to:to},IA.callData);
    },    
    callData: function(data){
        if(data.length && data[0].scope !== undefined)
            return false;
        //console.log('call ',data);
        if(!data.length)
            $('#results').append('<h1>Пусто</h1>');
        else{
            IA.addUserInfo(data, function(){IA.createAgents(data)});
        }
    },
    addUserInfo: function(data, call){
        var ids = '';
        var gids = '';
        for(i=0;data.length>i; i++){
            if(data[i].agent > 0){
                ids += data[i].agent + ',';
            }else{
                gids += data[i].agent + ',';
            }
            ids += data[i].admin+','+data[i].markers+ ',';
            data[i].markers = data[i].markers.split(',');
        }
        var users = ids.split(',').slice(0,-1);
        var groups = gids.split(',').slice(0,-1).map(function(g){return (g * -1).toString(10);});
        //console.log('users',users,' groups', groups);
        
        var apinedd = false;
        var checkNeeds = function(arr, obj){
            var needs = '';
            for(i=0; arr.length>i; i++){
                if(arr[i] != 'null' && obj[arr[i]] === undefined  ){
                    needs += arr[i] + ',';
                    apinedd = true;
                }
            }
            return needs == ''?false:needs;
        }
        
        var uneeds = checkNeeds(users, IA.users);
        var gneeds = checkNeeds(groups, IA.groups);
        //console.log('uneed ', uneeds);
        //console.log('gneed ', gneeds);
        
        var getGroupData = function(ids, call){
            IA.api("groups.getById", {group_ids:ids, fields:"start_date,description,photo_50"}, function(gdata){
                for(i=0; gdata.length>i; i++){
                   IA.groups[gdata[i].gid.toString(10)] = gdata[i];
                }
                //console.log(IA.groups);
                call();    
            });
        }
        if(uneeds){
            IA.api("users.get", {user_ids:uneeds, fields:"photo_50,domain"}, function(udata){
                for(i=0; udata.length>i; i++){
                   IA.users[udata[i].uid.toString(10)] = udata[i];
                }
                //console.log(IA.users);
                if(gneeds){
                    getGroupData(gneeds, call);
                }else{
                    call();
                }
            });
        }else{
            if(gneeds){
                getGroupData(gneeds, call);
            }else{
                call();
            }
        }
    },
    getApiData: function(id){
        //console.log(IA.users, IA.groups )
        if(id>0){
            var data = IA.users[id];
            data.name = data.first_name+' '+data.last_name;
            return data;
        }else{
            return IA.groups[id*-1];
        }
        
    },
    get_cat: function(id){
        var cat = $('#cats').css('display','block');
        cat.find('select').val(id);
    },
    getJSON:function(opt, call){
        opt.access_token = IA.vars.access_token;
        opt.ext = 1;
        opt.r = Math.random();
        IA.loading(true);
        //console.log(IA.url_scr,opt);
        IA.ajax(IA.url_scr, opt, "json", function(data){
            //console.log(data);
            IA.loading(false);
            call(data);
        });
        /*$.getJSON(IA.url_scr,opt,function(data){
            //console.log(data);
            IA.loading(false);
            call(data);
        });*/
    },
    ajax: function(url, opt, type, succ){
        $.ajax({
            dataType: type,
            url: url,
            data: opt,
            success: succ,
            complete: function(data){
                //console.log(data);
            },
            error: function(x, e, s){/*console.log(e, s)*/}
        });
    },
    uploadImgCat: function(){
        //console.log('upload Form');
        var url = IA.url_scr+'?a=upload&access_token='+IA.vars.access_token;
        var upl = $('<div id="upl"></div>').width(IA.limitImgSize).height(IA.limitImgSize).appendTo('#promptzone');
        $.ajaxUploadSettings.name = 'uploads';
	// Set promptzone
	upl.ajaxUploadPrompt({
		url : url,
		beforeSend : function () {
                        var types = {"image/png":1,"image/jpeg":1,"imsge/gif":1};
                        //console.log(this);
                        if(this.files[0].width != IA.limitImgSize || this.files[0].height != IA.limitImgSize){
                            $('#upl').html('Только\r\n'+IA.limitImgSize+'x'+IA.limitImgSize+'!');
                            $('#promptzone').css('background-image','');
                            return false;
                        }
                        if(types[this.files[0].type] === undefined){
                            $('#upl').html('Тип!\r\n'+this.files[0].type);
                            $('#promptzone').css('background-image','');
                            return false;
                        }
		},
		onprogress : function (e) {
			if (e.lengthComputable) {
				upl.html(e.loaded / e.total*100)
			}
		},
		error : function () {
		},
		success : function (data) {
                    data = $.parseJSON(data);
                    upl.html('').append('<img style="z-index:1000;" src="'+IA.url_file+'?id='+data[0].id+'"/>');
                    IA.catimgid = data[0].id;
                    $('#upl:before').css("content", '');
		}
	});
        return upl;
    },
    api: function(metod, opt, sucess, fail){
        //console.log('api ',metod,opt);
        IA.loading(true);
        var url = "https://api.vk.com/method/"+metod+'.json';
        opt.access_token = IA.vars.access_token;
        IA.ajax(url, opt, "json", function(data){
            if(data.error !== undefined || data.response.length == 0){
                if(fail !== undefined)
                    fail(data.error);
                //console.log('api error',data.error);
            }
            if(data.response !== undefined && data.response.length > 0){
                //console.log(data.response);
                sucess(data.response);
            }
            IA.loading(false);
        });
    },
    loading: function(sw){
        var start = function(){
            //$('.load').css('display','block');
            $('body').css("cursor",'progress');
        };
        var stop = function(){
            //$('.load').css('display','none');
            $('body').removeAttr("style");
        };
        if(sw){
            start();
            setTimeout(stop,5000);
        }else{
            stop();
        }
        
    },
    get:function(opt, call){
        opt.access_token = IA.vars.access_token;
        opt.ext = 1;
        $('.load').css('display','block');
        //console.log(IA.url_scr,opt)
        $.get(IA.url_scr,opt,function(data){
            //console.log(data);
            $('.load').css('display','none');
            call(data);
        });
    }
};

KangoAPI.onReady(function() {
    var vars;
    if(vars = kango.storage.getItem('vars')){
        IA.vars = vars;
        IA.init(IA.vars);
    }
});