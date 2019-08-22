"use strict";
(function () {

    var postMidias, postMidiaCover, videoURL;

    postMidias = document.getElementById('postMidias');
    postMidiaCover = document.getElementById('postMidiaCover');

    if (postMidias.dataset.video && postMidiaCover) {
        postMidiaCover.addEventListener('click', postVideo);
        videoURL = postMidias.dataset.video;
    }

    function postVideo() {

        if (videoURL.match(/vimeo.com/)) {
            postMidias.innerHTML = getVideo(VimeoGetID(videoURL), 'vimeo');
        } else {
            postMidias.innerHTML = getVideo(YouTubeGetID(videoURL), 'youtube');
        }
    }


    // Posts recomendados
    var postsContainer, postsObjLoader, postID;

    postsContainer = document.getElementById('postsContainer');
    postID = postsContainer.dataset.postid;

    function postsLoader() {
        postsObjLoader = {
            file: 'themes/default/_req/posts/post_loader.php',
            loader: 'loaderPosts',
            page: 1,
            id: postID,
            qtd: 3
        }
        ajax(postsObjLoader);
    }
    postsLoader();

})();


// Inseri um iframe com vídeo do Youtube ou Vimeo no editor de texto
function getVideo(videoID, source) {
    switch (source) {
        case 'youtube':
            return '<div class="video-container"><iframe allow="autoplay; encrypted-media" allowfullscreen frameborder="0" src="https://www.youtube.com/embed/' + videoID + '?rel=0&amp;showinfo=0&autoplay=1"></iframe></div>';
            break;

        case 'vimeo':
            return '<div class="video-container"><iframe src="https://player.vimeo.com/video/' + videoID + '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
            break;

        default:
            return '<div class="video-container"><video controls><source src="' + videoID + '" type="video/mp4" /></video></div>';
    }
}

// Recupera ID do vídeo hospedado no Youtube
function YouTubeGetID(url) {
    var ID = '';
    url = url.replace(/(>|<)/gi, '').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/);
    if (url[2] !== undefined) {
        ID = url[2].split(/[^0-9a-z_\-]/i);
        ID = ID[0];
    } else {
        ID = url;
    }
    return ID;
}

// Recupera ID do vídeo hospedado no Vimeo
function VimeoGetID(url) {
    return url.split('/')[videoURL.length - 1];
}