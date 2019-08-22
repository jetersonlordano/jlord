<?php

require '../Control.inc.php';
if (!PERMISSION(9)) {die;}

header('Content-Type: application/json; charset=utf-8');
$json = file_get_contents('php://input');
$obj = json_decode($json, true);

$user = CHECKDATA(TBUSERS[0], 'user_', $obj['id']);
if (!$user) {echo ERROR();die;}
unset($user['user_password']);

if($user['user_id'] == 1 || $USERLOGGEDIN['user_accesslevel'] < $user['user_accesslevel'])
{echo FNC::notify('Você não tem permissão para excluir este usuário', 'info');die;}

function searchUser($table, $field, $userID){
    $conn = new Conn();
    $conn->select('*', $table, "WHERE {$field} = :id", ['id' => $userID]);
    $conn->exec();
    return $conn->fetchAll();
}

function dados($tb)
{return FNC::notify("Não é possível exluir! Existem {$tb} dependentes.", 'warning');}

// Posts
$checkUserPosts = defined('TBPOSTS') ? searchUser(TBPOSTS[0], 'post_author', $user['user_id']) : null;
if($checkUserPosts){echo dados('Posts'); die;}

// Cursos
$checkUserCourses = defined('TBCOURSES') ? searchUser(TBCOURSES[0], 'crs_author', $user['user_id']) : null;
if($checkUserCourses){echo dados('Cursos'); die;}

// Comentários
$checkUserCom = defined('TBCOMMENTS') ? searchUser(TBCOMMENTS[0], 'com_user', $user['user_id']) : null;
if($checkUserCom){echo dados('Comentários'); die;}

$callback = [
    'action' => 'removed',
    'element' => 'user' . $obj['id'],
    'message' => "Usuário excluido com sucesso. Atualize a página",
];
$callback = json_encode($callback);

$userLog = ($obj['id'] == $USERLOGGEDIN['user_id']);
if($userLog){session_destroy();}
$reload = json_encode(['action' => 'reload']);

$conn = new Conn();
$conn->delete(TBUSERS[0], 'WHERE user_id = :id', ['id' => $obj['id']]);
if($conn->exec())
{echo !$userLog ? $callback : $reload;}else{ERROR();}
