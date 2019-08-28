<?php

$keyHeader = [];
$keyHeader['btnnewtitle'] = 'NOVO ITEM';
$keyHeader['btnNew'] = 'btnNewMenu';
echo FNC::view($keyHeader, 'tpl' . DS . 'page_header.html');

// Verfica se é super usuário
$SUPERUSER = $USERACTIVE[TBUSERS[1] . 'accesslevel'] == 10;
if (!PAGES && !$SUPERUSER) {PHPNOTIFY('Você não tem permissão para editar páginas');}

$get = filter_input_array(INPUT_GET, FILTER_DEFAULT);
$numChildren = 50;

$FIX = TBNAV[1];

// Consulta de post no banco de dados
$terms = "ORDER BY {$FIX}id DESC";

$conn = new Conn();
$conn->select('*', TBNAV[0], $terms);
$conn->exec();
$menus = $conn->fetchAll();


echo '<section class="row"><div class="col"><div class="card box-shadow bg-white radius"><div class="card-body justify-content-between align-items-center"><div class="card-title">MENU DE NAVEGAÇÂO</div></div><div id="pageContainer" class="card-body">';

if ($menus) {foreach ($menus as $keysMenus) {
    $keysMenus['ADM'] = ADM;

    $keysMenus['nav_link'] = $keysMenus['nav_url'];

    if (!strstr($keysMenus['nav_url'], 'http') && !strstr($keysMenus['nav_url'], 'www')) {
        $keysMenus['nav_link'] = HOME . '/' . $keysMenus['nav_url'];
    }
    echo FNC::view($keysMenus, 'tpl' . DS . 'menu_box.html');
}}

echo '</div></div></div></section>';

// Scripts
echo "<script async>(function(){newDataAsync('btnNewMenu', 'menu/new.php');delDataAync('pageContainer', 'btnDelMenu', 'menu/del.php');})();</script>";
