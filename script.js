$(function () {
    $(".mask_value").mask("#.##0,00", { reverse: true });
});

var btn_search = document.querySelector('#btn-search');
if (btn_search !== null) {
    btn_search.addEventListener('click', function () {
        document.querySelector('#form-search').submit();
    });
}

var btn_clean = document.querySelector("#btn-clean");
if (btn_clean !== null) {
    btn_clean.addEventListener('click', function () {
        document.querySelector('#input-search').value = "";
        document.querySelector('#form-search').submit();
    });
}

var rpp = document.querySelector('#rpp');
if (rpp !== null) {
    rpp.addEventListener('change', function () {
        document.querySelector('#form-select-rpp').submit();
    });
}

var icons_delete = document.querySelectorAll('.icon-delete');
icons_delete.forEach(item => {
    item.addEventListener('click', function (e) {
        var id = e.target.dataset.id;
        document.querySelector('#input-delete-id').value = id;
    });
});
