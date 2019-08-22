<?php

require '../Control.inc.php';
if (!PERMISSION(9)) {die;}

header('Content-Type: application/json; charset=utf-8');
$json = file_get_contents('php://input');
$obj = json_decode($json, true);

$page = CHECKDATA(TBPAGES[0], TBPAGES[1], $obj['id']);
if (!$page) {echo ERROR();die;}

$callback = [
    'action' => 'removed',
    'element' => 'page' . $obj['id'],
    'message' => "Página excluida com sucesso. Atualize a página",
];
$callback = json_encode($callback);
$conn = new Conn();
$conn->delete(TBPAGES[0], 'WHERE ' . TBPAGES[1] . 'id = :id', ['id' => $obj['id']]);
echo $conn->exec() ? $callback : ERROR();
