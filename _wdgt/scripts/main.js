"use strict";


// Dialog
function dialog(t, h, m, obj, o, c, a) {

    // Declaração das variaveis
    var container, icon, divDialog, divDialogIcon, iIcon, divHeader, divMsg, buttonOkay, buttonCancel, type, okay, cancel, closeTimer;

    type = t.toLowerCase(t.trim());
    okay = (String(o) !== 'undefined' ? o : 'Ok');
    cancel = (String(c) !== 'undefined' ? c : 'Cancelar');
    closeTimer = (String(a) !== 'undefined' ? a : 0);

    // Elemento container da messangem
    container = document.getElementById('return');

    /* Criação do contéudo */

    // Div dialog
    divDialog = document.createElement('div');
    divDialog.className = 'dialog ' + t.trim().toLowerCase();

    // Div dialog-icon
    divDialogIcon = document.createElement('div');
    divDialogIcon.className = 'dialog-icon';

    // Título da mensagem
    divHeader = document.createElement('div');
    divHeader.className = 'dialog-title';
    divHeader.innerHTML = h.trim();

    // Conteúdo da mensagem
    divMsg = document.createElement('div');
    divMsg.className = 'dialog-text';
    divMsg.innerHTML = m.trim();

    // Adiciona o elemento dialog
    container.appendChild(divDialog);

    // Adiciona os demais elementos
    divDialog.appendChild(divDialogIcon);
    divDialog.appendChild(divHeader);
    divDialog.appendChild(divMsg);

    // Muda o icone de acordo com o tipo da mensagem
    switch (type) {
        case 'success':
            icon = 'fa fa-check';
            break;
        case 'warning':
            icon = 'fa fa-exclamation';
            break;
        case 'danger':
            icon = 'fa fa-times';
            break;
        case 'question':
            icon = 'fa fa-question';
            break;
        default:
            icon = 'fa fa-info';
    }

    // Icone
    iIcon = document.createElement('i');
    iIcon.className = icon;
    iIcon.setAttribute("aria-hidden", "true");

    // Botão confirma
    buttonOkay = document.createElement('button');
    buttonOkay.className = 'btn okay';
    buttonOkay.innerHTML = okay;
    

    divDialogIcon.appendChild(iIcon);
    divDialog.appendChild(buttonOkay);

    buttonOkay.focus();

    if (String(type) === 'question') {

        buttonCancel = document.createElement('button');
        buttonCancel.className = 'btn btn-gray cancel';
        buttonCancel.innerHTML = cancel;

        divDialog.appendChild(buttonCancel);

        buttonOkay.addEventListener('click', function () {

            closeDialog();

            if (typeof obj.fn == 'function') {
                setTimeout(function () {
                    obj.fn(obj);
                }, 200);
            }
        }, false);

        buttonCancel.addEventListener('click', function () {
            closeDialog();
        }, false);

    } else {
        buttonOkay.addEventListener('click', function () {
            closeDialog();
        }, false);
    }

    // Evento de teclado
    // setTimeout(function () {
    //     document.addEventListener('keyup', eventKey, false);
    // }, 1000);


    function eventKey(evt) {

        if (String(type) === 'question' && evt.keyCode == 13) {
            if (typeof obj.fn == 'function') {
                setTimeout(function () {
                    obj.fn(obj);
                }, 200);
            }
        }
        closeDialog();
    }

    // Abre a box com animação
    container.style.display = "flex";
    setTimeout(function () {
        container.style.opacity = "1";
        divDialog.style.transform = "scale(1)";
        divDialog.style.webkitTransform = "scale(1)";
        divDialog.style.mozTransform = "scale(1)";
        divDialog.style.msTransform = "scale(1)";
        divDialog.style.oTransform = "scale(1)";
    }, 100);

    // Tempo de fechamento automatico
    if (closeTimer > 0) {
        setTimeout(function () {
            closeDialog();
        }, closeTimer);
    }

    // Metodo que fecha a box
    function closeDialog() {

        // Remove evento de teclado
        if (document.removeEventListener) {
            document.removeEventListener('keyup', eventKey, false);
        }

        divDialog.style.webkitTransform = "scale(0)";
        divDialog.style.MozTransform = "scale(0)";
        divDialog.style.msTransform = "scale(0)";
        divDialog.style.OTransform = "scale(0)";
        divDialog.style.transform = "scale(0)";

        // Reduz a opacidade do container
        setTimeout(function () {
            container.style.opacity = "0";
        }, 100);

        // Remove elemento da página
        setTimeout(function () {
            container.style.display = "none";
            container.innerHTML = '';

            // Limpa as variaveis
            container = null, icon = null, divDialog = null, divDialogIcon = null, iIcon = null, divHeader = null, divMsg = null, buttonOkay = null, buttonCancel = null, type = null, okay = null, cancel = null;

        }, 600);
    }
}

/**
 * Cria um dialog de entrada tipo prompt
 */
function request(obj) {

    // Declaração das variaveis
    var container, divDialog, inputData, divHeader, divMsg, image, imgLabel, select, inputType, inputName, inputPlaceholder, inputOptions, buttonOkay, buttonCancel, okay, cancel, closeTimer, urlHome, baseDir, fileReader;

    urlHome = jget('#urlHome');
    !urlHome ? alert('Configure o element <base> com id=urlHome') : null;
    baseDir = !urlHome.dataset.dir ? urlHome.href : urlHome.href + '/' + urlHome.dataset.dir;

    okay = (String(obj.confirmBtn) !== 'undefined' ? obj.confirmBtn : 'Confirmar');
    cancel = (String(obj.cancelBtn) !== 'undefined' ? obj.cancelBtn : 'Cancelar');
    inputType = (String(obj.inputType) !== 'undefined' ? obj.inputType : 'text');
    inputName = (String(obj.inputName) !== 'undefined' ? obj.inputName : 'data');
    inputPlaceholder = (String(obj.inputPlaceholder) !== 'undefined' ? obj.inputPlaceholder : 'Digite aqui');
    inputOptions = (String(obj.inputOptions) !== 'undefined' ? obj.inputOptions : null);

    // Elemento container da messangem
    container = document.getElementById('return');

    // Div dialog
    divDialog = document.createElement('div');
    divDialog.className = 'dialog';
    container.appendChild(divDialog);

    // Filtra o tipo do input
    switch (String(inputType)) {
        case 'file':

            imgLabel = document.createElement('label');
            imgLabel.className = 'dialog-img';
            imgLabel.setAttribute("for", 'dialog-' + inputName);
            divDialog.appendChild(imgLabel);

            inputData = document.createElement('input');
            inputData.type = inputType;

            // Imagem
            image = document.createElement('img');
            image.src = urlHome.href + '/_defaults/images/image-default.svg';
            inputData.required = 'true';
            imgLabel.appendChild(image);
            inputData.addEventListener('change', changeImage, false);

            break;

        case 'textarea':

            inputData = document.createElement('textarea');

            // Título da mensagem
            divHeader = document.createElement('div');
            divMsg.className = 'dialog-title';
            divHeader.innerHTML = obj.header;

            // Conteúdo da mensagem
            divMsg = document.createElement('div');
            divMsg.className = 'dialog-text';
            divMsg.innerHTML = obj.message;

            divDialog.appendChild(divHeader);
            divDialog.appendChild(divMsg);

            break;

        case 'select':

            inputData = document.createElement('select');

            // Input tipo select
            inputData.required = 'true';
            createOptions(obj.inputOptions);

            // Título da mensagem
            divHeader = document.createElement('div');
            divHeader.className = 'dialog-title';
            divHeader.innerHTML = obj.header;

            // Conteúdo da mensagem
            divMsg = document.createElement('p');
            divMsg.className = 'dialog-text';
            divMsg.innerHTML = obj.message;

            divDialog.appendChild(divHeader);
            divDialog.appendChild(divMsg);

            break;

        default:

            inputData = document.createElement('input');
            inputData.type = inputType;

            // Input tipo texto
            inputData.placeholder = inputPlaceholder;

            // Título da mensagem
            divHeader = document.createElement('h3');
            divHeader.innerHTML = obj.header;

            // Conteúdo da mensagem
            divMsg = document.createElement('p');
            divMsg.innerHTML = obj.message;

            divDialog.appendChild(divHeader);
            divDialog.appendChild(divMsg);

            break;

    }

    // Input tipo select
    inputData.required = 'true';

    // Nome do input
    inputData.name = inputName;
    inputData.id = 'dialog-' + inputName;

    // Cria oções do input select
    function createOptions(obj) {
        var option;
        for (var key in obj) {
            option = document.createElement('option');
            option.value = key;
            option.textContent = obj[key];
            inputData.appendChild(option);
        }
    }

    // Adiciona input
    divDialog.appendChild(inputData);

    // Botão confirma
    buttonOkay = document.createElement('button');
    buttonOkay.className = 'btn okay';
    buttonOkay.innerHTML = okay;
    divDialog.appendChild(buttonOkay);

    buttonCancel = document.createElement('button');
    buttonCancel.className = 'btn btn-gray cancel';
    buttonCancel.innerHTML = cancel;
    divDialog.appendChild(buttonCancel);

    function changeImage(evt) {
        var img, fileReader;
        img = evt.target.files[0];
        fileReader = new FileReader();
        if (img) {
            fileReader.readAsDataURL(img);
            fileReader.addEventListener('loadend', function () {
                image.src = fileReader.result;
            }, false);
        } else {
            image.src = urlHome.href + '/_defaults/images/image-default.svg';
        }
    }

    // Eventos de Teclado
    document.addEventListener('keydown', evtKey, false);
    document.addEventListener('keyup', evtKey, false);
    document.addEventListener('keypress', evtKey, false);

    // Eventos de click
    buttonOkay.addEventListener('click', confirmAction, false);

    function evtKey(evt) {
        if (String(evt.type) == 'keyup') {
            switch (String(evt.code).toLowerCase()) {
                case 'enter':
                    confirmAction();
                    break;
                case 'numpadenter':
                    confirmAction();
                    break;
                case 'escape':
                    closeDialog();
                    break;

                default:
                    // evt.preventDefault();
                    break;
            }

        } else {
            // evt.preventDefault();
        }

    }

    // Enviar os dados para class ajax
    function confirmAction() {
        buttonOkay.removeEventListener('click', confirmAction);
        if (typeof obj.fn == 'function') {

            var formData = new FormData();

            if (String(inputData.value) !== '') {

                if (String(inputType) !== 'file') {
                    formData.append(String(inputName), inputData.value);
                } else {
                    formData.append(String(inputName), inputData.files[0]);
                    formData.append('path', obj.path);
                }

                Object.keys(obj.data).forEach((key) => {
                    formData.append(String(key), obj.data[key]);
                });

                setTimeout(function () {
                    obj.fn(obj.data, formData);
                }, 200);

            }
        } else {
            console.log('Erro interno');
        }
        closeDialog();
    }

    buttonCancel.addEventListener('click', closeDialog, false);

    // Abre a box com animação
    container.style.display = "flex";
    setTimeout(function () {
        container.style.opacity = "1";
        divDialog.style.transform = "scale(1)";
        divDialog.style.webkitTransform = "scale(1)";
        divDialog.style.mozTransform = "scale(1)";
        divDialog.style.msTransform = "scale(1)";
        divDialog.style.oTransform = "scale(1)";
    }, 100);

    // Metodo que fecha a box
    function closeDialog() {

        // Remove eventos
        document.removeEventListener('keydown', evtKey);
        document.removeEventListener('keyup', evtKey);
        document.removeEventListener('keypress', evtKey);

        buttonOkay.removeEventListener('click', confirmAction);
        buttonCancel.removeEventListener('click', closeDialog);

        divDialog.style.webkitTransform = "scale(0)";
        divDialog.style.MozTransform = "scale(0)";
        divDialog.style.msTransform = "scale(0)";
        divDialog.style.OTransform = "scale(0)";
        divDialog.style.transform = "scale(0)";

        // Reduz a opacidade do container
        setTimeout(function () {
            container.style.opacity = "0";
        }, 100);

        // Remove elemento da página
        setTimeout(function () {
            container.style.display = "none";
            container.innerHTML = '';
        }, 600);
    }
}

// Cria um nova notificação
function notify(t, m, obj) {

    // Declaração das variaveis
    var notifyContainer, type, icon, divNotify, divNotifyMsg, divTimer, spanProgress, timeRemove;

    type = t.toLowerCase().trim();

    // Muda o icone de acordo com o tipo da mensagem
    switch (t) {
        case 'success':
            icon = 'fa fa-smile-o';
            break;
        case 'warning':
            icon = 'fa fa-frown-o';
            break;

        case 'danger':
            icon = 'fa fa-frown-o';
            break;
        default:
            icon = 'fa fa-meh-o';
    }

    // Container das notificações
    notifyContainer = document.getElementById('notify');

    divNotify = document.createElement('div');
    divNotify.className = 'notify-box ' + type;

    divNotifyMsg = document.createElement('p');
    divNotifyMsg.className = 'notify-msg';
    divNotifyMsg.innerHTML = '<i class="fa ' + icon + '" aria-hidden="true"></i>' + m.trim();
    divNotify.appendChild(divNotifyMsg);

    divTimer = document.createElement('div');
    divTimer.className = 'timer';
    spanProgress = document.createElement('span');

    divTimer.appendChild(spanProgress);
    divNotify.appendChild(divTimer);
    notifyContainer.appendChild(divNotify);

    // Animação de entrada
    setTimeout(function () {
        divNotify.style.right = '0';

        // Inicia animação do timer
        setTimeout(function () {
            spanProgress.style.width = '100%';

            // Chama o metodo que remove notificação
            timeRemove = timeRemove = setTimeout(function () {
                removeNotify();
            }, 3400);

        }, 300);

    }, 50);

    // Evento para remover a notificação
    divNotify.addEventListener('click', function (evt) {
        clearTimeout(timeRemove);
        removeNotify();
    }, false);

    // Verifica se função de click foi passada
    if (String(obj) !== 'undefined' && String(obj) !== 'null') {
        divNotify.addEventListener('click', function () {
            if (typeof obj.fn == 'function') {
                obj.input = inputData.value;
                setTimeout(function () {
                    obj.fn(obj);
                }, 200);
            }
        }, false);
    }

    // Medoto que remove notificação
    function removeNotify() {
        divNotify.style.right = '-300px';
        setTimeout(function () {
            notifyContainer.removeChild(divNotify);
            notifyContainer = null, divNotify = null, divNotifyMsg = null, divTimer = null, spanProgress = null, timeRemove = null;
        }, 400);
    }
}

/**
 * Atrasa o carregamento das imagens com data-delay
 */
(function () {

    function Delay(e) {

        "use strict";

        function scrollView() {
            if (pointView(e)) {
                render(e);
                window.removeEventListener('scroll', scrollView, false);
            }
        }

        window.addEventListener('scroll', scrollView, false);
        scrollView();
    }


    // Verifica se imagem está no ponto de visualização
    function pointView(e) {
        var coords = e.getBoundingClientRect();
        return (coords.top >= 0 && coords.left >= 0 && coords.top) <= (window.innerHeight || document.documentElement.clientHeight);
    }

    // Troca src da imagem pelo arquivo certo
    function render(e) {
        e.src = e.getAttribute("data-delay");
        e.addEventListener('load', loadImage, false);
    }

    // Ação para quando imagem é carregada
    function loadImage(evt) {
        evt.target.removeAttribute("data-delay");
    }

    // Varre documento e recupera imagens
    var listImgs = document.querySelectorAll('img[data-delay]');
    for (var i = 0; i < listImgs.length; i++) {
        new Delay(listImgs[i]);
    }

})();