<?php

/**
 * Posts Loader
 * Carrega os posts da base de dados
 */

if (!POSTS) {FNC::redirect(HOME);die;}

if (isset($LINK->index[0]) && $LINK->index[0] != 'home') {

    if ($LINK->index[0] != 'categoria' && $LINK->index[0] != 'pesquisa' && $LINK->index[0] != 'tag' && !preg_match('/^[0-9]*$/', $LINK->index[0])) {
        FNC::redirect(HOME);die;
    }
}

// Paginação
$numChildren = 4;
$pgIndex = 0;
if (isset($LINK->index[0]) && ($LINK->index[0] == 'categoria' || $LINK->index[0] == 'pesquisa' || $LINK->index[0] == 'tag')) {
    $pgIndex = 2;
}

$pgn = Check::pgn($LINK->index[$pgIndex] ?? 0, $numChildren);

// Prefixo dos campos
$FIX = TBPOSTS[1];
$FIXC = TBCATS[1];

// Consulta de post no banco de dados
$fields = "{$FIX}id, {$FIX}link, {$FIX}path, {$FIX}title, {$FIX}description, {$FIX}category, {$FIX}cover, {$FIX}lastupdate, {$FIXC}title, {$FIXC}link";
$terms = "INNER JOIN " . TBCATS[0] . " on {$FIX}category = {$FIXC}id";
$terms .= " WHERE ({$FIX}published = :published AND {$FIX}date <= :now)";
$values = ['published' => 1, 'now' => date('Y-m-d H:i:s')];

if (isset($LINK->index[0]) && isset($LINK->index[1])) {

    if ($LINK->index[0] == 'categoria') {
        $terms = "INNER JOIN " . TBCATS[0] . " on {$FIX}category = {$FIXC}id";
        $terms .= " WHERE ({$FIX}published = :published AND {$FIX}date <= :now) AND {$FIXC}link = :linkcat";
        $values = ['published' => 1, 'now' => date('Y-m-d H:i:s'), 'linkcat' => strip_tags(trim($LINK->index[1]))];
    }

    if ($LINK->index[0] == 'tag') {
        
        $tag = FNC::convertStr($LINK->index[1], 'text');
        $terms = "INNER JOIN " . TBCATS[0] . " on {$FIX}category = {$FIXC}id";
        $terms .= " WHERE {$FIX}tags LIKE :tag AND ({$FIX}published = :public AND {$FIX}date <= :now)";
        $values = ['tag' => '%' . $tag . '%', 'public' => 1, 'now' => date('Y-m-d H:i:s')];
    }

    if ($LINK->index[0] == 'pesquisa') {

        // Retira caracteres especiais
        $srch = FNC::convertStr($LINK->index[1], 'text');

        $terms = "INNER JOIN " . TBCATS[0] . " on {$FIX}category = {$FIXC}id";
        $terms .= " WHERE ({$FIX}title LIKE :srch OR {$FIX}description LIKE :srch OR {$FIX}tags LIKE :srch OR {$FIXC}title LIKE :srch) AND ";
        $terms .= "({$FIX}published = :public AND {$FIX}date <= :now)";
        $values = ['srch' => '%' . $srch . '%', 'public' => 1, 'now' => date('Y-m-d H:i:s')];

        // Valores da busca
        $values = ['srch' => '%' . $srch . '%', 'public' => 1, 'now' => date('Y-m-d H:i:s')];

        // Ordem e Limite
        $limit = " ORDER BY {$FIX}date DESC LIMIT {$pgn['init']}, " . $numChildren;

        // Search posts
        $SEARCHER = new Searcher($srch, 'posts', $fields, TBPOSTS[0], $terms . $limit, $values);
        $POSTS = $SEARCHER->getData();
    }
}

// Ordem e Limite
$limit = " ORDER BY {$FIX}date DESC LIMIT {$pgn['init']}, " . $numChildren;

// Select posts
$conn = new Conn();
$conn->select($fields, TBPOSTS[0], $terms . $limit, $values);
if ($LINK->index[0] != 'pesquisa') {
    $conn->exec();
    $POSTS = $conn->fetchAll();
}