<!DOCTYPE html>
<html lang="pt-br">

<?php

if (phpversion() < '7.0') {die('A versão do PHP não é compátivel com o JLord!<br> Instale o PHP 7.0 ou superior');}

$urlMain = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$urlMain = str_replace('install', '', $urlMain);
$urlMain = substr($urlMain, -1) == '/' ? substr($urlMain, 0, -1) : $urlMain;
$urlMain = substr($urlMain, -1) == '/' ? substr($urlMain, 0, -1) : $urlMain;

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Instalação do JLord</title>
    <link rel="stylesheet" href="install.css">
    <link rel="icon" href="uploads/covers/cover-default.png" type="image/png">


    <script>"use strict"; function $(t, e) { let n; switch (e = "string" == typeof e ? $(e) : e || document, t.substring(0, 1)) { case "#": n = "getElementById", t = t.substring(1); break; case ".": n = "getElementsByClassName", t = t.substring(1); break; default: n = "getElementsByTagName" }return e[n](t) } function $on(t, e, n, o, a) { let i = e.split(" "); for (var r = 0; r < i.length; r++)t[o ? "addEventListener" : "removeEventListener"](i[r], n, a) } function ajax(t) { let e, n, o, a; o = (n = -1 !== String(t.data).indexOf("FormData")) ? "X-Requested-With" : "Content-type", a = n ? "XMLHttpRequest" : "application/json; charset=utf-8", $on((e = new (window.XMLHttpRequest || ActiveXObject("MSXML2.XMLHTTP.3.0"))).upload, "abort error load loadend loadstart progress", t.upload, !0), $on(e, "readystatechange", function () { 4 === this.readyState && (200 === this.status ? t.success(this) : t.error(this)) }, !0), e.open(t.method || "POST", t.url || window.location.href, !0, t.user, t.psw), e.setRequestHeader(o, t.contentType || a), e.send("object" != typeof t.data || n ? t.data : JSON.stringify(t.data)), t.start && t.start() } function lazyImages() { function t(t) { function e() { (function (t) { return t.getBoundingClientRect().top <= (window.innerHeight || document.documentElement.clientHeight) && t.getBoundingClientRect().bottom >= 0 })(t) && (!function (t) { t.src = t.getAttribute("data-lazy"), t.removeAttribute("data-lazy") }(t), $on(document, "scroll", e, 0), $on(window, "resize orientationchange", e, 0)) } $on(document, "DOMContentLoaded scroll", e, !0), $on(window, "resize orientationchange", e, !0) } let e = document.querySelectorAll("img[data-lazy]"); for (let n = 0; n < e.length; n++)new t(e[n]) } lazyImages();</script>
</head>

<body>

    <div class="wrapper">
    <h1>Instalação do JLord</h1>
        <form id="formInstall" name="formInstall" action="javascript:void(0);" method="post">

            <div class="form_section">

                <label for="username">Nome:</label>
                <input type="text" id="username" name="username" placeholder="Digite seu nome" value="Jeterson Lordano" required>

                <label for="login">Email:</label>
                <input type="email" id="login" name="login" placeholder="Digite seu email" value="jetersonlordano@gmail.com" required>

                <label for="psw">Senha:</label>
                <input type="password" name="psw" id="psw" placeholder="Defina uma senha" value="useradmin" minlength="8">

                <label for="url">Domínio:</label>
                <input class="input_url" type="url" name="base" id="base" value="<?=$urlMain?>" readonly required>


                <input class="input_check" type="checkbox" name="www" id="www" title="usar WWW">
                <label class="check_label" for="www">Usar www no domínio</label>
            </div>

            <div class="form_section">
                 <label for="host">Servidor:</label>
                 <input type="text" name="host" placeholder="Servidor do banco de dados" id="host" value="localhost" required>

                 <label for="dbname">banco de dados:</label>
                 <input type="text" name="dbname" placeholder="Nome do banco de dados" id="dbname" required value="jet">

                 <label for="dbuser">Usuário:</label>
                 <input type="text" name="dbuser" id="dbuser" placeholder="Usuário do banco de dados" value="root" required>

                 <label for="dbpsw">Senha:</label>
                 <input type="password" name="dbpsw" id="dbpsw" placeholder="Senha do banco de dados" value="">

                 <button type="submit">Instalar</button>
            </div>

        </form>

    </div>

    <div id="loader">

       <div class="loader_conteiner">
       
       <div class="lds-circle">JLORD</div>
    
       </div>

    </div>

    <div id="return">
        <div class="return_container">
            <div class="return_content erro">
                <span id="returnTitle" class="title">ERRO!</span>
                <p id="returnText">Não foi possível instalar o sistema</p>
                <button id="btnReturn">Ok</button>
            </div>
        </div>
    </div>

    <script async>
       $on($('#formInstall'), 'submit', installSystem, !0);
        function installSystem(evt) {
            ajax({
                url: '<?=$urlMain?>/install/install.php',
                start: function () {
                    $('#loader').style.display = 'flex';
                },
                success: function (resp) {

                    $('#loader').style.display = 'none';
                    $('#return').style.display = 'flex';

                    try {
                        var jsonObj = JSON.parse(resp.responseText);

                        $('#returnTitle').innerHTML = 'Feito!';
                        $('#returnText').innerHTML = jsonObj['msg'];
                        $on($('#btnReturn'), 'click', function(){
                            window.location.href= '<?=$urlMain?>/admin';
                        }, !0);

                    } catch (e) {

                        $('#returnText').innerText = resp.responseText;

                        $on($('#btnReturn'), 'click', function(){
                            $('#return').style.display = 'none';

                        }, !0);
                    }
                },
                data: new FormData(evt.currentTarget)
            });
        }




    </script>

</body>

</html>
