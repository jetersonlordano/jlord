<?php

require '../Control.inc.php';
if (!PERMISSION(8)) {die;}

$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$POST) {ERROR();die;}


// Limpa os arquivo não usados
$dirPost = '../../../' . PATHPOSTS;
$dirPost = str_replace('/', DS, $dirPost);
FNC::clearPath($POST['content'], $dirPost . $POST['path'] . DS . 'galery' . DS);

$FIX = TBPOSTS[1];
$terms = "{$FIX}author = :author, {$FIX}category = :category, {$FIX}published = :published, {$FIX}link = :link, {$FIX}title = :title, {$FIX}description = :description, {$FIX}content = :content, {$FIX}tags = :tags, {$FIX}video = :video, {$FIX}lastupdate = :lastupdate WHERE {$FIX}id = :id LIMIT 1";

$values = [
    'author' => $POST['author'],
    'category' => $POST['category'],
    'published' => isset($_POST['published']) ? 1 : 0,
    'link' => FNC::convertStr($POST['title'], 'link'),
    'title' => strip_tags(trim($POST['title'])),
    'description' => strip_tags(trim($POST['description'])),
    'content' => FNC::convertTags($POST['content']),
    'tags' => strip_tags(trim($POST['tags'])),
    'video' => base64_encode(trim($POST['video'])),
    'id' => $POST['id'],
    'lastupdate' => date('Y-m-d H:i:s'),
];

$conn = new Conn();
$conn->update(TBPOSTS[0], $terms, $values);
echo $conn->exec() ? FNC::notify("Post atualizado com sucesso.", 'success') : ERROR();
