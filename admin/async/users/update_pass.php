<?php

require '../Control.inc.php';

// Dados do formulário
$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$POST) {echo ERROR();die;}

$FIX = TBUSERS[1];

// Proteje para não alterar a senha de outro usuário
if ($USERLOGGEDIN[$FIX . 'id'] != $POST['id']) {echo ERROR();die;}

// Verifica se os campos foram preenchidos
if (empty($POST['newpass']) || empty($POST['confirmpass'])) {echo FNC::notify('Por favor! Preencha os campos.', 'info');die;}

// Verifica se os campos correspondem.
if ($POST['newpass'] != $POST['confirmpass']) {echo FNC::notify("Os campos 'Nova senha' e 'Confirmação' são diferentes.", 'info');die;}

$terms = "WHERE {$FIX}id = :id";
$values = ['id' => $USERLOGGEDIN[$FIX . 'id']];
$conn = new Conn();
$conn->select($FIX . 'password', TBUSERS[0], $terms, $values);
$conn->exec();
$user = $conn->fetchAll()[0] ?? null;
if (!$user) {echo ERROR();die;}

// Verifica senha atual
$crypt = new JCrypt();
$checkPass = $crypt->checkHash($POST['password'], $user[$FIX . 'password']);
if (!$checkPass) {echo FNC::notify('A senha atual incorreta!', 'warning');die;}

// Cria um hash da nova senha
$hash = $crypt->createHash($POST['newpass']);

$terms = "{$FIX}password = :pass WHERE {$FIX}id = :id";
$values = ['pass' => $hash, 'id' => $USERLOGGEDIN[$FIX . 'id']];
$conn->update(TBUSERS[0], $terms, $values);
if (!$conn->exec()) {echo ERROR();die;}

echo FNC::notify('Senha altera com sucesso!', 'success');
session_destroy();
