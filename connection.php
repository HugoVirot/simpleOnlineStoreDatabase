<?php
session_start();
include('functions.php');

// ************* si on vient de la page inscription *************
if (isset($_POST['prenom'])) {
    //var_dump($_POST);
    createUser();
}

// ************* si on vient de la page connexion *************
if (isset($_POST['userConnection'])) {
    if (!isset($_SESSION['id'])) {
        logIn();
    } else {
        echo "<script>alert(\"Vous êtes déjà connecté !\")</script>";
    }
}
include('./head.php');
?>

<body>
    <header>
        <?php
        include('header.php');
        ?>
    </header>

    <main>
        <div class="container-fluid pb-3">
            <div class="row text-center">
                <img id="watchPhoto" src="images/bluewatch.jpg" style="width: 100vw">
            </div>
        </div>

        <div class="container w-50 p-3 text-center">
            <i class="fas fa-key fa-3x mb-2"></i>
            <h3 class="mb-3 text-center">Connexion</h3>
        </div>

        <div class="container w-50 border border-dark bg-light mb-4 p-5">
            <form action="connection.php" method="post">
                <input type="hidden" name="userConnection">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input name="email" type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="paul.dupont@exemple.fr">
                    <small id="emailHelp" class="form-text text-muted">Nous ne partagerons jamais votre e-mail avec des tiers.</small>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input name="password" type="password" class="form-control" id="password" placeholder="entrez le mot de passe">
                </div>
                <div class="row justify-content-center mt-3">
                    <button type="submit" class="btn btn-dark">Valider</button>
                </div>
            </form>
        </div>

        <div class="container w-50 p-3  text-center">
            <h3 class="mb-3">Pas encore inscrit ?</h3>
            <div class="row justify-content-center">
                <a href="registration.php">
                    <button class="btn btn-dark px-5">Je crée mon compte</button>
                </a>
            </div>
        </div>

    </main>

    <?php
    include('./footer.php');
    ?>

</body>

</html>