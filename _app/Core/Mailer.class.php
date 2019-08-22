<?php

/**
 * Automatiza o processo de envio de email autenticado usando PHPMailer
 * Nota: Configurar constantes SMTPHOST, SMTPAUTHOR, SMTPEMAIL, SMTPPASSWORD, SMTPSECURE e SMTPPORT no arquivo config.inic.php
 * @author Jeterson Lordano <jetersonlordano@gmail.com>
 */

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require 'Libraries/PHPMailer/src/Exception.php';
require 'Libraries/PHPMailer/src/PHPMailer.php';
require 'Libraries/PHPMailer/src/SMTP.php';

class Mailer
{
    
    // Host SMTP
    public $Host = SMTPHOST;

    // Autor do e-mail
    public $Author = SMTPAUTHOR;

    // E-MAIL SMTP
    public $User = SMTPEMAIL;

    // Senha do E-mail SMTP
    public $Pass = SMTPPASSWORD;

    // Tipo de segurança SMTP ( TLS , SSL, AUTO )
    public $Secure = SMTPSECURE;

    // Porta TCP do servidor SMTP
    public $Port = SMTPPORT;

    // Nome do usuário a quem o destinatário deve responder
    public $ReplayToUser = SMTPAUTHOR;

    // E-mail do usuário a quem o destinatário deve responder
    public $ReplayToMail = SMTPEMAIL;

    private $Mailer;
    private $Name;
    private $Email;
    private $Subject;
    private $Body;
    private $AltBody;

    public function __construct()
    {$this->Mailer = new PHPMailer;}

    /**
     * Atribui e valida os dados do destinatário do corpo da mensagem a ser enviáda
     * @param String $name Nome do destinatário da mensagem
     * @param String $email Endereço de email do destinatário
     * @param String $subject Assusnto da mensagem para o destinatário
     * @param String $body Corpo da mensagem - Pode conter Tags HTML
     */
    public function email($name, $email, $subject, $body)
    {

        $this->Name = (string) strip_tags(trim($name));
        $this->Email = (string) strip_tags(trim($email));
        $this->Subject = (string) strip_tags(trim($subject));
        $this->Body = (string) trim($body);
        $this->AltBody = (string) strip_tags($this->Body);

        // Valida o email do destinatário
        if (filter_var($this->Email, FILTER_VALIDATE_EMAIL)) {$this->connect();}
    }

    /**
     * Envia o email ao destinatário
     * @return Boolean
     */
    public function send()
    {
        // Repassa os parametros para PHPMailer
        $this->Mailer->Subject = $this->Subject;
        $this->Mailer->Body = $this->Body;
        $this->Mailer->AltBody = $this->AltBody;
        $this->Mailer->addAddress($this->Email, $this->Name);

        try {

            return $this->Mailer->send();

            // Limpar todos so recipientes da Classe PHPMAILER
            $this->Mailer->ClearAllRecipients();
            $this->Mailer->ClearAttachments();

            // Adiciona ao log o endereço de email para qual foi enviado e mensagem
            $this->Log = $this->Email;

        } catch (Exception $e) {
            echo 'Mensagem não pôde ser enviada! Erro: ' . $this->Mailer->ErrorInfo;
            return false;
        }
    }

    /**
     * Configura e conecta ao servidor SMTP do usuário
     */
    private function connect()
    {
        $this->Mailer->setLanguage('pt_br');
        $this->Mailer->isSMTP();
        $this->Mailer->Host = $this->Host;
        $this->Mailer->SMTPAuth = true;
        $this->Mailer->Username = $this->User;
        $this->Mailer->Password = $this->Pass;
        $this->Mailer->SMTPSecure = $this->Secure;
        $this->Mailer->Port = $this->Port;
        $this->Mailer->setFrom($this->User, $this->Author);
        $this->Mailer->addReplyTo($this->ReplayToMail, $this->ReplayToUser);
        $this->Mailer->isHTML(true);
        $this->Mailer->CharSet = 'utf-8';
        $this->Mailer->WordWrap = 70;
    }
}
