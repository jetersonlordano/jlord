<?php

/**
 * Configurações gerais do sistema
 * @author Jeterson Lordano <jetersonlordano@gmail.com>
 */

ob_start();

// Tempo do loop do usuário online
define('USERSTIMEEXPIRE', 15);

// Tempo para expirar a sessão se não hover ação
define('SESSIONTIMEEXPIRE', 30);
session_cache_expire(SESSIONTIMEEXPIRE);
session_start();

// Sistema
define('DS', DIRECTORY_SEPARATOR);
define('CMSNAME', 'JLord');
define('TRANSFERPROTOCOL', isset($_SERVER['HTTPS']) ? 'https://' : 'http://');
define('HOME', TRANSFERPROTOCOL . 'localhost/jlord');
define('ADM', HOME . '/admin');
define('SESSIONUSERID', md5('user' . $_SERVER['HTTP_HOST'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));

// Verifica se esta no editor
define('ISEDITOR', strpos($_SERVER['REQUEST_URI'], 'admin/editor.php') !== false);

// Dados do desenvolvedor
define('DEVNAME', 'Jeterson Lordano');
define('DEVSITE', 'https://www.jetersonlordano.com.br');
define('DEVTWITTERCREATOR', '@jetersonlordano');

// Banco de dados
define("DBHOST", 'localhost');
define("DBNAME", 'jet');
define("DBUSER", 'root');
define("DBPASS", '');

// E-mail autênticado
define('SMTP', false);
define('SMTPHOST', '');
define('SMTPAUTHOR', '');
define('SMTPEMAIL', '');
define('SMTPPASSWORD', '');
define('SMTPSECURE', 'SSL');
define('SMTPPORT', '587');

// Tabelas
define('TBCATS', ['jl_categories', 'cat_']);
define('TBCONFIG', ['jl_config', 'conf_']);
define('TBDOWNS', ['jl_downloads', 'dwn_']);
define('TBLEADS', ['jl_leads', 'lead_']);
define('TBNET', ['jl_networks', 'net_']);
define('TBPAGES', ['jl_pages', 'pg_']);
define('TBPOSTS', ['jl_posts', 'post_']);
define('TBSEARCHES', ['jl_searches', 'sch_']);
define('TBSESSIONS', ['jl_sessions', 'ses_']);
define('TBUSERS', ['jl_users', 'user_']);
define('TBONLINE', ['jl_userson', 'onl_']);
define('TBVIEWS', ['jl_views', 'views_']);
define('TBNAV', ['jl_navigation', 'nav_']);

// Diretórios
define('WDGT', '_wdgt');
define('DEFAULTS', '_defaults');
define('CACHEDIR', '_cacheDir');
define('PATHTHEMES', '_themes');
define('PATHPOSTS', 'uploads/posts/');
define('PATHAUTHORS', 'uploads/authors/');
define('PATHCOVERS', 'uploads/covers/');

// Mídias default
define('AVATAR', HOME . '/' . DEFAULTS . '/images/avatar-default.svg');
define('IMAGE', HOME . '/' . DEFAULTS . '/images/image-default.svg');

// Branding
define('LOGO', HOME . '/uploads/branding/logo.png');
define('COVER', HOME . '/uploads/covers/cover-default.jpg');
define('COVERW', null);
define('COVERH', null);
define('ICONS', ['favicon.png' => 'image/png']);

// Nível de acesso dos usuários
define('ACCESSLEVEL', [
    '10' => 'Desenvolvedor',
    '9' => 'Administrador',
    '8' => 'Editor',
    '7' => 'Moderador',
    '6' => 'Analista',
    '5' => 'Cliente',
    '4' => 'Cliente',
    '3' => 'Assinante',
    '2' => 'Visitante',
    '1' => 'Visitante',
    '0' => 'Visitante',
]);

/**
 * Notificações do PHP
 * @param String $msg Texto da notificação
 * @param String $type Tipo de notificação - 'info', 'danger', 'warning' e 'success'
 */

function PHPNOTIFY(string $msg, string $type = 'info')
{echo $type . '<br>' . $msg;}

function AUTOLOAD($class)
{
    $file = __DIR__ . DS . 'Core' . DS . $class . '.class.php';
    file_exists($file) ? include_once $file : die("Erro ao incluir: {$class}.class.php");
}
spl_autoload_register('AUTOLOAD');
