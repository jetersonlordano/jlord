<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php if (GOOGLEANALYTICSID) {echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . GOOGLEANALYTICSID . '"></script>';
    echo "<script>window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', '" . GOOGLEANALYTICSID . "');</script>";}?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?=TITLE?></title>
    <meta name="description" content="<?=DESCRIPTION?>" />
    <base href="<?=HOME?>">
    <meta name="robots" content="index, follow">
    <meta name="url" content="<?=HOME?>">
    <link rel="base" href="<?=HOME?>">
    <link rel="home" href="<?=HOME?>">
    <link rel="canonical" href="<?=HOME?>">
<?php

if (SEO) {
    $head = ['home' => HOME, 'title' => TITLE, 'description' => DESCRIPTION];
    $head['cover'] = isset($POST) ? HOME . '/' . PATHPOSTS . $POST['post_path'] . '/' . $POST['post_cover'] : COVER;
    $head['twitter'] = isset(SOCIALNETWORKS['twitter']) ? SOCIALNETWORKS['twitter'][1] : null;
    echo FNC::view($head, TPL . 'metas.html');
}

?>

    <!-- Icones -->
    <link rel="icon" href="<?=HOME?>/uploads/branding/favicon.png" type="image/png">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?=ADD?>/assets/css/vars.css">
    <link rel="stylesheet" href="<?=ADD?>/assets/css/reset.css">
    <link rel="stylesheet" href="<?=ADD?>/assets/css/colors.css">
    <link rel="stylesheet" href="<?=ADD?>/assets/css/grid.css">
    <link rel="stylesheet" href="<?=ADD?>/assets/css/helpers.css">
    <link rel="stylesheet" href="<?=ADD?>/assets/css/elements.css">
    <link rel="stylesheet" href="<?=ADD?>/assets/css/main.css">
    <link rel="stylesheet" href="<?=ADD?>/assets/css/post.css">
    <link rel="stylesheet" href="<?=ADD?>/assets/css/blog.css">
    <link rel="stylesheet" href="<?=ADD?>/assets/icons/css/font-awesome.css">
    <?php if ($LINK->index[0] == 'post') {echo '<link rel="stylesheet" href="' . HOME . '/_wdgt/css/jlplayer.css">';}?>

    <script>"use strict";function $(t,e){let n;switch(e="string"==typeof e?$(e):e||document,t.substring(0,1)){case"#":n="getElementById",t=t.substring(1);break;case".":n="getElementsByClassName",t=t.substring(1);break;default:n="getElementsByTagName"}return e[n](t)}function $on(t,e,n,o,a){let i=e.split(" ");for(var r=0;r<i.length;r++)t[o?"addEventListener":"removeEventListener"](i[r],n,a)}function ajax(t){let e,n,o,a;o=(n=-1!==String(t.data).indexOf("FormData"))?"X-Requested-With":"Content-type",a=n?"XMLHttpRequest":"application/json; charset=utf-8",$on((e=new(window.XMLHttpRequest||ActiveXObject("MSXML2.XMLHTTP.3.0"))).upload,"abort error load loadend loadstart progress",t.upload,!0),$on(e,"readystatechange",function(){4===this.readyState&&(200===this.status?t.success(this):t.error(this))},!0),e.open(t.method||"POST",t.url||window.location.href,!0,t.user,t.psw),e.setRequestHeader(o,t.contentType||a),e.send("object"!=typeof t.data||n?t.data:JSON.stringify(t.data)),t.start&&t.start()}function lazyImages(){function t(t){function e(){(function(t){return t.getBoundingClientRect().top<=(window.innerHeight||document.documentElement.clientHeight)&&t.getBoundingClientRect().bottom>=0})(t)&&(!function(t){t.src=t.getAttribute("data-lazy"),t.removeAttribute("data-lazy")}(t),$on(document,"scroll",e,0),$on(window,"resize orientationchange",e,0))}$on(document,"DOMContentLoaded scroll",e,!0),$on(window,"resize orientationchange",e,!0)}let e=document.querySelectorAll("img[data-lazy]");for(let n=0;n<e.length;n++)new t(e[n])}lazyImages();</script>

</head>

<body>

    <?php if ($LINK->index[0] == 'post' && FACEBOOKPAGEID) {echo '<div id="fb-root"></div><script async defer crossorigin="anonymous" src="https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v4.0&appId=' . FACEBOOKPAGEID . '&autoLogAppEvents=1"></script>';}?>

    <div id="topBar" class="wrapper bg-white">
        <div class="container">
            <div class="row justify-content-between">

                <a class="main_logo flex align-items-center align-content-center" href="<?=HOME?>" title="<?=TITLE?>">
                    <img src="<?=HOME?>/<?=PATHAUTHORS?>logo-default.png" alt="<?=TITLE?>">
                </a>


                <div class="nav_resp flex align-items-center">
                    <div id="navResp" class="nav_icon btn" data-open="false">
                        <span class="bar1"></span>
                        <span class="bar2"></span>
                        <span class="bar3"></span>
                    </div>
                </div>

                <nav id="mainNav" class="main_nav" data-open="false">
                    <ul>
<?php foreach (NAVIGATION as $key => $value) {$urlNav = $value[2];if (!strstr($value[2], 'http') && !strstr($value[2], 'www')) {$urlNav = HOME . '/' . $value[2];}
    $targetBlank = $value[3] == 0 ? null : ' target="_blank"';
    echo '<li><a href="' . $urlNav . '" title="' . $value[1] . '"' . $targetBlank . '>' . $value[0] . '</a></li>';}
?>
                    </ul>
                </nav>

            </div>
        </div>
    </div>