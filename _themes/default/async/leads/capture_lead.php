<?php

require '../../../../_app/Client.inc.php';

$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$post) {Error();die;}

$email = strip_tags(trim($post['email']));

if (!Check::email($email, true)) {
    $callback = [
        'action' => 'dialog',
        'type' => 'info',
        'header' => 'Email Inválido',
        'message' => 'Hey! Use seu melhor e-mail',
    ];

    echo json_encode($callback);die;
}

function Error()
{
    $callback = [
        'action' => 'dialog',
        'type' => 'danger',
        'header' => 'Erro interno!',
        'message' => 'Hey! Desculpe pelo transtorno.',
    ];
    echo json_encode($callback);die;
}

$conn = new Conn();
$conn->select('lead_id', TBLEADS[0], "WHERE lead_email = :email LIMIT 1", ['email' => $email]);
$conn->exec();
$result = $conn->fetchAll();

if ($result) {

    $values = [
        'email' => $email,
        'lp' => strip_tags(trim($post['lp'])),
        'lpname' => strip_tags(trim($post['lpname'])),
        'confirm' => 0,
        'date' => date('Y-m-d H:i:s'),
        'id' => $result[0]['lead_id'],
    ];
    $conn->update(TBLEADS[0], "lead_email = :email, lead_lp = :lp, lead_lpname = :lpname, lead_confirm = :confirm, lead_date = :date WHERE lead_id = :id LIMIT 1", $values);

    if ($conn->exec()) {sendMailConfirm($email);} else {Error();}

} else {
    $values = [
        'lead_email' => $email,
        'lead_lp' => strip_tags(trim($post['lp'])),
        'lead_lpname' => strip_tags(trim($post['lpname'])),
        'lead_date' => date('Y-m-d H:i:s'),
    ];
    $conn->insert(TBLEADS[0], $values);
    if ($conn->exec()) {sendMailConfirm($email);} else {Error();}
}

function sendMailConfirm($email)
{
    $subject = "[LISTA VIP] Confirme aqui sua inscrição!";
    $bodyMsg = "";

    $emailencode = base64_encode($email);

    $emailKeys = ['linkconfirm', 'linkcancel', 'home', 'sitename', 'avatar'];
    $emailKeys['linkconfirm'] = HOME . "/confirm.php?email={$emailencode}";
    $emailKeys['linkcancel'] = HOME . "/cancel.php?email={$emailencode}";
    $emailKeys['home'] = HOME;
    $emailKeys['sitename'] = SITENAME;
    $emailKeys['avatar'] = HOME . PATHAUTHORS . 'jeterson-lordano.jpg';

    $bodyMsg = FNC::view($emailKeys, 'tpl/list_vip.html');

    $mailer = new Mailer();
    $mailer->email('Amig@', $email, $subject, $bodyMsg);
    if ($mailer->send()) {

        $callback = [
            'action' => 'dialog',
            'type' => 'success',
            'header' => 'Yeah! Seja super bem-vind@!',
            'message' => 'Dá uma conferida no seu email ;)',
        ];
        $callback = json_encode($callback);
        echo $callback;

    } else {Error();}
}
