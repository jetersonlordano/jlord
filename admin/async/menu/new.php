<?php

require '../Control.inc.php';
if (!PERMISSION(9)) {die;}

$newName = 'Nova página ' . date('d-m-y');
$value = [
    TBNAV[1] . 'name' => 'novo',
    TBNAV[1] . 'menu' => 'Novo',
    TBNAV[1] . 'title' => 'Novo item',
];

$conn = new Conn();
$conn->insert(TBNAV[0], $value);
$callback = ['action' => 'reload'];
$callback = json_encode($callback);
echo $conn->exec() ? $callback : ERROR();
