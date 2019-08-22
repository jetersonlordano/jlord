<?php

require '../Control.inc.php';
if (!PERMISSION(8)) {die;}

$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$POST) {ERROR();die;}

// Limpa os arquivo não usados
// $dirBus = '../../../' . PATHPOSTS;
// $dirBus = str_replace('/', DS, $dirBus);
// FNC::clearPath($POST['content'], $dirBus . $POST['path'] . DS . 'galery' . DS);

$FIX = TBBUSINESS[1];
$terms = "{$FIX}owner = :owner, {$FIX}category = :category, {$FIX}published = :published, {$FIX}link = :link, {$FIX}name = :name, {$FIX}description = :description, {$FIX}content = :content, {$FIX}tags = :tags, {$FIX}type = :type, {$FIX}phone = :phone, {$FIX}cel = :cel, {$FIX}email = :email, {$FIX}address = :address, {$FIX}coordinates = :coordinates, {$FIX}partner = :partner, {$FIX}lastupdate = :lastupdate WHERE {$FIX}id = :id LIMIT 1";

$values = [
    'owner' => $POST['owner'],
    'category' => $POST['category'],
    'published' => isset($_POST['published']) ? 1 : 0,
    'link' => FNC::convertStr($POST['name'], 'link'),
    'name' => strip_tags(trim($POST['name'])),
    'description' => strip_tags(trim($POST['description'])),
    'content' => FNC::convertTags($POST['content']),
    'tags' => strip_tags(trim($POST['tags'])),
    'type' => strip_tags(trim($POST['type'])),
    'phone' => strip_tags(trim($POST['phone'])),
    'cel' => strip_tags(trim($POST['cel'])),
    'email' => strip_tags(trim($POST['email'])),
    'address' => strip_tags(trim($POST['address'])),
    'coordinates' => strip_tags(trim($POST['coordinates'])),
    'partner' => isset($_POST['partner']) ? 1 : 0,
    'lastupdate' => date('Y-m-d H:i:s'),
    'id' => $POST['id'],
];

$conn = new Conn();
$conn->update(TBBUSINESS[0], $terms, $values);
echo $conn->exec() ? FNC::notify("Atualizado com sucesso.", 'success') : ERROR();
