"use strict";
(function () {

    var coverImg, coverInput;

    // Atualiza post
    submitForm({
        file: 'async/posts/update.php',
        loader: 'loaderPost'
    }, 'postForm');

    // Upload Avatar
    jReplaceImg('postCoverInput', 'postCoverImg', 'async/posts/upload_cover.php', 4);

})();



/**
 * Faz upload da imagens de conteúdo
 */
function uploadImageEditor() {

    "use strict";

    var postEditor, actionsToolbarEditor, btnImage, imgLi, mediumEditorElement, postPath;

    postEditor = document.getElementById('content');
    actionsToolbarEditor = postEditor.getElementsByClassName('medium-editor-toolbar-actions')[0];
    mediumEditorElement = postEditor.getElementsByClassName('editable')[0];

    /** Sistema de upload de imagem direto no post **/

    // Cria o botão de imagem
    imgLi = document.createElement('li');
    btnImage = document.createElement('button');
    btnImage.className = "medium-editor-action";
    btnImage.title = "Imagem";
    btnImage.innerHTML = '<i class="fa fa-picture-o"></i>';
    btnImage.type = 'button';
    actionsToolbarEditor.appendChild(imgLi);
    imgLi.appendChild(btnImage);

    // Recupera nome do diretório para enviar imagem
    postPath = document.getElementById('path');

    // Ação click para upload de imagem
    btnImage.addEventListener('click', function () {

        // Verifica se textArea esta em foco
        if (mediumEditorElement.getAttribute('data-medium-focused')) {

            var newObj = {
                path: postPath.value,
                inputType: 'file',
                inputName: 'midia',
                fn: ajax,
                data: {
                    file: 'async/posts/upload_image.php'
                }
            };

            request(newObj);
        }

    }, false);
}
// Cria botão e ação de inserir imagens no conteúdo
uploadImageEditor();