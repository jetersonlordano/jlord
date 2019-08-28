<footer id="mainFooter" class="wrapper bg-white">
        <div class="container">
            <div class="row">

                <div class="col-12 col-md-4">
                    <div class="footer_item">
                        <span class="footer_title">Navegação</span>
                        <nav class="footer_nav">
                            <ul>
<?php

foreach (NAVIGATION as $key => $value) {
    $urlNav = $value[2] ?? HOME . '/' . $key;
    $targetBlank = $value[3] == 0 ? null : ' target="_blank"';
    echo '<li><a href="' . $urlNav . '" title="' . $value[1] . '"'.$targetBlank .'>' . $value[0] . '</a></li>';
}

?>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="footer_item">
                        <span class="footer_title">Contato</span>
                        <nav class="footer_contato">
                            <ul>
<?php
if (EMAIL) {echo '<li><a href="mailto:' . EMAIL . '" title="EMAIL ' . TITLE . '"><i class="fa fa-envelope"></i>' . EMAIL . '</a></li>';}
if (PHONE) {echo '<li><a href="tel:+55' . str_replace(['(', ')', '-', ' '], '', PHONE) . '" title="Telefone ' . TITLE . '"><i class="fa fa-phone"></i>' . PHONE . '</a></li>';}
if (WHATSAPP) {
    $apiWhats = preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]) ? 'api' : 'web';
    $linkWhats = 'https://' . $apiWhats . '.whatsapp.com/send?phone=' . str_replace(['(', ')', '-', ' '], '', WHATSAPP);
    echo '<li><a href="' . $linkWhats . '" title="Telefone ' . TITLE . '"><i class="fa fa-whatsapp"></i>' . WHATSAPP . '</a></li>';
}

if (ADDRESS) {
    $linkLocal = 'https://google.com.br/maps/place/';
    $linkLocal .= COORDINATES ? COORDINATES : FNC::convertStr(ADDRESS);
    echo '<li><a href="' . $linkLocal . '" title="Faça-nos uma visita" target="_blank"><i class="fa fa-map-marker"></i>' . ADDRESS . '</a></li>';
}
?>


                            </ul>
                        </nav>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="footer_item">
                        <span class="footer_title">Conecte-se</span>
                        <nav class="footer_social nav">


<?php

foreach (SOCIALNETWORKS as $key => $value) {
    $socialTitle = TITLE . ' no ' . ucfirst($key);
    echo nl2br('<a href="' . $value[0] . '/' . $value[1] . '" target="_blank" title="' . $socialTitle . '" rel="nofollow"><i class="' . $value[2] . '"></i></a> ');
}

?>
                        </nav>
                    </div>
                </div>

                <div class="col-12">
                    <p class="footer_copyright">
                        © 2019 - Orgulhosamente desenvolvido com <a href="https://www.jetersonlordano.com.br/jlord" title="JLord CMS" target="_blank" rel="author">JLord</a>
                    </p>
                </div>

            </div>
        </div>
    </footer>
    <script async>"use strict";const $navResp=$("#navResp"),$mainNav=$("#mainNav");let navOpen=!1;function closeNav(){navOpen&&($navResp.dataset.open=!1,$mainNav.dataset.open=!1,navOpen=!1,$on(window,"resize orientationchange",closeNav,0))}function explandeNav(){if(!navOpen)return $navResp.dataset.open=!0,$mainNav.dataset.open=!0,navOpen=!0,$on(window,"resize orientationchange",closeNav,!0),!0;$navResp.dataset.open=!1,$mainNav.dataset.open=!1,navOpen=!1}$on($navResp,"click",explandeNav,!0);</script>
</body></html>