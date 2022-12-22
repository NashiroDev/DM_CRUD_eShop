<?php

session_start();

include_once('/app/conf/variables.php');
include_once($rootPath.'/requests/users.php');

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
    <title>Administration utilisateurs</title>
</head>
<body>
    <?php include_once($rootTemplates.'/header.php'); ?>
    <main>
        <section>
            <div class="container">
                <h1 class="AU">Administration des utilisateurs</h1>
                <?php if (isset($_SESSION['message']['error'])) : ?>
                    <div class="notify alert-danger"><?= $_SESSION['message']['error']; ?></div>
                    <?php unset($_SESSION['message']['error']); ?>
                <?php elseif (isset($_SESSION['message']['success'])) : ?>
                    <div class="notify alert-success"><?= $_SESSION['message']['success']; ?></div>
                    <?php unset($_SESSION['message']['success']); ?>
                <?php endif; ?>
                <div class="users-list">
                    <?php foreach (getAllUsers() as $user) : ?>
                        <div class="user-card <?= in_array('ROOT_USER', json_decode($user['roles'])) ? 'root-user' : 'classic-user' ?>">
                            <div class="user-text">
                                <h3><b>Nom complet</b> : <br><?= "$user[prenom] $user[nom]" ?></h3>
                                <h4><b>Email</b> : <br><?= strip_tags($user['email'])?></h4>
                                <p><b>ID</b> : <?= $user['id'] ?></p>
                            </div>
                            <div class="card-button">
                                <a href="<?= "$rootUrl/admin/users/update.php?id=$user[id]" ?>"
                                    class="button modify-button">MODIFIER</a>
                                <form action="<?= "$rootUrl/admin/users/delete.php" ?>" method="POST"
                                    onsubmit="return confirm('Êtes vous sur de vouloir supprimer cet utilisateur ?')">
                                    <input type="hidden" name="id" value="<?= $user['id']; ?>">
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
    <?php include_once($rootTemplates.'/footer.php'); ?>
</body>
</html>