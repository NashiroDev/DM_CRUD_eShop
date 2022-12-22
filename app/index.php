<?php session_start();

include_once('./conf/variables.php');
include_once('./requests/products.php');

$listOfArray = getAll();
var_dump($_SESSION);
        
// Toggle boutons ?

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=".<?= $stylePath ?>main.css">
    <title>eChiottes</title>
</head>

<body>
    <?php include_once($rootTemplates . 'header.php'); ?>
    <main>
        <section>
            <div class="container">
                <div class="top-banner">
                    <div class="title">
                        <h1>Zalanventre</h1>
                        <h3>Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, officia provident impedit quibusdam velit delectus. Fuga, totam! Et quo deserunt perspiciatis animi voluptate? Magni quae ipsa nobis fugit quibusdam rerum?</h3>
                    </div>
                    <div class="image">
                        <img src="<?= $rootImages ?>top-image.jpg" alt="">
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="container">
                <div class="section-title" id="browse">
                    <h1>SÉLÉCTIONNEZ UNE CATÉGORIE</h1>
                </div>
                <div class="select-section">
                    <ul class="section-list">
                        <li><a id="TS">T-SHIRTS</a></li>
                        <li><a id="PU">PULLS</a></li>
                        <li><a id="PA">PANTALONS</a></li>
                        <li><a id="SO">SOUS-VÊTEMENTS</a></li>
                        <li><a id="CHT">CHAUSSETES</a></li>
                        <li><a id="CHS">CHAUSSURES</a></li>
                    </ul>
                </div>
            </div>
            <?php foreach ($listOfArray as $listOfItem) : ?>
                <div class="container <?= $listOfItem[0]['css_class'] ?>">
                    <?php foreach ($listOfItem as $item) : ?>
                        <div class="card">
                            <div class="card-image">
                                <img src="<?= $rootImages . $item['image_path'] ?>" alt="">
                            </div>
                            <div class="card-text">
                                <h3><?= $item['nom'] ?></h3>
                                <p>Taille : <?= "$item[taille] // Prix : $item[prix]" ?>€</p>
                            </div>
                            <button>Ajouter au panier</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </section>
    </main>
    <?php include_once($rootTemplates . 'footer.php'); ?>
    <script src="./assets/scripts/main.js"></script>
</body>

</html>