// EDITOR DE CONTEÚDO
var editor = new MediumEditor('.editable', {
    imageDragging: false,
    toolbar: {
        buttons: ['bold', 'italic',
            'underline', 'strikethrough', 'h2', 'h3', 'h4', 'h5', 'quote', 'anchor', 'superscript', 'subscript', 'orderedlist', 'unorderedlist', 'pre', 'html'
        ],
        relativeContainer: document.getElementById('editor-textarea')
    },
    anchor: {
        targetCheckbox: true
    },
    paste: {
        forcePlainText: false,
        cleanPastedHTML: true,
        cleanAttrs: ['style', 'dir'],
        cleanTags: ['label', 'meta', 'span'],
    },

});

// Inserir conteúdo no editor de texto do post
function insertContent(str) {
    editor.pasteHTML(str);
}