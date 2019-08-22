<?php

$keyHeader = [];
$keyHeader['btnnewtitle'] = 'NOVO USUÁRIO';
$keyHeader['btnNew'] = 'btnNewUser';
echo FNC::view($keyHeader, 'tpl' . DS . 'page_header.html');

$FIX = TBUSERS[1];
$conn = new Conn();
$conn->select('*', TBUSERS[0]);
$conn->exec();
$users = $conn->fetchAll();

echo '<div id="usersContainer" class="row">';

if ($users) {foreach ($users as $userKeys) {
    $userKeys['ADM'] = ADM;
    $userKeys['func'] = ACCESSLEVEL[$userKeys['user_accesslevel']];
    $avatar = '../' . PATHAUTHORS . $userKeys['user_avatar'];
    $userKeys[$FIX . 'avatar'] = Check::Image($avatar, AVATAR);

    echo FNC::view($userKeys, 'tpl' . DS . 'user_box.html');
}}

echo '</div>';

echo "<script async>(function(){delDataAync('usersContainer','btnDelUser','users/del_user.php');var btnNewUser,newObjUser;btnNewUser=jget('#btnNewUser');jevt(btnNewUser,'click',newCat,!0);function newCat(evt){newObjUser={inputType:'email',inputName:'email',header:'Novo Usuário',message:'Qual o e-mail do usuário?',fn:ajax,data:{file:'async/users/new_user.php', loader:'loader'}};request(newObjUser);jevt(evt.target,'click',newCat,0);setTimeout(function(){jevt(evt.target,'click',newCat,!0);},1000);}})();</script>";

