<?php $fbSet = Config::get('app.fb'); ?>
<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function() {
        var flag = false;
        FB.init({appId: '<?php echo $fbSet['APP_ID']; ?>', status: true, cookie: true, xfbml: true});
        // FB.Canvas.setAutoGrow();
        FB.Canvas.setSize({ height: $('html').height() });
    };
    (function() {
        var e = document.createElement('script'); e.async = true;
        e.src = document.location.protocol +
            '//connect.facebook.net/es_ES/all.js';
        document.getElementById('fb-root').appendChild(e);
    }());
    Modernizr.Detectizr.detect({detectScreen:false, detectBrowser: true});
</script>