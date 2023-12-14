<?php
include_once "header.php";
include_once "index-action.php";
?>

<div class="container border rounded mt-3 p-3 shadow bg-light">
    <div class="row">
        <h3><i class="fa-solid fa-cash-register"></i> Controle de Caixas</h3>
    </div>

    <div class="row mt-3">
        <div class="col-lg mb-2">
            <button class="btn btn-success btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#add-new-caixa">
                <i class="fa-solid fa-plus"></i> Criar Novo Caixa
            </button>
        </div>
        <div class="col-lg mb-2">
            <form method="get" id="form-search">
                <div class="input-group">
                    <span class="input-group-text" title="Limpar Busca" id="btn-clean">
                        <i class="fa-solid fa-rotate-left"></i>
                    </span>
                    <span class="input-group-text" title="Buscar" id="btn-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="search" name="busca" id="input-search" class="form-control" placeholder="Busca..." value="<?= $busca ?? ''; ?>">
                </div>
            </form>
        </div>
        <div class="col-lg">
            <form method="get" class="d-flex w-full justify-content-center" id="form-select-rpp">
                <input type="hidden" name="p" value="<?= '1'; ?>">
                <input type="hidden" name="busca" value="<?= $busca ?? ''; ?>">
                <div class="input-group">
                    <span class="input-group-text">Mostrar</span>

                    <select name="rpp" id="rpp" class="border btn btn-outline-secondary">
                        <option <?= $rpp == '10' ? 'selected' : '' ?> value="10">10</option>
                        <option class="<?= $qt_registros >= 20 ? '' : 'd-none' ?>" <?= $rpp == '20' ? 'selected' : '' ?> value="20">20</option>
                        <option class="<?= $qt_registros >= 40 ? '' : 'd-none' ?>" <?= $rpp == '40' ? 'selected' : '' ?> value="40">40</option>
                        <option <?= $rpp == 'todos' ? 'selected' : '' ?> value="todos">Todos</option>
                    </select>

                    <span class="input-group-text">resultados por página</span>
                </div>
            </form>
        </div>
    </div>

    <div class="row mt-3">
        <div class="busca-result d-flex justify-content-between">
            <p>Mostrando <?= $offset + 1 ?> a <?= ($offset + $limit) > $qt_registros ? $qt_registros : ($offset + $limit) ?> de <?= $qt_registros ?></p>
            <p>Página <?= $p ?> de <?= $qt_paginas ?></p>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <?php
            if (!empty($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
        </div>
        <div class="col" style="height: 74px;">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-end">
                    <li class="page-item <?= $p <= 2 ? 'd-none' : '' ?>">
                        <a title="Primeira Página" href="?p=1&busca=<?= $busca ?>&rpp=<?= $rpp ?>" class="page-link" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item <?= $p <= 1 ? 'd-none' : '' ?>">
                        <a href="?p=<?= $p - 1 ?>&busca=<?= $busca ?>&rpp=<?= $rpp ?>" class="page-link <?= $p <= 2 ? 'rounded-start' : '' ?>" aria-label="Previous">
                            <span aria-hidden="true"><?= $p - 1  ?></span>
                        </a>
                    </li>
                    <li class="page-item active">
                        <span class="page-link <?= $p <= 1 ? 'rounded-start' : '' ?> <?= $p >= $qt_paginas ? 'rounded-end' : '' ?>" aria-hidden="true"><?= $p ?></span>
                    </li>
                    <li class="page-item <?= $p >= $qt_paginas ? 'd-none' : '' ?>">
                        <a href="?p=<?= $p + 1 ?>&busca=<?= $busca ?>&rpp=<?= $rpp ?>" class="page-link <?= $p >= $qt_paginas - 1 ? 'rounded-end' : '' ?>" aria-label="Previous">
                            <span aria-hidden="true"><?= $p + 1 ?></span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a title="Última Página" href="?p=<?= $qt_paginas ?>&busca=<?= $busca ?>&rpp=<?= $rpp ?>" class="page-link <?= $p >= $qt_paginas - 1 ? 'd-none' : '' ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Caixa</th>
                        <th>Saldo Atual</th>
                        <th colspan="2" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($caixas as $item) : ?>
                        <tr>
                            <td>#<?= $item['id'] ?></td>
                            <td><?= $item['nome'] ?></td>
                            <td>R$ <?= number_format($item['saldo_inicial'], 2, ',', '.') ?></td>
                            <td class="text-center">
                                <a title="Detalhes do Caixa" href="show-caixa.php?id=<?= $item['id'] ?>">
                                    <i class="fa-solid fa-circle-info action-icon text-warning"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <i title="Excluir Caixa" class="fa-solid fa-trash-can action-icon icon-delete text-danger" data-bs-toggle="modal" data-bs-target="#delete-caixa" data-id="<?= $item['id'] ?>"></i>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Criar Novo Caixa -->
    <div class="modal fade" id="add-new-caixa" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="add-caixa-action.php" method="post" class="needs-validation" novalidate>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Criar Novo Caixa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nome" class="form-label">Nome do Caixa</label>
                            <input type="text" name="nome" id="nome" class="form-control" required>
                            <div class="invalid-feedback">O campo é obrigatório.</div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="saldo_inicial" class="form-label">Saldo Inicial</label>
                            <input type="text" name="saldo_inicial" id="saldo_inicial" class="form-control mask_value">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Confirma exclusão do caixa -->
    <div class="modal fade" id="delete-caixa" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="delete-caixa-action.php">
                    <input type="hidden" name="id" value="" id="input-delete-id">
                    <div class="modal-header bg-danger text-light">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="fa-solid fa-triangle-exclamation"></i> ATENÇÃO!!!
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Confirma a exclusão do caixa e todos os lançamentos vinculados?</p>
                        <p>Importante: Esta ação não pode ser desfeita.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" name="action" value="deletar">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>

<?php include_once "footer.php"; ?>