<?php

require '../Control.inc.php';

// Check image
$MIDIA = $_FILES['midia'] ?? null;
if (!$MIDIA || $MIDIA['error']) {echo ERROR();die;}

// Largura e altura
list($midiaW, $midiaH) = getimagesize($MIDIA['tmp_name']);

// Check post
$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$POST) {echo ERROR();die;}

// Check aspecto quadrado
if ($midiaW != $midiaH) {echo FNC::notify("Envie uma imagem com aspecto quadrado!", 'info');die;}

// Prefixo
$FIX = TBUSERS[1];

// Recupera dados do usuário
$USER = CHECKDATA(TBUSERS[0], $FIX, $USERLOGGEDIN[$FIX . 'id']);
if (!$USER) {echo ERROR();die;}

// Diretório base
$baseDir = '../../../' . PATHAUTHORS;

// faz upload e substitui a imagem
$AVATAR = REPLACEIMAGE($baseDir, null, $USER[$FIX . 'avatar'], $MIDIA, $USER[$FIX . 'name'], 2);

// Atualiza no banco
$terms = "{$FIX}avatar = :avatar, {$FIX}lastupdate = :lastupdate WHERE {$FIX}id = :id LIMIT 1";
$values = ['avatar' => $AVATAR, 'lastupdate' => date('Y-m-d H:i:s'), 'id' => $USER[$FIX . 'id']];
$conn = new Conn();
$conn->update(TBUSERS[0], $terms, $values);
if (!$conn->exec()) {echo ERROR();die;}

$callback = [
    'action' => 'imagereload',
    'imgid' => trim($POST['callback']),
    'imgsrc' => HOME . '/' . PATHAUTHORS . $AVATAR,
    'message' => "Avatar alterado com sucesso!",
];

echo json_encode($callback);

// Altera imagem na sessão
$_SESSION['admin' . SESSIONUSERID][$FIX . 'avatar'] = $values['avatar'];
