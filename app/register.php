<?php
session_start();

include_once('conf/variables.php');
include_once('requests/users.php');


if (!empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['password']))
    {
        $token = filter_input(INPUT_POST, 'token', FILTER_DEFAULT);
    
        if (!$token || $token !== $_SESSION['token']) {
            $errorMessage = "Une erreur est survenue, token invalide.";
        } else {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS);
            $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS);
            $password = $_POST['password'];
    
            $isEmailExisting = checkEmailExistance($email);
    
            if (!$isEmailExisting && !isset($errorMessage)) {
                $confirmChange = insertUser($nom, $prenom, $email, $password);
                header("Location:$rootUrl");
            } else {
                $errorMessage = isset($errorMessage) ? $errorMessage : "L'email est déjà utilisé par un autre compte";
            }
        }
    } else {
        $_SESSION['token'] = bin2hex(random_bytes(35));
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $stylePath; ?>main.css">
    <title>eChiottes - Inscription</title>
</head>

<body>
    <?php include_once($rootTemplates . 'header.php'); ?>
    <main>
        <section>
            <?php if (!isset($confirmChange)) : ?>
                <?php if (isset($errorMessage)) : ?>
                    <div class="notify alert-danger">
                        <p>
                            <?= $errorMessage; ?>
                        </p>
                    </div>
                <?php endif; ?>
                <div class="form-page">
                    <div class="form-content">
                        <h2>Inscription</h2>
                        <form action="<?= $_SERVER['REQUEST_URI']; ?>" class="form form-register" method="POST" enctype="multipart/form-data">
                            <div class="group">
                                <label for="prenom">Prenom :</label>
                                <input type="text" name="prenom" placeholder="Jacky" required>
                                <label for="nom">Nom :</label>
                                <input type="text" name="nom" placeholder="Chan" required>
                            </div>
                            <div class="group">
                                <label for="email">Adresse email :</label>
                                <input type="email" name="email" placeholder="jacky.chan@kick.com" required>
                            </div>
                            <div class="group">
                                <label for="password">Mot de passe</label>
                                <input type="password" name="password" placeholder="Ilov3M4rti4l4rt5" required>
                            </div>
                            <input type="hidden" name='token' value="<?= $_SESSION['token'] ?>">
                            <button type="submit" class="submit-button">Valider le formulaire</button>
                        </form>
                    </div>
                </div>
            <?php else : ?>
                <?php if ($confirmChange) : ?>
                    <div class="notify alert-success">
                        <p>Vous êtes bien inscrit !</p>
                    </div>
                <?php else : ?>
                    <div class="notify alert-danger">
                        <p>Une erreur est survenue. Veuillez rééssayer!</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    </main>
    <?php include_once($rootTemplates . 'footer.php'); ?>
</body>

</html>