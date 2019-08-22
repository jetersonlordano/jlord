<?php
require '../Control.inc.php';
if (!PERMISSION(9)) {die;}

header('Content-Type: application/json; charset=utf-8');
$json = file_get_contents('php://input');
$obj = json_decode($json, true);

$FIX = TBPOSTS[1];

$post = CHECKDATA(TBPOSTS[0], $FIX, $obj['id']);
if (!$post) {echo ERROR();die;}

// Limpa diretório
$baseDir = '../../../' . PATHPOSTS;
$baseDir = str_replace('/', DS, $baseDir);
$path = $baseDir . $post[$FIX . 'path'];
FNC::cleanDir($path, !0);

$callback = [
    'action' => 'removed',
    'element' => 'post' . $obj['id'],
    'message' => "Post excluido com sucesso. Atualize a página",
];
$callback = json_encode($callback);
$conn = new Conn();
$conn->delete(TBPOSTS[0], "WHERE {$FIX}id = :id", ['id' => $obj['id']]);
echo $conn->exec() ? $callback : ERROR();
