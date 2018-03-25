var IA = {
    auth:false,
    db:{},
    uservars:{},
    init: function(){
        //kango.console.log('start 5kolonna');
        setTimeout(IA.updateDB,0);
        kango.addMessageListener('OptionsPage', function(event) {
            if(IA.auth)kango.ui.optionsPage.open($.param(event.data))
        });
        kango.addMessageListener('listenEnter', function(event) {
            IA.listenEnter();
        });
        kango.addMessageListener('updateDB', function(event) {
            IA.updateDB();
        });
        kango.addMessageListener('getFirstActiv', function(event) {
            //console.log('getFirstActiv', event.data);
            IA.getFirstActive(event.data, function(date){
                //console.log('FirstActivM', date);
                event.target.dispatchMessage('dateFirstActiv', date);
            });
        });
        kango.addMessageListener('getFromDb', function(event) {
           //console.log('bd', event.data);
            var agent = IA.db[event.data.key];
            if(agent == undefined){
                agent = null;
            }
            event.target.dispatchMessage('callbackDb', {
                value:agent, 
                call:event.data.call
            });
        });
        kango.addMessageListener('exit', function(event) {
            IA.exit();
        });
        kango.addMessageListener('uclear', function(event) {
            IA.clearItems('u');
        });
        kango.addMessageListener('gclear', function(event) {
            IA.db = {};
            kango.storage.setItem('countDB', 0);
        });
        kango.addMessageListener('clearAll', function(event) {
            kango.storage.clear();
            IA.defaultOpts();
            IA.get(url, {a: 'cats'}, IA.insertCatsToDB);
        });
        kango.addMessageListener('setAgent', function(event) {
            //console.log('setAgent',event.data);
            IA.setAgent(event.data);
        });
        kango.addMessageListener('changeOpt', function(event) {
            //kango.console.log('changeOpt backgr',event.data);
            kango.storage.setItem('opts', event.data);
            IA.message('changeOptC',event.data);
        });
        //console.log(kango.storage.getKeys());
        IA.checkAuth();
        IA.defaultOpts();
        IA.getAdsHTML();
    },
    getAdsHTML: function(){
        IA.get(kango.getExtensionInfo().urlapp + '/ads.html',{r:Math.random()},function(data){
            //console.log(data)
            kango.storage.setItem('adsHTML',data);
        },'text');
    },
    defaultOpts: function(){
        //kango.console.log('se def opts')
        var opts = kango.storage.getItem('opts');
        if(!opts){ 
            opts = {glist:true,mylist:true,delet:false,ava:true};
            kango.storage.setItem('opts', opts);
        }
    },
    clearItems: function(start){
        var keys = kango.storage.getKeys();
        keys.map(function(key){
            if(key.charAt(0) == start){
                kango.storage.removeItem(key);
            }
        });
    },
    message: function(name, mess){
        kango.browser.tabs.getAll(function(tabs) {
            for (var i = 0; i < tabs.length; i++) {
             tabs[i].dispatchMessage(name, mess);
            }
        });
    },
    checkAuth: function(){
        var vars = kango.storage.getItem('vars');
        if(!vars) return false;
        if(vars.access_token !== undefined){
            IA.uservars = vars;
            IA.api('users.get',{fields:'photo_50'},function(r){
                vars = $.extend(vars,r[0]);
                //kango.console.log(IA.uservars);
                kango.storage.setItem('vars', vars);
                IA.uservars = vars;
                IA.auth = true;
                IA.updateCounts();
            },function(){
                IA.exit();
            });
        }else{
            IA.exit();            
        }       
    },
    updateCounts: function(){
        IA.query({a:'counts'},function(data){
            //console.log('counts',data)
            if(parseInt(data[0].scope) > 1){
                kango.ui.browserButton.setBadgeValue(parseInt(data[0].marker_c));
            }
            kango.storage.setItem('counts',data[0]);
        });
    },
    setAgent: function(agent){
        //kango.console.log(agent)
        if(agent.mylist)
            kango.storage.setItem('u'+agent.id,agent); 
        if(IA.auth){
            agent.a = 'addmarker';
            if(agent.anonim) agent.anonim = 1;
            else agent.anonim = 0;
            IA.query(agent, function(data){
                IA.updateCounts();
                kango.ui.notifications.show('Сообщение', 'Ваша отметка добавлена. Спасибо', kango.io.getResourceUrl('res/icon128.png'), function() {
                    //kango.console.log('Notification add marker');
                });
            });  
        }
        
    },
    getFirstActive: function(id, call){
        IA.api('wall.get', {owner_id: id, offset:2000000, filter:'all', count:1},function(data1){
            //console.log(data1);
            if(data1[0]){
                IA.api('wall.get', {owner_id: id, offset:(data1[0]-1), filter:'all', count:1},function(data2){
                    //console.log(data2);
                    call(data2[1].date);
                });
            }
        });
    },
    exit: function(){
        kango.storage.removeItem('vars');
        IA.uservars = {};
        kango.ui.browserButton.setBadgeValue();
        IA.auth = false;
    },
    api: function(metod, opt, sucess, fail){
       //console.log('api ',metod,opt);
        var url = "https://api.vk.com/method/"+metod+'.json';
        opt.access_token = IA.uservars.access_token;
        opt.version = '5.45';
        IA.get(url, opt, function(data){
            if(data.error !== undefined || data.response.length == 0){
                if(fail !== undefined)
                    fail(data.error);
               //console.log('api error',data.error);
            }
            if(data.response !== undefined && data.response.length > 0){
               //console.log(data.response);
                sucess(data.response);
            }
        });
    },
    updateDB: function(){
        var url = kango.getExtensionInfo().urlapp + '/scr/get.php';
        kango.storage.setItem('countDB', 0);
        IA.get(url, {a: 'all', from: 0}, function(data){
            IA.get(url, {a: 'cats'}, IA.insertCatsToDB);
            IA.insertUsrsToDB(data);
        });
    },
    query: function(opt, call){
        var url = kango.getExtensionInfo().urlapp + '/scr/get.php';
        opt.ext = 1;
        opt.access_token = IA.uservars.access_token;
        IA.get(url, opt, call);
    },
    insertUsrsToDB: function(data){
        //kango.console.log(data.length)
        if(!data.length)
            return;
        var key, count = 0;
        
        for(i=0; i<data.length; i++){
            IA.db[data[i].id] = data[i];
            count++;
            //kango.storage.setItem('g'+data[i].id, data[i]);
        }
        kango.storage.setItem('countDB', count);
    },
    insertCatsToDB: function(data){
        if(!data.length) return;
        var arr = {};
        IA.addFiles(data);
        for(i=0; i<data.length; i++){
            arr[data[i].id] = data[i];
        }    
        kango.storage.setItem('cats', arr);
        //kango.console.log(kango.storage.getKeys());
    },
    addFiles: function(cats){
        var url = kango.getExtensionInfo().urlapp + '/scr/sqlfile64.php';
        cats.map(function(cat){
            IA.save2storage(url + '?id=' + cat.id_file, 'file' + cat.id_file);
        });
    },
    save2storage: function(src, key){
        //kango.console.log(key);
        IA.get(src, {},function(data){
           //console.log(data);
            kango.storage.setItem(key, data);
        }, 'text');
    },
    get: function(url,opt,call, type){
        //console.log(url, opt)
        if(type === undefined){var type = 'json';}
        $.ajax({
            dataType: type,
            url: url,
            data: opt,
            success: call,
            error: function(x, e, s){/*console.log(e, s)*/ },
            complete: function(d){console.log(d)}
        });
    },    
    listenEnter: function(){
        var handlerDoc = function(event){
            if('OAuth Blank' == event.target.getTitle()){
                var vars = $.querystring(event.target.getUrl().substring(32));
                if(vars.access_token !== undefined){
                    //kango.console.log(vars);
                    kango.storage.setItem('vars', vars);
                    kango.browser.removeEventListener(kango.browser.event.DOCUMENT_COMPLETE, handlerDoc);
                    event.target.close();
                    IA.checkAuth();
                    
                }
            }
        };
        kango.browser.addEventListener(kango.browser.event.DOCUMENT_COMPLETE, handlerDoc);
    }
};

IA.init();
