<?php

// Header da página
$keyHeader = [];

$keyHeader['btnnewtitle'] = 'NOVO POST';
$keyHeader['btnNew'] = 'btnNewPost';
echo FNC::view($keyHeader, 'tpl' . DS . 'page_header.html');

// Verifica paginação
$pg = isset($_GET['pg']) ? $_GET['pg'] : null;
$pgn = Check::pgn($pg, 8);
$linkPgn = ADM . '/posts/&pg=';

// Ordem
$order = isset($_GET['order']) ? strtolower($_GET['order']) : 'date';
$seq = isset($_GET['seq']) ? strtoupper($_GET['seq']) : 'DESC';

// Prefixos
$FIX = TBPOSTS[1];
$FIXC = TBCATS[1];
$FIXU = TBUSERS[1];

// Consulta de post no banco de dados
$distinct = null;
$join = 'INNER JOIN ' . TBCATS[0] . " on {$FIXC}id = {$FIX}category ";
$fields = "{$FIX}id, {$FIX}published, {$FIX}author, {$FIX}link, {$FIX}path, {$FIX}title, {$FIX}category, {$FIX}cover, {$FIX}views, {$FIX}date, {$FIXC}title as catname";
$terms = "ORDER BY {$FIX}published ASC, {$FIX}{$order} {$seq}";
$values = null;

// Limite de dados
$limit = " LIMIT {$pgn['init']}, " . 8;

// Select posts
$conn = new Conn();
$conn->select($distinct . $fields, TBPOSTS[0], $join . $terms . $limit, $values);
$conn->exec();
$posts = $conn->fetchAll();

// Total de posts
$conn->select($distinct . "count({$FIX}id) as posts_total", TBPOSTS[0], $join . $terms, $values);
$conn->exec();
$totalPosts = $conn->fetchAll()[0]['posts_total'];

// Total de posts encontrados para páginação
$numPosts = count($posts);

echo '<div id="postsContainer" class="row align-items-stretch">';

if ($posts) {foreach ($posts as $postKeys) {
    $postKeys['ADM'] = ADM;
    $postKeys['edittext'] = $postKeys[$FIX . 'published'] > 0 ? 'Editar' : 'Pendente';
    $postKeys['editcolor'] = $postKeys[$FIX . 'published'] > 0 ? 'main' : 'warning';
    $postKeys[$FIX . 'date'] = date('d/m/Y', strtotime($postKeys[$FIX . 'date']));
    $cover = '../' . PATHPOSTS . $postKeys[$FIX . 'path'] . '/' . $postKeys[$FIX . 'cover'];
    $postKeys[$FIX . 'cover'] = Check::Image($cover, IMAGE);
    $postKeys[$FIX . 'link'] = HOME . '/post/' . $postKeys[$FIX . 'link'];
    $postKeys['catname'] = strtoupper($postKeys['catname']);
    echo FNC::view($postKeys, 'tpl' . DS . 'post_box.html');
}}

echo '</div>';

// Paginação
$pagination = FNC::pagination($numPosts, $totalPosts, $pgn, 10, $linkPgn);
echo FNC::view($pagination, 'tpl' . DS . 'pagination.html');

// Javascript
echo "<script async>(function() {newDataAsync('btnNewPost', 'posts/new_post.php'); delDataAync('postsContainer', 'btnDelPost', 'posts/del_post.php');})();</script>";
