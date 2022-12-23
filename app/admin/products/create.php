<?php

session_start();

include_once('/app/conf/variables.php');
include_once($rootPath . 'requests/products.php');

$getCSSClass = [
    1 => 'ts',
    2 => 'pu',
    3 => 'pa',
    4 => 'so',
    5 => 'cht',
    6 => 'chs',
];
$getDirectory = [
    1 => 'tshirt/',
    2 => 'pull/',
    3 => 'pantalon/',
    4 => 'sous-vetement/',
    5 => 'chaussette/',
    6 => 'chaussure/',
];

if (!isset($_SESSION['CURRENT_USER']) || !in_array('ROOT_USER', $_SESSION['CURRENT_USER']['roles'])) {
    header("Location:$rootUrl");
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
        $css_class = $getCSSClass[$categorie_id];

        /** Image part */
        if ($_FILES['image']['size'] < 10000000 && $_FILES['image']['error'] === 0) {

            $extension = pathinfo($_FILES['image']['name'])['extension'];
            $extensionAllowed = ['jpg', 'jpeg'];

            if (in_array($extension, $extensionAllowed)) {

                $imageUploadName = uniqid() . '.' . $extension;
                $imagePath = $getDirectory[$categorie_id] . $imageUploadName;
                $target = '/app' . $rootImages . $imagePath;

                move_uploaded_file($_FILES['image']['tmp_name'], $target);
            } else {
                $errorMessage = "L'extension du fichier n'est pas acceptée.";
            }
        } else {
            $errorMessage = "Fichier trop volumineux ou invalide.";
        }
    }
    if (!isset($errorMessage)) {
        if (createProduct($nom, $taille, $prix, $dispo, $imagePath, $css_class, $categorie_id)) {
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
    <title>Ajouter un produit</title>
</head>

<body>
    <!-- <?php include_once($rootTemplates . 'header.php') ?> -->
    <main>
        <section>
            <div class="container">
                <div class="form-page">
                    <div class="form-content">
                        <h2>Création d'un produit</h2>
                        <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
                            <div class="group">
                                <label for="nom">Nom :</label>
                                <input type="text" name="nom" placeholder="T-shirt orange" required>
                                <label for="taille">Taille(s) :</label>
                                <input type="text" name="taille" placeholder="XS-S-M" required>
                            </div>
                            <div class="group">
                                <label for="prix">Prix (€):</label>
                                <input type="text" name="prix" placeholder="16.9" required>
                            </div>
                            <div class="group">
                                <label for="dispo">Dispo ? :</label>
                                <input type="checkbox" name="dispo" value=true>
                            </div>
                            <div class="group">
                                <label for="categorie_id">Catégorie ? :</label>
                                <input type="radio" name="categorie_id" value=1 checked>T-shirt /
                                <input type="radio" name="categorie_id" value=2>Pull /
                                <input type="radio" name="categorie_id" value=3>Pantalons /
                                <input type="radio" name="categorie_id" value=4>Sous-vêtements /
                                <input type="radio" name="categorie_id" value=5>Chaussetes /
                                <input type="radio" name="categorie_id" value=6>Chaussures
                            </div>
                            <div class="group">
                                <label for="image">Visuel (.jpeg ou .jpg) : </label>
                                <input type="file" name="image" required>
                            </div>
                            <input type="hidden" name='token' value="<?= $_SESSION['token']; ?>">
                            <button type="submit" class="submit-button">Valider le formulaire</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include_once($rootTemplates . 'footer.php') ?>
</body>

</html>