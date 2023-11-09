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
                <?php $msg; ?>
            </div>
            <div class="d-flex justify-content-end">
                <p><small>Saldo Atual:</small> R$ 0,00</p>
            </div>
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
                    <td>00/00/000</td>
                    <td>Saldo Anterior</td>
                    <td>-</td>
                    <td>-</td>
                    <td>0,00</td>
                    <td></td>
                </tr>
                <tr>
                    <td>00/00/000</td>
                    <td>Lançamento ref. recebimento</td>
                    <td>100,00</td>
                    <td>-</td>
                    <td>100,00</td>
                    <td><i class="fa-solid fa-pen-to-square"></i></td>
                </tr>
                <tr>
                    <td>00/00/000</td>
                    <td>Lançamento ref. pagamento</td>
                    <td>-</td>
                    <td>50,00</td>
                    <td>50,00</td>
                    <td><i class="fa-solid fa-pen-to-square"></i></td>
                </tr>
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