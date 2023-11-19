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
    // Insere no banco de dados

    $dados['valor_movimento'] = str_replace(',', '.', str_replace('.', '', $dados['valor_movimento']));
    $dados['data_movimento'] = $dados['data_movimento'] . ' ' . date('H:i:s');

    foreach ($dados as $key => $value) {
        $set[] = $key . " = :" . $key;
    }
    $set = implode(', ', $set);

    $sql = $db->prepare("INSERT INTO caixas_lancamentos SET $set");

    foreach ($dados as $key => $value) {
        $sql->bindValue(":{$key}", $value);
    }
    // $sql->bindValue(":id_caixa", $dados['id_caixa']);
    // $sql->bindValue(":discriminacao_movimento", $dados['discriminacao_movimento']);
    // $sql->bindValue(":data_movimento", $dados['data_movimento']);
    // $sql->bindValue(":valor_movimento", $dados['valor_movimento']);
    // $sql->bindValue(":movimento", $dados['movimento']);
    if (!$sql->execute()) {
        $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! Lançamento não criado.</p>";
        header("Location: show-caixa.php?id=" . $dados['id_caixa']);
        exit;
    }
    $_SESSION['msg'] = "<p class='alert alert-success'><i class='fa-regular fa-thumbs-up'></i> Sucesso! Lançamento criado.</p>";
    header("Location: show-caixa.php?id=" . $dados['id_caixa']);
    exit;
} else {
    $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! Todos os campos devem estar preenchidos.</p>";
    header("Location: show-caixa.php?id=" . $dados['id_caixa']);
    exit;
}
