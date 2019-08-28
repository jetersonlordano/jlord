<?php

if (!$POST) {FNC::redirect(HOME);die;}

$FIX = TBPOSTS[1];
$FIXC = TBCATS[1];
$FIXU = TBUSERS[1];

$SECTION = '/blog/';
$SINGLE = '/post/';
$avatar = PATHAUTHORS . $POST[$FIXU . 'avatar'];
$pCover = PATHPOSTS . $POST[$FIX . 'path'] . '/' . $POST[$FIX . 'cover'];

$POST['IMAGE'] = IMAGE;
$POST['AVATAR'] = IMAGE;
$POST['TITLE'] = TITLE;
$POST[$FIXU . 'avatar'] = Thumb::Nail($avatar, 60, null, $POST[$FIXU . 'lastupdate'], 'users', AVATAR);
$POST[$FIX . 'cover'] = Thumb::Nail($pCover, 600, null, $POST[$FIX . 'lastupdate'], 'post');
$POST['date'] = strftime('%d de %B de %Y', strtotime($POST[$FIX . 'lastupdate']));
$POST['linkcat'] = HOME . $SECTION . FNC::convertStr($POST[$FIXC . 'link']);
$POST[$FIX . 'video'] = base64_decode($POST[$FIX . 'video']);
//$POST[$FIX . 'content'] = html_entity_decode($POST[$FIX . 'content']);
$POST[$FIX . 'tags'] = FNC::inLink($POST[$FIX . 'tags'], HOME . '/tag');
$POST[$FIXC . 'title'] = strtoupper($POST[$FIXC . 'title']);
$POST['link'] = HOME . $SINGLE . $POST[$FIX . 'link'];

$POST[$FIX . 'content'] = str_replace('><iframe', '><span class="iframe"><iframe', $POST[$FIX . 'content']);
$POST[$FIX . 'content'] = str_replace('</iframe>', '</iframe></span>', $POST[$FIX . 'content']);

$POST['midia'] = checkMidia($POST[$FIX . 'video'], $POST[$FIX . 'cover'], $POST[$FIX . 'title']);

function checkMidia(string $url, string $cover, $title)
{
    if (strpos($url, 'watch?v=')) {
        $itens = parse_url($url);
        parse_str($itens['query'], $params);
        if (isset($params['v'])) {
            return '<div class="video_container"><iframe src="https://www.youtube.com/embed/' . $params['v'] . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
        }
    }

    if (strpos($url, 'vimeo.com')) {
        $exp = explode('/', $url);
        return '<div class="video_container"><iframe src="https://player.vimeo.com/video/' . trim(end($exp)) . '" width="640" height="360" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe></div>';
    }

    if (strpos($url, '.mp4')) {
        $script = HOME . '/_wdgt/scripts/jlplayer.js';
        return '<div class="video_container"><video preload="none" poster="' . $cover . '" class="jlplayer-video"><source src="' . $url . '" type="video/mp4"></video></div><script src="' . $script . '" async></script>';
    }

    return '<img class="img radius" src="' . $cover . '" alt="Capa do post ' . $title . '">';
}

?>

<div id="mainWrapper" class="wrapper">
    <div class="container">
        <div class="row align-items-start">

            <div class="col-12 col-md-7 col-lg-8">

                <!-- Post -->
                <?=FNC::view($POST, TPL . 'post.html');?>

            </div>


            <!-- sidebar -->
            <?php include_once __DIR__ . DS . 'parts' . DS . 'aside.php';?>

        </div>
    </div>
</div>
