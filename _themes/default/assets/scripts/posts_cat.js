/* posts cat*/
(function () {

    var postsContainer, postsObjLoader, postPlusBtn, postsPage, category, tag;

    postsContainer = document.getElementById('postsContainer');
    postPlusBtn = document.getElementById('postPlusBtn');
    postPlusBtn.addEventListener('click', postsLoader);
    postsPage = 1;
    category = postsContainer.dataset.category;
    tag = postsContainer.dataset.tag;



    function postsLoader() {
        if (postPlusBtn.dataset.plus == 'true') {
            postPlusBtn.dataset.plus = 'false';
            postsPage++;

            postsObjLoader = {
                file: 'themes/default/_req/posts/posts_loader_cat.php',
                loader: 'loaderPosts',
                page: postsPage,
                category: category
            }

            if (tag) {
                postsObjLoader = {
                    file: 'themes/default/_req/posts/posts_loader_cat.php',
                    loader: 'loaderPosts',
                    page: postsPage,
                    tag: tag
                }
            }
            ajax(postsObjLoader);
        }
    }
})();