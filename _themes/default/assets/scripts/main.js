"use strict";

/* Search */
(function () {

    var blogSearch, overlay, btnSearchs, searchBarOpen;

    blogSearch = document.getElementById('blogSearch');
    overlay = document.getElementById('overlay');
    btnSearchs = document.getElementById('btnSearchs');
    searchBarOpen = false;



    if (blogSearch) {

        btnSearchs.addEventListener('click', activeSearch, false);
        overlay.addEventListener('click', activeSearch, false);

        function activeSearch() {

            if (!searchBarOpen) {
                blogSearch.dataset.visible = 'true';
                overlay.dataset.visible = 'true';
                searchBarOpen = true;
            } else {
                blogSearch.dataset.visible = 'false';
                overlay.dataset.visible = 'false';
                searchBarOpen = false;
            }
        }
    }


})();


/* Capture EMail List */
(function () {

    var formEmailListHeader, formEmailListFooter, fileCaptureEmailList;

    fileCaptureEmailList = 'themes/default/_req/leads/capture_lead.php';

    formEmailListHeader = document.getElementById('formEmailListHeader');

    if (formEmailListHeader) {
        var objEmailListHeader = {
            file: fileCaptureEmailList,
            loader: 'emailListLoaderHeader',
            resetForm: 'formEmailListHeader'
        };
        submitForm(objEmailListHeader, 'formEmailListHeader');
    }

    formEmailListFooter = document.getElementById('formEmailListFooter');

    if (formEmailListFooter) {
        var objEmailListFooter = {
            file: fileCaptureEmailList,
            loader: 'emailListLoaderFooter',
            resetForm: 'formEmailListFooter'
        };
        submitForm(objEmailListFooter, 'formEmailListFooter');
    }

})();


function clientPostsLoading(obj) {

    var postsContainer, content, dataTotal, result, postPlusBtn;

    content = '<div class="col col-3"><article class="radius"><a class="cover" href="#post_link#" title="#headline#"><img class="img" src="#post_cover#" alt="#headline#"></a><div class="box_content"><h3><a href="#linkcat#" title="Categoria #cat_title#">#cat_title#</a></h3><h2><a href="#post_link#" title="#headline#">#headline#</a></h2><p>#post_description#</p></div></article></div>';

    dataTotal = obj.length;
    postsContainer = document.getElementById('postsContainer');
    postPlusBtn = document.getElementById('postPlusBtn');

    for (var i = 0; i < dataTotal; i++) {
        result = content.replace(/#headline#/gi, obj[i].post_title);
        result = result.replace(/#post_link#/gi, obj[i].post_link);
        result = result.replace(/#cat_title#/gi, obj[i].cat_title);
        result = result.replace(/#post_description#/gi, obj[i].post_description);
        result = result.replace(/#post_cover#/gi, obj[i].post_cover);
        result = result.replace(/#linkcat#/gi, obj[i].post_category);
        postsContainer.innerHTML += result;

        if (i >= (dataTotal - 1) && postPlusBtn) {
            postPlusBtn.dataset.plus = 'true';
        }
    }
}