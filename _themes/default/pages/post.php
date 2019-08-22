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
$POST['day'] = date('d-m-Y', strtotime($POST['post_date']));
$POST['hour'] = date('H:i', strtotime($POST['post_date']));
$POST['linkcat'] = HOME . $SECTION . FNC::convertStr($POST[$FIXC . 'link']);
$POST[$FIX . 'video'] = base64_decode($POST[$FIX . 'video']);
$POST[$FIX . 'content'] = html_entity_decode($POST[$FIX . 'content']);
$POST[$FIX . 'tags'] = FNC::inLink($POST[$FIX . 'tags'], HOME . '/pesquisa');
$POST[$FIXC . 'title'] = strtoupper($POST[$FIXC . 'title']);
$POST['link'] = HOME . $SINGLE . $POST[$FIX . 'link'];

echo '<div class="wrapper"><div class="container"><div class="row align-items-stretch justify-content-center">';

echo FNC::view($POST, TPL . 'post.html');

// Inclui sidebar
include_once __DIR__ . DS . '_parts' . DS . 'sidebar.php';

echo '</div></div></div>';
