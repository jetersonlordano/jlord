<?php

require '../Control.inc.php';
if (!PERMISSION(8)) {die;}

$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$POST) {Error();die;}

$FIX = TBNAV[1];
$terms = "{$FIX}name = :name,  {$FIX}menu = :menu, {$FIX}title = :title,  {$FIX}url = :url, {$FIX}blank = :blank, {$FIX}order = :order WHERE {$FIX}id = :id LIMIT 1";
$values = [
    'name' => strip_tags(trim($POST['name'])),
    'menu' => strip_tags(trim($POST['menu'])),
    'title' => strip_tags(trim($POST['title'])),
    'url' => strip_tags(trim($POST['url'])),
    'blank' => strip_tags(trim($POST['blank'])),
    'order' => strip_tags(trim($POST['order'])),
    'id' => $POST['id'],
];

$conn = new Conn();
$conn->update(TBNAV[0], $terms, $values);
echo $conn->exec() ? FNC::notify("Atualizado com sucesso.", 'success') : ERROR();
