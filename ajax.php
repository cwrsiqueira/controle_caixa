<?php
session_start();
include_once "db.php";

$action = filter_input(INPUT_GET, "action", FILTER_DEFAULT);

if ($action && $action === "edit_modal") {
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

    $sql = $db->prepare("SELECT * FROM caixas_lancamentos WHERE id = :id");
    $sql->bindValue(":id", $id);
    $sql->execute();
    if ($sql->rowCount() <= 0) {
        echo json_encode("erro");
        exit;
    }
    $res = $sql->fetch(PDO::FETCH_ASSOC);
    echo json_encode($res);
}
