<?php

/**
 * Page posts
 */

echo '<section class="wrapper"><div class="container"><div id="postsContainer" class="row blog_posts align-items-stretch justify-content-center">';

if ($POSTS) {foreach ($POSTS as $postKey) {
    $postCover = PATHPOSTS . $postKey['post_path'] . '/' . $postKey['post_cover'];
    $postKey['image-default'] = IMAGE;
    $postKey['post_cover'] = Check::Image($postCover, IMAGE);
    $postKey['post_link'] = HOME . '/post/' . $postKey['post_link'];
    $postKey['post_category'] = HOME . '/' . $PAGES->getData()['pg_link'] . '/' . FNC::convertStr($postKey['cat_title']);
    echo FNC::view($postKey, TPL . 'post_box.html');
}}

echo '</div><div class="row blog_posts_plus"><div class="col post_plus center"><div id="loaderPosts"><div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div> <button id="postPlusBtn" class="btn" title="Carregar mais posts" data-plus="true">VER MAIS</button></div></div></div> </section>';

echo '<script async src="' . ADD . '/assets/scripts/posts.js"></script>';
