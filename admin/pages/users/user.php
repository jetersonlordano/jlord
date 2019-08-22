<?php

// Nível de acesso dos usuários
$FIX = TBUSERS[1];
$conn = new Conn();
$conn->select('*', TBUSERS[0], "WHERE {$FIX}id = :id LIMIT 1", ['id' => $LINK->index[2]]);
$conn->exec();
$user = $conn->fetchAll();
$user = $user[0];
if (!$user) {die('Não encontrado!');}

$avatarUser = '../' . PATHAUTHORS . $user[$FIX . 'avatar'];
$avatarUser = Check::Image($avatarUser, AVATAR);

//echo '<div class="avatar"><img class="round" src="' . $avatarUser . '" alt="#user_name#"><div class="user_info"><h3>' . $user[$FIX . 'name'] . '</h3><span class="main">' . ACCESSLEVEL[$user[$FIX . 'accesslevel']] . '</span></div>';

echo '<div class="row"><div class="col col-4"><div class="card bg-white radius box-shadow"><div class="card-body justify-content-between align-items-center"><div class="card-title">USUÁRIO</div></div>';

echo '<div class="card-body bg-light justify-content-center">';

?>

<label class="card-avatar-top card-body-item block" for="userAvatarInput">
                        <img id="userAvatarImg" class="img round" src="<?=$USERAVATAR?>" alt="<?=$USERACTIVE[$FIX . 'avatar']?>">
                </label>
<?php

echo '<form class="form-flex radius" id="userForm" name="userForm" action="javascript:void(0);" method="post" enctype="multipart/form-data">';




// Inputs hidden
echo '<input type="hidden" name="id" value="' . $user[$FIX . 'id'] . '">';

// E-mail
echo Form::Input('email', 'email', 'E-mail', 'E-mail do usuário', null, $user[$FIX . 'email'], true, null, 'disabled');

// Telefone
echo Form::Input('cel', 'phone', 'Telefone', 'Telefone', null, $user[$FIX . 'phone'], true, null, 'disabled');

// CPF
//echo Form::Input('cpf', 'cpf', 'CPF', 'CPF', null, $user[$FIX . 'cpf'], true, null, 'disabled');

// Níveis de usuários
echo Form::Select('accesslevel', 'Nível de acesso', 'Nível de acesso', null, ACCESSLEVEL, $user[$FIX . 'accesslevel'], true);


// Salvar
echo Form::Save('Salvar', 'userFormLoader', 'userForm', true);

echo '</form></div></div></div></div>';

echo "<script async>var newObjCat={file:'async/users/update.php',loader:'userFormLoader'};submitForm(newObjCat,'userForm');</script>";
