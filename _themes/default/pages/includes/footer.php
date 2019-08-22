<?php

$footer = [];
$footer['contenteditable'] = 'contenteditable="true"';
echo FNC::view($footer, '..\\' . TPL . 'footer.html');

echo '<footer class="wrapper bg-light center main_footer padding_section"><div class="container"><div class="row"><div class="col"><div class="social_midias">';

// REDES SOCIAIS
$SOCIALNETWORKS = SOCIALNETWORKS;
foreach ($SOCIALNETWORKS as $mds) {
    echo '<a href="' . $mds[0] . $mds[1] . ' " title="' . TITLE . '" target="_blank"><i class="' . $mds[2] . '"></i></a>';
}

// FOOTER
echo '</div><div class="copyright center"><div class="copyright center"><p>&copy; ' . date('Y') . ' - Todos os diretos reservados<br><b>Orgulhosamente desenvolvido por <a href="' . DEVSITE . '" title="Desenvolvimento por ' . DEVNAME . '" rel="author">' . DEVNAME . '</a></b></p></div></div></div></div></footer><div id="overlay" data-visible="false"></div><div id="return"></div>';

echo '<script async src="' . HOME . '/' . WDGT . '/scripts/main.js"></script><script async src="' . ADD . '/assets/scripts/main.js"></script>';
