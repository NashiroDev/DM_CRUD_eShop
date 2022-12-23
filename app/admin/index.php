<?php
session_start();

include_once('/app/conf/variables.php');

if (!isset($_SESSION['CURRENT_USER']) || !in_array('ROOT_USER', $_SESSION['CURRENT_USER']['roles'])) {
    header("Location:$rootUrl");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $stylePath ?>main.css">
    <title>eChiottes - Administration</title>
</head>

<body>
    <?php include_once($rootTemplates.'header.php'); ?> 
    <main>
        <section>
            <div class="container">
                <div class="manage-bar">
                    <h1>♠ Admin hub</h1>
                    <div class="redirect-place">
                        <a href="./users/">GÉRER LES UTILISATEURS</a>
                        <a href="./products/">GÉRER LES PRODUITS</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include_once($rootTemplates.'footer.php'); ?>
</body>
</html>