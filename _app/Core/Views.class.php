<?php

class Views
{
    public $URL;
    private $controlSession;

    public function __construct($LINK)
    {
        // Controla sessão única
        $this->controlSession = md5('controlSession' . SESSIONUSERID);

        // Recupera URL que usuário está
        foreach ($LINK->index as $key) {$this->URL .= '/' . $key;}

        if (ANALYTICS) {$this->start();}
    }

    private function start()
    {
        // Verifica usuário online Cadastra ou Atualiza
        $userOnline = $this->checkUser();
        if ($userOnline) {$this->updateOnline();} else { $this->newUserOnline();}

        // Contador de visitas
        $todayViews = $this->checkViews();
        if ($todayViews) {$this->updateViews($todayViews[0]);} else { $this->newCountViews();}
    }

    private function checkViews()
    {
        $conn = new Conn();
        $conn->select('*', TBVIEWS[0], 'WHERE ' . TBVIEWS[1] . 'date = :date', ['date' => date('Y-m-d')]);
        $conn->exec();
        return $conn->fetchAll();
    }

    private function newCountViews()
    {
        $FIX = TBVIEWS[1];
        $conn = new Conn();
        $conn->insert(TBVIEWS[0], [$FIX . 'date' => date('Y-m-d')]);
        $conn->exec();
    }

    private function updateViews(array $todayViews)
    {
        $FIX = TBVIEWS[1];
        $terms = "{$FIX}pages = :pages WHERE {$FIX}id = :id";
        $values = ['pages' => $todayViews[$FIX . 'pages'] + 1, 'id' => $todayViews[$FIX . 'id']];

        if (!isset($_SESSION[$this->controlSession])) {
            $_SESSION[$this->controlSession] = true;
            $terms = "{$FIX}sessions = :sessions, {$FIX}pages = :pages WHERE {$FIX}id = :id";
            $values['sessions'] = $todayViews[$FIX . 'sessions'] + 1;
        }

        $conn = new Conn();
        $conn->update(TBVIEWS[0], $terms, $values);
        $conn->exec();
    }

    private function checkUser()
    {
        $conn = new Conn();
        $conn->select(TBONLINE[1] . 'id', TBONLINE[0], 'WHERE ' . TBONLINE[1] . 'id = :id', ['id' => SESSIONUSERID]);
        $conn->exec();
        return $conn->fetchAll();
    }

    private function updateOnline()
    {
        $FIX = TBONLINE[1];
        $time = time() + USERSTIMEEXPIRE;
        $end = date('Y-m-d H:i:s', $time);
        $terms = "{$FIX}end = :end, {$FIX}url = :url WHERE {$FIX}id = :id LIMIT 1";
        $values = ['end' => $end, 'url' => $this->URL, 'id' => SESSIONUSERID];
        $conn = new Conn();
        $conn->update(TBONLINE[0], $terms, $values);
        $conn->exec();
    }

    private function newUserOnline()
    {
        $time = time() + USERSTIMEEXPIRE;
        $FIX = TBONLINE[1];
        $values = [
            $FIX . 'id' => SESSIONUSERID,
            $FIX . 'start' => date('Y-m-d H:i:s'),
            $FIX . 'end' => date('Y-m-d H:i:s', $time),
            $FIX . 'ip' => $_SERVER['REMOTE_ADDR'],
            $FIX . 'url' => $this->URL,
        ];
        $conn = new Conn();
        $conn->insert(TBONLINE[0], $values);
        $conn->exec();
    }
}
