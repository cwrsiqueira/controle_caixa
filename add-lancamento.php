<?php
session_start();
include "db.php";

$dados = filter_input_array(INPUT_POST);

echo '<pre>';
var_dump($dados);
echo '</pre>';
