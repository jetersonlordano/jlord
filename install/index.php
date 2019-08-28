<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Instalação do JLord</title>
    <link rel="stylesheet" href="install.css">

    <script>"use strict"; function $(t, e) { let n; switch (e = "string" == typeof e ? $(e) : e || document, t.substring(0, 1)) { case "#": n = "getElementById", t = t.substring(1); break; case ".": n = "getElementsByClassName", t = t.substring(1); break; default: n = "getElementsByTagName" }return e[n](t) } function $on(t, e, n, o, a) { let i = e.split(" "); for (var r = 0; r < i.length; r++)t[o ? "addEventListener" : "removeEventListener"](i[r], n, a) } function ajax(t) { let e, n, o, a; o = (n = -1 !== String(t.data).indexOf("FormData")) ? "X-Requested-With" : "Content-type", a = n ? "XMLHttpRequest" : "application/json; charset=utf-8", $on((e = new (window.XMLHttpRequest || ActiveXObject("MSXML2.XMLHTTP.3.0"))).upload, "abort error load loadend loadstart progress", t.upload, !0), $on(e, "readystatechange", function () { 4 === this.readyState && (200 === this.status ? t.success(this) : t.error(this)) }, !0), e.open(t.method || "POST", t.url || window.location.href, !0, t.user, t.psw), e.setRequestHeader(o, t.contentType || a), e.send("object" != typeof t.data || n ? t.data : JSON.stringify(t.data)), t.start && t.start() } function lazyImages() { function t(t) { function e() { (function (t) { return t.getBoundingClientRect().top <= (window.innerHeight || document.documentElement.clientHeight) && t.getBoundingClientRect().bottom >= 0 })(t) && (!function (t) { t.src = t.getAttribute("data-lazy"), t.removeAttribute("data-lazy") }(t), $on(document, "scroll", e, 0), $on(window, "resize orientationchange", e, 0)) } $on(document, "DOMContentLoaded scroll", e, !0), $on(window, "resize orientationchange", e, !0) } let e = document.querySelectorAll("img[data-lazy]"); for (let n = 0; n < e.length; n++)new t(e[n]) } lazyImages();</script>
</head>

<body>

    <div class="wrapper">

        <form id="formInstall" name="formInstall" action="javascript:void(0);" method="post">

            Domínio:<br><input type="url" name="base" id="base" value="localhost/jlord/install" readonly required>
            <input type="checkbox" name="www" id="www" title="usar WWW">Usar WWW<br>

            Nome:<br><input type="text" id="username" name="username" placeholder="Digite seu nome" value="Jeterson Lordano" required><br>
            Login:<br><input type="email" id="login" name="login" placeholder="Digite seu email" value="jetersonlordano@gmail.com" required><br>
            Senha:<br><input type="password" name="psw" id="psw" placeholder="Defina uma senha" value="useradmin" minlength="8"><br>

            <br><br>

            Servidor: <br><input type="text" name="host" placeholder="Servidor de dados" id="host" value="localhost" required><br>
            Banco de dados:<br><input type="text" name="dbname" placeholder="Nome do banco de dados" id="dbname" required value="jet"><br>
            Usuário: <br><input type="text" name="dbuser" id="dbuser" placeholder="Usuário" value="root" required><br>
            Senha: <br><input type="password" name="dbpsw" id="dbpsw" placeholder="Usuário" value=""><br>

            <button type="submit">Instalar</button>
        </form>

    </div>

    <script async>



        $on($('#formInstall'), 'submit', installSystem, !0);

        function installSystem(evt) {
            ajax({
                url: 'https://localhost/jlord/install/install.php',
                start: function () { console.log('está installando') },
                success: function (resp) { console.log(resp.responseText) },
                data: new FormData(evt.currentTarget)
            });

        }

    </script>

</body>

</html>
