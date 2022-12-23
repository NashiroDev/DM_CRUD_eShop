<?php

include_once('/app/conf/mysql.php');

/**
 * Cherche en base de données toutes les entrées ayant la même catégorie et qui sont disponibles
 *
 * @param integer $category
 * @return array|boolean
 */
function getAvailableProductsByCategory(int $category) : array|bool
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
function getAllAvailable() : array
{
    $sectionList = ['t-shirts', 'pulls', 'Pantalons', 'sous-vêtements', 'chaussetes', 'chaussures'];
    $fullAvailableProducts = [];

    $count = 1;
    foreach ($sectionList as $section) {

        if (getAvailableProductsByCategory($count)) {
            $fullAvailableProducts[$section] = getAvailableProductsByCategory($count);
        }
        $count += 1;
    }

    return $fullAvailableProducts;
}

/**
 * Récupère tout les produits présent en db
 *
 * @return array
 */
function getAllProducts() : array
{
    global $db;
    
    $query = 'SELECT id, nom, taille, prix, dispo, image_path, categorie_id FROM produits ORDER BY categorie_id ASC';
    $sqlStatement = $db->prepare($query);
    $sqlStatement->execute();

    return $sqlStatement->fetchAll();
}

/**
 * On ajoute en db un nouveau produit
 *
 * @param string $nom
 * @param string $taille
 * @param float $prix
 * @param string $dispo
 * @param string $image_path
 * @param string $css_class
 * @param integer $categorie_id
 * @return boolean
 */
function createProduct(string $nom, string $taille, float $prix, string $dispo, string $image_path, string $css_class, int $categorie_id): bool {

    global $db;

    try {
        $query = 'INSERT INTO produits (nom, taille, prix, dispo, image_path, css_class, categorie_id) VALUE (:nom, :taille, :prix, :dispo, :image_path, :css_class, :categorie_id)';
        $sqlStatement =  $db->prepare($query);
        $sqlStatement->execute([
            'nom' => $nom,
            'taille' => $taille,
            'prix' => $prix,
            'dispo'  => $dispo,
            'image_path' => $image_path,
            'css_class' => $css_class,
            'categorie_id' => $categorie_id,
        ]);
    } catch (PDOException $e) {
        return false;
    }
    return true;
}