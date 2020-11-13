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
            <nav class="navbar navbar-expand-md navbar-light bg-light">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ranges.php">Gammes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="panier.php">Panier<span>
                                    <?php
                                    if (isset($_SESSION['cart'])) {
                                        echo "(" . count($_SESSION['cart']) . ")";
                                    } ?>
                                </span>
                            </a>
                        </li>
                        <?php
                        if (!isset($_SESSION['id'])) {
                            echo "<li class=\"nav-item\">
                                        <a class=\"nav-link\" href=\"connection.php\">Connexion / créer compte</a>
                                        </li>";
                        } else {
                            echo "<li class=\"nav-item\">
                                        <a class=\"nav-link\" href=\"account.php\">Mon compte</a>
                                      </li>
                                      <li class=\"nav-item\">
                                        <form action=\"index.php\" method=\"post\" class=\"nav-link\">
                                            <input type=\"hidden\" name=\"logout\">
                                            <input class=\"bg-light\" style=\"border: none\" type=\"submit\" value=\"Déconnexion\">
                                        </form>
                                      </li>";
                        } ?>
                    </ul>
                </div>
            </nav>
    </div>
</header>