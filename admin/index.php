<?php

require '../_app/Client.inc.php';

// Controle de Sessão
$SESSION = new Session('admin', ADM . '/', ADM . '/login.php');
$LINK = new Link('', 'pages', true);

// Dados do usuários
$USERACTIVE = $SESSION->getData();
$FIRSTUSERNAME = $USERACTIVE['user_nickname'];
$LINKPERFIL = ADM . '/usuarios/perfil/' . $USERACTIVE['user_id'];
$USERAVATAR = '../' . PATHAUTHORS . $USERACTIVE['user_avatar'];
$USERAVATAR = Check::Image($USERAVATAR, AVATAR);

// Init head
echo '<!DOCTYPE html><html lang="pt_BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0" /><meta http-equiv="X-UA-Compatible" content="IE=edge"><title>' . CMSNAME . '</title><base id="urlHome" href="' . HOME . '" data-dir="admin">';

// ICONES
if (ICONS) {foreach (ICONS as $icon => $typeico) {
    $linkIco = HOME . '/uploads/branding/' . $icon;
    echo "<link rel=\"shortcut icon\" type=\"{$typeico}\" href=\"{$linkIco}\"/>";
}}

// Google fonts
echo '<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">';

// CSS
echo '<link rel="stylesheet" href="' . ADM . '/assets/styles/boot.css">';
echo '<link rel="stylesheet" href="' . ADM . '/assets/styles/forms.css">';
echo '<link rel="stylesheet" href="' . ADM . '/assets/styles/elements.css">';
echo '<link rel="stylesheet" href="' . ADM . '/assets/styles/style.css">';

// CSS Editor
echo '<link rel="stylesheet" href="' . HOME . '/' . WDGT . '/editor/css/medium-editor.css">';

// FONT ICONS
echo '<link rel="stylesheet" href="' . HOME . '/' . WDGT . '/fonts/icons/css/font-awesome.css">';

// Ajax
echo '<script src="' . HOME . '/' . WDGT . '/scripts/ajax.js"></script>';

/**
 * Body
 */

echo '</head><body><main class="wrapper main_wrapper">';

// Seções do painel
$JSECTIONS = [
    '' => 'DASHBOARD',
    'home' => 'DASHBOARD',
    'cats' => 'CATEGORIAS',
    'pgs' => 'PÁGINAS',
    'posts' => 'POSTS',
    'business' => 'EMPRESAS',
    'analytics' => 'RELATÓRIOS',
    'searches' => 'PESQUISAS',
    'comments' => 'COMENTÁRIOS',
    'users' => 'USUÁRIOS',
    'system' => 'SISTEMA',
    'perfil' => 'PERFIL',
];

// Topbar
$textTopBar = $JSECTIONS[$LINK->index[0]];
$linkTopBar = ADM . ($LINK->index[0] != 'home' ? '/' . $LINK->index[0] : null);

?>

<div id="topbar" class="container">
    <div class="row">
        <div class="col flex justify-content-between">
            <div class="item topbar_title_page">
                <a href="<?=$linkTopBar?>" title="Painel de controle"><?=$textTopBar?></a>
            </div>
            <div class="item">
                <div id="navIcon"><span></span><span></span><span></span></div>
            </div>
            <div class="topbar_logo item">
                <span class="logo"><?=CMSNAME?></span>
            </div>
            <div class="item">
                <div id="notifyIcon">
                    <span class="icon fa fa-bell">
                        <b class="bg-red round white none">0</b>
                    </span>
                </div>
                <div class="topbar_user">

                    <div id="userTopbar" class="block">
                        <div class="topbar_avatar round">
                            <img class="img round" src="<?=$USERAVATAR?>" alt="<?=$FIRSTUSERNAME?>">
                        </div>
                        <span><?=$FIRSTUSERNAME?></span>
                    </div>

                    <div id="navUser" data-expanded="false">
                        <ul class="bg-white radius box-shadow">
                            <li class="item">
                                <a href="<?=ADM?>/perfil" title="Meu perfil"><i class="fa fa-user"></i>Meu perfil</a>
                            </li>
                            <li class="item">
                                <a href="<?=ADM?>/system" title="Configurações"><i class="fa fa-cog"></i>Configurações</a>
                            </li>
                            <li class="item">
                                <a href="https://www.jetersonlordano.com.br" target="_blank"><i class="fa fa-life-ring"></i>Suporte</a>
                            </li>
                            <li class="item">
                                <a href="<?=ADM?>/logout.php"><i class="fa fa-sign-out"></i> Desconectar</a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<?php

/**
 * Conteúdo
 */

echo '<div class="container">';
require $LINK->file;
echo '</div>';

// Footer
echo '<div class="container"><div class="row main_footer justify-content-between"><div class="col flex flex-wrap"><p>© ' . date('Y') . ' <a class="main" href="' . HOME . '/admin" title="Gerenciador de conteúdo ' . CMSNAME . '"><b>' . CMSNAME . '</b></a></p><p>Orgulhosamente desenvolvido por <a class="main" href="' . DEVSITE . '" rel="author" title="' . DEVNAME . ' Web Developer" target="_blank"><b>' . DEVNAME . '</b></a></p></div></div></div>';

echo '</main>';

// Sidebar
require 'sidebar.php';

// Overlay
echo '<div id="overlay" data-visible="false"></div>';

// Loader
echo '<div id="loader" class="loader-container"><div class="loader-box"><div class="gif-loader"><div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div></div>';

// Callbacks
echo '<div id="return"></div><div id="notify"></div>';

// Scripts
echo '<script async src="' . HOME . '/' . WDGT . '/scripts/main.js">';
echo '</script><script async src="' . ADM . '/assets/scripts/dashboard.js"></script>';

// Fim
echo '</body></html>';
ob_end_flush();
