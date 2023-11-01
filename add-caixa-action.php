<?php
session_start();
include_once "db.php";

$dados = filter_input_array(INPUT_POST, $_POST, FILTER_DEFAULT);

if ($dados['nome'] == '') {
    $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! O campo nome é obrigatório.</p>";
    header("Location: index.php");
    exit;
}

$dados['saldo_inicial'] = str_replace('.', '', $dados['saldo_inicial']);
$dados['saldo_inicial'] = str_replace(',', '.', $dados['saldo_inicial']);

$sql = $db->prepare("SELECT id FROM caixas WHERE nome = :nome");
$sql->bindValue(":nome", $dados['nome']);
$sql->execute();
if ($sql->rowCount() > 0) {
    $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! Caixa já existe. Tente novamente com outro nome.</p>";
    header("Location: index.php");
    exit;
}

$sql = $db->prepare("INSERT INTO caixas SET nome = :nome, saldo_inicial = :saldo_inicial");
$sql->bindValue(":nome", $dados['nome']);
$sql->bindValue(":saldo_inicial", $dados['saldo_inicial']);
if (!$sql->execute()) {
    $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! Caixa não criado. Tente novamente.</p>";
    header("Location: index.php");
    exit;
}

$_SESSION['msg'] = "<p class='alert alert-success'><i class='fa-regular fa-thumbs-up'></i> Sucesso! Caixa criado.</p>";
header("Location: index.php");
exit;
