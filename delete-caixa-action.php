<?php
session_start();
include_once "db.php";

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $sql = $db->prepare("DELETE FROM caixas WHERE id = :id");
    $sql->bindValue(':id', $id);
    if (!$sql->execute()) {
        $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! Caixa já excluído ou inexistente.</p>";
        header("Location: index.php");
        exit;
    }
    $_SESSION['msg'] = "<p class='alert alert-success'><i class='fa-regular fa-thumbs-up'></i> Sucesso! Caixa excluído.</p>";
    header("Location: index.php");
    exit;
}
