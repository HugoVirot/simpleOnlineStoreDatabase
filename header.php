<header>
    <?php
    if (isset($_SESSION['id']) && isset($_SESSION['prenom']) && isset($_SESSION['nom'])) {
        echo "<div class=\"container w-50 pt-3\">
                <div class=\"row justify-content-center text-center\">Connecté en tant que " . $_SESSION['prenom'] . " " . $_SESSION['nom'] . "
                </div>
              </div>";
    }
    ?>

    <div class="container-fluid text-center">
        <a href="index.php" style="text-decoration: none; color: inherit">
            <h1 class="pt-5">Arinfo</h1>
        </a>
        <h4 class="font-weight-light font-italic pt-1 pb-5">Montres intemporelles</h2>
    </div>

    <nav class="navbar bg-dark navbar-expand-md border-bottom border-body" data-bs-theme="dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center align-items-center text-center" id="navbarNavDropdown">
                <ul class="navbar-nav">

                    <?php
                    $page = $_SERVER['REQUEST_URI'];
                    $page = str_replace("/simpleOnlineStoreDatabase/", "", $page);
                    ?>

                    <li class="nav-item">
                        <a class="nav-link <?php if ($page == "index.php") {
                                                echo 'active';
                                            } ?>" aria-current="page" href="index.php">Accueil</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php if ($page == "ranges.php") {
                                                echo 'active';
                                            } ?>" href="ranges.php">Gammes</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php if ($page == "panier.php") {
                                                echo 'active';
                                            } ?>" href="panier.php">Panier<span>
                                <?php
                                if (isset($_SESSION['cart'])) {
                                    echo "(" . count($_SESSION['cart']) . ")";
                                } ?>
                            </span>
                        </a>
                    </li>
                    <?php
                    if (!isset($_SESSION['id'])) {  // si l'utilisateur n'est pas connecté
                    ?><li class="nav-item">
                            <a class="nav-link <?php if ($page == "connection.php") {
                                                    echo 'active';
                                                } ?>" href="connection.php">Connexion / créer compte</a>
                        </li>
                    <?php
                    } else {
                    ?> <li class="nav-item">
                            <a class="nav-link <?php if ($page == "account.php") {
                                                    echo 'active';
                                                } ?>" href="account.php">Mon compte</a>
                        </li>
                        <li class="nav-item">
                            <form action="index.php" method="post" class="nav-link">
                                <input type="hidden" name="logout">
                                <input type="submit" value="Déconnexion">
                            </form>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

</header>