<?php

require '../Control.inc.php';
if (!PERMISSION(9)) {die;}

// Verifica categoria
$catID = CHECKCATEGORIE('business', true);
if (!$catID) {echo ERROR();die;}

$FIX = TBBUSINESS[1];
$path = date('ymd') . '-' . uniqid();
$values = [
    "{$FIX}owner" => $USERLOGGEDIN[TBUSERS[1] . 'id'],
    "{$FIX}category" => $catID,
    "{$FIX}link" => $path,
    "{$FIX}path" => $path,
    "{$FIX}phone" => '(45) 99907-6777',
    "{$FIX}date" => date('Y-m-d H:i:s'),
];

$conn = new Conn();
$conn->insert(TBBUSINESS[0], $values);

header('Content-Type: application/json; charset=utf-8');
echo $conn->exec() ? json_encode(['action' => 'reload']) : ERROR();
