<?php

/**
 * Configurações do site 
 * @author Jeterson Lordano <jetersonlordano@gmail.com> 
 */

// Configurações do servidor 
require 'Config.inc.php';

// Informações do site
define('TITLE', 'JLord');
define('DESCRIPTION', 'Mais um site desenvolvido com JLord');
define('THEME', 'default');

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de URLs e Diretórios do projetos
define('ADD', HOME . '/' . PATHTHEMES . '/' . THEME);
define('REQ', PATHTHEMES . DS . THEME . DS);
define('TPL', REQ . 'html' . DS);

// Recursos do sistema
define('POSTS', true);
define('PAGES', true);
define('USERSONLINE', true);
define('ANALYTICS', true);
define('SEARCH', true);
define('SEO', true);

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
define('SOCIALNETWORKS', []);

// Menu principal
define('NAVIGATION', []);