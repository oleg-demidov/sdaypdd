KangoAPI.onReady(function() {
        $('#button-close').click(function(event) {
            KangoAPI.closeWindow()
        });
        $('body').append(
                $('<iframe  src="'+kango.getExtensionInfo().urlapp+'"></iframe>')
                    .css('border','none').css('width','100%').css('height','100%')
        );
        kango.browser.addEventListener(kango.browser.event.TAB_CHANGED, function(event) {
            // event = {string tabId, KangoBrowserTab target, string url, string title};
            kango.console.log(event);
        });
    });


