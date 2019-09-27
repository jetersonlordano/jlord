<?php

define('DS', DIRECTORY_SEPARATOR);

$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$POST) {die;}

// Campos obrigatórios
$fields = ['base', 'username', 'login', 'psw', 'host', 'dbname', 'dbuser'];
foreach ($fields as $fds) {
    if (!isset($POST[$fds]) || empty($POST[$fds])) {
        die('Preencha todos os campos obrigatórios *');
    }
}

$urlBase = isset($POST['www']) ? 'www.' . trim($POST['base']) : trim($POST['base']);
$userName = trim($POST['username']);
$email = trim($POST['login']);
if (!filter_var(trim($POST['login']), FILTER_VALIDATE_EMAIL)) {
    die('email invalido!');
}
$psw = $POST['psw'];

$DBHOST = trim($POST['host']);
$DBNAME = trim($POST['dbname']);
$DBUSER = trim($POST['dbuser']);
$DBPASS = trim($POST['dbpsw']);

// Configuração da base de dados
define('DBCONFIG', [
    'host' => $DBHOST,
    'db' => $DBNAME,
    'user' => $DBUSER,
    'psw' => $DBPASS,
]);


require_once 'Database/PDOEasy.class.php';
use Database\PDOEasy;


/**
 * Listar tabelas
 */

$listTables = file_exists('sql') ? glob("sql/*.jsql", GLOB_BRACE) : [];
if (!$listTables) {die('Nenhuma tabela encontrada!');}


/**
 * Verificar tabelas que já existem no banco
 */
// $existingTables = mysql('SHOW TABLES', true);
// if ($existingTables) {
//     die('Seu banco de dados não pode conter tabelas');
// }

$conn = new PDOEasy();
$conn->query = 'SHOW TABLES';
$conn->exec();
$existingTables = $conn->fetchAll();
if ($existingTables) {
    die('Seu banco de dados não pode conter tabelas');
}


/**
 * Criar novas tabelas
 */

function createTable($index)
{
    global $listTables;

    // Nome da tabela
    $tbName = str_replace('/', DS, $listTables[$index]);
    $tbName = explode(DS, $tbName);
    $tbName = str_replace('.jsql', '', end($tbName));

    // Conteúdo SQL
    $tbContent = file_get_contents($listTables[$index]);

    $conn = new PDOEasy();
    $conn->query = $tbContent;
   
    if(!$conn->exec()){
        die('Erro ao criar tabela ' . $listTables[$index]);
    }

    if (!isset($listTables[$index + 1])) {
        insertUser();
        return false;
    }

    createTable($index + 1);

}

// Inicia a criação das tabelas
createTable(0);


/**
 * Inserir usuário principal na tabela users
 */

function insertUser()
{

    $conn = new PDOEasy();
    $conn->select('jl_users');
    $conn->exec();
    $result = $conn->fetchAll();


    if (!$result) {

        global $userName;
        global $email;
        global $psw;

        $num = 10;
        $pass = password_hash($psw, PASSWORD_DEFAULT, ['cost' => 10]);

        $insertSQL = [
            'user_accesslevel' => $num,
            'user_password' => $pass,
            'user_name' => $userName,
            'user_email' => $email,
        ];

        $conn->insert('jl_users', $insertSQL);

        if (!$conn->exec()){
            die('Erro ao inserir usuário');
        }
        configs();
    }
}

function view(array $values, string $file = null, string $strTpl = null)
{
    $file = file_exists($file) ? file_get_contents($file) : null;
    $tpl = $file ?? $strTpl;
    $links = '#' . implode('#&#', array_keys($values)) . '#';
    $keys = explode('&', $links);
    return $tpl ? str_replace($keys, array_values($values), $tpl) : null;
}

function configs()
{
    global $urlBase;
    global $DBHOST;
    global $DBNAME;
    global $DBUSER;
    global $DBPASS;

    $conf = [
        'base' => str_replace(['https://', 'http://'], '', $urlBase),
        'dbhost' => $DBHOST,
        'dbname' => $DBNAME,
        'dbuser' => $DBUSER,
        'dbpsw' => $DBPASS,
    ];
    $configContent = view($conf, 'tpl/config.txt');
    $indexContent = view([], 'tpl/index.txt');

    // Cria arquivo Config.inc.php
    @file_put_contents("../_app/Config.inc.php", $configContent);

    // Cria arquivo index.php
    if (@file_put_contents("../index.php", $indexContent)) {
        if (cleanDir('../install', true)) {
            $callback = json_encode(['fn' => 'reload', 'msg' => 'JLord Instalado com sucesso!']);
            echo $callback;
        }
    }

}

function cleanDir(string $dir, bool $delDir = false)
{
    if ((file_exists($dir) && is_dir($dir))) {
        $objects = array_diff(scandir($dir), array('.', '..'));
        foreach ($objects as $vlr) {
            $obj = $dir . DIRECTORY_SEPARATOR . $vlr; (filetype($obj) == 'dir') ? cleanDir($obj, !0) : unlink($obj);}
        reset($objects);
        if ($delDir) {rmdir($dir);}
    }
    return !0;
}