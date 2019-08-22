<?php
require '../Control.inc.php';
if (!PERMISSION(9)) {die;}

header('Content-Type: application/json; charset=utf-8');
$json = file_get_contents('php://input');
$obj = json_decode($json, true);

$FIX = TBBUSINESS[1];

$BUS = CHECKDATA(TBBUSINESS[0], $FIX, $obj['id']);
if (!$BUS) {echo ERROR();die;}

// Limpa diretório
$baseDir = '../../../' . PATHBUSINESS;
$baseDir = str_replace('/', DS, $baseDir);
$path = $baseDir . $BUS[$FIX . 'path'];
FNC::cleanDir($path, !0);

$callback = [
    'action' => 'removed',
    'element' => 'bus' . $obj['id'],
    'message' => "Excluido com sucesso. Atualize a página",
];

$conn = new Conn();
$conn->delete(TBBUSINESS[0], "WHERE {$FIX}id = :id", ['id' => $obj['id']]);
echo $conn->exec() ? json_encode($callback) : ERROR();
