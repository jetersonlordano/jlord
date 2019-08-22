<?php

$FIX = TBUSERS[1];

?>

<div class="row">

    <div class="col col-4">
        <div class="card bg-white radius box-shadow">

            <div class="card-body bg-light radius justify-content-center flex-wrap">

                <label class="card-avatar-top card-body-item block" for="userAvatarInput">
                        <img id="userAvatarImg" class="img round" src="<?=$USERAVATAR?>" alt="<?=$USERACTIVE[$FIX . 'avatar']?>">
                </label>

                <form class="form-flex radius card-body-item" id="perfilEdit" name="perfilEdit" action="javascript:void(0);" method="post" enctype="multipart/form-data">
<?php

// input Avatar
echo '<input hidden type="file" id="userAvatarInput" name="avatar[]" title="Avatar" accept="image/jpg, image/jpeg, image/png" data-id="' . $USERACTIVE[$FIX . 'id'] . '">';

// Inputs hidden
echo '<input type="hidden" id="perfil' . $USERACTIVE[$FIX . 'id'] . '" name="id" value="' . $USERACTIVE[$FIX . 'id'] . '">';

// Nome
echo Form::Input('text', 'nickname', 'Usuário', 'Como você é conhecido?', null, $USERACTIVE[$FIX . 'nickname'], true, 45);

// Nome
echo Form::Input('text', 'name', 'Nome', 'Seu nome completo', null, $USERACTIVE[$FIX . 'name'], true, 45);

// Nivel de acesso
echo Form::Input('text', 'accesslevel', 'Nível de acesso', 'Nível de acesso', null, ACCESSLEVEL[$USERACTIVE[$FIX . 'accesslevel']], false, 45, 'readonly disabled');

?>

                </form>
            </div>
        </div>
    </div>

    <div class="col col-8">
        <div class="card bg-white radius box-shadow">
            <div class="card-body justify-content-between align-items-center">
                <div class="card-title">INFORMAÇÕES</div>
            </div>

            <div class="card-body bg-light radius">
                <div class="form-flex radius">
<?php

// Email
echo Form::Input('email', 'email', 'E-mail', 'Seu e-mail', 'input-width-50', $USERACTIVE[$FIX . 'email'], true, 60, 'form="perfilEdit"');

// Telefone
echo Form::Input('tel', 'phone', 'Telefone', 'Telefone', 'input-width-50', $USERACTIVE[$FIX . 'phone'], false, 45, 'form="perfilEdit"');

// RG
echo Form::Input('text', 'rg', 'RG', 'Seu RG', 'input-width-50', $USERACTIVE[$FIX . 'rg'], false, 15, 'form="perfilEdit"');

// CPF
echo Form::Input('text', 'cpf', 'CPF', 'Seu CPF', 'input-width-50', $USERACTIVE[$FIX . 'cpf'], true, 15, 'form="perfilEdit" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}"');

// Data de nascimento
echo Form::Input('date', 'dateofbirth', 'Nascimento', 'Data de nascimento', 'input-width-50', $USERACTIVE[$FIX . 'dateofbirth'], true, 10, 'form="perfilEdit"');

// Gênero sexo
echo Form::Select('gender', 'Gênero', 'Gênero (sexo)', 'input-width-50', ['F' => 'Feminino', 'M' => 'Masculino'], $USERACTIVE[$FIX . 'gender'], true, 'form="perfilEdit"');

echo Form::Textarea('address', 'Endereço', 'Endereço completo', null, $USERACTIVE[$FIX . 'address'], false, 200, 'form="perfilEdit" row="2"');

// Salvar
echo Form::Save('Salvar', 'perfilEditLoader', 'perfilEdit', false);

?>

                </div>
            </div>
        </div>
    </div>

     <div class="col">
        <div class="card bg-white radius box-shadow">

            <div class="card-body justify-content-between align-items-center">
                <div class="card-title">SENHA</div>
            </div>

            <div class="card-body bg-light radius">

                <form class="form-flex radius card-body-item" id="passEdit" name="passEdit" action="javascript:void(0);" method="post">
<?php

// Nova senha
echo Form::Input('password', 'newpass', 'Nova senha', 'Digite uma senha segura', 'input-width-50', null, false, 30, 'minlength="10"');

// Confirmação
echo Form::Input('password', 'confirmpass', 'Confirmação', 'Confirme a nova senha', 'input-width-50', null, false, 30, 'minlength="10"');

// Salvar
echo Form::Save('Salvar', 'passEditLoader', 'passEdit', false, 'id="alterUserPass"');

?>

                </form>
            </div>
        </div>
    </div>

</div>

<script async>

(function () {

    // Atualiza dados
    submitForm({file:'async/users/update_perfil.php', loader:'perfilEditLoader'},'perfilEdit');

    // Altera a senha
    var btnAlterPass= jget('#alterUserPass'),
    newPassInput = jget('#newpass'),
    confirmPassInput= jget('#confirmpass');

    function alterPass(evt) {

        request({
            inputType: 'password',
            inputName: 'password',
            header: 'Password',
            message: 'Digite sua senha atual',
            fn: ajax,
                data: {
                    file: 'async/users/update_pass.php',
                    loader:'passEditLoader',
                    id: <?=$USERACTIVE[$FIX . 'id']?>,
                    newpass: newPassInput.value,
                    confirmpass: confirmPassInput.value,
                }
            });

        jevt(evt.target, 'click', alterPass, 0);
        setTimeout(function () {jevt(evt.target, 'click', alterPass, !0);}, 1000);
    }
    jevt(btnAlterPass, 'click', alterPass, !0);

    // Upload Avatar
    jReplaceImg('userAvatarInput', 'userAvatarImg', 'async/users/upload_avatar.php', 2);

})();

</script>
