<?php
include_once "header.php";
include_once "show-caixa-action.php";
?>
<div class="container border rounded mt-3 p-3 shadow bg-light">

    <div class="row">
        <div class="col">
            <h3><i class="fa-solid fa-circle-exclamation"></i> Detalhes do Caixa</h3>
        </div>
        <div class="col d-flex justify-content-end align-items-center">
            <button class="btn btn-sm btn-light"><a href="index.php"><i class="fa-solid fa-chevron-left"></i></a></button>
            <small>Controle de Caixas</small>
        </div>
    </div>
    <hr>

    <div class="row mt-3">
        <div class="col">
            <h4><i class="fa-solid fa-pen-to-square"></i> Editar Caixa</h4>
        </div>
    </div>
    <div class="row mt-3">
        <form action="edit-caixa.php" method="post" class="needs-validation" novalidate>
            <input type="hidden" name="id" value="<?= $id; ?>">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="nome" class="form-label">Nome do Caixa</label>
                        <input type="text" name="nome" class="form-control" id="nome" value="<?= $caixa['nome']; ?>" required>
                        <div class="invalid-feedback">O campo é obrigatório.</div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="saldo_inicial" class="form-label">Saldo Inicial</label>
                        <input type="text" name="saldo_inicial" id="saldo_inicial" class="form-control mask_value" value="<?= $caixa['saldo_inicial']; ?>">
                    </div>
                </div>
                <div class="col-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>
    </div>
    <hr>
    <div class="row mt-3">
        <div class="col">
            <h4><i class="fa-solid fa-pen-clip"></i> Lançamentos</h4>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add-new-lancamento">Adicionar Lançamento</button>
            <div class="row mt-3">
                <?= $msg; ?>
            </div>
            <div class="d-flex justify-content-end">
                <p><small>Saldo Atual:</small> R$ <?= number_format($saldo_atual, 2, ',', '.'); ?></p>
            </div>
        </div>
    </div>

    <div class="row mt-3 d-flex justify-content-end">
        <div class="col-4">
            <form method="get" id="form-filter-date">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="action" value="show">
                <div class="input-group">
                    <span title="Limpar Busca" class="input-group-text" id="btn-filter-date-clean"><i class="fa-solid fa-rotate-left"></i></span>
                    <span title="Buscar" class="input-group-text" id="btn-filter-date"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="date" name="data-ini" id="data-ini" value="<?= $data_ini ?? date('Y-m-01') ?>" class="form-control">
                    <input type="date" name="data-fin" id="data-fin" value="<?= $data_fin ?? date('Y-m-t') ?>" class="form-control">
                </div>
            </form>
        </div>
    </div>

    <div class="row mt-3">
        <div class="table-responsive">
            <table class="table table-hover">
                <tr>
                    <th>Data Movimento</th>
                    <th>Discriminação</th>
                    <th>Entrada</th>
                    <th>Saida</th>
                    <th>Saldo</th>
                    <th></th>
                </tr>
                <tr>
                    <td><?= date('d/m/Y', strtotime($data_ini)) ?></td>
                    <td>Saldo Anterior</td>
                    <td>-</td>
                    <td>-</td>
                    <td><?= number_format($saldo_inicial, 2, ',', '.'); ?></td>
                    <td></td>
                </tr>
                <?php foreach ($lancamentos as $lancamento) : ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($lancamento['data_movimento'])) ?></td>
                        <td><?= $lancamento['discriminacao_movimento'] ?></td>
                        <td><?= ($lancamento['movimento'] == 'entrada') ? number_format($lancamento['valor_movimento'], 2, ',', '.') : ' - '; ?></td>
                        <td><?= ($lancamento['movimento'] == 'saida') ? number_format($lancamento['valor_movimento'], 2, ',', '.') : ' - '; ?></td>
                        <td><?= number_format($lancamento['saldo_atual'], 2, ',', '.') ?></td>
                        <td><i data-bs-toggle="modal" data-bs-target="#edit-lancamento" class="fa-solid fa-pen-to-square action-icon-edit" onclick="modal_edit(<?= $lancamento['id'] ?>)"></i></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <!-- Modal Adicionar Lançamento -->
    <div class="modal fade" id="add-new-lancamento" tabindex="-1" aria-labelledby="add-new-lancamentoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="add-lancamento.php" method="post" class="needs-validation" novalidate>
                    <input type="hidden" name="id_caixa" value="<?= $id ?>">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="add-new-lancamentoLabel">Adicionar Lançamento</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="discriminacao_movimento" class="form-label">Discriminação do Movimento</label>
                            <input type="text" name="discriminacao_movimento" id="discriminacao_movimento" class="form-control" required>
                            <div class="invalid-feedback">O campo é obrigatório.</div>
                        </div>
                        <div class="form-group">
                            <label for="data_movimento" class="form-label">Data do Lançamento</label>
                            <input type="date" name="data_movimento" id="data_movimento" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                            <div class="invalid-feedback">O campo é obrigatório.</div>
                        </div>
                        <div class="form-group">
                            <label for="valor_movimento" class="form-label">Valor do Lançamento</label>
                            <input type="text" name="valor_movimento" id="valor_movimento" class="form-control mask_value" required>
                            <div class="invalid-feedback">O campo é obrigatório.</div>
                        </div>
                        <div class="form-group">
                            <label for="movimento" class="form-label">Tipo do Movimento</label>
                            <select name="movimento" id="movimento" class="form-control" required>
                                <option value="entrada">Entrada</option>
                                <option value="saida">Saida</option>
                            </select>
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

    <!-- Modal Editar Lançamento -->
    <div class="modal fade" id="edit-lancamento" tabindex="-1" aria-labelledby="edit-lancamentoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="edit-lancamento.php" method="post" class="needs-validation" novalidate>
                    <input type="hidden" name="id_caixa" id="edit_id_caixa" value="<?= $id ?>" required>
                    <input type="hidden" name="id_lancamento" id="edit_id_lancamento" required>
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="edit-lancamentoLabel">Editar Lançamento</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="discriminacao_movimento" class="form-label">Discriminação do Movimento</label>
                            <input type="text" name="discriminacao_movimento" id="edit_discriminacao_movimento" class="form-control" required>
                            <div class="invalid-feedback">O campo é obrigatório.</div>
                        </div>
                        <div class="form-group">
                            <label for="data_movimento" class="form-label">Data do Lançamento</label>
                            <input type="date" name="data_movimento" id="edit_data_movimento" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                            <div class="invalid-feedback">O campo é obrigatório.</div>
                        </div>
                        <div class="form-group">
                            <label for="valor_movimento" class="form-label">Valor do Lançamento</label>
                            <input type="text" name="valor_movimento" id="edit_valor_movimento" class="form-control mask_value" required>
                            <div class="invalid-feedback">O campo é obrigatório.</div>
                        </div>
                        <div class="form-group">
                            <label for="movimento" class="form-label">Tipo do Movimento</label>
                            <select name="movimento" id="edit_movimento" class="form-control" required>
                                <option value="entrada">Entrada</option>
                                <option value="saida">Saida</option>
                            </select>
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