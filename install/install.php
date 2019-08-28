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

/**
 * Listar tabelas
 */

$listTables = file_exists('sql') ? glob("sql/*.jsql", GLOB_BRACE) : [];
if (!$listTables) {die('Nenhuma tabela encontrada!');}

/**
 * Conexão com banco de dados
 */

$dsn = 'mysql:host=' . $DBHOST . ';dbname=' . $DBNAME;
$PDOOptions = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];

try {
    $PDO = new PDO($dsn, $DBUSER, $DBPASS, $PDOOptions);
} catch (PDOException $erro) {
    die('Não foi possível se conectar com o banco de dados');
}

$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/**
 * Função genérica MYSQL
 */

function mysql(string $sql, bool $return = false)
{
    try {

        global $PDO;
        $statement = $PDO->prepare($sql);
        $statement->execute();
        return $return ? $statement->fetchAll(PDO::FETCH_NUM) : true;

    } catch (PDOException $e) {

        echo $e->getMessage();
        die;

    }
}

/**
 * Verificar tabelas que já existem no banco
 */

$existingTables = mysql('SHOW TABLES', true);
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

    if (mysql($tbContent)) {

        if (!isset($listTables[$index + 1])) {
            insertUser();
            return false;
        }

        createTable($index + 1);

    } else {die('Erro ao criar tabela ' . $listTables[$index]);}

}

// Inicia a criação das tabelas
createTable(0);

/**
 * Inserir usuário principal na tabela users
 */

function insertUser()
{

    $result = mysql('select * from jl_users', true);

    if (!$result) {

        global $PDO;
        global $userName;
        global $email;
        global $psw;

        $dados = $PDO->prepare('INSERT INTO jl_users (user_accesslevel, user_password, user_name, user_email) VALUES (:accesslevel, :pass, :nome, :email)');

        $num = 10;
        $pass = password_hash($psw, PASSWORD_DEFAULT, ['cost' => 10]);

        $dados->bindParam(':accesslevel', $num, PDO::PARAM_STR);
        $dados->bindParam(':pass', $pass, PDO::PARAM_STR);
        $dados->bindParam(":nome", $userName, PDO::PARAM_STR);
        $dados->bindParam(":email", $email, PDO::PARAM_STR);

        $exec = $dados->execute();

        if (!$exec) {
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
