<?php
require '../_app/Client.inc.php';
$login = new Session('admin', ADM . '/', ADM . '/login.php');
?>
<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <link rel="shortcut icon" type="image/x-icon" href="<?=HOME?>/uploads/standard/favicon.ico">
        <link rel="icon" type="image/png" href="<?=HOME?>/uploads/standard/favicon.png">

        <title>Bem-vindo ao <?=CMSNAME?></title>

        <meta name="googlebot" content="noindex">
        <meta name="googlebot" content="nofollow">
        <meta name="robots" content="noindex">
        <meta name="robots" content="nofollow">

        <!-- Base -->
        <base id="urlHome" href="<?=HOME?>" data-dir="admin">

        <link rel="stylesheet" href="<?=ADM?>/assets/styles/boot.css">
        <link rel="stylesheet" href="<?=ADM?>/assets/styles/elements.css">
        <link rel="stylesheet" href="<?=ADM?>/assets/styles/forms.css">
        <link rel="stylesheet" href="<?=ADM?>/assets/styles/login.css">
        <link rel="stylesheet" href="<?=HOME . '/' . WDGT?>/fonts/icons/css/font-awesome.css">

        <!-- Font web -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">

        <!-- Class Ajax -->
        <script src="<?=HOME . '/' . WDGT?>/scripts/ajax.js"></script>
    </head>

    <body>
        <div class="wrapper main_login bg-blue-dark">
            <div class="container">
                <div class="row flex-direction-column justify-content-around align-items-center">

                    <div class="header_login">
                        <span class="header_title">Bem vindo(a) ao <?= CMSNAME ?>!</span>
                        <p class="gray">Use esse formulários para fazer login ou contate o desenvolvedor para criar uma conta e começar a gerenciar o sistema.</p>
                    </div>

                    <div class="login_box">
                        <div class="card bg-light radius box-shadow">
                            <div class="card-body">
                                <span class="form_login_title gray">Entre com suas credenciais</span>
                                <form class="form-flex radius" id="formLogin" name="formLogin" action="javascript:void(0);" method="post">

                                    <div class="input-field input-icon">
                                        <input type="email" id="email" name="email" title="Email de usuário" placeholder="Email" maxlength="80" required>
                                        <label for="email" class="icon fa fa-envelope"></label>
                                    </div>

                                    <div class="input-field input-icon">
                                        <input type="password" id="password" name="password" title="Senha de usuário" placeholder="Senha" maxlength="30" required>
                                        <label for="password" class="icon fa fa-unlock-alt"></label>
                                    </div>

                                    <div class="input-field-inline">
                                        <div class="checkbox">
                                            <input id="keep" type="checkbox" name="keep">
                                            <label for="keep"><span class="fa fa-check"></span></label>
                                        </div>
                                        <label class="label" for="keep">Manter-me conectado</label>
                                    </div>

                                    <div class="block width-100"></div>

                                    <button name="submit-login" type="submit" class="btn btn-main btn-form box-shadow-alt">
                                        <i class="fa fa-sign-in"></i> Entrar
                                        <span id="loaderLogin" class="btn-form-loader">
                                            <i class="fa fa-spinner fa-pulse"></i>
                                        </span>
                                    </button>

                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="footer_login gray center">
                        <p>© <?= date('Y') ?> <a class="main" href="<?= HOME ?>/admin" title="Gerenciador de conteúdo JControl"><b><?= CMSNAME ?></b></a></p>
                        <p>Orgulhosamente desenvolvido por <a class="main" href="<?= DEVSITE ?>" rel="author" title="<?= DEVNAME ?> Web Developer"><?= DEVNAME ?></a></p>
                    </div>


                </div>
            </div>
        </div>

        <!-- Overlay -->
        <div id="overlay" data-visible="false"></div>

        <!-- Loader -->
        <div id="loader" class="loader-container"><div class="loader-box"><div class="gif-loader"><div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div></div>

        <!-- Return -->
        <div id="return"></div><div id="notify"></div>

        <!-- Scripts -->
        <script src="<?=HOME . '/' . WDGT?>/scripts/main.js" async></script>
        <script async>submitForm({file: 'async/login.php'}, 'formLogin');</script>
    </body>
</html>
<?php ob_end_flush();