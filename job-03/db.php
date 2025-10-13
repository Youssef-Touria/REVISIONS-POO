<?php
declare(strict_types=1);

$pdo = new PDO(
    'mysql:host=localhost;dbname=draft-shop;charset=utf8mb4',
    'root', // adapte si besoin
    '',     // mot de passe si besoin
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);
