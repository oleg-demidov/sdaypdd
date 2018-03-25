VK.init({
  apiId: 4861527
});

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
    VK.Api.call('wall.post', { message:'Присоединяйся к гонкам по правилам sdaypdd.loc/index.php?a=race',
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

function postWallOk(){
    OKSDK.Widgets.post('http://sdaypdd.loc/index.php?a=obuchenie', 
        JSON.stringify({"media": [{ "type": "link",  "url": "http://sdaypdd.ru/index.php?a=obuchenie" }]})
    );
}
