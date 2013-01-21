<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title></title>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/player/skin/blue.monday/jplayer.blue.monday.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/player/skin/blue.monday/mobile.css">
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
                    margin: 0 auto;

                }
				.header_top{
					border-top:1px solid #ff8a85;
					border-bottom:1px solid #cc1814;
					height:12em;
					background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#e1463f), color-stop(100%,#c1120f), color-stop(100%,#2989d8), color-stop(100%,#207cca)); /* Chrome,Safari4+ */
					background: -webkit-linear-gradient(top,  #e1463f 0%,#c1120f 100%,#2989d8 100%,#207cca 100%); /* Chrome10+,Safari5.1+ */
					background: -o-linear-gradient(top,  #e1463f 0%,#c1120f 100%,#2989d8 100%,#207cca 100%); /* Opera 11.10+ */
					background: linear-gradient(to bottom,  #e1463f 0%,#c1120f 100%,#2989d8 100%,#207cca 100%); /* W3C */
					box-shadow: 0px 1px 0px #B32226;
				}
				.header_top h3{
					font-size: 50px;
					color: white;
					text-shadow: 0px 2px 2px #570101;
					text-align: center;
					letter-spacing: -0.1em;
					word-spacing: 0.1em;
					line-height:2.5em;
				}
					#headerTT{
                    overflow: hidden;
                    width: 100%;
					background:#9D9B9C;
					height: 16em;
                }


                #headerTT #logo {
                    float:left;
                    width: 100%;
					padding: 3.2em 0 0 0;
                }


                #headerTT #logo a {
                    display: block;
                    background: url("http://apiv2.tonguetango.com/css/player_logo.png") no-repeat 49% 88% transparent;
                    height: 130px;
                    margin: 0 auto;
                    text-indent: -99999px;
                    width: 100%;
                    background-size: 78% 106%;
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
                    background: url("http://dev.tonguetango.com/assets/images/link_page/buttoms.png") no-repeat -137px 0 transparent;
                }

                #headerTT .social li .facebook {
                    background: url("http://dev.tonguetango.com/assets/images/link_page/buttoms.png") no-repeat -184px 0 transparent;
                }


                #main-player{
					border-top: 1px solid #C0BFC0;
					padding: 0 4em;
                }


                #profile {
                    background: url("http://dev.tonguetango.com/assets/images/link_page/profile-bg.png") no-repeat 0 0 transparent;
                    background-size: 100% 100%;
					height: 100%;
					border-left: 1px solid #343534;
					border-right: 1px solid #343534;
					border-radius: 9px;
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
                a.jp-play, a.jp-pause,a.jp-stop {
                    width: 36px;
                    height: 100%;
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

                #web{
                    display:block;
                }

                #last {
                    float: left;
                    margin: 0 0 0 34px;
                    width: 566px;
                }
                a.jp-play, a.jp-pause{
                    height:100%;
                    width: 100%;
                }
                a.jp-stop{
                    height:100%;
                    width: 103%;
                }
                a.jp-play{
                    background:url(/js/player/skin/blue.monday/icon_play.png) no-repeat center center;
                    
                }
                a.jp-pause{
                    background:url(/js/player/skin/blue.monday/icon_pause.png) no-repeat center center;                 
                }
               a.jp-stop{
                    background:url(/js/player/skin/blue.monday/icon_stop.png) no-repeat center center;
                    margin-top: 0;      
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
					background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#cd0a0e), color-stop(100%,#9b0201), color-stop(100%,#2989d8), color-stop(100%,#207cca)); /* Chrome,Safari4+ */
					background: -webkit-linear-gradient(top,  #cd0a0e 0%,#9b0201 100%,#2989d8 100%,#207cca 100%); /* Chrome10+,Safari5.1+ */
					background: -o-linear-gradient(top,  #cd0a0e 0%,#9b0201 100%,#2989d8 100%,#207cca 100%); /* Opera 11.10+ */
					background: -ms-linear-gradient(top,  #cd0a0e 0%,#9b0201 100%,#2989d8 100%,#207cca 100%); /* IE10+ */
					background: linear-gradient(to bottom,  #cd0a0e 0%,#9b0201 100%,#2989d8 100%,#207cca 100%); /* W3C */
					box-shadow: 0px 1px 0px #B32226;
					margin: 4em auto;
					width: 91%;
					text-align: center;
					height: 12em;
					border-radius: 15px;
                    -webkit-border-radius: 15px;
                } 


                #footerTT .center {
                    font-family: "HelveticaNeueBold", "HelveticaNeue-Bold", "Helvetica Neue Bold", "HelveticaNeue", "Helvetica Neue", 'TeXGyreHerosBold', "Helvetica", "Tahoma", "Geneva", "Arial", sans-serif;
                    font-weight: 600;
                    font-stretch: normal;
                    margin: 0 auto;
                    font-size: 14px;
					line-height: 6em;
                }

                #footerTT .center .app {
                    background: url("http://dev.tonguetango.com/assets/images/link_page/buttoms.png") no-repeat 0 0 transparent;
                    display: block;
                    height: 52px;
                    width: 132px;
                    text-indent: -99999px;
                    margin: 0 auto;
                }

                #footerTT .center p {
                    font-size: 50px;
                    color: black;
                    text-shadow: 0 2px 1px #e6b4b4;                    
                    line-height: 2.4em;
                }

                #footerTT .center p a{
                    color: #000;

                }
                
                div.jp-audio {
                    width:65% !important;
                }
                body{
                    background: url("http://dev.tonguetango.com/assets/images/link_page/header-bg.png") repeat-x 0 0 #2d2d2d;
                    width: 100%;
					font-family: "Bebas Neue", Arial, Helvetica, sans-serif;
                }
				.user_block_top{
					margin: 1em 0 0.6em 0;
					overflow: hidden;
				}
                .user_block{
                    position: relative;
                    overflow: hidden;
                    width: 15em;
					height: 20em;
					float: left;
                    padding:0.3em 0.3em 0 0.3em;  
                    margin-right:2em;
                }
				.user_block_top h3{
					color: #edece7;
					font-size: 50px;
					overflow: hidden;
					margin-top: 1.7em;
					
				}
                
                .player_bg {
                        background-position: 12.5em 16.5em !important;
                }
                
                .img_bg_black{
                    position: absolute;
                    top: 0;
					left: 0;
					height: 100%;
                    border:0 none;
                }
              
                .jp-play,.jp-pause{
                    display: block!important;
                    
                }
                div.jp-audio ul.jp-controls li:last-child{
                    border-right:0 none!important;
                }
                .play_icon{
                    float:right;
                    margin-top: -5px; 
                    margin-right: -6px;
                }
				.player_bg { 
					overflow: hidden;
					padding: 4em 2em 6em 2em;
                    background-size:auto auto;
                    background-position:1.6em 16em;
                    height:35em;
				}
                .player_bg h3{
                    color: #EDECE7;
                    font-size: 29px;
                    font-family: arial;
                    padding-left: 48px;
                }
			    div.jp-audio{
					height: auto;
					padding: 2em 2em 0 2em;
					width: 90%;
					margin: 0 auto;
					float: none;
			    }
                div.jp-audio ul.jp-controls{
                   height: 13em;
                    padding-bottom:0.1em
                }
				a.jp-stop{
					margin-left:0;
                }
				div.jp-video ul.jp-controls, div.jp-interface ul.jp-controls li {
                    padding: 0;
                    width: 33%;
                    height: 13.1em;
				}
               
				div.jp-audio div.jp-type-single div.jp-progress{
					left:0;
					width: 100%;
					margin: 0 auto;
                    top:6.5em;
                    height: 20px;
				}
                div.jp-audio div.jp-type-single div.jp-interface{
                    height:100%
                }
                div.jp-seek-bar{  
                       min-width:100%
                }
                 div.jp-play-bar{                    
                       max-width:100%;
                }
				div.jp-current-time{
					left: 117%;
					padding: 0em 0.4em;
                    font-size:40px;
                    width:auto;
				}
				.bottom {
					padding: 1em 1em 4em 1em;
					overflow:hidden;
                }
				.bottom_block{
					width: 100%;
					margin: 0 auto;
                    overflow: hidden;
				}
				
				.bottom_block > img{
					width: 53%;
					float: left;
                    margin-right:0.2em;
                }
				.app_store_block{
					background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#d00e16), color-stop(100%,#9e0203), color-stop(100%,#9b0201), color-stop(100%,#9e0203)); /* Chrome,Safari4+ */
					background: -webkit-linear-gradient(top,  #d00e16 0%,#9e0203 100%,#9b0201 100%,#9e0203 100%); /* Chrome10+,Safari5.1+ */
					background: -o-linear-gradient(top, #d00e16 0%,#9e0203 100%,#9b0201 100%,#9e0203 100%); /* Opera 11.10+ */
					background: -ms-linear-gradient(top, #d00e16 0%,#9e0203 100%,#9b0201 100%,#9e0203 100%); /* IE10+ */
					background: linear-gradient(to bottom,  #d00e16 0%,#9e0203 100%,#9b0201 100%,#9e0203 100%); /* W3C */
					overflow:hidden;
					padding: 0.8em 2em 0 2em;
					border-radius: 5px;
					border: 2px solid #eaeaea;
					height: 9.8em;
					margin:1.8em 5em 0 0;
					text-align:center;
				}
                .app_store_block div{
                    width:90%;
                    margin:0 auto;
                }
				.app_store_block img{
					float:left; 
                    width:18%;
                    margin-left:1em;
				} 
				.app_store_block span{
					color:#ffffff;
					text-shadow:1px 1px 1px #930710;
					display: block;
					overflow: hidden;
					padding-left: 0.3em;
					line-height: 1.3em;
					
				}
				.app_store_block span:first-of-type{
					font-size: 26px;
				}
				.app_store_block span:last-child{
					font-size: 41px;
					font-weight: bold;
					padding-left: 0.2em;
				} 
			</style>



           
			<link href="<?php echo Yii::app()->request->baseUrl; ?>/js/player/skin/blue.monday/media_query.css"/>
    </head>
    <body >
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
                    $(".jp-play").mousedown(function(){ $(".jp-play").trigger("click");});
                    $(".jp-pause").mousedown(function(){ $(".jp-pause").trigger("click");});
                    $(".jp-stop").mousedown(function(){ $(".jp-stop").trigger("click");});
                });
            ');
                ?>
        <div id="wrapperTT">
            <div id="headerTT">
                <h1 id="logo"><a href="Tongue Tango">Tongue Tango</a></h1>
            </div>

            <div id="main-player">
				<div class="user_block_top">
					 <div class="user_block">
						<img width="155" style="margin: 13px;"" src="<?php echo Yii::app()->request->baseUrl."/images/".$photo; ?>"/>
						<img src="<?php echo Yii::app()->request->baseUrl; ?>/js/player/skin/blue.monday/bg_black.png" class="img_bg_black" alt=""/>
					</div>			
					 <h3><?php echo $firstname." ".$lastname; ?></h3>
				</div>
                <section id="profile">

                    <div id="jquery_jplayer_1" class="jp-jplayer"></div>
                    <div class="player_bg">
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
		 <div id="footerTT">

            <div class="center" id="mobile">                
                <p><a style="font-size:68px" href="http://www.tonguetango.com">www.tonguetango.com</a> </p>
            </div>	
        </div>

      
        <div class="bottom">
			<div class="bottom_block"  style="height:154px">
			<img src="<?php echo Yii::app()->request->baseUrl; ?>/js/player/skin/blue.monday/iphone_bottom.png" alt=""/>
			<div class="app_store_block">
                <div>
                    <a href="https://itunes.apple.com/app/tongue-tango/id472642395?mt=8">
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/js/player/skin/blue.monday/iphone.png" alt=""/>
                        <span>Available on Apple</span>
                        <span>App Store</span>
                    </a>
                </div>
			</div>
			</div>
		</div><!--CLOSE BOTTOM-->
    </body>    
</html>