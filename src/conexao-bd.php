<?php

// 1. Define the options array BEFORE the connection attempt
$opcoes = [
    // FIX: Change '->' to '=>' in the first option
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

$usuario = "root";
$senha = ""; 
$db_host = getenv('DB_HOST') ?: 'localhost';

// 2. Pass $opcoes as the FOURTH argument
$pdo = new PDO(
    'mysql:host=localhost;dbname=revestedb', 
    $usuario, 
    $senha, 
    $opcoes // <-- Now correctly passing the defined array
);

// 3. Remove this redundant line, as the first option already sets this
// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// var_dump($pdo);