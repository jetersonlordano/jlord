<?php
/**
 * Configurações do site 
 * @author Jeterson Lordano <jetersonlordano@gmail.com> 
 */
// Configurações do servidor 
require 'Config.inc.php';

// Informações do site
define('TITLE', 'JLord');
define('DESCRIPTION', 'O meu site do mundo é o seu');
define('THEME', 'jetersonlordano');

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
define('PHONE', '(45) 99979-0215');
define('WHATSAPP', '(45) 99907-677');
define('EMAIL', 'jetersonlordano@gmail.com');

// Endereço
define('CEP', '85415-000');
define('ADDRESS', 'Cafelândia PR');
define('COORDINATES', '-24.617009, -53.321955');

// APIS
define('GOOGLEANALYTICSID', 'UA-100125286-4');
define('FACEBOOKPAGEID', '485972552196910');

// Redes sociais
define('SOCIALNETWORKS', [
    'facebook' => ['https://facebook.com', 'jetersonlordano', 'fa fa-facebook'],
    'instagram' => ['https://instagram.com', 'jetersonlordano', 'fa fa-instagram'],
    'twitter' => ['https://twitter.com', 'jetersonlordano', 'fa fa-twitter'],
    'youtube' => ['https://youtube.com', 'jetersonlordano', 'fa fa-youtube'],
    'github' => ['https://github.com', 'jetersonlordano', 'fa fa-github'],
]);

// Menu principal
define('NAVIGATION', [
    'home' => ['Página inicial', 'Página inicial', 'https://www.jetersonlordano.com.br', 0],
    'blog' => ['Blog', 'Blog', '', 0],
    'jlplayers' => ['JLPlayers', 'Player de videos', 'https://www.jetersonlordano.com.br/jlplayer/test', 1],
]);