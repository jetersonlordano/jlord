<?php

// Header da página
$keyHeader = [];
$keyHeader['btnnewtitle'] = 'APLICAR CONFIGURAÇÕES';
$keyHeader['btnNew'] = 'applySettings';
echo FNC::view($keyHeader, 'tpl' . DS . 'page_header.html');

// Verfica se é super usuário
$SUPERUSER = $USERACTIVE[TBUSERS[1] . 'accesslevel'] == 10;
$DISABLED = $SUPERUSER ? null : 'readonly';

// Consulta configurações no banco
$FIXCF = TBCONFIG[1];
$fields = "{$FIXCF}name, {$FIXCF}value, {$FIXCF}type";
$terms = "";
$values = [];
$conn = new Conn();
$conn->select($fields, TBCONFIG[0], $terms, $values);
$conn->exec();
$configs = $conn->fetchAll();

// Cria um array com os nomes das configurações
$namesConfigs = array_column($configs, $FIXCF . 'name');

/**
 * Cadastra a configuração no banco se não existir
 * @param String $name Nome da configuração
 * @param String $comment
 * @param String $value
 * @param String $type Tipo do valor que será escrito
 * @param String $fnc Função que será gerada
 */
function setConfig($name, $comment, $value = null, $type = null, $fnc = null)
{
    global $namesConfigs;
    $FIX = TBCONFIG[1];
    $values = [];

    if (!in_array($name, $namesConfigs)) {

        $values[$FIX . 'fnc'] = $fnc ?? 'define';
        $values[$FIX . 'name'] = $name;
        $values[$FIX . 'value'] = $value ?? '';
        $values[$FIX . 'comment'] = $comment;
        $values[$FIX . 'type'] = $type ?? 'string';

        $conn = new Conn();
        $conn->insert(TBCONFIG[0], $values);
        $conn->exec();
    }
}

// Recupera a chave do array de resultados
function getKey(string $name)
{
    $n = $name;
    global $configs;
    $filtered_array = array_filter($configs, function ($e) use ($n) {
        return ($e[TBCONFIG[1] . 'name'] == $n);
    });
    return key($filtered_array);
}

function getValue(string $name)
{
    global $configs;
    $key = getKey($name);
    $vlr = $configs[$key]['conf_value'] ?? '';
    return !empty($vlr) ? $vlr : null;
}

echo '<div class="row">';

/**
 * Configuração base
 */

echo '<div class="col"><div class="card radius bg-white box-shadow"><div class="card-body"><div class="card-title">SISTEMA</div></div><div class="card-body bg-light"><form class="form-flex radius" id="system" name="system" action="javascript:void(0);" method="post"><input type="hidden" name="form" value="system">';

// TITLE
echo Form::input('text', 'TITLE', 'Nome do site', 'Nome do site', null, getValue('TITLE') ?? CMSNAME, true, 80);
setConfig('TITLE', 'Informações do site', CMSNAME);

// DESCRIPTION
echo Form::Textarea('DESCRIPTION', 'Descrição do site', 'Descrição do site', null, getValue('DESCRIPTION') ?? CMSNAME, true, 200, 'rows="2"');
setConfig('DESCRIPTION', 'Informações do site', 'O meu site do mundo é o seu');

// THEME
echo Form::input('text', 'THEME', 'Tema do site', 'Tema do site', 'input-width-50', getValue('THEME') ?? THEME, true, 80, $DISABLED);
setConfig('THEME', 'Informações do site', 'default');

// TIMEZONE
$TIMEZONE = ['America/Sao_Paulo' => 'São Paulo'];
echo Form::Select('TIMEZONE', 'Fuso horário', 'Fuso horário do sistema', 'input-width-50', $TIMEZONE, 'America/Sao_Paulo', true);
setConfig('TIMEZONE', 'Timezone', getValue('TIMEZONE') ?? 'America/Sao_Paulo', null, 'date_default_timezone_set');

// Configurações de URLs e Diretórios do projetos
$defineComment = 'Configurações de URLs e Diretórios do projetos';
setConfig('ADD', $defineComment, "HOME . '/' . PATHTHEMES . '/' . THEME", 'concatenated');
setConfig('REQ', $defineComment, "PATHTHEMES . DS . THEME . DS", 'concatenated');
setConfig('TPL', $defineComment, "REQ . 'html' . DS", 'concatenated');

// Save
echo Form::Save('Salvar', 'systemLoader', 'system', true);

echo '</form></div></div></div>';

/**
 * Recursos do sistema
 */
setConfig('POSTS', 'Recursos do sistema', 'true', 'boolean');
setConfig('PAGES', 'Recursos do sistema', 'true', 'boolean');
setConfig('USERSONLINE', 'Recursos do sistema', 'true', 'boolean');
setConfig('ANALYTICS', 'Recursos do sistema', 'true', 'boolean');
setConfig('SEARCH', 'Recursos do sistema', 'true', 'boolean');
setConfig('SEO', 'Recursos do sistema', 'true', 'boolean');

if ($SUPERUSER):

    echo '<div class="col"><div class="card radius bg-white box-shadow"><div class="card-body"><div class="card-title">RECURSOS</div></div><div class="card-body bg-light"><form class="form-flex radius" id="resources" name="resources" action="javascript:void(0);" method="post"><input type="hidden" name="form" value="resources">';

    $ONOFF = ['true' => 'Ativado', 'false' => 'Desativado'];

    // POSTS
    echo Form::Select('POSTS', 'Posts', 'Postagens de artigos', 'input-width-50', $ONOFF, getValue('POSTS') ?? 'false', true);

    // PAGES
    echo Form::Select('PAGES', 'Páginas', 'Gerenciamento de páginas', 'input-width-50', $ONOFF, getValue('PAGES') ?? 'false', true);

    // USERSONLINE
    echo Form::Select('USERSONLINE', 'Usuários Online', 'Contador de usuários Online', 'input-width-50', $ONOFF, getValue('USERSONLINE') ?? 'false', true);

    // ANALYTICS
    echo Form::Select('ANALYTICS', 'Análises do sistema', 'Análises do sistema', 'input-width-50', $ONOFF, getValue('ANALYTICS') ?? 'false', true);

    // SEARCH
    echo Form::Select('SEARCH', 'Pesquisas', 'Análises de pesquisas', 'input-width-50', $ONOFF, getValue('SEARCH') ?? 'false', true);

    // SEO
    echo Form::Select('SEO', 'SEO', 'Otimização para mecanismos de busca', 'input-width-50', $ONOFF, getValue('SEO') ?? 'false', true);

    // Save
    echo Form::Save('Salvar', 'resourcesLoader', 'resources', true);

    echo '</form></div></div></div>';

    echo "<script async>(function() {submitForm({file: 'async/system/client.php', loader: 'resourcesLoader'}, 'resources');})();</script>";

endif;

/**
 * Informaçoes sobre o negócio
 */

echo '<div class="col"><div class="card radius bg-white box-shadow"><div class="card-body"><div class="card-title">EMPRESA</div></div><div class="card-body bg-light"><form class="form-flex radius" id="business" name="business" action="javascript:void(0);" method="post"><input type="hidden" name="form" value="business">';

// PHONE
echo Form::Input('tel', 'PHONE', 'Telefone', 'Telefone', 'input-width-50', getValue('PHONE') ?? '', true, 15);
setConfig('PHONE', 'Contatos', '');

// WHATSAPP
echo Form::Input('tel', 'WHATSAPP', 'WhatsApp', 'WhatsApp', 'input-width-50', getValue('WHATSAPP') ?? '', false, 15);
setConfig('WHATSAPP', 'Contatos', '');

// EMAIL
echo Form::Input('email', 'EMAIL', 'E-mail', 'E-mail', 'input-width-50', getValue('EMAIL') ?? '', true, 80);
setConfig('EMAIL', 'Contatos', '');

// CEP
echo Form::Input('text', 'CEP', 'CEP', 'CEP', 'input-width-50', getValue('CEP') ?? '', true, 9, 'pattern="\d{5}-\d{3}"');
setConfig('CEP', 'Endereço');

// ADDRESS
echo Form::Textarea('ADDRESS', 'Endereço', 'Endereço', 'input-width-50', getValue('ADDRESS') ?? '', true, 150, 'rows="1"');
setConfig('ADDRESS', 'Endereço');

// COORDINATES
echo Form::Input('text', 'COORDINATES', 'Coordenadas', 'Google Maps coordenadas', 'input-width-50', getValue('COORDINATES') ?? '', false, 45);
setConfig('COORDINATES', 'Endereço');

// Save
echo Form::Save('Salvar', 'businessLoader', 'business', true);

echo '</form></div></div></div>';

/**
 * APIs
 */

echo '<div class="col"><div class="card radius bg-white box-shadow"><div class="card-body"><div class="card-title">APLICAÇÕES</div></div><div class="card-body bg-light"><form class="form-flex radius" id="apis" name="apis" action="javascript:void(0);" method="post"><input type="hidden" name="form" value="apis">';

// GOOGLEANALYTICSID
echo Form::Input('text', 'GOOGLEANALYTICSID', 'ID Google Analytics', 'ID Google Analytics', 'input-width-50', getValue('GOOGLEANALYTICSID') ?? '', false, 45);
setConfig('GOOGLEANALYTICSID', 'APIS');

// FACEBOOKPAGEID
echo Form::Input('text', 'FACEBOOKPAGEID', 'ID Facebook Page', 'ID Facebook Page', 'input-width-50', getValue('FACEBOOKPAGEID') ?? '', false, 45);
setConfig('FACEBOOKPAGEID', 'APIS');

// Save
echo Form::Save('Salvar', 'apisLoader', 'apis', true);

echo '</form></div></div></div>';

/**
 * Redes sociais
 */

echo '<div class="col"><div class="card radius bg-white box-shadow"><div class="card-body"><div class="card-title">REDES SOCIAIS</div></div><div class="card-body bg-light"><form class="form-flex radius" id="socialnetworks" name="socialnetworks" action="javascript:void(0);" method="post">';

$NETSICONS = [
    'facebook' => ['https://facebook.com', 'fa fa-facebook'],
    'twitter' => ['https://twitter.com', 'fa fa-twitter'],
    'instagram' => ['https://instagram.com', 'fa fa-instagram'],
    'linkdin' => ['https://linkedin.com/in', 'fa fa-linkedin'],
    'youtube' => ['https://youtube.com', 'fa fa-youtube'],
    'github' => ['https://github.com', 'fa fa-github'],
];

function selectSociais()
{
    $FIXSN = TBNET[1];
    $conn = new Conn();
    $conn->select('*', TBNET[0]);
    $conn->exec();
    return $conn->fetchAll();
}

$SOCIALNETWORKS = selectSociais();

if (!$SOCIALNETWORKS) {

    foreach ($NETSICONS as $keySN => $valueSN) {
        insertSocial([
            TBNET[1] . 'name' => $keySN,
            TBNET[1] . 'base' => $valueSN[0],
            TBNET[1] . 'icon' => $valueSN[1],
        ]);
    }
}

function insertSocial(array $socialValues)
{
    $conn = new Conn();
    $conn->insert(TBNET[0], $socialValues);
    return $conn->exec();
}

$SOCIALNETWORKS = selectSociais();

$FIXSN = TBNET[1];
if ($SOCIALNETWORKS) {foreach ($SOCIALNETWORKS as $keys => $SN) {
    
    $nameSN = $SN[$FIXSN . 'name'];
    $titleSN = ucfirst($nameSN);
    $reqSN = $nameSN == 'facebook' ? true : false;
    echo Form::Input('text', $SN[$FIXSN . 'name'], $titleSN, $titleSN, 'input-width-50', $SN[$FIXSN . 'perfil'], $reqSN, 80, null, $NETSICONS[$nameSN][1]);
}}
// Save
echo Form::Save('Salvar', 'socialnetworksLoader', 'socialnetworks', true);

echo '</form></div></div></div>';

echo '</div>';

?>

<script async>
    (function() {

    var client = 'async/system/client.php';
    submitForm({file: client, loader: 'systemLoader'}, 'system');
    submitForm({file: client, loader: 'businessLoader'}, 'business');
    submitForm({file: client, loader: 'apisLoader'}, 'apis');
    submitForm({file: 'async/system/socialnetworks.php', loader: 'socialnetworksLoader'}, 'socialnetworks');

    var applySettings = jget('#applySettings');
    function applyConfigs(){ajax({file: 'async/system/apply.php', loader: 'loader'});}
    jevt(applySettings, 'click', applyConfigs, !0);
     })();

</script>
