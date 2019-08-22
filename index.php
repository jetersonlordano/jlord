<?php

require_once '_app/Client.inc.php';

$LINK = new Link();
$PAGES = new Pages($LINK);
$VIEWS = new Views($LINK);

// Carrega conteúdo
foreach ($PAGES->files as $pgfile) {
    if ($pgfile) {
        require_once $pgfile;
    }
}
ob_end_flush();
