<?php
session_start();
include_once "db.php";

$dados = filter_input_array(INPUT_POST, $_POST, FILTER_DEFAULT);

if ($dados['nome'] == '') {
    $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! O campo nome é obrigatório.</p>";
    header("Location: index.php");
    exit;
}

$dados['saldo_inicial'] = str_replace(',', '.', str_replace('.', '', $dados['saldo_inicial']));

$sql = $db->prepare("SELECT id FROM caixas WHERE nome = :nome AND id <> :id");
$sql->bindValue(":nome", $dados['nome']);
$sql->bindValue(":id", $dados['id']);
$sql->execute();
if ($sql->rowCount() > 0) {
    $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! Caixa já existe. Tente outro nome.</p>";
    header("Location: show-caixa.php?id=" . $dados['id']);
    exit;
}

$sql = $db->prepare("UPDATE caixas SET nome = :nome, saldo_inicial = :saldo_inicial WHERE id = :id");
$sql->bindValue(":nome", $dados['nome']);
$sql->bindValue(":saldo_inicial", $dados['saldo_inicial']);
$sql->bindValue(":id", $dados['id']);
if (!$sql->execute()) {
    $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! Caixa não editado. Tente novamente.</p>";
    header("Location: show-caixa.php?id=" . $dados['id']);
    exit;
}

$_SESSION['msg'] = "<p class='alert alert-success'><i class='fa-solid fa-thumbs-up'></i> Sucesso! Caixa alterado.</p>";
header("Location: show-caixa.php?id=" . $dados['id']);
exit;
