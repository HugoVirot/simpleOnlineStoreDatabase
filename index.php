<?php
session_start();

include('functions.php');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (isset($_POST['orderValidated'])) {
    saveOrder(calculateTotalPrice());
    emptyCart(false);
}

if (isset($_POST['logout'])) {
    logOut();
}

// si on vient de la page connexion, c'est forcément un succès => on affiche le message de succès
if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "http://localhost/simpleOnlineStoreDatabase/connection.php") {
    echo '<script>alert(\'Vous êtes connecté !\')</script>';
}

include('./head.php');
?>

<body>

    <?php
    include('./header.php');
    ?>

    <main>

        <div class="container-fluid pb-3 mt-2">
            <div class="row text-center">
                <img id="watchPhoto" src="images/watchdark.jpg" style="width: 100vw">
            </div>
        </div>

        </div>

        <div class="container-fluid text-dark pb-5 pt-5">
            <div class="row text-center justify-content-center">
                <?php showArticles(getArticles()) ?>
            </div>

            <div class="row mt-5 text-center justify-content-center">
                <a href="ranges.php">
                    <button class="btn btn-lg btn-dark">
                        Découvrir toutes nos gammes
                    </button>
                </a>
            </div>
        </div>

    </main>

    <?php
    include('./footer.php');
    ?>

</body>

</html>