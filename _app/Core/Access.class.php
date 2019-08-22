<?php

/**
 * Faz gereciamento de Sessões com acessos restritos
 * @author Jeterson Lordano <jetersonlordano@gmail.com>
 */

class Access extends JCrypt
{

    /**
     * Accesso público
     */
    public $log;
    public $check = false;

    /**
     * Controle de sessão
     */
    private $token;
    private $section;
    private $minLevel;
    private $attempts = 0;
    private $timeLocked = 30;

    /**
     * Seção do sistema
     */
    private $home;
    private $pageLogin;

    /**
     * Dados do usuário
     */
    private $userLogin;
    private $userPass;
    private $userDataSelect;
    private $userID = 0;

    /**
     * Coockíe
     */
    private $setCoockie;

    /**
     * @param String $section Identificação da seção do sistema
     * @param String $urlHome URL da página home da seção
     * @param String $urlLogin URL da página de login
     * @param Int $minLevel Nível mínimo de acesso para está seção
     */
    public function __construct($section = 'home', $urlHome = null, $urlLogin = null, $minLevel = 0)
    {
        // ID da sessão
        $this->section = (string) strtolower(strip_tags(trim($section)));
        $this->minLevel = (int) $minLevel;
        $this->token = md5(CMSNAME . $this->section . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        $this->home = (string) ($urlHome != null ? $urlHome : HOME);
        $this->pageLogin = (string) ($urlLogin != null ? $urlLogin : HOME . '/login.php');
    }

    /**
     * Passa os dados do formulário para login
     * @param String $login Login do usuário
     * @param String $pass Senha de acesso do usuário
     * @param Boolena $setCoockie
     */
    public function login($login, $pass, $setCoockie = false)
    {
        // Converte em minuscula
        $this->section = strtolower(trim($this->section));
        $this->userLogin = strip_tags(trim($login));
        $this->userPass = strip_tags(trim($pass));
        $this->setCoockie = (boolean) $setCoockie;
        $this->createSession();
    }

    /**
     * Verifica ou Cria uma nova sessão no banco de dados
     */
    private function createSession()
    {
        $terms = "WHERE ses_token = :token AND ses_section = :section LIMIT 1";
        $values = ['token' => $this->token, 'section' => $this->section];

        // Busca sessão no banco de dados
        $conn = new Conn();
        $conn->select('*', TBSESSIONS[0], $terms, $values);
        $conn->exec();

        // Dados da sessão recuperados do banco
        $resultSession = $conn->fetchAll();

        if ($resultSession) {

            // Verifica se a seção está bloqueada
            if ($resultSession[0]['ses_locked'] != 0) {

                // Verifica se o tempo de bloqueio da seção ja passou
                if (time() >= $resultSession[0]['ses_expire']) {

                    $terms = "ses_locked = :locked, ses_expire = :expire, ses_attempts = :attempts , ses_date = :date WHERE ses_token = :token AND ses_section = :section LIMIT 1";
                    $values = [
                        'locked' => 0,
                        'expire' => time() + (SESSIONTIMEEXPIRE * 60),
                        'attempts' => 0,
                        'date' => date('Y-m-d H:i:s'),
                        'token' => $this->token,
                        'section' => $this->section,
                    ];

                    // Atualiza tabela sessions e libera para tentar novamente
                    $conn = new Conn();
                    $conn->update(TBSESSIONS[0], $terms, $values);

                    if ($conn->exec()) {

                        $this->attempts = 0;
                        $this->checkAccess();

                    } else {die('Erro ao atualizar sessão class Access');}

                } else {die('Seção bloqueada! Aguarde 30 minutos e tente novamente.');}

            } else {
                $this->attempts = $resultSession[0]['ses_attempts'];
                $this->checkAccess();
            }

        } else {

            $fields = "ses_token, ses_section, ses_expire, ses_ip, ses_date";
            $values = [
                'ses_token' => $this->token,
                'ses_section' => $this->section,
                'ses_expire' => time() + (SESSIONTIMEEXPIRE * 60),
                'ses_ip' => $_SERVER['REMOTE_ADDR'],
                'ses_date' => date('Y-m-d H:i:s'),
            ];

            // Inseri um nova sessão no banco de dados
            $conn->insert(TBSESSIONS[0], $values);
            $conn->exec();
            $this->checkAccess();
        }
    }

    /**
     * Verifica se o usuário existe e se a senha está correta
     */
    private function checkAccess()
    {

        $terms = "WHERE user_email = :userfield AND user_accesslevel >= :restricted LIMIT 1";
        $values = ['userfield' => $this->userLogin, 'restricted' => $this->minLevel];
        $conn = new Conn();
        $conn->select('*', TBUSERS[0], $terms, $values);
        $conn->exec();

        // Usuário encontrado no banco
        $result = $conn->fetchAll();

        if ($result) {

            $this->userDataSelect = $result[0];

            // Remove password dos dados que vão para fora da class
            unset($this->userDataSelect['user_password']);

            // ID do usuário no banco
            $this->userID = $result[0]['user_id'];

            // Verifica a senha do usuário

            if (parent::checkHash($this->userPass, $result[0]['user_password'])) {

                $_SESSION[$this->token] = $this->section;
                $this->attempts = 0;
                $this->check = true;
                $this->log = 'Usuário logado!';

            } else {

                $this->attempts++;
                $this->log = 'Os dados não correspondem!';
                $this->userDataSelect = null;
            }

        } else {

            $this->attempts++;
            $this->log = 'Os dados não correspondem!';
        }

        $this->updateSession();
    }

    /**
     * Atualiza a sessão no banco de dados
     */
    private function updateSession()
    {
        $locked = $this->attempts >= 5 ? 1 : 0;
        $expire = $locked == 0 ? time() + (SESSIONTIMEEXPIRE * 60) : time() + ($this->timeLocked * 60);

        $terms = "ses_user = :user, ses_attempts = :attempts, ses_expire = :expire, ses_locked = :locked WHERE ses_token = :token AND ses_section = :section LIMIT 1";
        $values = [
            'user' => $this->userID,
            'attempts' => $this->attempts,
            'expire' => $expire,
            'locked' => $locked,
            'token' => $this->token,
            'section' => $this->section,
        ];

        $conn = new Conn();
        $conn->update(TBSESSIONS[0], $terms, $values);
        $conn->exec();

        // Verifica se o usuário não está bloqueado
        if ($locked == 0) {

            if ($this->userID != 0) {

                // Cria uma session com os dados do usuário
                $_SESSION[$this->section . SESSIONUSERID] = $this->userDataSelect;

            } else {session_destroy();}

        } else {session_destroy();}
    }
}
