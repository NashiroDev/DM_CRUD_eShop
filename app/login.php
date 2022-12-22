<?php

session_start();

include_once('/app/conf/variables.php');
include_once($rootPath . 'requests/users.php');

if (isset($_POST['email']) && isset($_POST['password'])) {

    $user = checkEmailExistance($_POST['email']);

    if ($user && password_verify($_POST['password'], $user['password'])) {

        $_SESSION['CURRENT_USER'] = [
            'id' => $user['id'],
            'nom' => $user['nom'],
            'prenom' => $user['prenom'],
            'email' => $user['email'],
            'roles' => json_decode($user['roles']),
        ];
        header("Location:$rootUrl");
    } else {
        $errorMessage = "Adresse email invalide ou mot de passe erroné.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=".<?= $stylePath ?>main.css">
    <title>eChiottes - Se connecter</title>
</head>

<body>
    <?php include_once($rootTemplates . 'header.php') ?>
    <main>
        <section>
            <?php if (isset($errorMessage)) : ?>
                <div class="notify alert-danger">
                    <?= $errorMessage ?>
                </div>
            <?php endif; ?>
            <div class="form-page">
                <div class="form-content">
                    <h2>Connexion</h2>
                    <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="POST">
                        <div class="group">
                            <label for="email">Email : </label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="group">
                            <label for="password">Mot de passe : </label>
                            <input type="password" name="password" required>
                        </div>
                        <button type="submit" class="submit-button">Se connecter</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <?php include_once($rootTemplates . 'footer.php') ?>
</body>

</html>