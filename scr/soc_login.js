/*var userData;
VK.init({
  apiId: 4861527
});
mailru.loader.require('api', function() {
    mailru.app.init('732185','c1e7fedbc6be0bdc6bbc4d89d20394a0');
    //console.log('ready MM');
    
});

$('body').ready(function(){
    $('#vk_btn').attr('onclick','loginVk();return false;');//click(loginVk);
    $('#ok_btn').attr('onclick','loginOk();return false;');
    $('#mm_btn').attr('onclick','loginMM();return false;');
    if(window.location.hash != ''){
        //console.log('hash',window.location.hash)
        loginOk();
    }
    checkPublishOk();
});*/
$('body').ready(function(){
    $('#vk_btn').attr('onclick','add_data(this);return false;');
    $('#ok_btn').attr('onclick','add_data(this);return false;');
    $('#mm_btn').attr('onclick','add_data(this);return false;');
});

function add_data(el){
    var host = window.location.host;
    var pars = {
        url: window.location.protocol+'//'+window.location.host,
        image: window.location.protocol+'//'+window.location.host+'/images/logo128.jpg',
        text: $('meta[name="description"]').attr('content'),
        header: window.document.title
    };
    
    pars = JSON.stringify(pars);
    pars = encodeURIComponent(pars);
    
    var url = $(el).attr('href')+'?pars='+pars+'&back='+window.location.href;
    window.open(url , "", "height=600, width=800, top=200, left=300, scrollbars=0");
    $(window).focus(function() {
        window.location.href = 'http://'+host+'/oauth/'+$(el).attr('id').substring(0,2)+'/wall_post.php?back='+window.location.href; 
    });    
}


/*function loginMM(){
    mailru.events.listen(mailru.connect.events.login, function(session) {
        //console.log(session); // показывает привилегии залогиненного пользователя
        checkWall(session.oid, 'mm', function(res){
            if(res.post == 'no'){
                //console.log(post.post, 'need post');
                postWallMM(function(postResult){
                    enterAuth('mm',postResult);
                });
                return;
            }
            enterAuth('mm','yes');
        })
    });
    mailru.connect.login(['stream']);
    return false;
}
function postWallMM(call){
    mailru.events.listen(mailru.common.events.streamPublish, function(event) {
        //console.log(event.status);
        if(event.status == 'publishSuccess'){
            call('yes');
            return;
        }
        call('no')
    });
    //console.log('MM')
    mailru.common.stream.post({'title':'Подготовка к экзамену в ГИБДД', 'text': 'Удобное приложение для тренировки по тестам ПДД РФ. Сдай экзамен без ошибок.','action_links': [{'text': 'SDAYPDD.RU', 'href': 'http://sdaypdd.ru'}] });
}

function loginVk(){
    VK.Auth.login(function(r){
        if(r.session===undefined){
            alert('Ошибка входа');
            return;
        }
        //console.log('VK', r);
        userData = r.session.user;
        checkWall(r.session.user.id,'vk',function(post){
            //console.log(post.post);
            if(post.post == 'no'){
                //console.log(post.post, 'need post');
                postWallVk(function(postResult){
                    enterAuth('vk',postResult);
                });
                return;
            }
            enterAuth('vk','yes');
        });
    },8192);
    return false;
}

function postWallVk(call){
    //console.log('post wall');
    VK.Api.call('wall.post', { message:'Подготовка к экзамену в ГИБДД sdaypdd.ru',
            attachments:'photo40551223_456239028'}, function(r) {
        ////console.log(r);
        if(r.response === undefined){
            //console.log('no wall');
            call('no');
            return;
        }
        call('yes');
    });
}

function checkWall(uid, soc, callback){
    var url = 'http://'+window.location.host+'/api/get_user_wall.php?uid='+uid+'&soc='+soc;
    //console.log(url);
    $.getJSON(url, function(r){
        //console.log('wall', r);
        callback(r);
    });
}

function enterAuth(soc, post){
    //console.log('post',post);
    window.document.location.href = 'http://'+window.document.location.host+'/oauth/'+soc+'/auth.php?post='+post+'&back='+window.document.location.href;
 }
 
function loginOk(){
    OKSDK.init({
            app_id: 1132797440,
            app_key: 'CBAPBGIEEBABABABA'//,
            //oauth :{scope:'LONG_ACCESS_TOKEN,VALUABLE_ACCESS'}
        }, function () {
            //console.log('OK');
            OKSDK.REST.call("users.getCurrentUser", {}, function (status, data, error) {
                //console.log(status, data, error)
                if(status != 'ok' || error != null){
                    alert('Ошибка авторизации. Попробуйте другую соцсеть');
                    return;
                }
                checkWall(data.uid,'ok',function(post){
                    //console.log(post.post);
                    if(post.post == 'no'){
                        //console.log(post.post, 'need post');
                        postWallOk();
                    }else
                        enterAuth('ok','yes');
                });
            });
        }, function (error) {
           alert('Ошибка авторизации. Попробуйте другую соцсеть')
    });
    return false;
}
function parseQuery(){
    var map = {};

    if ("" != window.location.search) {
      var groups = window.location.search.substr(1).split("&"), i;

      for (i in groups) {
        i = groups[i].split("=");
        map[decodeURIComponent(i[0])] = decodeURIComponent(i[1]);
      }
    }

    return map;
}
function checkPublishOk(){
    var map = parseQuery();
    if(map.result === undefined)
        return;
    var res = $.parseJSON(map.result);
    //console.log(res);
    if(res.type == 'error'){
        enterAuth('ok', 'no');
        return;
    }
    enterAuth('ok', 'yes');
}
function postWallOk(){
    OKSDK.Widgets.post('http://sdaypdd.loc/index.php?a=obuchenie', 
        JSON.stringify({"media": [{ "type": "link",  "url": "http://sdaypdd.ru/index.php?a=obuchenie" }]})
    );
}*/