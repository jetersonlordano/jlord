<?php

require '../Control.inc.php';
if (!PERMISSION(9)) {die;}

// Verifica categoria
$catID = CHECKCATEGORIE('posts', true);
if (!$catID) {echo ERROR();die;}

$FIX = TBPOSTS[1];
$path = date('ymd') . '-' . uniqid();
$values = [
    "{$FIX}author" => $USERLOGGEDIN[TBUSERS[1] . 'id'],
    "{$FIX}link" => $path,
    "{$FIX}path" => $path,
    "{$FIX}category" => $catID,
];

$conn = new Conn();
$conn->insert(TBPOSTS[0], $values);

header('Content-Type: application/json; charset=utf-8');
$callback = ['action' => 'reload'];
$callback = json_encode($callback);
echo $conn->exec() ? $callback : ERROR();
