<?php

session_start();

include_once('/app/conf/variables.php');
include_once($rootPath .  'requests/products.php');

if (!isset($_SESSION['CURRENT_USER']) || !in_array('ROOT_USER', $_SESSION['CURRENT_USER']['roles'])) {
    header("Location:$rootUrl");
}

$product = getProductById(isset($_GET['id']) ? $_GET['id'] : null);

if (!$product) {
    $_SESSION['message']['error'] = 'Produit introuvable';

    header("Location:$rootUrl/admin/products");
} elseif (
    !empty($_POST['nom'])
    && !empty($_POST['taille'])
    && !empty($_POST['prix'])
    && !empty($_POST['categorie_id'])
    && !empty($_FILES['image'])
) {
    $token = filter_input(INPUT_POST, 'token', FILTER_DEFAULT);

    if (!$token || $token !== $_SESSION['token']) {
        $errorMessage = "Une erreur est survenue, token invalide.";
    } else {
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS);
        $taille = filter_input(INPUT_POST, 'taille', FILTER_SANITIZE_SPECIAL_CHARS);
        $prix = filter_input(INPUT_POST, 'prix', FILTER_SANITIZE_SPECIAL_CHARS);
        $dispo = (filter_input(INPUT_POST, 'dispo', FILTER_SANITIZE_SPECIAL_CHARS) === 'true') ? 'true' : 'false';
        $categorie_id = filter_input(INPUT_POST, 'categorie_id', FILTER_SANITIZE_SPECIAL_CHARS);
        $css_class = $_SESSION['getCSSClass'][$categorie_id];

        /** Image part */
        if ($_FILES['image']['name'] === '') {
            unset($_FILES['image']);
        }
        
        if (isset($_FILES['image'])) {
            if ($_FILES['image']['size'] < 10000000 && $_FILES['image']['error'] === 0) {

                $extension = pathinfo($_FILES['image']['name'])['extension'];
                $extensionAllowed = ['jpg', 'jpeg'];

                if (in_array($extension, $extensionAllowed)) {

                    $imageUploadName = uniqid() . '.' . $extension;
                    $imagePath = $_SESSION['getDirectory'][$categorie_id] . $imageUploadName;
                    $target = '/app' . $rootImages . $imagePath;

                    move_uploaded_file($_FILES['image']['tmp_name'], $target);
                } else {
                    $errorMessage = "L'extension du fichier n'est pas acceptée.";
                }
            } else {
                $errorMessage = "Fichier trop volumineux ou invalide.";
            }
        } else {
            $imagePath = $product['image_path'];
        }
    }
    if (!isset($errorMessage)) {
        if (updateProduct($nom, $taille, $prix, $dispo, $imagePath, $css_class, $categorie_id, $product['id'])) {
            $_SESSION['message']['success'] = "Produit ajouté avec succès.";

            header("Location:$rootUrl/admin/products");
        } else {
            $errorMessage = isset($errorMessage) ? $errorMessage : "Une erreur est survenue lors de  l'ajout, veuillez réessayer.";
        }
    }
} else {
    $_SESSION['token'] = bin2hex(random_bytes(35));
}

?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $stylePath ?>main.css">
    <title>Mise à jour produit</title>
</head>

<body>
    <!-- <?php include_once($rootTemplates . 'header.php'); ?> -->
    <main>
        <section>
            <div class="container">
                <?php if (isset($errorMessage)) : ?>
                    <div class="notify alert-danger">
                        <p>
                            <?= $errorMessage; ?>
                        </p>
                    </div>
                <?php endif; ?>
                <div class="form-page">
                    <div class="form-content">
                        <h2>Mettre a jour produit n°<?= $_GET['id'] ?></h2>
                        <div class="card-image">
                            <img src="<?= strip_tags($rootImages . $product['image_path']); ?>" alt="">
                        </div>
                        <p>Chemin du visuel : <?= strip_tags($product['image_path']);  ?></p>
                        <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
                            <div class="group">
                                <label for="nom">Nom :</label>
                                <input type="text" name="nom" placeholder="<?= strip_tags($product['nom']); ?>" value="<?= strip_tags($product['nom']); ?>" required>
                                <label for="taille">Taille(s) :</label>
                                <input type="text" name="taille" placeholder="<?= strip_tags($product['taille']); ?>" value="<?= strip_tags($product['taille']); ?>" required>
                            </div>
                            <div class="group">
                                <label for="prix">Prix (€):</label>
                                <input type="text" name="prix" placeholder="<?= strip_tags($product['prix']); ?>" value="<?= strip_tags($product['prix']); ?>" required>
                            </div>
                            <div class="group">
                                <label for="dispo">Dispo ? :</label>
                                <input type="checkbox" name="dispo" value=true <?= $product['dispo'] === 'true' ? 'checked' : null; ?>>
                            </div>
                            <div class="group">
                                <label for="categorie_id">Catégorie :</label>
                                <input type="radio" name="categorie_id" value=1 <?= $product['categorie_id'] === 1 ? 'checked' : null; ?>>T-shirt /
                                <input type="radio" name="categorie_id" value=2 <?= $product['categorie_id'] === 2 ? 'checked' : null; ?>>Pull /
                                <input type="radio" name="categorie_id" value=3 <?= $product['categorie_id'] === 3 ? 'checked' : null; ?>>Pantalons /
                                <input type="radio" name="categorie_id" value=4 <?= $product['categorie_id'] === 4 ? 'checked' : null; ?>>Sous-vêtements /
                                <input type="radio" name="categorie_id" value=5 <?= $product['categorie_id'] === 5 ? 'checked' : null; ?>>Chaussetes /
                                <input type="radio" name="categorie_id" value=6 <?= $product['categorie_id'] === 6 ? 'checked' : null; ?>>Chaussures
                            </div>
                            <div class="group">
                                <label for="image">Nouveau visuel (.jpeg ou .jpg) : </label>
                                <input type="file" name="image">
                            </div>
                            <input type="hidden" name='token' value="<?= $_SESSION['token']; ?>">
                            <button type="submit" class="submit-button">Valider le formulaire</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include_once($rootTemplates . 'footer.php'); ?>
</body>

</html>