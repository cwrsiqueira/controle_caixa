<?php
session_start();
include_once "db.php";

$dados = filter_input_array(INPUT_POST);

$campo_vazio = false;
foreach ($dados as $item) {
    if ($item == '') {
        $campo_vazio = true;
    }
}

if (!$campo_vazio) {

    $dados['valor_movimento'] = str_replace(',', '.', str_replace('.', '', $dados['valor_movimento']));
    $dados['data_movimento'] = $dados['data_movimento'] . ' ' . date('H:i:s');

    $sql = $db->prepare("UPDATE caixas_lancamentos SET id_caixa = :id_caixa, movimento = :movimento, data_movimento = :data_movimento, discriminacao_movimento = :discriminacao_movimento, valor_movimento = :valor_movimento WHERE id = :id");

    $sql->bindValue(":id", $dados['id_lancamento']);
    $sql->bindValue(":id_caixa", $dados['id_caixa']);
    $sql->bindValue(":movimento", $dados['movimento']);
    $sql->bindValue(":data_movimento", $dados['data_movimento']);
    $sql->bindValue(":discriminacao_movimento", $dados['discriminacao_movimento']);
    $sql->bindValue(":valor_movimento", $dados['valor_movimento']);

    if (!$sql->execute()) {
        $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! Lançamento não editado.</p>";
        header("Location: show-caixa.php?id=" . $dados['id_caixa']);
        exit;
    }
    $_SESSION['msg'] = "<p class='alert alert-success'><i class='fa-regular fa-thumbs-up'></i> Sucesso! Lançamento editado.</p>";
    header("Location: show-caixa.php?id=" . $dados['id_caixa']);
    exit;
} else {
    $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! Todos os campos devem estar preenchidos.</p>";
    header("Location: show-caixa.php?id=" . $dados['id_caixa']);
    exit;
}
