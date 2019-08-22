<?php

/**
 * Posts Loader
 * Carrega os posts da base de dados
 */

if (!POSTS) {FNC::redirect(HOME);die;}

$cat = $LINK->index[1] ?? null;
$cat = $cat ? strip_tags(trim($cat)) : null;

// Prefixo dos campos
$FIX = TBPOSTS[1];
$FIXC = TBCATS[1];

// Paginação
$numChildren = 8;
$pgn = Check::pgn(0, $numChildren);

// Consulta de post no banco de dados
$distinct = null;
$fields = "{$FIX}id, {$FIX}link, {$FIX}path, {$FIX}title, {$FIX}description, {$FIX}category, {$FIX}cover, {$FIX}lastupdate, {$FIXC}title, {$FIXC}link";
$terms = "INNER JOIN " . TBCATS[0] . " on {$FIX}category = {$FIXC}id";
$terms .= " WHERE ({$FIX}published = :published AND {$FIX}date <= :now)";
$values = ['published' => 1, 'now' => date('Y-m-d H:i:s')];

// Por Categorias
if ($cat) {
    $terms = "INNER JOIN " . TBCATS[0] . " on {$FIX}category = {$FIXC}id";
    $terms .= " WHERE ({$FIX}published = :published AND {$FIX}date <= :now) AND {$FIXC}link = :linkcat";
    $values['linkcat'] = $cat;
}

// Ordem e Limite
$limit = " ORDER BY {$FIX}date DESC LIMIT {$pgn['init']}, " . $numChildren;

// Select posts
$conn = new Conn();
$conn->select($distinct . $fields, TBPOSTS[0], $terms . $limit, $values);
$conn->exec();
$POSTS = $conn->fetchAll();
