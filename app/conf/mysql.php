<?php

try {
    $db = new PDO(
        'mysql:host=DataBase;dbname=eCommerce;charset=utf8mb4',
        'root'
    );
} catch (PDOException $e) {
    die('Erreur occured :'.$e->getMessage());
}