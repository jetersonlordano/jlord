<?php

/**
 * Faz verificação de dados
 * @author Jeterson Lordano <jetersonlordano@gmail.com>
 */

class Check
{

    /**
     * Verifica se a imagem existe
     * @param String $url URL local da imagem - SEM HOME
     * @param String $default Imagem padrão
     * @return String Retorna URL da imagem verificada
     */
    public static function Image(string $url, string $default)
    {
        $file = str_replace('/', DS, $url);
        $file = (file_exists($file) && !is_dir($file)) ? HOME . '/' . $url : $default;
 
        $file = str_replace('../', '', $file);
        return $file;
    }

    /**
     * Verifica se a remota imagem existe
     * @param String $url URL local da imagem
     * @param String $default Imagem padrão
     * @return String Retorna URL da imagem verificada
     */
    public static function ImageRemote(string $url, string $default)
    {
        $types = ['image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png', 'image/gif', 'image/svg'];
        $headers = get_headers($url, 1);
        $answer = explode(' ', $headers[0]);
        $file = $default;
        if ($answer[1] == '200') {$file = in_array(strtolower($headers['Content-Type']), $types) ? $url : $default;}
        return $file;
    }

    /**
     * Verfica se é e-mail válido
     * @param String $email Endereço de e-mail
     * @param Boolean Filtro de dominios validos na lista interna $listDomain
     */
    public static function Email($email, $filter = false)
    {
        $listDomain = ['gmail.com', 'yahoo.com', 'yahoo.com.br', 'ymail.com', 'ymail.com.br', 'rocketmail.com', 'rocketmail.com.br', '@bol.com.br', 'hotmail.com', 'hotmail.com.br', 'live.com', 'live.com.br', 'outlook.com', 'outlook.com.br', 'msn.com', 'ig.com.br', 'globomail.com', 'glob.com', 'oi.com.br', 'pop.com.br', 'r7.com', 'folha.com.br', 'zipmail.com.br'];

        $expDomain = explode('@', $email);
        $emailDomain = strtolower(array_pop($expDomain));
        $valid = filter_var($email, FILTER_VALIDATE_EMAIL) && checkdnsrr($emailDomain, "MX");
        $valid = $filter ? ($valid && in_array($emailDomain, $listDomain)) : $valid;
        return $valid;
    }

    /**
     * Verifica o acesso do usuário no banco de dados
     * @param Int $access Nível de acesso permitido
     * @return Boolean Retorna verdadeiro ou falso de acordo com o processo
     */
    public static function UserAccess(float $access, string $section = 'home')
    {
        $token = md5(CMSNAME . $section . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        $userID = $_SESSION[$section . SESSIONUSERID]['user_id'];

        $fields = 'user_id, user_accesslevel, ses_token';
        $terms = "INNER JOIN " . TBSESSIONS[0] . " ON user_id = ses_user WHERE user_id = :id AND ses_token = :token LIMIT 1";

        $conn = new Conn();
        $conn->select($fields, TBUSERS[0], $terms, ['id' => $userID, 'token' => $token]);
        $conn->exec();
        $userResult = $conn->fetchAll();
        return $userResult ? $userResult[0]['user_accesslevel'] >= $access : false;

    }

    /**
     * Faz gestão de indices de paginação
     * @param Object $local Objeto instaciado da classe Link
     * @param Int $numChildren Número de filhos - Geralmente da constante NUMCHILDREN
     * @return Array [page] = Numero da página, [init] = Inicio do carremento no banco
     */
    public static function pgn($local, int $numChildren)
    {
        // Verifica o existe indice para páginação e controla para não dar erro
        $pgNum = (!isset($local) || $local == '' || $local < 1) ? 1 : $local;
        // Onde vai começar a busca no banco...
        $initChild = ($pgNum * $numChildren) - $numChildren;
        return array('page' => $pgNum, 'init' => $initChild);
        $local = null;
    }

    /**
     * Controla ações através de tempo
     * @param int $interval Intervalo de tempo entre as ações
     * @return boolean Retorna se a próxima ação pode ser executada
     */
    public static function TimeAction($interval = 1)
    {
        // Sessão de controle de ações
        $sessionControlName = md5('controlTimess' . CMSNAME);
        $_SESSION[$sessionControlName] = $_SESSION[$sessionControlName] ?? time() - ($interval * 2);
        $valid = (time() > $_SESSION[$sessionControlName] + $interval);
        $_SESSION[$sessionControlName] = $valid ? time() : $_SESSION[$sessionControlName];
        return $valid;
    }

    /**
     * Verificar ou cria os diretórios
     * @param String $baseDir Diretório base - padrão 'cacheDir'
     * @param String $dir Diretório onde a imagem será enviada
     * @return String Caminho do diretório
     */
    public static function Path($baseDir, $dir)
    {
        $baseDirExists = !file_exists($baseDir) ? mkdir($baseDir) : !0;
        return $baseDirExists ? FNC::createDir($baseDir, $dir) : null;
    }

}
