(function () {

    var postsContainer, postsObjLoader, postPlusBtn, postsPage;

    postsContainer = document.getElementById('postsContainer');
    postPlusBtn = document.getElementById('postPlusBtn');
    postPlusBtn.addEventListener('click', postsLoader);
    postsPage = 1;

    function postsLoader() {
        if (postPlusBtn.dataset.plus == 'true') {
            postPlusBtn.dataset.plus = 'false';
            postsPage++;
            postsObjLoader = {
                file: 'themes/default/_req/posts/post_loader.php',
                loader: 'loaderPosts',
                page: postsPage
            }
            ajax(postsObjLoader);
        }
    }
  
})();