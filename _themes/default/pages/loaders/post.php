<?php

/**
 * Loader Post Single
 */

if (!POSTS) {FNC::redirect(HOME);die;}

$linkSingle = $LINK->index[1] ?? null;
if (!$linkSingle || empty($linkSingle)) {FNC::redirect(HOME);die;}

$FIXPG = TBPAGES[1];
$FIX = TBPOSTS[1];
$FIXC = TBCATS[1];
$FIXU = TBUSERS[1];

$fields = TBPOSTS[0] . ".*, {$FIXC}title, {$FIXC}link, {$FIXU}name, {$FIXU}avatar, {$FIXU}lastupdate";
$terms = "INNER JOIN " . TBCATS[0] . " ON {$FIXC}id = {$FIX}category ";
$terms .= "INNER JOIN " . TBUSERS[0] . " ON {$FIXU}id = {$FIX}author ";
$terms .= "WHERE {$FIX}link LIKE :link AND ({$FIX}date < :date AND {$FIX}published = :published) ";
$terms .= "ORDER BY {$FIX}lastupdate DESC LIMIT 1";
$values = ['link' => $linkSingle . '%', 'date' => date('Y-m-d H:i:s'), 'published' => 1];

// Consulta o post no banco
$conn = new Conn();
$conn->select($fields, TBPOSTS[0], $terms, $values);
$conn->exec();
$POST = $conn->fetchAll()[0] ?? null;

// Verifica se existe post ou redireciona
if (!$POST) {FNC::redirect(HOME);die;}

// Informações da página para a header
$JPAGEINFO[$FIXPG . 'title'] = $POST[$FIX . 'title'];
$JPAGEINFO[$FIXPG . 'description'] = $POST[$FIX . 'description'];
$JPAGEINFO[$FIXPG . 'link'] = 'post/' . $POST[$FIX . 'link'];
$JPAGEINFO[$FIXPG . 'type'] = 'Article';
$JPAGEINFO[$FIXPG . 'cover'] = PATHPOSTS . $POST[$FIX . 'path'] . '/' . $POST[$FIX . 'cover'];

// Verifica se o link está correto ou redireciona
FNC::vldLink($linkSingle, $POST[$FIX . 'link'], $LINK->index[0]);

// Atualiza a view no banco de dados
FNC::updateView($POST[$FIX . 'id'], $POST[$FIX . 'views'], TBPOSTS);