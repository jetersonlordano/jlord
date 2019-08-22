<?php

require '../Control.inc.php';
if (!PERMISSION(8)) {die;}

// Check image
$MIDIA = $_FILES['midia'] ?? null;
if (!$MIDIA) {echo FNC::notify("Imagem não enviada! Contate o suporte.", 'danger');die;}

// Check post
$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$POST) {ERROR();die;}

// Verifica dimensões da image
if (!empty(COVERW) && !empty(COVERH)) {
    $dimensions = COVERW . 'x' . COVERH . 'px';
    $checkImg = CHECKIMAGE($MIDIA['tmp_name'], COVERW, COVERH);
    if (!$checkImg) {echo FNC::notify("Envie uma imagem com as dimensões {$dimensions}", 'info');die;}
}

// Prefixo
$FIX = TBPOSTS[1];

// Recupera dados do post
$ARTICLE = CHECKDATA(TBPOSTS[0], $FIX, $POST['id']);
if (!$ARTICLE) {echo ERROR();die;}

// Diretório base
$baseDir = '../../../' . PATHPOSTS;

// faz upload e substitui a imagem
$COVER = REPLACEIMAGE($baseDir, $ARTICLE[$FIX . 'path'], $ARTICLE[$FIX . 'cover'], $MIDIA, $ARTICLE[$FIX . 'title'], 4);

// Atualiza post na base de dados
$terms = "{$FIX}cover = :cover, {$FIX}lastupdate = :lastupdate WHERE {$FIX}id = :id LIMIT 1";
$values = ['cover' => $COVER, 'lastupdate' => date('Y-m-d H:i:s'), 'id' => $ARTICLE[$FIX . 'id']];

$callback = [
    'action' => 'imagereload',
    'imgid' => trim($POST['callback']),
    'imgsrc' => HOME . '/' . PATHPOSTS . $ARTICLE[$FIX . 'path'] . '/' . $COVER,
    'message' => "Capa foi alterada com sucesso!",
];

$conn = new Conn();
$conn->update(TBPOSTS[0], $terms, $values);
echo $conn->exec() ? json_encode($callback) : ERROR();
