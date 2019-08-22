<?php

/**
 * Header padrão
 */

// ICONES
if (ICONS) {foreach (ICONS as $icon => $typeico) {
    $linkIco = HOME . '/uploads/branding/' . $icon;
    echo "<link rel=\"shortcut icon\" type=\"{$typeico}\" href=\"{$linkIco}\"/>";
}}

// Google fonts
echo '<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet" type="text/css"/>';

// Arquivos CSS
$CSSLIST = ['assets/styles/boot.css', 'assets/styles/style.css', 'assets/icons/css/font-awesome.css'];
foreach ($CSSLIST as $JCSS) {echo '<link rel="stylesheet" href="' . ADD . '/' . $JCSS . '">';}

echo '</head><body>';

/**
 *  Início da body
 */

// AJAX
echo '<script src="' . HOME . '/' . WDGT . '/scripts/ajax.js"></script>';

// !IMPORTANTE

// Criar menu dinamico do bando de dados

?>


        <!-- Search -->
        <div id="blogSearch" data-visible="false">
            <div class="blog_search bg-main flex justify-content-center align-items-center">
                <form id="blogFormSearch" class="center" name="blogFormSearch" action="" method="post">
                    <input type="search" class="radius" name="search" placeholder="O que você procura?">
                    <button type="submit" class="btn btn-yellow radius">BUSCAR</button>
                </form>
            </div>
        </div>

        <!-- Topbar -->
        <div class="wrapper main_topbar topbar_blog">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="main_topbar flex justify-content-between align-items-center">
                            <a class="main_logo" href="<?=HOME?>" title="Desenvolvimento web para negócios">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 268.296 25"><path d=" M 256.115 0.071 C 259.48 0.071 262.348 1.28 264.741 3.721 C 267.111 6.138 268.296 9.052 268.296 12.488 C 268.296 15.901 267.111 18.84 264.741 21.256 C 262.348 23.697 259.456 24.905 256.115 24.905 C 252.75 24.905 249.883 23.697 247.489 21.256 C 245.12 18.84 243.935 15.924 243.935 12.488 C 243.935 9.076 245.12 6.138 247.489 3.721 C 249.86 1.28 252.75 0.071 256.115 0.071 Z  M 256.115 22.156 C 258.723 22.156 260.95 21.209 262.821 19.313 C 264.67 17.418 265.594 15.142 265.594 12.464 C 265.594 9.787 264.67 7.512 262.821 5.617 C 260.973 3.721 258.723 2.773 256.115 2.773 C 253.508 2.773 251.282 3.721 249.409 5.617 C 247.561 7.512 246.637 9.787 246.637 12.464 C 246.637 15.142 247.561 17.418 249.409 19.313 C 251.258 21.233 253.508 22.156 256.115 22.156 Z " class="lastname"/><path d=" M 240.357 0.332 L 243.035 0.332 L 243.035 24.669 L 240.642 24.669 L 233.011 10.735 L 231.897 8.412 L 230.475 6.09 L 230.475 24.669 L 227.821 24.669 L 227.821 0.332 L 230.238 0.332 L 237.869 14.241 L 239.125 16.73 L 240.381 18.887 L 240.381 0.332 L 240.357 0.332 Z " class="lastname"/><path d=" M 217.68 0.332 L 215.404 0.332 L 206.471 24.645 L 209.361 24.645 L 212.395 16.019 L 213.319 13.342 L 216.495 4.407 L 219.694 13.342 L 220.594 16.019 L 223.628 24.645 L 226.613 24.645 L 217.68 0.332 Z " class="lastname"/><path d=" M 205.214 3.957 C 202.845 1.588 199.977 0.402 196.613 0.402 L 189.859 0.402 L 192.514 3.058 L 196.589 3.058 C 199.22 3.058 201.447 3.981 203.295 5.829 C 205.167 7.678 206.091 9.929 206.091 12.536 C 206.091 15.166 205.167 17.394 203.295 19.242 C 201.447 21.09 199.196 22.015 196.589 22.015 L 192.514 22.015 L 192.514 19.716 L 189.836 17.014 L 189.836 24.692 L 196.589 24.692 C 199.955 24.692 202.797 23.508 205.192 21.138 C 207.585 18.767 208.769 15.901 208.769 12.536 C 208.793 9.194 207.608 6.327 205.214 3.957 Z " class="lastname"/><path d=" M 180.973 13.72 C 183.153 13.484 184.789 12.82 185.878 11.706 C 187.016 10.521 187.537 8.887 187.465 6.754 C 187.394 5.047 186.849 3.579 185.855 2.369 C 184.694 0.996 183.129 0.332 181.21 0.332 L 173.39 0.332 L 176.043 2.985 L 181.186 2.985 C 182.371 2.985 183.272 3.413 183.912 4.242 C 184.456 4.953 184.741 5.806 184.789 6.849 C 184.836 8.2 184.574 9.194 183.958 9.834 C 183.129 10.711 181.305 11.137 178.485 11.137 L 176.043 11.137 L 173.39 11.137 L 173.39 24.645 L 176.043 24.645 L 176.043 13.697 L 177.703 13.697 L 185.38 24.645 L 188.626 24.645 L 180.973 13.72 Z " class="lastname"/><path d=" M 159.906 0.071 C 163.271 0.071 166.139 1.28 168.532 3.721 C 170.901 6.138 172.086 9.052 172.086 12.488 C 172.086 15.901 170.901 18.84 168.532 21.256 C 166.139 23.697 163.248 24.905 159.906 24.905 C 156.541 24.905 153.674 23.697 151.281 21.256 C 148.911 18.84 147.726 15.924 147.726 12.488 C 147.726 9.076 148.911 6.138 151.281 3.721 C 153.674 1.28 156.541 0.071 159.906 0.071 Z  M 159.906 22.156 C 162.513 22.156 164.741 21.209 166.613 19.313 C 168.461 17.418 169.385 15.142 169.385 12.464 C 169.385 9.787 168.461 7.512 166.613 5.617 C 164.765 3.721 162.513 2.773 159.906 2.773 C 157.3 2.773 155.072 3.721 153.2 5.617 C 151.352 7.512 150.427 9.787 150.427 12.464 C 150.427 15.142 151.352 17.418 153.2 19.313 C 155.072 21.233 157.3 22.156 159.906 22.156 Z " class="lastname"/><path d=" M 139.669 21.991 L 150.736 21.991 L 150.736 24.645 L 137.016 24.645 L 137.016 0.332 L 139.669 0.332 L 139.669 21.991 L 139.669 21.991 Z " class="lastname"/><path d=" M 132.939 24.645 L 123.982 9.242 L 123.982 24.645 L 120.19 24.645 L 120.19 0.332 L 123.2 0.332 L 132.11 15.688 L 132.11 0.332 L 135.901 0.332 L 135.901 24.669 L 132.939 24.669 L 132.939 24.645 Z " class="firstname"/><path d=" M 106.446 0 C 109.835 0 112.725 1.232 115.119 3.674 C 117.512 6.114 118.721 9.052 118.721 12.488 C 118.721 15.924 117.512 18.887 115.119 21.327 C 112.725 23.792 109.835 25 106.446 25 C 103.058 25 100.167 23.768 97.749 21.327 C 95.356 18.887 94.147 15.948 94.147 12.488 C 94.147 9.052 95.356 6.114 97.749 3.674 C 100.167 1.232 103.058 0 106.446 0 Z  M 106.446 21.114 C 108.745 21.114 110.735 20.284 112.37 18.602 C 114.006 16.92 114.835 14.882 114.835 12.512 C 114.835 10.143 114.006 8.128 112.37 6.422 C 110.735 4.74 108.745 3.91 106.446 3.91 C 104.125 3.91 102.157 4.74 100.522 6.422 C 98.863 8.105 98.034 10.143 98.034 12.512 C 98.034 14.882 98.863 16.92 100.522 18.602 C 102.157 20.284 104.125 21.114 106.446 21.114 Z " class="firstname"/><path d=" M 86.21 3.91 C 85.166 3.91 84.265 4.242 83.532 4.882 C 82.797 5.522 82.441 6.304 82.441 7.227 C 82.441 8.152 82.726 8.816 83.318 9.265 C 83.863 9.668 84.976 10.119 86.707 10.593 C 88.864 11.185 90.427 11.825 91.375 12.536 C 93.057 13.768 93.911 15.498 93.911 17.773 C 93.911 19.763 93.152 21.47 91.659 22.867 C 90.167 24.289 88.342 25 86.232 25 C 84.1 25 82.3 24.289 80.807 22.867 C 79.313 21.446 78.555 19.763 78.555 17.773 L 82.465 17.773 C 82.465 18.697 82.821 19.478 83.555 20.119 C 84.29 20.783 85.19 21.09 86.232 21.09 C 87.276 21.09 88.175 20.759 88.911 20.119 C 89.645 19.478 90.001 18.697 90.001 17.773 C 90.001 16.802 89.669 16.067 89.029 15.616 C 88.508 15.237 87.394 14.81 85.664 14.336 C 83.532 13.768 81.991 13.128 81.043 12.417 C 79.385 11.209 78.555 9.479 78.555 7.227 C 78.555 5.237 79.313 3.531 80.807 2.109 C 82.3 0.687 84.124 0 86.232 0 C 88.365 0 90.167 0.711 91.659 2.109 C 93.152 3.531 93.911 5.237 93.911 7.227 L 90.001 7.227 C 90.001 6.304 89.645 5.522 88.911 4.882 C 88.153 4.242 87.252 3.91 86.21 3.91 Z " class="firstname"/><path d=" M 71.872 14.147 C 73.579 13.839 74.906 13.175 75.83 12.181 C 77.038 10.925 77.584 9.218 77.513 7.038 C 77.441 5.237 76.849 3.696 75.735 2.417 C 74.503 1.02 72.916 0.309 70.971 0.309 L 62.939 0.309 L 66.73 4.1 L 70.971 4.1 C 72.038 4.1 72.821 4.55 73.294 5.451 C 73.579 5.972 73.721 6.54 73.744 7.18 C 73.792 8.271 73.579 9.076 73.128 9.55 C 72.465 10.237 70.901 10.593 68.413 10.593 L 66.73 10.593 L 62.939 10.593 L 62.939 24.645 L 66.73 24.645 L 66.73 13.389 L 74.645 24.645 L 79.266 24.645 L 71.872 14.147 Z " class="firstname"/><path d=" M 46.849 0.332 L 61.802 0.332 L 61.802 4.124 L 50.641 4.124 L 50.641 10.593 L 58.152 10.593 L 58.152 14.384 L 50.641 14.384 L 50.641 20.924 L 61.802 20.924 L 61.802 24.692 L 46.849 24.692 L 46.849 0.332 Z " class="firstname"/><path d=" M 45.64 0.332 L 45.64 4.124 L 40.403 4.124 L 40.403 24.669 L 36.612 24.669 L 36.612 4.124 L 31.398 4.124 L 31.398 0.332 L 45.64 0.332 Z " class="firstname"/><path d=" M 15.237 0.332 L 30.166 0.332 L 30.166 4.1 L 19.005 4.1 L 19.005 10.569 L 26.517 10.569 L 26.517 14.361 L 19.005 14.361 L 19.005 20.878 L 30.166 20.878 L 30.166 24.645 L 15.237 24.645 L 15.237 0.332 Z " class="firstname"/><path d=" M 13.744 0.332 L 13.744 18.152 C 13.744 20.024 13.08 21.635 11.73 22.987 C 10.379 24.313 8.768 24.976 6.872 24.976 C 4.977 24.976 3.365 24.313 2.014 22.987 C 0.664 21.635 0 20.024 0 18.129 L 3.791 18.129 C 3.791 18.982 4.1 19.716 4.692 20.308 C 5.284 20.924 6.019 21.233 6.872 21.233 C 7.725 21.233 8.436 20.924 9.052 20.332 C 9.645 19.739 9.953 19.005 9.953 18.176 L 9.953 0.356 L 13.744 0.356 L 13.744 0.332 Z " class="firstname"/></svg>
                            </a>

                            <!-- Menu de navegação -->
                            <!-- <div class="main_nav">
                               <div class="menu">
                                    <div class="nav_icon">
                                        <span class="bar1"></span>
                                        <span class="bar2"></span>
                                        <span class="bar3"></span>
                                    </div>
                                    <nav class="nav">
                                        <ul> -->
<?php
// if ($nav) {
//     foreach ($nav as $keyNav) {
//         $section = $keyNav['pg_section'] == 'home' ? null : '/' . $keyNav['pg_section'];
//         $link = $keyNav['pg_link'] == 'home' ? null : '/' . $keyNav['pg_link'];
//         $keyNav['pg_link'] = HOME . $section . $link;
//         echo FNC::view($keyNav, null, null, $listNav);
//     }
// }
?>
                                        <!-- </ul>
                                    </nav>
                                </div> -->
                                <button id="btnSearchs" class="btn btn-main btn_search radius" title="Buscar no blog">
                                    <svg viewBox="0 0 17 17"><path d="M16.533 16.533a1.597 1.597 0 0 1-2.26 0l-2.816-2.818A7.392 7.392 0 0 1 7.45 14.9a7.45 7.45 0 1 1 7.45-7.45 7.4 7.4 0 0 1-1.186 4.007l2.818 2.818a1.596 1.596 0 0 1 0 2.257zM7.45 2.13a5.322 5.322 0 1 0 0 10.642 5.322 5.322 0 0 0 0-10.643z"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>