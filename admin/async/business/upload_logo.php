<?php

require '../Control.inc.php';
if (!PERMISSION(8)) {die;}

// Check image
$MIDIA = $_FILES['midia'] ?? null;
if (!$MIDIA) {echo FNC::notify("Imagem não enviada! Contate o suporte.", 'danger');die;}

// Check post
$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$POST) {ERROR();die;}

// Verifica dimensões da logo
$checkLogo = CHECKIMAGE($MIDIA['tmp_name'], 500, 500);
if (!$checkLogo) {echo FNC::notify("Carregue uma imagem quadrada com 500px", 'info');die;}

// Verifica se post existe no banco de dados
$FIX = TBBUSINESS[1];
$fields = "{$FIX}id, {$FIX}path, {$FIX}name";
$terms = "WHERE {$FIX}id = :id LIMIT 1";
$conn = new Conn();
$conn->select($fields, TBBUSINESS[0], $terms, ['id' => $POST['id']]);
$conn->exec();
$BUS = $conn->fetchAll()[0] ?? null;
if (!$BUS) {echo ERROR();die;}

// Deleta imagem atual
$basePath = '../../../' . PATHBUSINESS;
$basePath = str_replace('/', DS, $basePath);
$delImg = DELIMAGE($basePath . $BUS[$FIX . 'path'] . DS . $POST['logo']);
if (!$delImg) {echo ERROR();die;}

// Upload da imagem
$up = new Upload();
$up->baseDir = $basePath;
$up->maxSize = 3;
$upload = $up->newFile($MIDIA, $BUS[$FIX . 'path'], $BUS[$FIX . 'name']);
if (!$upload) {echo FNC::notify($up->log, 'warning');die;}

// Atualiza post na base de dados
$terms = "{$FIX}logo = :logo, {$FIX}lastupdate = :lastupdate WHERE {$FIX}id = :id LIMIT 1";
$values = ['logo' => $up->name . $up->type, 'lastupdate' => date('Y-m-d H:i:s'), 'id' => $BUS[$FIX . 'id']];

$callback = [
    'action' => 'imagereload',
    'imgid' => trim($POST['callback']),
    'imgsrc' => HOME . '/' . PATHBUSINESS . $BUS[$FIX . 'path'] . '/' . $up->name . $up->type,
    'message' => "Alterado com sucesso!",
];

$conn = new Conn();
$conn->update(TBBUSINESS[0], $terms, $values);
echo $conn->exec() ? json_encode($callback) : ERROR();
