<?php

$keyHeader = [];
$keyHeader['btnnewtitle'] = 'NOVA CATEGORIA';
$keyHeader['btnNew'] = 'btnNewCat';
echo FNC::view($keyHeader, 'tpl' . DS . 'page_header.html');

// Seções com categórios
$SECTIONS = [];

// Arquivos: pages/cats/home, pages/cats/cat, async/cats/del
if (POSTS) {$SECTIONS['posts'] = ['Posts', TBPOSTS];}

$get = filter_input_array(INPUT_GET, FILTER_DEFAULT);
$numChildren = 10;

// Paginação
$pg = isset($get['pg']) ? $get['pg'] : null;
$pgn = Check::pgn($pg, $numChildren);
$linkPgn = ADM . '/cats/&pg=';

// Prefixos
$FIX = TBCATS[1];

// Ordem
$order = isset($get['order']) ? strtolower($get['order']) : 'date';
$seq = isset($get['seq']) ? strtoupper($get['seq']) : 'DESC';

// Consulta de post no banco de dados
$terms = "ORDER BY {$FIX}{$order} {$seq}";
$values = null;

// Limite de dados
$limit = " LIMIT {$pgn['init']}, {$numChildren}";

// Select
$conn = new Conn();
$conn->select('*', TBCATS[0], $terms . $limit, $values);
$conn->exec();
$cats = $conn->fetchAll();

// Total de posts encontrados para páginação
$catsTotal = count($cats);

// Total de dados
$conn->select("count({$FIX}id) as {$FIX}total", TBCATS[0], $terms, $values);
$conn->exec();
$totalData = $conn->fetchAll()[0][$FIX . 'total'];

echo '<div class="row"><div class="col"><div class="card box-shadow bg-white radius"><div class="card-body justify-content-between align-items-center"><div class="card-title">CATEGORIAS</div></div><div id="catContainer" class="card-body">';

if ($cats) {foreach ($cats as $keysCats) {
    array_push($keysCats, 'adm');
    $keysCats['ADM'] = ADM;
    $keysCats[$FIX . 'icon'] = $keysCats[$FIX . 'icon'] ?? 'fa fa-filter';
    $keysCats[$FIX . 'icon'] = strstr($keysCats[$FIX . 'icon'], 'fa fa-') ? $keysCats[$FIX . 'icon'] : 'fa fa-filter';
    $keysCats[$FIX . 'section'] = strtoupper($keysCats[$FIX . 'section']);
    $keysCats[$FIX . 'date'] = date('d/m/Y', strtotime($keysCats[$FIX . 'date']));
    echo FNC::view($keysCats, 'tpl' . DS . 'cat_box.html');
}}

echo '</div></div></div></div>';

// Paginação
$pagination = FNC::pagination($catsTotal, $totalData, $pgn, $numChildren, $linkPgn);
echo FNC::view($pagination, 'tpl' . DS . 'pagination.html');

$newSections = [];
foreach ($SECTIONS as $key => $value) {
    $newSections[$key] = $value[0];
    if ($value == end($SECTIONS)) {$callback = json_encode($newSections);}
}

?>
<script async>
    var btnNewCat, newObjCat, optionsObj;
    optionsObj = <?=$callback?>;
   btnNewCat = jget('#btnNewCat');
   jevt(btnNewCat, 'click', newCat, !0);
    function newCat(evt) {
        newObjCat = {
            inputType: 'select',
            inputOptions: optionsObj,
            inputName: 'section',
            header: 'Nova categoria',
            message: 'Escolha uma seção no campo abaixo',
            fn: ajax,
            data: {
                file: 'async/cats/new_cat.php'
            }
        };
        request(newObjCat);

        jevt(evt.target, 'click', newCat, 0);
        setTimeout(function () {
            jevt(evt.target, 'click', newCat, !0);
        }, 1000);
    }

    delDataAync('catContainer', 'btnDelCat', 'cats/del_cat.php');
</script>