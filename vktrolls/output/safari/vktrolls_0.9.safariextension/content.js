// ==UserScript==
// @name ChristmasTree
// @include http://vk.com
// @include https://vk.com
// @require scr/jquery.js
// @require kango-ui/kango_api.js
// @all-frames true
// ==/UserScript==


IA = {
    auth:false,
    pageType: 0,
    initCount:false,
    uids:[],
    shagAds: 40,
    cats:{},
    scope:0,
    cButs:0,
    opt:{},
    VK:{},
    url_reply: 'https://vk.com/wall',
    calls:[],
    dateFirstActiv:0,
    dateActiveEl:{},
    init:function(){
       //kango.console.log('init', window.dApi)
        IA.cats = kango.storage.getItem('cats');
        var counts = kango.storage.getItem('counts');
        if(counts)IA.scope = counts.scope;        
        IA.opt = kango.storage.getItem('opts');
        kango.addMessageListener('changeOptC', function(event) {
           //kango.console.log('changeOptC', event.data)
            kango.storage.setItem('opts', event.data);
            IA.opt = event.data;           
        });
        kango.addMessageListener('dateFirstActiv', function(event) {
            //console.log('dateFirstActivC', event.data);
            IA.dateFirstActiv = event.data;
            IA.dateActiveEl.append(IA.dateToString(event.data));           
        });
        kango.addMessageListener('callbackDb', function(event) {
           //kango.console.log(event)
            IA.calls[event.data.call](event.data.value);
            delete IA.calls[event.data.call];
        });
        if(kango.storage.getItem('vars')){
            IA.auth = true;
        }else{
            IA.auth = false;
        }
        IA.objParse(document.body);
        IA.bindMutation(document);
       //kango.console.log(IA.opt)
       //kango.console.log(kango.storage.getKeys())
    },
    nodeMod: function(nl){
        for(i=0;nl.length>i;i++){
            if(IA.checkElement(nl[i]))
                IA.objParse(nl[i]);
        }
    },
    objParse: function(obj){
        $(obj).find('.author').map(function(){
            var el = $(this);
            if(el.attr('mark')=='true') return;
            el.attr('mark','true');
            var id = IA.getId(el);
            console.log('id', id)
            //var id = el.attr('data-from-id');
            if(id === undefined) return;
            if(IA.cButs > IA.shagAds && id < 0){
                IA.addAds(el);
                IA.cButs = 0;
            }
            IA.cButs++; //console.log(IA.cButs);
            IA.getAgent(id, function(agent){
                if(!agent) { 
                    //console.log('addbut',this)
                    IA.addBut({id:id}, el);
                    return;
                }
                //1console.log('addagent', agent)
                IA.setAgent(agent, el);
            });
        });
    },
    getId:function(el){
        var id;
        if((id = el.attr('data-from-id')) !== undefined){
             return id;
        }
        if((id = el.attr('data-post-id')) !== undefined){
            return id.split('_')[0];
        }
        var c = 0;
        var post;
        el.parents("div[id^='post']").map(function(){ if(!c){post = $(this);c++} });
        var id = post.attr('id').split('_')[0].split('post')[1];
        return id;
    },
    getAgent: function(id, call){
        if(IA.opt.mylist){
            var agent = kango.storage.getItem('u'+id);
            if(agent){ 
                agent.u = 1;
                call(agent);
                return false;
            }
        }
        if(IA.opt.glist){
            IA.getFromDb(id, function(gagent){
                if(gagent){ 
                    gagent.u = 0;
                }
                call(gagent);
            });
        }
    },
    getFromDb: function(key, call){
        IA.calls[IA.calls.length] = call;
        kango.dispatchMessage('getFromDb', {key:key,call:IA.calls.length-1});
    },
    addBut: function(agent, el){
        el.after(
            $('<a href="#" class="marker"></a>').append(
                $(document.createElement('img')).attr({
                    src: kango.io.getResourceUrl('res/lapa.png'),
                    title: 'Отметить'
                }).css('opacity', '0.3')
                        .hover(function(){$(this).css('opacity', '1')},
                        function(){$(this).css('opacity', '0.3')})
            ).css('margin-left','5px').click(function(){
                IA.showFormMarker(this,agent,el);
                return false;
            })
        );
    },
    showFormMarker: function(but,agent,el){
       //kango.console.log('agent',agent);
        $('.popupdiv').remove();
        but = $(but);
        var p = but.offset();
        var sel,imgcat;
        var shablon ='<table></tr><tr><td id="dateactiv" colspan="4">Первая активность: <span></span></td></tr><tr><td rowspan="2" id="mImg"></td><td colspan="2" id="mSel"></td><td id="mClose"></td></tr><tr><td id="mAnonim"></td><td id="mMylist"></td><td id="mSumbit"></td></tr><tr><td id="acomment" colspan="4"></td></tr></table>';
        var pop = $('<div class="popupdiv" style="background:rgb(255, 238, 238);"></div>').appendTo('body').css({
            top:p.top-5,
            left:p.left+20,
            border: '1px solid lightsteelblue',
            padding: '5px',
            'box-shadow':         '0px 1px 5px 0px rgba(50, 50, 50, 0.5)',
            position: 'absolute',
            'z-index':'1000',
            display: 'none',
            background:'#fff'
        }).html(shablon);
        if(IA.auth){
            IA.getFirstActiv(agent.id, pop.find('#dateactiv span'));
        }else{
            pop.find('#dateactiv').parents('tr').first().remove();
        }
        pop.find('#mImg').append(imgcat = $('<img id="imgcat"/>'));
        pop.find('#mSel').append(sel = $('<select></select>'));
        pop.find('#mClose').append($('<button type="button">Закрыть</button>').click(function(){pop.remove()}));
        pop.find('#mAnonim').append($('<label><input style="vertical-align: middle;" id="anonim" type="checkbox"/>анонимно</label>'));
        pop.find('#mMylist').append($('<label><input style="vertical-align: middle;" id="tomy" type="checkbox"/>в мой список</label>'));
        pop.find('#acomment').append('<textarea id="comma" style="box-sizing: border-box;width:100%;"></textarea>');
        pop.find('#mSumbit').append($('<button type="button">Отметить</button>').click(function(){
                $(this).unbind();
                var datapost = el.parents('.post').attr('id');
                var post = 0 , wall = 0;
                if(datapost !== undefined){
                    wall = datapost.split('_')[0].split('post')[1];
                    post = datapost.split('_')[1];
                }
                var reply = '';
                if(el.parent().is('.reply_text')){
                reply = el.nextAll('div[id^="wpt"]')
                    if(reply.is('div[id^="wpt"]'))
                        reply = reply.attr('id').split('_')[1];
                }
               //kango.console.log(post, reply);
                var anonim = pop.find('#anonim').prop( "checked" );
                var mylist = pop.find('#tomy').prop( "checked" );
                var comm = pop.find('#comma').val();
                var opts = {anonim:anonim, mylist:mylist, id:agent.id, cat: sel.val(), post: post, wall:wall, reply: reply, comm:comm, first_active:IA.dateFirstActiv};
               //kango.console.log('marker',opts)
                kango.dispatchMessage('setAgent', opts);
                
                if(mylist){
                    agent.cat = sel.val();
                    agent.u = 1;
                    el.offsetParent().find('.marker').remove();
                    IA.setAgent(agent, el);
                }
                pop.remove();
            })
        );
        pop.fadeIn(300);
       //kango.console.log(IA.cats)
        Object.keys(IA.cats).map(function(cat){
            sel.append('<option value="'+IA.cats[cat].id+'">'+IA.cats[cat].name+'</option>');
        });
        function setImgcat(cat) {
            imgcat.attr('src',IA.createImg('file'+IA.cats[cat].id_file));
        }
        /*var t;
        pop.hover(function(){clearTimeout(t)},function(){
            t = setTimeout(function(){pop.remove()},3000);
        })*/
        setImgcat(sel.val());
        sel.change(function(){
            setImgcat(sel.val());
        });
        //kango.dispatchMessage('Marker', {domain:domain});
    },
    getFirstActiv: function(id, el){
        IA.dateActiveEl = el;
        kango.dispatchMessage('getFirstActiv', id);
    },
    dateToString: function(date){
        var d = new Date();
        d.setTime(date*1000);
        return d.getDate()+'.'+d.getMonth()+'.'+d.getFullYear();
    },
    setAgent: function(agent, el){
        //console.log(IA.cats);
        if(IA.opt.delet){
            IA.hideAgent(agent, el);
            return false;
        }
        if(IA.opt.ava){
            var ava = el.parent().parent().parent().offsetParent(); 
       //kango.console.log(agent, el, ava.find("a[href='"+el.attr('href')+"']"));
        //var i=0; 
            var obj = ava.find("a.post_image[href='"+el.attr('href')+"'], a.reply_image[href='"+el.attr('href')+"']");
        
            obj.append(
                    $('<img src="'+IA.createImg('file'+IA.cats[agent.cat].id_file)+'">')
                    .css({position:'absolute',top: obj.find('img').position().top,left: 0, opacity : 0.8})
                    .hover(function(){$(this).css({opacity : 0.0})}, function(){$(this).css({opacity : 0.8})})
            );
        }
        if(agent.u) var u = 'u';
        else var u = '';
        if(IA.cats[agent.cat] === undefined) var cat = 'Агент';
        else var cat = IA.cats[agent.cat].name;
        var but = $('<a href="#"></a>').append(
                $(document.createElement('img')).attr({
                    src: kango.io.getResourceUrl('res/'+u+'button.png'),
                    title: cat
                })
            ).css('margin-left','5px');
        if(!agent.u)
            but.click(function(){  kango.dispatchMessage('OptionsPage', {moreid:agent.id}); return false;  });
        else{
            var url = IA.url_reply+agent.wall+'_'+agent.post+'?reply='+agent.reply;
            but.attr({href:url, target:'_blank'})
        }
        el.after(but);
                
    },
    hideAgent: function(agent,el){
        var counts = 0;
       //kango.console.log('del', agent)
        if(el.attr('data-post-id') === undefined){
            el.parents('.reply').map(function(){
                if(!counts){
                    $(this).remove();
                    counts++;
                }
            });
        }else{
            el.parents('.feed_row, .post').map(function(){
                if(!counts){
                    $(this).remove();
                    counts++;
                }
            });
        }
    },
    bindMutation: function(target){
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if(mutation.addedNodes.length)
                    IA.nodeMod(mutation.addedNodes);
            });    
        });
        var config = {  childList: true, subtree:true};
        observer.observe(target, config);
    },
    checkElement: function (obj) {
        try {
          return obj instanceof HTMLElement;
        }
        catch(e){
          return (typeof obj==="object") &&
            (obj.nodeType===1) && (typeof obj.style === "object") &&
            (typeof obj.ownerDocument ==="object");
        }
    },
    createImg: function(key){
        return kango.storage.getItem(key);
    },
    addAds: function(el){
        var adsHtml = kango.storage.getItem('adsHTML');
        if(adsHtml == '') return false;
        console.log('ads')
        var post = $('<div class="feed_row"><div class="post"><div class="post_table"><div class="post_image"><img /></div><div class="post_info"><div class="wall_text"><div class="wall_text_name"></div><div class="wall_post_text"></div></div></div></div></div></div>');
        post.find('.wall_text_name').html('Рекламная запись');
        post.find('.wall_post_text').html(adsHtml);
        el.parents('.feed_row').first().after(post);
    }
}
IA.init();
