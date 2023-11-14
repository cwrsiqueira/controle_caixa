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

var btn_filter_date = document.querySelector('#btn-filter-date');
if (btn_filter_date !== null) {
    btn_filter_date.addEventListener('click', function () {
        document.querySelector('#form-filter-date').submit();
    });
}

var btn_filter_date_clean = document.querySelector('#btn-filter-date-clean');
if (btn_filter_date_clean !== null) {
    btn_filter_date_clean.addEventListener('click', function () {
        document.querySelector('#data-ini').value = '';
        document.querySelector('#data-fin').value = '';
        document.querySelector('#form-filter-date').submit();
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

function modal_edit(id) {
    if (id === undefined) {
        alert('ID inválido');
        return false;
    }

    var ajax = new XMLHttpRequest();

    ajax.open("GET", `ajax.php?id=${id}&action=edit_modal`);
    ajax.setRequestHeader("Content-type", "application/json");
    ajax.send();

    ajax.onreadystatechange = function () {
        if (ajax.readyState === 4) {
            if (ajax.status === 200) {
                let res = JSON.parse(ajax.responseText);
                if (res === 'erro') {
                    alert('Erro! Algo saiu errado, tente novamente.');
                    return false;
                }

                document.querySelector('#edit_id_caixa').value = res.id_caixa;
                document.querySelector('#edit_id_lancamento').value = res.id;
                document.querySelector('#edit_discriminacao_movimento').value = res.discriminacao_movimento;
                document.querySelector('#edit_data_movimento').value = res.data_movimento.split(' ')[0];
                document.querySelector('#edit_valor_movimento').value = parseFloat(res.valor_movimento).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.querySelector('#edit_movimento').value = res.movimento;

            } else {
                console.log(ajax.status);
            }
        }
    }
}
