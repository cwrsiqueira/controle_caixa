<?php
session_start();
include_once "db.php";

$data_ini = filter_input(INPUT_GET, 'data_ini');
$data_fin = filter_input(INPUT_GET, 'data_fin');
if (!$data_ini) {
    $data_ini = date('Y-m-d', strtotime('-1 week'));
}
if (!$data_fin) {
    $data_fin = date('Y-m-d');
}

if ($data_fin < $data_ini) {
    $_SESSION['msg'] = "<p class='alert alert-danger'>Informe a data corretamente e clique Buscar novamente.</p>";
    $data_ini = date('Y-m-d', strtotime('-1 week'));
    $data_fin = date('Y-m-d');
}

$inicio = new DateTime($data_ini);
$fim = new DateTime($data_fin);
$fim = $fim->modify('+1 day'); // Inclui a data final no intervalo
$dateInterval = $inicio->diff($fim);
$qt_dias_mes = date('t', strtotime($data_ini));

if ($dateInterval->days > $qt_dias_mes) {
    $_SESSION['msg'] = "<p class='alert alert-danger'>O intervalo deve ser menor que 1 mês.</p>";
    $data_ini = date('Y-m-d', strtotime('-1 week'));
    $data_fin = date('Y-m-d');
    $inicio = new DateTime($data_ini);
    $fim = new DateTime($data_fin);
}

$intervalo = new DateInterval('P1D'); // Intervalo de 1 dia
$periodo = new DatePeriod($inicio, $intervalo, $fim);

$datas = [];
foreach ($periodo as $data) {
    $datas[] = $data->format("Y-m-d");
}

$sql = $db->query("SELECT id, nome, saldo_inicial FROM caixas");
$caixas = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach ($caixas as $key => $caixa) {
    foreach ($datas as $date) {

        $entradas = [];
        $sql = $db->prepare("SELECT SUM(valor_movimento) as saldo_entradas FROM caixas_lancamentos WHERE id_caixa = :id_caixa AND movimento = 'entrada' AND data_movimento <= :data_movimento");
        $sql->bindValue(":id_caixa", $caixa['id']);
        $sql->bindValue(":data_movimento", $date . ' 23:59:59');
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $entradas = $sql->fetch(PDO::FETCH_ASSOC);
        }

        $saidas = [];
        $sql = $db->prepare("SELECT SUM(valor_movimento) as saldo_saidas FROM caixas_lancamentos WHERE id_caixa = :id_caixa AND movimento = 'saida' AND data_movimento <= :data_movimento");
        $sql->bindValue(":id_caixa", $caixa['id']);
        $sql->bindValue(":data_movimento", $date . ' 23:59:59');
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $saidas = $sql->fetch(PDO::FETCH_ASSOC);
        }

        $caixas[$key]['saldo_entradas'][] = $entradas['saldo_entradas'] ?? 0.00;
        $caixas[$key]['saldo_saidas'][] = $saidas['saldo_saidas'] ?? 0.00;
        $caixas[$key]['saldo_total'][] = $caixa['saldo_inicial'] + ($entradas['saldo_entradas'] ?? 0.00) - ($saidas['saldo_saidas'] ?? 0.00);
    }
    $caixas[$key]['saldo_entradas'] = implode(',', $caixas[$key]['saldo_entradas']);
    $caixas[$key]['saldo_saidas'] = implode(',', $caixas[$key]['saldo_saidas']);
    $caixas[$key]['saldo_total'] = implode(',', $caixas[$key]['saldo_total']);
}

$datas = [];
foreach ($periodo as $data) {
    $datas[] = $data->format("d/m/Y");
}
$datas = '"' . implode('", "', $datas) . '"';
