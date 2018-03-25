<?php
$keys = array(
    rand(1,3), rand(1,3), rand(1,3)
);
$_SESSION['captchaKeys'] = $keys;?>
<link href="/scr/captcha/captcha.css" rel="stylesheet">
<div class="captcha">
    <img src="/scr/captcha/sec_img/<?php echo rand(1,10);?>.png" id="sec1" class="rotate<?php echo $keys[0];?>"/>
    <img src="/scr/captcha/sec_img/<?php echo rand(1,10);?>.png" id="sec2" class="rotate<?php echo $keys[1];?>"/>
    <img src="/scr/captcha/sec_img/<?php echo rand(1,10);?>.png" id="sec3" class="rotate<?php echo $keys[2];?>"/>
</div>
<script>
    $('.captcha img').click(function(){
        var nowR = $(this).attr('class').substring(6);
        nowR++;
        if(nowR > 3) nowR = 0;
        $(this).attr('class', 'rotate' + nowR);
    })
    function checkCaptcha(){
        return ($('.captcha').find('.rotate0').length == 3);
    }
</script>