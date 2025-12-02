<?php

$pdo = new PDO(
    'mysql:host=localhost;dbname=revestedb;charset=utf8mb4',
    'root',      // usuário
    'root',      // SENHA root
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);
