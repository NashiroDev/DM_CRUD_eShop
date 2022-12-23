<?php

session_start();

include_once('/app/conf/variables.php');
include_once($rootPath . 'requests/users.php');

if (!isset($_SESSION['CURRENT_USER']) || !in_array('ROOT_USER', $_SESSION['CURRENT_USER']['roles'])) {
    header("Location:$rootUrl");
}

$user = getUserById(isset($_GET['id']) ? $_GET['id'] : null);

if (!$user) {
    $_SESSION['message']['error'] = 'Utilisateur introuvable';

    header("Location:$rootUrl/admin/users");
} elseif (
    !empty($_POST['email'])
    && !empty($_POST['nom'])
    && !empty($_POST['prenom'])
    && !empty($_POST['roles'])
) {
    $token = filter_input(INPUT_POST, 'token', FILTER_DEFAULT);

    if (!$token || $token !== $_SESSION['token']) {
        $errorMessage = "Une erreur est survenue, veuillez réessayer.";
    } else {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS);
        $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS);
        $roles = $_POST['roles'];

        if (!isset($errorMessage)) {
            if (updateUser($nom, $prenom, $email, $user['id'], $roles)) {
                $_SESSION['message']['success'] = 'Utilisateur mis à jour';

                header("Location:$rootUrl/admin/users");

            } else {
                $errorMessage = "Une erreur est survenue, veuillez réessayer.";
            }
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
    <title>Mise à jour utilisateur</title>
</head>

<body>
    <?php include_once($rootTemplates . 'header.php'); ?>
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
                        <h2>Mettre a jour utilisateur n°<?= $_GET['id']; ?></h2>
                        <form action="<?= $_SERVER['REQUEST_URI']; ?>" class="form form-register" method="POST" enctype="multipart/form-data">
                            <div class="group">
                                <label for="prenom">Prenom :</label>
                                <input type="text" name="prenom" placeholder="<?= strip_tags($user['prenom']); ?>" value="<?= strip_tags($user['prenom']); ?>" required>
                                <label for="nom">Nom :</label>
                                <input type="text" name="nom" placeholder="<?= strip_tags($user['nom']); ?>" value="<?= strip_tags($user['nom']); ?>" required>
                            </div>
                            <div class="group">
                                <label for="email">Adresse email :</label>
                                <input type="email" name="email" placeholder="<?= strip_tags($user['email']); ?>" value="<?= strip_tags($user['email']); ?>" required>
                            </div>
                            <div class="group">
                                <label for="roles[]">Rôle utilisateur :</label>
                                <input type="checkbox" name="roles[]" <?= strip_tags(in_array('CLASSIC_USER', json_decode($user['roles'])) ? 'checked' : null); ?> value="CLASSIC_USER">
                                <label for="roles[]">Rôle administrateur :</label>
                                <input type="checkbox" name="roles[]" <?= strip_tags(in_array('ROOT_USER', json_decode($user['roles'])) ? 'checked' : null); ?> value="ROOT_USER">
                            </div>
                            <input type="hidden" name='token' value="<?= $_SESSION['token'] ?>">
                            <button type="submit" class="submit-button">Appliquer les changements</button>
                        </form>
                    </div>
                </div>
            </div>
            <a href="<?= "$rootUrl/admin/users"; ?>" class="button go-back">Retour à la liste</a>
        </section>
    </main>
    <?php include_once($rootTemplates.'footer.php'); ?>
</body>

</html>