<?php

require '../Control.inc.php';
if (!PERMISSION(9)) {die;}

$newName = 'Nova página ' . date('d-m-y');
$value = [
    TBPAGES[1] . 'name' => $newName,
    TBPAGES[1] . 'title' => $newName,
    TBPAGES[1] . 'link' => FNC::convertStr($newName),
    TBPAGES[1] . 'theme' => THEME,
];

$conn = new Conn();
$conn->insert(TBPAGES[0], $value);
$callback = ['action' => 'reload'];
$callback = json_encode($callback);
echo $conn->exec() ? $callback : ERROR();
