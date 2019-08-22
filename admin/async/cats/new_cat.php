<?php

header('Content-Type: application/json; charset=utf-8');
require '../Control.inc.php';
if (!PERMISSION(9)) {die;}
$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$post) {ERROR();die;}
$FIX = TBCATS[1];
$values = ["{$FIX}section" => $post['section'], "{$FIX}link" => uniqid()];

// Cria uma nova categoria
$conn = new Conn();
$conn->insert(TBCATS[0], $values);
$callback = json_encode(['action' => 'reload']);
echo $conn->exec() ? $callback : ERROR();