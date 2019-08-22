<?php

require '../Control.inc.php';

$avatar = null;
$baseDir = '../../../' . PATHAUTHORS;
$baseDir = str_replace('/', DS, $baseDir);

$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$POST) {echo ERROR();die;}

$FIX = TBUSERS[1];

// Evitar que altere o perfil de outro usuário
if ($USERLOGGEDIN[$FIX . 'id'] != $POST['id']) {echo ERROR();die;}


$terms = "{$FIX}nickname = :nickname, {$FIX}name = :name, {$FIX}email = :email, {$FIX}phone = :phone, {$FIX}rg = :rg, {$FIX}cpf = :cpf, {$FIX}dateofbirth = :dateofbirth, {$FIX}gender = :gender, {$FIX}address = :address WHERE {$FIX}id = :id LIMIT 1";

$values = [
    'nickname' => trim($POST['nickname']),
    'name' => trim($POST['name']),
    'email' => trim($POST['email']),
    'phone' => $POST['phone'],
    'rg' => trim($POST['rg']),
    'cpf' => trim($POST['cpf']),
    'dateofbirth' => $POST['dateofbirth'],
    'gender' => $POST['gender'],
    'address' => trim($POST['address']),
    'id' => $USERLOGGEDIN[$FIX . 'id'],
];


$_SESSION['admin' . SESSIONUSERID][$FIX . 'nickname'] = $values['nickname'];
$_SESSION['admin' . SESSIONUSERID][$FIX . 'name'] = $values['name'];
$_SESSION['admin' . SESSIONUSERID][$FIX . 'email'] = $values['email'];
$_SESSION['admin' . SESSIONUSERID][$FIX . 'phone'] = $values['phone'];
$_SESSION['admin' . SESSIONUSERID][$FIX . 'rg'] = $values['rg'];
$_SESSION['admin' . SESSIONUSERID][$FIX . 'cpf'] = $values['cpf'];
$_SESSION['admin' . SESSIONUSERID][$FIX . 'dateofbirth'] = $values['dateofbirth'];
$_SESSION['admin' . SESSIONUSERID][$FIX . 'gender'] = $values['gender'];
$_SESSION['admin' . SESSIONUSERID][$FIX . 'address'] = $values['address'];

$conn = new Conn();
$conn->update(TBUSERS[0], $terms, $values);
echo $conn->exec() ? FNC::notify('Perfil atualizado com sucesso.', 'success') : ERROR();
