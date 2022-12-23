<?php

session_start();

include_once('/app/conf/variables.php');
include_once($rootPath.'requests/products.php');

if (!isset($_SESSION['CURRENT_USER']) || !in_array('ROOT_USER', $_SESSION['CURRENT_USER']['roles'])) {
    header("Location:$rootUrl");
} elseif (!empty($_POST['id']) && !empty($_POST['token']) && $_POST['token'] === $_SESSION['token']) {
    if (deleteProduct($_POST['id'])) {
        $_SESSION['message']['success'] = "Produit supprimé avec succès";
    } else {
        $_SESSION['message']['error'] = "Une erreur est survenue, veuillez réessayer";
    }
} else {
    $_SESSION['message']['error'] = "Une erreur est survenue, veuillez réessayer";
}

header("Location:$rootUrl/admin/products");