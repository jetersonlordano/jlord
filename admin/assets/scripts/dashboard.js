// Abre menu de navegação
(function () {

    var overlay, navIcon, navigation, sideBar, sideBarOpen;

    overlay = document.getElementById('overlay');
    navIcon = document.getElementById('navIcon');
    sideBar = document.getElementById('sidebar');
    navigation = document.getElementById('navigation');
    sideBarOpen = false;

    if (sideBar) {

        function sideAction() {
            if (!sideBarOpen) {
                sideBar.dataset.visible = 'true';
                overlay.dataset.visible = 'true';
                sideBarOpen = true;
            } else {
                sideBar.dataset.visible = 'false';
                overlay.dataset.visible = 'false';
                sideBarOpen = false;
            }
        }

        navIcon.addEventListener('click', function () {
            sideAction();
        }, false);

        function ifOpen() {
            if (sideBarOpen) {
                sideAction();
            }
        }

        overlay.addEventListener('click', ifOpen, false);
        window.addEventListener('resize', ifOpen, false);

        // Menu Ativo
        var totalItems, currentSection, item;

        totalItems = navigation.getElementsByClassName('nav_item').length;
        currentSection = navigation.getAttribute('data-target');

        function activeMenu() {

            item = navigation.getElementsByClassName('nav_item');

            for (let i = 0; i < totalItems; i++) {

                if (item[i].dataset.section == currentSection) {
                    item[i].dataset.active = 'true';
                }
            }
        }
        activeMenu();
    }

})();

// Menu do usuário
(function () {

    var navUser = jget('#navUser'),
        userTopbar = jget('#userTopbar'),
        expandNavUser = false;

    jevt(userTopbar, 'click', fncExpandNavUser, !0);

    function fncExpandNavUser(evt) {

        if (!expandNavUser) {
            expandNavUser = true;
            navUser.dataset.expanded = 'true';
            setTimeout(function () {
                jevt(document, 'click', fncExpandNavUser, !0);
            }, 100);
        } else {
            expandNavUser = false;
            navUser.dataset.expanded = 'false';
            jevt(document, 'click', fncExpandNavUser, 0);
        }
    }

})();

// (function () {
// })();