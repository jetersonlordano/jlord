<?php

/**
 * Gerencia sessões do sistema e seções de acesso restrito
 * @author Jeterson Lordano <jetersonlordano@gmail.com>
 */

class Session
{
    /**
     * Log do sistema
     */
    public $log;

    /**
     * Dados da Seção
     */
    private $home;
    private $pageLogin;
    private $section;
    private $token;
    private $sessionData;
    private $urlAtual;
    private $minLevel;

    /**
     * @param String $section Identificação da seção do sistema
     * @param String $urlHome URL da página home da seção
     * @param String $urlLogin URL da página de login
     */
    public function __construct($section = 'home', $urlHome = null, $urlLogin = null, $minLevel = 0)
    {

        $this->home = (string) ($urlHome != null ? $urlHome : HOME);
        $this->pageLogin = (string) ($urlLogin != null ? $urlLogin : HOME . '/login.php');
        $this->section = (string) strtolower(strip_tags(trim($section)));
        $this->minLevel = (int) $minLevel;

        // ID da sessão
        $this->token = md5(CMSNAME . $this->section . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);

        // Pega a url atual
        //$this->urlAtual = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $this->urlAtual = isset($_SERVER['HTTPS']) ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        // Verifica se a sessão existe ou redireciona para login
        if (isset($_SESSION[$this->token])) {$this->checkSession();} else { $this->redirectPageLogin();}
    }

    /**
     * Recupera os dados da sessão atual
     * @return Array
     */
    public function getData()
    {return $_SESSION[$this->section . SESSIONUSERID] ?? null;}

    /**
     * Verifica se existe sessão no banco de dados
     * Verifica se sessão expirou
     * Verifica se o IP está bloqueado
     */
    private function checkSession()
    {
        // Consulta a sessão no banco de dados
        $terms = "WHERE ses_token = :token AND ses_section = :section AND ses_ip = :ip LIMIT 1";
        $values = ['token' => $this->token, 'section' => $this->section, 'ip' => $_SERVER['REMOTE_ADDR']];

        $conn = new Conn();
        $conn->select('*', TBSESSIONS[0], $terms, $values);
        $conn->exec();

        $result = $conn->fetchAll();
        if (!$result) {$this->redirectPageLogin();}
        $this->sessionData = $result;

        if ($result) {

            // Verifica se o IP está bloqueado
            if ($result[0]['ses_locked'] > 0) {

                // Verfica se ja passou o tempo de bloqueio
                if (time() > $result[0]['ses_expire']) {

                    // Deleta a sessão quando passar o tempo de bloqueio
                    $conn->delete(TBSESSIONS[0], "WHERE ses_token = :token LIMIT 1", ['token' => $result[0]['ses_token']]);
                    $conn->exec();
                    $this->destroySession();

                } else { $this->log = 'IP Bloqueado temporariamente!';}

            } else {

                // Verfica se não passou o tempo limite da sessão
                if (time() < $result[0]['ses_expire']) {

                    $this->updateSession();

                } else { $this->destroySession();}
            }

        }
    }

    /**
     * Atualiza o tempo de expiração da sessão
     */
    private function updateSession()
    {

        if (!$this->sessionData) {$this->destroySession();}

        if ($this->sessionData) {

            $limit = time() + (SESSIONTIMEEXPIRE * 60);
            $terms = "ses_expire = :expire WHERE ses_token = :token";
            $conn = new Conn();
            $conn->update(TBSESSIONS[0], $terms, ['expire' => $limit, 'token' => $this->token]);
            $conn->exec();

            // Redireciona para home se estiver na página de login
            if ($this->urlAtual == $this->pageLogin) {header("Location: " . $this->home);die();}
        }
    }

    /**
     * Redireciona para página login
     */
    private function redirectPageLogin()
    {if ($this->urlAtual != $this->pageLogin) {$this->destroySession();}}

    /**
     * Destrói a sessão
     * Redireciona para página login
     */
    private function destroySession()
    {
        if (isset($_SESSION[$this->token])) {unset($_SESSION[$this->token]);}
        header("Location: " . $this->pageLogin);die();
    }
}
