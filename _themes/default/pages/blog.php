<?php


echo '<div class="wrapper"><div class="container"><section class="row align-items-stretch justify-content-center">';

/**
 * Posts
 */

echo '<div class="col col-7 posts_box">';

$FIX = TBPOSTS[1];
$FIXC = TBCATS[1];
$FIXPG = TBPAGES[1];

// $JPAGEINFO = $PAGES->getData();
$SECTION = '/blog/';
$SINGLE = '/post/';

if ($POSTS) {foreach ($POSTS as $pkey) {
    $pCover = PATHPOSTS . $pkey[$FIX . 'path'] . '/' . $pkey[$FIX . 'cover'];
    $pkey['IMAGE'] = IMAGE;
    $pkey['post_cover'] = Thumb::Nail($pCover, 600, null, $pkey[$FIX . 'lastupdate'], 'blog');
    $pkey['link'] = HOME . $SINGLE . $pkey[$FIX . 'link'];
    $pkey['linkcat'] = HOME . $SECTION . $pkey[$FIXC . 'link'];
    $pkey[$FIXC . 'title'] = strtoupper($pkey[$FIXC . 'title']);
    echo FNC::view($pkey, TPL . 'post_box.html');
}} else {PHPNOTIFY('Não existe postagens disponíveis.');}

echo '</div>';

// Inclui sidebar
include_once __DIR__ . DS . '_parts' . DS . 'sidebar.php';

echo '</section></div></div>';
