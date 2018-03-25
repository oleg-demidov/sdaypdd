KangoAPI.onReady(function() {
    var vars;
    var counts = kango.storage.getItem('counts');
    if(vars = kango.storage.getItem('vars')){
        var scopeNames = [
            'Гость',
            'Участник',
            'Модератор',
            'Администратор'
        ];
        $('#avatar').attr('src', vars.photo_50);
        $('#username').html(vars.first_name+' '+vars.last_name);
        $('#scope').html(scopeNames[counts.scope]);
        $('#exitbut').click(function(){
            kango.dispatchMessage('exit');
            KangoAPI.closeWindow();
        });
        $('#accaunt').css('display','table');
        
    }else{
        var url = 'https://oauth.vk.com/authorize?client_id='+kango.getExtensionInfo().app_id+'&display=page&redirect_uri=https://oauth.vk.com/blank.html&scope=groups&response_type=token&v=5.44';
        //kango.console.log(url)
        $($('<table><tr><td><button id="enter" type="button">Войти</button></td></tr></table>').click(function(){
            kango.browser.tabs.create({url:url});
            KangoAPI.closeWindow();
        })).prependTo('body').click(function(){
            kango.dispatchMessage('listenEnter');
        });
    }
    
    var getCounts = function(start){
        var keys = kango.storage.getKeys();
        var counts = 0;
        keys.map(function(key){
            if(key.charAt(0) == start){
                counts++;
            }
        });
        return counts;
    };
    var opts = kango.storage.getItem('opts');
    if(!opts){ 
        opts = {glist:true,mylist:true,delet:false,ava:true};
    }
    var changeOpt = function(opt){
        if(opts[opt]) opts[opt] = false;
        else opts[opt] = true;
        kango.storage.setItem('opts', opts);
        kango.dispatchMessage('changeOpt', opts);
    };
    $('#mylist').prop('checked',function(){
        if(opts.mylist) return 'checked';
    }).change(function(){ changeOpt('mylist') })
        .parent().after(' <img src ="'+kango.io.getResourceUrl('res/ubutton.png')+'"/>')
        .after(' <span style="font-size:12px;margin:0 0 2px 0;">('+getCounts('u')+')</span>');
    $('#glist').prop('checked',function(){
        if(opts.glist) return 'checked';
    }).change(function(){ changeOpt('glist') })
        .parent().after(' <img src ="'+kango.io.getResourceUrl('res/button.png')+'"/>')
        .after(' <span style="font-size:12px;margin:0 0 2px 0;">('+kango.storage.getItem('countDB')+')</span>');
    $('#delet').prop('checked',function(){
        if(opts.delet) return 'checked';
    }).change(function(){ changeOpt('delet') })
    $('#ava').prop('checked',function(){
        if(opts.ava) return 'checked';
    }).change(function(){ changeOpt('ava') })
    
    $('#uclear').click(function(){
        if(window.confirm("Вы уверены, что хотите очистить свой список?"))
            kango.dispatchMessage('uclear');
            KangoAPI.closeWindow();
    });
    $('#gclear').click(function(){
        kango.dispatchMessage('updateDB');
        KangoAPI.closeWindow();
    });
    $('#clearAll').click(function(){
        if(window.confirm("Вы уверены, что хотите очистить все?"))
            kango.dispatchMessage('clearAll');
    });
    if(parseInt(counts.marker_c) && parseInt(counts.scope)>1) 
        $('#openglist').after(' (<span style="font-weight:bolder;">'+counts.marker_c+'</span>)')
    $('#openglist').click(function(){
        kango.ui.optionsPage.open();
        KangoAPI.closeWindow();
    });
    $('#popup-close').click(function(event) {
        KangoAPI.closeWindow()
    });
    $('#popup-option').click(function(event) {
        kango.ui.optionsPage.open()
    });

    $('#popup-resize').click(function(event) {
        KangoAPI.resizeWindow(600, 600);
        $('#popup-properies').attr('rows', 8);
    });

});
