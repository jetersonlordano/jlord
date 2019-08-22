"use strict";
(function () {

    // Atualiza post
    submitForm({file: 'async/business/update.php'}, 'busForm');

    var busLogoImg, busLogoInput;

    // Upload Logo
    busLogoImg = jget('#busLogoImg');
    busLogoInput = jget('#busLogoInput');
    jevt(busLogoInput, 'change', uploadPostLogo, !0);

    function uploadPostLogo(evt) {

        if (checkImageSize(evt.currentTarget.files[0], 3)) {
            var formData = new FormData();
            formData.append('midia', evt.currentTarget.files[0]);
            formData.append('logo', busLogoImg.dataset.name);
            formData.append('id', busLogoImg.dataset.id);
            formData.append('callback', 'busLogoImg');
            ajax({file: 'async/business/upload_logo.php'}, formData);
        } else {new notify('info', 'Arquivo muito grande! Respeite o limite de 3MB.');}

        evt.currentTarget.value = '';

        jevt(evt.currentTarget, 'change', uploadPostLogo, 0);
        setTimeout(function () {jevt(busLogoInput, 'change', uploadPostLogo, !0);}, 500);
    }

})();



function checkImageSize(file, maxSize) {
    return file.size <= (maxSize * (1024 * 1024));
}