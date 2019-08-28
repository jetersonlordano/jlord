<?php

require '../Control.inc.php';
if (!PERMISSION(9)) {die;}

header('Content-Type: application/json; charset=utf-8');
$json = file_get_contents('php://input');
$obj = json_decode($json, true);

$nav = CHECKDATA(TBNAV[0], TBNAV[1], $obj['id']);
if (!$nav) {echo ERROR();die;}

$callback = [
    'action' => 'removed',
    'element' => 'nav' . $obj['id'],
    'message' => "Excluido com sucesso. Atualize a página",
];
$callback = json_encode($callback);
$conn = new Conn();
$conn->delete(TBNAV[0], 'WHERE ' . TBNAV[1] . 'id = :id', ['id' => $obj['id']]);
echo $conn->exec() ? $callback : ERROR();
