<?php

require '../Control.inc.php';
if (!PERMISSION(9)) {die;}

$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$POST) {echo ERROR();die;}

$FIX = TBUSERS[1];

// Nega permissão para altera o próprio nível
if ($USERLOGGEDIN[$FIX . 'id'] == $POST['id']) {echo FNC::notify('Você não Você não tem permissão para alterar seu próprio nível de acesso!', 'info');die;}

$conn = new Conn();
$conn->select("{$FIX}accesslevel", TBUSERS[0], "WHERE {$FIX}id = :id LIMIT 1", ['id' => $POST['id']]);
$conn->exec();
$USER = $conn->fetchAll()[0] ?? null;
if (!$USER) {echo ERROR();die;}

// Nega permissão para alterar o nível de acesso do super usuário
$accessPermissionUser = ($USER[$FIX . 'accesslevel'] > $USERLOGGEDIN[$FIX . 'accesslevel']);
if ($accessPermissionUser) {echo FNC::notify('Você não tem permissão para alterar o nível de acesso deste usuário.', 'info');die;}

// Nega permissão para nível superior
$accessPermission = ($POST['accesslevel'] > $USERLOGGEDIN[$FIX . 'accesslevel']);
if ($accessPermission) {echo FNC::notify('Você não tem permissão para realizar esta ação.', 'info');die;}

$terms = "{$FIX}accesslevel = :access WHERE {$FIX}id = :id LIMIT 1";
$values = ['access' => (int) trim($POST['accesslevel']), 'id' => $POST['id']];
$conn = new Conn();
$conn->update(TBUSERS[0], $terms, $values);
echo $conn->exec() ? FNC::notify("Nível de acesso alterado com sucesso.", 'success') : ERROR();
