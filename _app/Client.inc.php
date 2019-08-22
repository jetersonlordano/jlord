<?php

 * Configurações do site 
 * @author Jeterson Lordano <jetersonlordano@gmail.com> 
 */

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
define('POSTS', false);
define('PAGES', false);
define('USERSONLINE', false);
define('ANALYTICS', false);
define('SEARCH', false);
define('SEO', false);

// Contatos
define('PHONE', '');
define('WHATSAPP', '');
define('EMAIL', '');

// Endereço
define('CEP', '');
define('ADDRESS', '');
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