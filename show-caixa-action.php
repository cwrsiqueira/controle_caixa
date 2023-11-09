<?php
session_start();
require_once "db.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! Caixa não existe ou já foi excluído.</p>";
    header("Location: index.php");
    exit;
}

$sql = $db->prepare("SELECT id, nome, saldo_inicial FROM caixas WHERE id = :id");
$sql->bindValue(":id", $id);
$sql->execute();
if ($sql->rowCount() <= 0) {
    $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! Caixa não não existe ou já foi excluído.</p>";
    header("Location: index.php");
    exit;
}

$caixa = $sql->fetch();
$msg = '';
$msg = "<p class='alert alert-success'>Mensagem Teste</p>";
if (!empty($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}
