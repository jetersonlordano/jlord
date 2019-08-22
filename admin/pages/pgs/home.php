<?php

$keyHeader = [];
$keyHeader['btnnewtitle'] = 'NOVA PÁGINA';
$keyHeader['btnNew'] = 'btnNewPage';
echo FNC::view($keyHeader, 'tpl' . DS . 'page_header.html');

// Verfica se é super usuário
$SUPERUSER = $USERACTIVE[TBUSERS[1] . 'accesslevel'] == 10;
if (!PAGES && !$SUPERUSER) {PHPNOTIFY('Você não tem permissão para editar páginas');}

$get = filter_input_array(INPUT_GET, FILTER_DEFAULT);
$numChildren = 10;

// Paginação
$pg = isset($get['pg']) ? $get['pg'] : null;
$pgn = Check::pgn($pg, $numChildren);
$linkPgn = ADM . '/pgs/&pg=';

$FIX = TBPAGES[1];

// Consulta de post no banco de dados
$distinct = null;
$fields = '*';
$terms = "ORDER BY {$FIX}lastupdate DESC";
$values = null;

$conn = new Conn();
$conn->select($fields, TBPAGES[0], $terms);
$conn->exec();
$paginas = $conn->fetchAll();

// Total de posts encontrados para páginação
$totalPgs = count($paginas);

// Total de dados
$conn->select($distinct . "count({$FIX}id) as {$FIX}total", TBPAGES[0], $terms, $values);
$conn->exec();
$totalData = $conn->fetchAll()[0][$FIX . 'total'];

echo '<section class="row"><div class="col"><div class="card box-shadow bg-white radius"><div class="card-body justify-content-between align-items-center"><div class="card-title">PÁGINAS</div></div><div id="pageContainer" class="card-body">';

if ($paginas) {foreach ($paginas as $keysPgs) {
    $keysPgs['ADM'] = ADM;
    $keysPgs[$FIX . 'date'] = date('d/m/Y', strtotime($keysPgs[$FIX . 'date']));
    echo FNC::view($keysPgs, 'tpl' . DS . 'pgs_box.html');
}}

echo '</div></div></div></section>';

// Paginação
$pagination = FNC::pagination($totalPgs, $totalData, $pgn, $numChildren, $linkPgn);
echo FNC::view($pagination, 'tpl' . DS . 'pagination.html');

// Scripts
echo "<script async>(function(){newDataAsync('btnNewPage', 'pgs/new_page.php');delDataAync('pageContainer', 'btnDelPg', 'pgs/del_page.php');})();</script>";
