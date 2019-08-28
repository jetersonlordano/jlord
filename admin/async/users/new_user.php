<?php

require '../Control.inc.php';
if (!PERMISSION(9)) {die;}

$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$POST) {echo ERROR();die;}

$email = trim($POST['email']);
$CheckEmail = Check::email($email);
if (!$CheckEmail) {echo FNC::notify('E-mail invalido!', 'warning');die;}

$FIX = TBUSERS[1];
$conn = new Conn();
$conn->select("{$FIX}id", TBUSERS[0], "WHERE {$FIX}email = :email", ['email' => $email]);
$conn->exec();
$user = $conn->fetchAll();
if ($user) {echo FNC::notify('Usuário já cadastrado no sistema!', 'info');die;}

$JCrypt = new JCrypt();
$password = uniqid();
$hash = $JCrypt->createHash($password);
if (!$hash) {echo ERROR();die;}

// Adiciona um novo curso na base de dados
$fields = "{$FIX}accesslevel, {$FIX}accesslevel, {$FIX}email, {$FIX}password, {$FIX}date";
$values = [
    $FIX . 'nickname' => 'Bonequinho',
    $FIX . 'accesslevel' => 1,
    $FIX . 'email' => trim($POST['email']),
    $FIX . 'password' => $hash,
    $FIX . 'date' => date('Y-m-d H:i:s'),
];

$conn->insert(TBUSERS[0], $values);

if ($conn->exec()) {

    $linkJ = HOME . '/admin ';
    $body = 'Você foi cadastrado no sistema ' . CMSNAME . '<br>';
    $body .= "Acesse o link <a href=\"{$linkJ}\" title=\"Acesso ao painel\">{$linkJ}</a>";
    $body .= ' para começar a usar.<br>';
    $body .= "<br><b>Usuário:</b> {$email}<br><b>Senha temporaria:</b> $password";

    $emailSend = CMSNAME . " <" . EMAIL . ">";
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1;' . "\r\n";
    $headers .= "Return-Path: $emailSend \r\n";
    $headers .= "From: $emailSend \r\n";
    $headers .= "Reply-To: $emailSend \r\n";

    $sendMail = @mail($email, 'Acesso a plataforma', nl2br($body), $headers);
   
    $callback = ['action' => 'reload'];
    $callback = json_encode($callback);
    echo $sendMail ? FNC::notify('Usuário cadastro e E-mail enviado com sucesso. ', 'success') : FNC::notify('E-mail de confirmação não enviado!', 'danger');
}
