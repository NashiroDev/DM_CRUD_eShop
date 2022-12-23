<?php

session_start();

include_once('/app/conf/variables.php');
include_once($rootPath.'/requests/products.php');

$_SESSION['getCSSClass'] = [
    1 => 'ts',
    2 => 'pu',
    3 => 'pa',
    4 => 'so',
    5 => 'cht',
    6 => 'chs',
];
$_SESSION['getDirectory'] = [
    1 => 'tshirt/',
    2 => 'pull/',
    3 => 'pantalon/',
    4 => 'sous-vetement/',
    5 => 'chaussette/',
    6 => 'chaussure/',
];

if (!isset($_SESSION['CURRENT_USER']) || !in_array('ROOT_USER', $_SESSION['CURRENT_USER']['roles'])) {
    header("Location:$rootUrl");
} else {
    $_SESSION['token'] = bin2hex(random_bytes(35));
}

?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $stylePath ?>main.css">
    <title>Administration des produits</title>
</head>
<body>
    <?php include_once($rootTemplates.'header.php'); ?>
    <main>
        <section>
            <div class="container">
                <div class="hold">
                    <h1 class="AU">Administration des produits</h1>
                    <a href="<?= "$rootUrl/admin/products/create.php" ?>" class="button create-button">AJOUTER UN PRODUIT</a>
                </div>
                <?php if (isset($_SESSION['message']['error'])) : ?>
                    <div class="notify alert-danger"><?= $_SESSION['message']['error']; ?></div>
                    <?php unset($_SESSION['message']['error']); ?>
                <?php elseif (isset($_SESSION['message']['success'])) : ?>
                    <div class="notify alert-success"><?= $_SESSION['message']['success']; ?></div>
                    <?php unset($_SESSION['message']['success']); ?>
                <?php endif; ?>
                <div class="products-list">
                    <?php foreach (getAllProducts() as $product) : ?>
                        <div class="product-card <?= $product['dispo'] === 'true' ? 'dispo':'not-avail'; ?>">
                            <div class="product-text">
                                <img src="<?= $rootImages . $product['image_path']; ?>" alt="">
                                <h4><b>Nom</b> : <br><?= $product['nom']; ?></h4>
                                <p><b>Taille</b> : <br><?= $product['taille']; ?></p>
                                <p><b>Prix</b> : <?= $product['prix']; ?>€</p>
                                <p><b>ID</b> : <?= $product['id']; ?></p>
                                <p><b>ID de catégorie</b> : <?= $product['categorie_id']; ?></p>
                            </div>
                            <div class="card-button">
                                <a href="<?= "$rootUrl/admin/products/update.php?id=$product[id]" ?>"
                                    class="button modify-button">MODIFIER</a>
                                <form action="<?= "$rootUrl/admin/products/delete.php" ?>" method="POST"
                                    onsubmit="return confirm('Êtes vous sur de vouloir supprimer ce produit ?')">
                                    <input type="hidden" name="id" value="<?= $product['id']; ?>">
                                    <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                                    <button class="button delete-button">SUPPRIMER</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>
    <?php include_once($rootTemplates.'footer.php'); ?>    
</body>
</html>
