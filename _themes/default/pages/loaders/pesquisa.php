<?php

/**
 * LOADER PESQUISA
 * Pesquisa na seção post
 */

// Termo da pesquisa
$srch = $LINK->index[1] ?? null;
if (!$srch) {FNC::redirect(HOME);die;}
if (empty($srch)) {FNC::redirect(HOME);die;}

// Prefixo dos campos
$FIX = TBPOSTS[1];
$FIXC = TBCATS[1];

// Paginação
$numChildren = 8;
$pgn = Check::pgn(0, $numChildren);

// Consulta de post no banco de dados
$fields = "DISTINCT {$FIX}id, ";
$fields = "{$FIX}id, {$FIX}link, {$FIX}path, {$FIX}title, {$FIX}description, {$FIX}category, {$FIX}cover, {$FIX}lastupdate, {$FIXC}title, {$FIXC}link";
$terms = "INNER JOIN " . TBCATS[0] . " on {$FIX}category = {$FIXC}id";
$terms .= " WHERE ({$FIX}title LIKE :srch OR {$FIX}description LIKE :srch OR {$FIX}tags LIKE :srch OR {$FIXC}title LIKE :srch) AND ";
$terms .= "({$FIX}published = :public AND {$FIX}date <= :now)";

// Ordem e Limite
$terms .= " ORDER BY {$FIX}date DESC LIMIT {$pgn['init']}, " . $numChildren;

// Valores da busca
$values = ['srch' => '%' . $srch . '%', 'public' => 1, 'now' => date('Y-m-d H:i:s')];

// Search posts
$SEARCHER = new Searcher($srch, 'posts', $fields, TBPOSTS[0], $terms, $values);
$POSTS = $SEARCHER->getData();

// Informações da página para a <head>
$FIXPG = TBPAGES[1];
$JPAGEINFO[$FIXPG . 'title'] = 'Pesquisa por ' . $srch;
$JPAGEINFO[$FIXPG . 'description'] = 'Encontre os melhores posts no blog' . TITLE;
