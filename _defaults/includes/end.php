<?php

// Usuário Online
if (USERSONLINE) {
    echo "<script async>(function(){var usersOnObj={urlhome:1,urlpage: '{$VIEWS->URL}',file:'_defaults/async/usersOnline.php'};setInterval(function(){ajax(usersOnObj);},15000);})();</script>";
}

echo '</body></html>';
