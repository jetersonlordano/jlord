<?php
/**
 * Configurações do site 
 * @author Jeterson Lordano <jetersonlordano@gmail.com> 
 */
// Configurações do servidor 
require 'Config.inc.php';

// Informações do site
define('TITLE', 'JLord Beta');
define('DESCRIPTION', 'Mais um site desenvolvido com JLord');
define('THEME', 'default');

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de URLs e Diretórios do projetos
define('ADD', HOME . '/' . PATHTHEMES . '/' . THEME);
define('REQ', PATHTHEMES . DS . THEME . DS);
define('TPL', REQ . 'tpl' . DS);

// Recursos do sistema
define('POSTS', true);
define('PAGES', true);
define('USERSONLINE', true);
define('ANALYTICS', true);
define('SEARCH', true);
define('SEO', true);

// Contatos
define('PHONE', '(99) 99999-9999');
define('WHATSAPP', '(99) 99999-9999');
define('EMAIL', 'jetersonlordano@gmail.com');

// Endereço
define('CEP', '85415-000');
define('ADDRESS', 'Cafelândia PR');
define('COORDINATES', '');

// APIS
define('GOOGLEANALYTICSID', '');
define('FACEBOOKPAGEID', '');

// Redes sociais
define('SOCIALNETWORKS', [
]);

// Menu principal
define('NAVIGATION', [
]);