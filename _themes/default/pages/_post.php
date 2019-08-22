<?php

if (!$POST) {FNC::redirect(HOME);die;}

$avatar =  ADD . '/uploads/authors/' . $POST['user_avatar'];
$cover =  ADD . '/uploads/posts/' . $POST['post_path'] . '/' . $POST['post_cover'];
$POST['add'] = ADD;
$POST['IMAGE'] = IMAGE;
$POST['user_avatar'] = AVATAR;
$POST['post_cover'] = $cover;
$POST['day'] = date('d-m-Y', strtotime($POST['post_date']));
$POST['hour'] = date('H:i', strtotime($POST['post_date']));
$POST['catlink'] = HOME . '/categoria/' . FNC::convertStr($POST['cat_title']);
$POST['post_video'] = base64_decode($POST['post_video']);
$POST['post_content'] = html_entity_decode($POST['post_content']);
$POST['post_tags'] = FNC::inLink($POST['post_tags'], HOME . '/tag');
$POST['cat_title'] = strtoupper($POST['cat_title']);
$POST['post_link'] = HOME . '/posts' . $POST['post_link'];
echo FNC::view($POST, TPL . 'post.html');
