<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
require_once "db.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! Caixa não existe ou já foi excluído.</p>";
    header("Location: index.php");
    exit;
}

$data_ini = filter_input(INPUT_GET, "data-ini", FILTER_DEFAULT);
$data_fin = filter_input(INPUT_GET, "data-fin", FILTER_DEFAULT);
if (!$data_ini) {
    $data_ini = date('Y-m-01');
}
if (!$data_fin) {
    $data_fin = date('Y-m-t');
}

/**
 * PEGA OS DADOS DO CAIXA ATUAL
 */
$sql = $db->prepare("SELECT id, nome, saldo_inicial FROM caixas WHERE id = :id");
$sql->bindValue(":id", $id);
$sql->execute();
if ($sql->rowCount() <= 0) {
    $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! Caixa não não existe ou já foi excluído.</p>";
    header("Location: index.php");
    exit;
}
$caixa = $sql->fetch();

/**
 * VERIFICA SE EXISTE MENSAGEM E ATRIBUI A VARIÁVEL $msg
 */
$msg = '';
if (!empty($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}

/**
 * PEGA OS LANÇAMENTOS DO CAIXA ATUAL
 */
$sql = $db->prepare("SELECT id, id_caixa, movimento, data_movimento, discriminacao_movimento, valor_movimento
                    FROM caixas_lancamentos
                    WHERE id_caixa = :id AND (data_movimento BETWEEN :data_ini AND :data_fin)
                    ORDER BY data_movimento");
$sql->bindValue(":id", $id);
$sql->bindValue(":data_ini", $data_ini);
$sql->bindValue(":data_fin", $data_fin);
$sql->execute();
$lancamentos = $sql->fetchAll(PDO::FETCH_ASSOC);


/**
 * ATUALIZA O SALDO ATÉ AQUELA DATA E LANÇAMENTO
 */

foreach ($lancamentos as $key => $lancamento) {
    $entradas = 0;
    $saidas = 0;

    $sql = $db->prepare("SELECT SUM(valor_movimento) AS entradas FROM caixas_lancamentos WHERE id_caixa = :id_caixa AND movimento = 'entrada' AND data_movimento <= :data_movimento");
    $sql->bindValue(":id_caixa", $id);
    $sql->bindValue(":data_movimento", $lancamento['data_movimento']);
    $sql->execute();
    if ($sql->rowCount() > 0) {
        $entradas = $sql->fetch(PDO::FETCH_ASSOC)['entradas'];
    }

    $sql = $db->prepare("SELECT SUM(valor_movimento) AS saidas FROM caixas_lancamentos WHERE id_caixa = :id_caixa AND movimento = 'saida' AND data_movimento <= :data_movimento");
    $sql->bindValue(":id_caixa", $id);
    $sql->bindValue(":data_movimento", $lancamento['data_movimento']);
    $sql->execute();
    if ($sql->rowCount() > 0) {
        $saidas = $sql->fetch(PDO::FETCH_ASSOC)['saidas'];
    }

    $lancamentos[$key]['saldo_atual'] = $caixa['saldo_inicial'] + $entradas - $saidas;
}

/**
 * ATUALIZA SALDO ATUAL
 */

$entradas = 0;
$saidas = 0;

$sql = $db->prepare("SELECT SUM(valor_movimento) AS entradas FROM caixas_lancamentos WHERE id_caixa = :id_caixa AND movimento = 'entrada'");
$sql->bindValue(":id_caixa", $id);
$sql->execute();
if ($sql->rowCount() > 0) {
    $entradas = $sql->fetch(PDO::FETCH_ASSOC)['entradas'];
}

$sql = $db->prepare("SELECT SUM(valor_movimento) AS saidas FROM caixas_lancamentos WHERE id_caixa = :id_caixa AND movimento = 'saida'");
$sql->bindValue(":id_caixa", $id);
$sql->execute();
if ($sql->rowCount() > 0) {
    $saidas = $sql->fetch(PDO::FETCH_ASSOC)['saidas'];
}

$saldo_atual = $caixa['saldo_inicial'] + $entradas - $saidas;


/**
 * ATUALIZA SALDO INICIAL
 */

$saldo_inicial = $caixa['saldo_inicial'];
$entradas = 0;
$saidas = 0;

$sql = $db->prepare("SELECT SUM(valor_movimento) AS entradas FROM caixas_lancamentos WHERE id_caixa = :id_caixa AND movimento = 'entrada' AND data_movimento < :data_movimento");
$sql->bindValue(":id_caixa", $id);
$sql->bindValue(":data_movimento", $data_ini . " 00:00:00");
$sql->execute();
if ($sql->rowCount() > 0) {
    $entradas = $sql->fetch(PDO::FETCH_ASSOC)['entradas'];
}

$sql = $db->prepare("SELECT SUM(valor_movimento) AS saidas FROM caixas_lancamentos WHERE id_caixa = :id_caixa AND movimento = 'saida' AND data_movimento < :data_movimento");
$sql->bindValue(":id_caixa", $id);
$sql->bindValue(":data_movimento", $data_ini . " 00:00:00");
$sql->execute();
if ($sql->rowCount() > 0) {
    $saidas = $sql->fetch(PDO::FETCH_ASSOC)['saidas'];
}

$saldo_inicial = $saldo_inicial + $entradas - $saidas;
