<?php

include_once('/app/conf/mysql.php');

/**
 * Cherche en base de données toutes les entrées ayant la même catégorie et qui sont disponibles
 *
 * @param integer $category
 * @return array|boolean
 */
function getAllAvailableProductsByCategory(int $category) : array|bool
{
    global $db;

    $query = "SELECT id, nom, taille, prix, css_class, image_path FROM produits WHERE categorie_id = :category AND dispo = 'true'";
    $sqlStatement = $db->prepare($query);
    $sqlStatement->execute([
        'category' => $category,
    ]);

    return $sqlStatement->fetchAll();
}

/**
 * Boucle la fonction getAllAvailableProductsByCategory 6fois, une fois pour chaque catégorie
 *
 * @return array 
 */
function getAll() : array
{
    $sectionList = ['t-shirts', 'pulls', 'Pantalons', 'sous-vêtements', 'chaussetes', 'chaussures'];
    $fullAvailableProducts = [];

    $count = 1;
    foreach ($sectionList as $section) {

        if (getAllAvailableProductsByCategory($count)) {
            $fullAvailableProducts[$section] = getAllAvailableProductsByCategory($count);
        }
        $count += 1;
    }

    return $fullAvailableProducts;
}