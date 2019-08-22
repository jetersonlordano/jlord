'use strict';

function closeElement(e, t) {
    t = !t ? 400 : t;
    e.style.opacity = "0";
    setTimeout(function () {
        e.style.display = "none";
        e.innerHTML = '';
    }, t);
}

function jget(e, p) {
    p = !p ? document : p;
    var g
    switch (e.substring(0, 1)) {
        case '#':
            g = "getElementById";
            e = e.substring(1);
            break;
        case '.':
            g = "getElementsByClassName";
            e = e.substring(1);
            break;
        default:
            g = "getElementsByTagName";
    }
    return p[g](e);
}

/**
 * @param {Object} e Objeto foco do evento
 * @param {String} t Tipos de eventos separados por espaça
 * @param {Object} f Método ouvinte do evento
 * @param {Boolean} r Referencia do evento !0
 * @param {Boolean} c Capture
 */
function jevt(e, t, f, r, c) {
    var s = t.split(" ");
    for (var i = 0; i < s.length; i++) {
        e[r ? "addEventListener" : "removeEventListener"](s[i], f, c);
    }
}

// Verifica tamanha da arquivo
function jCheckSize(file, maxSize) {
    return file.size <= (maxSize * (1024 * 1024));
}

function ajax(obj, formData) {

    var urlHome, baseDir, objJson, loader, pct;

    urlHome = jget('#urlHome');
    !urlHome ? alert('Configure o element <base> com id=urlHome') : null;
    baseDir = obj.urlhome ? urlHome.href : (!urlHome.dataset.dir ? urlHome.href : urlHome.href + '/' + urlHome.dataset.dir);
    reqAjax(objXML(), (!formData ? obj : formData), baseDir + '/' + obj.file, ajaxCallback);

    function objXML() {
        return new(window.XMLHttpRequest || ActiveXObject)('MSXML2.XMLHTTP.3.0');
    }

    function reqAjax(objAjax, dados, urlData, callback) {
        if (String(obj.loader) !== 'undefined') {
            loader = jget('#' + obj.loader);
            activeLoader('block');
        }
        jevt(objAjax, 'readystatechange', ajaxCallback, !0);
        jevt(objAjax.upload, 'abort error load loadend loadstart progress', ajaxProgress, !0);
        objAjax.open('POST', urlData, true);
        if (String(formData).indexOf('FormData') == -1) {
            objAjax.setRequestHeader("Content-type", "application/json; charset=utf-8");
            objAjax.send(JSON.stringify(dados));
        } else {
            objAjax.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            objAjax.send(dados);
        }
    }

    function ajaxProgress(evt) {
        pct = Math.floor((evt.loaded * 100) / evt.total);

        switch (evt.type) {
            case 'loadstart':
                break;
            case 'progress':
                break;
            default:
        }
    };


    function activeLoader(display) {
        if (loader) loader.style.display = display
    }


    function clearForm() {
        if (String(obj.resetForm) !== 'undefined') jget('#' + obj.resetForm).reset()
    }


    function ajaxCallback(evt) {

        if (evt.target.readyState == 4) {
            clearForm();
            activeLoader('none');
            try {
                objJson = JSON.parse(evt.target.responseText);
                ajaxAction(objJson);
            } catch (e) {
                console.log(evt.target.responseText);
            }
        }
    }
}

function ajaxAction(obj) {

    switch (obj.action) {

        case 'function':
            var fnstring = String(obj.fn);
            var fn = window[fnstring];
            if (typeof fn === "function") fn.apply(null, [obj.data]);
            break;
        case 'notify':
            new notify(obj.type, obj.message);
            break;

        case 'dialog':
            new dialog(obj.type, obj.header, obj.message);
            break;

        case 'imagereload':
            jget('#' + obj.imgid).src = obj.imgsrc + '?t=' + new Date().getTime();
            new notify('success', obj.message);
            break;

        case 'close':
            closeElement(jget('#' + obj.element));
            break;

        case 'insert':
            jget('#' + obj.element).innerHTML += obj.content;
            break;

        case 'add':
            jget('#' + obj.element).innerHTML += obj.content;
            new notify('success', obj.message);
            break;

        case 'removed':
            new notify('success', obj.message);
            closeElement(jget('#' + obj.element));
            break;

        case 'redirect':
            window.location.href = obj.url;
            break;

        case 'reload':
            window.location.reload();
            break;

        case 'msg':
            var divReturn = jget('#return');
            divReturn.style.display = 'block';
            divReturn.innerHTML = obj.message;
            break;
    }
}

function submitForm(objAjax, formID) {
    var formAjax;
    formAjax = jget('#' + formID);
    jevt(formAjax, 'submit', submitData, !0);

    function submitData(evt) {
        evt.preventDefault();

        var formData = new FormData(evt.currentTarget);
        ajax(objAjax, formData);

        jevt(formAjax, 'submit', submitData, 0);
        setTimeout(function () {
            jevt(formAjax, 'submit', submitData, !0);
        }, 1000);
    }
}

function newDataAsync(btnId, file) {
    var newBtn = jget('#' + btnId);
    jevt(newBtn, 'click', newData, !0);

    function newData(evt) {
        ajax({
            file: 'async/' + file
        });
        jevt(evt.target, 'click', newData, 0);
        setTimeout(function () {
            jevt(newBtn, 'click', newData, !0);
        }, 200);
    }
}

function delDataAync(container, btnClass, file) {
    var pContainer, btnDel, pLength;
    pContainer = jget('#' + container);
    btnDel = jget('.' + btnClass, pContainer);
    pLength = btnDel.length;
    for (var i = 0; i < pLength; i++) {
        btnDel[i].addEventListener('click', delData);
    }

    function delData(evt) {
        var delObj = {
            fn: ajax,
            file: 'async/' + file,
            id: this.dataset.id
        };
        dialog('question', 'Excluir dados', 'Deseja excluir da base de dados?', delObj);
    }
}


function jReplaceImg(inputId, imgId, file, limit) {

    var inputImg = jget('#' + inputId);
    jevt(inputImg, 'change', jUploadImg, !0);

    function jUploadImg(evt) {
        if (jCheckSize(evt.target.files[0], limit)) {

            var formData = new FormData();
            formData.append('midia', evt.target.files[0]);
            formData.append('id', inputImg.dataset.id);
            formData.append('callback', imgId);

            ajax({
                file: file
            }, formData);

        } else {
            new notify('info', 'Arquivo muito grande! Respeite o limite de ' + limit + 'MB.');
        }

        evt.currentTarget.value = '';

        jevt(evt.target, 'change', jUploadImg, 0);
        setTimeout(function () {
            jevt(evt.target, 'change', jUploadImg, !0);
        }, 500);
    }
}