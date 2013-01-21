<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title></title>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/player/skin/blue.monday/jplayer.blue.monday.css">
            <style>
                html, body, body div, span, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, abbr, address, cite, code, del, dfn, em, img, ins, kbd, q, samp, small, strong, sub, sup, var, b, i, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, figure, footer, header, hgroup, menu, nav, section, time, mark, audio, video {
                    margin: 0;
                    padding: 0;
                    border: 0;
                    outline: 0;
                    font-size: 100%;
                    vertical-align: baseline;
                    background: transparent;
                }

                article, aside, figure, footer, header, hgroup, nav, section {
                    display: block;
                }

                html {
                    overflow-y: scroll;
                }

                ul {
                    list-style: none;
                }

                blockquote, q {
                    quotes: none;
                }

                blockquote:before, blockquote:after, q:before, q:after {
                    content: '';
                    content: none;
                }

                a {
                    margin: 0;
                    padding: 0;
                    font-size: 100%;
                    vertical-align: baseline;
                    background: transparent;
                    text-decoration: none;
                }

                del {
                    text-decoration: line-through;
                }

                abbr[title], dfn[title] {
                    border-bottom: 1px dotted #000;
                    cursor: help;
                }

                table {
                    border-collapse: collapse;
                    border-spacing: 0;
                }

                th {
                    font-weight: bold;
                    vertical-align: bottom;
                }

                td {
                    font-weight: normal;
                    vertical-align: top;
                }

                hr {
                    display: block;
                    height: 1px;
                    border: 0;
                    border-top: 1px solid #ccc;
                    margin: 1em 0;
                    padding: 0;
                }

                input, select {
                    vertical-align: middle;
                }

                pre {
                    white-space: pre;
                    white-space: pre-wrap;
                    white-space: pre-line;
                    word-wrap: break-word;
                }

                .ie6 input {
                    vertical-align: text-bottom;
                }

                select, input, textarea {
                    font: 99% sans-serif;
                }

                table {
                    font-size: inherit;
                    font: 100%;
                }

                a:hover, a:active {
                    outline: none;
                }

                small {
                    font-size: 85%;
                }

                strong, th {
                    font-weight: bold;
                }

                td, td img {
                    vertical-align: top;
                }

                sub, sup {
                    font-size: 75%;
                    line-height: 0;
                    position: relative;
                }

                sup {
                    top: -0.5em;
                }

                sub {
                    bottom: -0.25em;
                }

                pre, code, kbd, samp {
                    font-family: monospace, sans-serif;
                }

                .clickable,label, input[type=button], input[type=submit], button {
                    cursor: pointer;
                }

                button, input, select, textarea {
                    margin: 0;
                }

                button {
                    width: auto;
                    overflow: visible;
                }

                .ie7 img {
                    -ms-interpolation-mode: bicubic;
                }

                .ie6 html {
                    filter: expression(document.execCommand("BackgroundImageCache", false, true));
                }

                .clearfix:before, .clearfix:after {
                    content: "\0020";
                    display: block;
                    height: 0;
                    overflow: hidden;
                }

                .clearfix:after {
                    clear: both;
                }

                .clearfix {
                    zoom: 1;
                }

                body {
                    font-size:62.5%;
                }

                body, select, input, textarea {
                    color: #333;
                }

                a {
                    color: #03f;
                }

                ins {
                    background-color: #fcd700;
                    color: #000;
                    text-decoration: none;
                }

                mark {
                    background-color: #fcd700;
                    color: #000;
                    font-style: italic;
                    font-weight: bold;
                }

                /*STYLES TONGUE TANGO WEBSITE*/



                #wrapperTT{
                    width: 630px;
                    margin: 0 auto;


                }

                #headerTT{
                    overflow: hidden;
                    width: 100%;
                }


                #headerTT #logo {
                    float:left;
                    width: 363px;
                    padding: 21px 0 0 0;
                }


                #headerTT #logo a {
                    display: block;
                    background: url("<?php echo Yii::app()->baseUrl; ?>/css/logo2.png") no-repeat 0 0 transparent;
                    height: 74px;
                    width: 364px;
                    text-indent: -99999px;
                }

                #headerTT .social {
                    float: right;
                    width: 177px;
                    padding: 30px 0 0;
                }

                #headerTT .social li{
                    float: left;
                }


                #headerTT .social li a{
                    display: block;
                    height: 43px;
                    width: 53px;
                    text-indent: -99999px;
                }

                #headerTT .social li .twitter {
                    background: url("<?php echo Yii::app()->baseUrl; ?>/css/buttoms.png") no-repeat 0 0 transparent;
                }

                #headerTT .social li .facebook {
                    background: url("<?php echo Yii::app()->baseUrl; ?>/css/buttoms.png") no-repeat -50px 0 transparent;
                }


                #main-player{
                    width: 100%;
                    padding: 50px 0 0 0;
                }


                #profile {
                    background: url("<?php echo Yii::app()->baseUrl; ?>/css/player-main-bg.png") no-repeat 0 0 transparent;
                    width: 609px;
                    height: 322px;
                    padding: 47px 0 0 19px;
                }

                .playerbtn {
                    background: url("http://dev.tonguetango.com/assets/images/link_page/player-btn.png") no-repeat 0 0 transparent;
                    display: block;
                    width: 256px;
                    height: 258px;
                    text-indent: -9999px;
                    margin: 0 auto;
                }

                .container {
                    float: left;
                    width: 547px;
                    padding: 31px 0 0 0;
                }


                .container  h2 {
                    color: #ECECE6;
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 3.2em;
                    padding: 0 0 6px 15px;
                }

                .player {
                    background: url("http://dev.tonguetango.com/assets/images/link_page/bg-player-content2.png") no-repeat 32px 16px transparent;
                    overflow: hidden;
                    width: 261px;
                    height: 59px;
                    padding: 44px 0 0 70px;
                }

                .user{
                    float: left;
                }

                .player li {
                    float: left;
                    padding: 0 68px 0 0;
                }

                .player li:last-child {
                    padding: 0 0 0 6px;
                }

                .player li a{
                    display: block;
                    height: 31px;
                    width: 28px;
                    text-indent:-99999px;
                }

                .player li.play a {
                    background: url("http://dev.tonguetango.com/assets/images/link_page/player-control.png") no-repeat 0 0 transparent;

                }


                .player li.pause a {
                    background: url("http://dev.tonguetango.com/assets/images/link_page/player-control.png") no-repeat -99px 0 transparent;
                }


                .player li.stop a {
                    background: url("http://dev.tonguetango.com/assets/images/link_page/player-control.png") no-repeat -199px 0 transparent;
                }

                .controls {
                    background: url("http://dev.tonguetango.com/assets/images/link_page/controls-bg.png") no-repeat 0 0 transparent;
                    height: 119px;
                    width: 348px;
                }

                .controls .var {
                    background: url("http://dev.tonguetango.com/assets/images/link_page/progressbar.png") no-repeat 0 0 transparent;
                    display: block;
                    height: 61px;
                    width: 334px;
                    margin: 29px 0 0 16px;
                    float: left;
                }

                .bottom {
                    background: url("http://dev.tonguetango.com/assets/images/link_page/illustra-bg.png") repeat-x 0 0 transparent;
                    height: 117px;
                    width: 100%;

                    margin-top: 25px;

                }

                #mobile{
                    display:none;
                }

                #web{
                    display:block;
                }

                #last {
                    float: left;
                    margin: 0 0 0 34px;
                    width: 566px;
                }

                #last .site {
                    display: block;
                    background: url("http://dev.tonguetango.com/assets/images/link_page/buttoms-2.png") no-repeat 0 0 transparent;
                    height: 100px;
                    width: 570px;
                    font-family: "HelveticaNeueHeavy", "HelveticaNeue-Heavy", "Helvetica Neue Heavy", "HelveticaNeue", "Helvetica Neue", 'TeXGyreHerosBold', "Arial Black", sans-serif;
                    font-weight: 700;
                    font-stretch: normal;
                    font-size: 4em;
                    color: black;
                    text-shadow: 0 2px #EF7474;
                    text-align: center;
                    line-height: 90px;
                    margin: 20px 0 0 0;
                }

                #last .icons {
                    float: left;
                    padding: 21px 0 0;
                }

                #last .appstore {
                    display: block;
                    background: url("http://dev.tonguetango.com/assets/images/link_page/buttoms-2.png") no-repeat right -115px transparent;
                    float: right;
                    width: 248px;
                    height: 113px;
                    text-indent: -999999pc;
                }


                #footerTT {
                    background: url("<?php echo Yii::app()->baseUrl; ?>/css/footer-bg.png") repeat-x 0 0 transparent;
                    width: 100%;
                    height: 113px;
                    margin-top:31px;
                } 


                #footerTT .center {
                    width: 400px;
                    font-family: "HelveticaNeueBold", "HelveticaNeue-Bold", "Helvetica Neue Bold", "HelveticaNeue", "Helvetica Neue", 'TeXGyreHerosBold', "Helvetica", "Tahoma", "Geneva", "Arial", sans-serif;
                    font-weight: 600;
                    font-stretch: normal;
                    margin: 0 auto;
                    padding: 27px 0 0 0;
                }

                #footerTT .center .app {
                    background: url("<?php echo Yii::app()->baseUrl;?>/css/appstore_link.png") no-repeat 0 0 transparent;
                    display: block;
                    height: 52px;
                    width: 132px;
                    text-indent: -99999px;
                    margin: 0 auto;
                }

                #footerTT .center p {
                    font-size: 1.5em;
                    color: black;
                    text-shadow: 0 1px #ef7474;
                }

                #footerTT .center p a{
                    color: #000;

                }


                /*SUBPAGE STYLES*/
                @media handheld, only screen and (max-device-width: 320px) {

                    a{

                        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);

                    }

                    a:link {text-decoration: none;  background-color: transparent ;}
                    a:visited {text-decoration: none; background-color: transparent ;}
                    a:active {text-decoration: none; background-color: transparent ;}
                    a:hover {text-decoration: none; background-color: transparent ;}


                    #footerTT .center .app {
                        background: url("<?php echo Yii::app()->baseUrl;?>/css/appstore_link.png") no-repeat 0 0 transparent;
                        display: block;
                        height: 52px;
                        width: 132px;
                        text-indent: -99999px;
                        margin: 0 auto;
                    }

                    #mobile{
                        display:block;
                    }

                    #web {
                        display:none;
                    }

                    #footerTT .center {
                        font-family: "HelveticaNeueBold", "HelveticaNeue-Bold", "Helvetica Neue Bold", "HelveticaNeue", "Helvetica Neue", 'TeXGyreHerosBold', "Helvetica", "Tahoma", "Geneva", "Arial", sans-serif;
                        font-weight: 600;
                        font-stretch: normal;
                        margin: 0 auto;
                        padding: 15px 0;
                        text-align:center;
                        width:320px;
                    }

                    .bottom {
                        background: url("http://dev.tonguetango.com/assets/images/link_page/illustra-bg.png") repeat-x 0 0 transparent;
                        background-size:320px auto;
                        height: 65px;
                        width: 100%;
                        margin-top: -100px;
                    }

/*                    body{
                        background: url("http://apiv2.tonguetango.com/images/header-bg.png") repeat-x 0 0 #2d2d2d;
                        background-size:0 100px;
                        width: 100%;
                    }*/

                    #headerTT #logo {
                        margin: 0 auto;
                        width: 320px;
                        padding: 10px 0 0 0;
                    }

                    #headerTT .social {
                        display:none;
                    }

                    #wrapperTT {
                        width: 320px;
                        margin: 0 auto;
                    }


                    .container {

                        width: 100%;
                        padding: 20px 0 0 0;
                    }

                    .playerbtn {
                        background: url("http://dev.tonguetango.com/assets/images/link_page/player-btn.png") no-repeat 0 0 transparent;
                        background-size:150px auto;
                        display: block;
                        width: 150px;
                        height: 152px;
                        text-indent: -9999px;
                        margin: 0 auto;
                    }

                    #main-player {
                        width: 100%;
                        padding: 50px 0 0 0;
                        overflow: hidden;
                    }

                    #logo{

                    }

                    #logo a {
                        background: url("http://dev.tonguetango.com/assets/images/link_page/logo3.png") no-repeat 0 0 transparent;
                        background-size:320px auto;
                        height: 163px;
                        width: 320px;
                    }

                    #profile {
                        background: url("<?php echo Yii::app()->baseUrl; ?>/css/player-main-bg.png") no-repeat 0 0 transparent;
                        width: 300px;
                        height:172;
                        padding: 10px 0;
                        margin: 20px 0 0 10px;
                    }



                    .player {
                        background: url("http://dev.tonguetango.com/assets/images/link_page/bg-player-content2.png") no-repeat 42px 20px transparent;
                        overflow: hidden;
                        width: 403px;
                        height: 98px;
                        padding: 61px 0 0 10px;
                    }

                    .user{
                        float: left;
                    }

                    .player li {
                        float: left;
                        padding: 0 99px 0 0;
                    }

                    .player li:last-child {
                        padding: 0 0 0 6px;
                    }

                    .player li a {
                        display: block;
                        height: 42px;
                        width: 38px;
                        text-indent: -99999px;
                    }

                    .player li.play a {
                        background: url("http://dev.tonguetango.com/assets/images/link_page/player-control2.png") no-repeat 0 0 transparent;

                    }


                    .player li.pause a {
                        background: url("http://dev.tonguetango.com/assets/images/link_page/player-control2.png") no-repeat -145px 0 transparent;
                    }

                    .player li.stop a {
                        background: url("http://dev.tonguetango.com/assets/images/link_page/player-control2.png") no-repeat -288px 0 transparent;
                    }

                    .controls {
                        background: url("http://dev.tonguetango.com/assets/images/link_page/controls2-bg.png") no-repeat 0 0 transparent;
                        height: 167px;
                        width: 496px;
                        margin: 0 0 0 20px;
                    }

                    .controls .var {
                        background: url("http://dev.tonguetango.com/assets/images/link_page/progressbar2.png") no-repeat 0 0 transparent;
                        display: block;
                        height: 77px;
                        width: 477px;
                        margin: 29px 0 0 22px;
                        float: left;
                    }

                    footer{
                        background: none;
                    }


                }

                body{
                    background: url("<?php echo Yii::app()->baseUrl; ?>/css/header-bg.png") repeat-x 0 0 #2d2d2d;
                    width: 100%;
                }

                .user_block{
                    float: left;
                    position: relative;
                    overflow: hidden;
                    width: 203px;
                    height: 240px;      
                }
                .img_bg{
                    position: absolute;
                    top: -36px;
                    left: -20px;
                }
                .jp-play,.jp-pause{
                    display: block!important;
                    
                }
                div.jp-audio ul.jp-controls li:last-child{
                    border-right:0 none!important;
                }
                .play_icon{
                    float:right;
                    margin-top: -8px;
                    margin-right: -6px;
                }
                .player_bg h3{
                    color: #EDECE7;
                    font-size: 29px;
                    font-family: arial;
                    padding-left: 48px;
                }
               
            </style>

    </head>

    <?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/player/jquery.jplayer.min.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScript('player', '
       $(document).ready(function(){

        $("#jquery_jplayer_1").jPlayer({
            ready: function (event) {
                $(this).jPlayer("setMedia", {
                    mp3:"'.$uri.'"
                });
            },
            swfPath: "js",
            supplied: "m4a, oga, mp3",
            wmode: "window"
        });
        $(".jp-play-bar").mouseover(function() {$(".play_hours").show();} );
        $(".jp-play-bar").mouseout(function() {$(".play_hours").hide();} );
    });
');
    ?>


    <body >
        <div id="wrapperTT">
            <div id="headerTT">
                <h1 id="logo"><a href="Tongue Tango">Tongue Tango</a></h1>
                <ul class="social">
                    <li><a class="twitter" href="https://twitter.com/tonguetango">Twitter</a></li>
                    <li><a class="facebook" href="https://www.facebook.com/TongueTango">Facebook</a></li>
                </ul>
            </div>

            <div id="main-player">
                <section id="profile">
                    <div class="user_block">
                        <img style="margin-left: 2px;" src="<?php echo Yii::app()->request->baseUrl."/images/".$photo; ?>"/>
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/js/player/skin/blue.monday/bg.png" class="img_bg"/>
                    </div>
                    <div id="jquery_jplayer_1" class="jp-jplayer"></div>
                    <div class="player_bg">
                        <h3 ><?php echo $firstname." ".$lastname; ?></h3>
                        <div id="jp_container_1" class="jp-audio">
                            <div class="jp-type-single">
                                <div class="jp-gui jp-interface">
                                    <ul class="jp-controls">
                                        <li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
                                        <li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
                                        <li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
                                    </ul>
                                       <div class="jp-progress">
                                            <div class="jp-seek-bar">
                                                <div class="jp-play-bar"><img src="<?php echo Yii::app()->request->baseUrl; ?>/js/player/skin/blue.monday/play_icon.png" class="play_icon"/>
                                                    <div class="play_hours" style="display:none"><div class="jp-current-time"></div></div>
                                                </div>
                                            </div>
                                       </div>
                                </div>
                              
                                <div class="jp-no-solution">
                                    <span>Update Required</span>
                                    To play the media you will need to either update your browser to a recent version or update your 
                                    <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
                                </div>
                            </div>
                         </div>
                            
                    </div>
                </section><!--CLOSE PROFILE-->
            </div><!--CLOSE MAIN-->


        </div><!--CLOSE WRAPPER-->
        <div class="bottom"></div><!--CLOSE BOTTOM-->
        <div id="footerTT">
            <div class="center" id="web">
                <a href="http://itunes.apple.com/app/tongue-tango/id472642395?mt=8" class="app">app store</a>
                <p><a href="http://www.tonguetango.com">www.tonguetango.com</a> / <a href="mailto:tongue@tonguetango.com">tongue@tonguetango.com</a> </p>

            </div>
            <div class="center" id="mobile">
                <a href="http://itunes.apple.com/app/tongue-tango/id472642395?mt=8" class="app">app store</a>
                <p><a href="http://www.tonguetango.com">www.tonguetango.com</a> </p>

            </div>	
        </div>

    </body>    
</html>