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
//echo $conn->exec() ? FNC::notify('Usuário cadastro e E-mail enviado com sucesso. ', 'success') : FNC::notify('E-mail de confirmação não enviado!', 'danger');

if ($conn->exec()) {
    $mail = new Mailer();
    $linkJ = HOME . '/admin ';
    $body = 'Você foi cadastrado no sistema ' . CMSNAME . '<br>';
    $body .= "Acesse o link <a href=\"{$linkJ}\" title=\"Acesso ao painel\">{$linkJ}</a>";
    $body .= ' para começar a usar.<br>';
    $body .= "<br><b>Usuário:</b> {$email}<br><b>Senha temporaria:</b> $password";
    $body .= "<br><br><br><span><img style=\"display:inline-block;border-radius:50%;width:50px;vertical-align: top;\" src=\"https://plus.google.com/u/0/_/focus/photos/public/AIbEiAIAAABDCJmCsuC_qIfgICILdmNhcmRfcGhvdG8qKDYxZWJkNzMwNjBlYzE0N2Q1MmNhNDg5ZjhiMDZlN2FmNjJkNDAwY2UwAcffhyFiS6OHYKba4Bpg5F2n7w8_?sz=50\"
            alt=\"Jeterson Lordano\"><span style=\"display: inline-block; margin-left: 10px; font-size: 13px;\"><b>Jeterson Lordano</b><br>Full Stack Web Developer<br></span></span>";
    $mail->email('Novo usuário', $email, 'Acesso a plataforma', $body);

    $callback = ['action' => 'reload'];
    $callback = json_encode($callback);
    echo $mail->send() ? FNC::notify('Usuário cadastro e E-mail enviado com sucesso. ', 'success') : FNC::notify('E-mail de confirmação não enviado!', 'danger');
}
