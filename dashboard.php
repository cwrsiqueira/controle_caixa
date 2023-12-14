<?php
include_once "header.php";
include_once "dashboard-action.php";
?>

<div class="container border rounded mt-3 p-3 shadow bg-light">
    <div class="row">
        <h3><i class="fa-solid fa-chart-line"></i> Dashboard</h3>

        <div class="col">
            <?php
            if (!empty($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
        </div>
    </div>

    <div class="row my-3 d-flex justify-content-end">
        <div class="col-lg-6">
            <form method="get" id="form-filter-date">
                <div class="input-group">
                    <span title="Limpar Busca" class="input-group-text" id="btn-filter-date-clean"><i class="fa-solid fa-rotate-left"></i></span>
                    <span title="Buscar" class="input-group-text" id="btn-filter-date"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="date" name="data_ini" id="data-ini" value="<?= $data_ini ?? date('Y-m-01') ?>" class="form-control">
                    <input type="date" name="data_fin" id="data-fin" value="<?= $data_fin ?? date('Y-m-t') ?>" class="form-control">
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="row">
        <?php foreach ($caixas as $caixa) : ?>
            <div class="col-lg-6">
                <h2 class="fw-bold my-3 fs-5"> <?= $caixa['nome'] ?></h2>
                <canvas id="caixa<?= $caixa['id'] ?>"></canvas>
            </div>
        <?php endforeach; ?>
    </div>


</div>

<?php foreach ($caixas as $caixa) : ?>
    <script>
        let grafico<?= $caixa['id'] ?> = document.querySelector('#caixa<?= $caixa['id'] ?>');

        new Chart(grafico<?= $caixa['id'] ?>, {
            type: 'line',
            data: {
                labels: [<?= $datas; ?>],
                datasets: [{
                        label: "Entradas",
                        data: [<?= $caixa['saldo_entradas']; ?>],
                        borderWidth: 1
                    },
                    {
                        label: "Saidas",
                        data: [<?= $caixa['saldo_saidas']; ?>],
                        borderWidth: 1
                    },
                    {
                        label: "Saldo Atual",
                        data: [<?= $caixa['saldo_total']; ?>],
                        borderWidth: 1
                    },
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
<?php endforeach; ?>

<?php include_once "footer.php"; ?>