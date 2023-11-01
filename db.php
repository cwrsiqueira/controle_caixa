<?php

$host = 'localhost';
$dbname = 'controle_caixas';
$user = 'root';
$pass = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    // echo "Banco de dados conectado com sucesso.";
} catch (PDOException $e) {
    echo $e->getMessage();
}
