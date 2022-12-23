<header>
    <div class="navbar">
        <div class="icon">
            <a href="/index.php"><img src="<?= $rootImages ?>payment-method.png" alt=""></a>
        </div>
        <div class="chunk product">
            <p><a href="/index.php#browse">PRODUITS</a></p>
        </div>
        <?php if (!isset($_SESSION['CURRENT_USER'])) : ?>
        <div class="chunk log">
            <p><a href="login.php">CONNEXION</a></p>
        </div>
        <div class="chunk reg">
            <p><a href="register.php">S'INSCRIRE</a></p>
        </div>
        <?php elseif (isset($_SESSION['CURRENT_USER']) && in_array('ROOT_USER', $_SESSION['CURRENT_USER']['roles'])) : ?>
        <div class="chunk reg">
            <p><a href="/logout.php">DECONNEXION</a></p>
        </div>
        <div class="chunk reg">
            <p><a href="/admin/">ADMINISTRATEUR</a></p>
        </div>
        <?php else: ?>
        <div class="chunk reg">
            <p><a href="/logout.php">DECONNEXION</a></p>
        </div>
        <?php endif; ?>
    </div>
</header>