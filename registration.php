<?php
session_start();
include('functions.php');

include('./head.php');
?>

<body>
    <header>
        <?php
        include('header.php');
        ?>
    </header>

    <main>
        <div class="container-fluid pb-5">
            <div class="row text-center">
                <img id="watchPhoto" src="images/bluewatch.jpg" style="width: 100vw">
            </div>
        </div>

        <div class="container w-50 p-3 text-center">
            <i class="fas fa-user fa-3x mb-2"></i>
            <h3 class="mb-3 text-center">Créer mon compte</h3>
        </div>

        <div class="container border border-dark bg-light mb-4 p-5">

            <form action="./connection.php" method="post">

                <div class="form-row text-center mb-3">
                    <div class="form-group col-md-6 mx-auto">
                        <label for="inputFirstName">Prénom</label>
                        <input name="prenom" type="text" class="form-control mt-2" id="inputFirstName" placeholder="Paul" required>
                    </div>
                </div>

                <div class="form-row text-center mb-3">
                    <div class="form-group col-md-6 mx-auto">
                        <label for="inputName">Nom</label>
                        <input name="nom" type="text" class="form-control mt-2" id="inputName" placeholder="DUPONT" required>
                    </div>
                </div>

                <div class="form-row text-center mb-3">
                    <div class="form-group col-md-6 mx-auto">
                        <label for="inputEmail">Email</label>
                        <input name="email" type="email" class="form-control mt-2" id="inputEmail" placeholder="paul.dupont@exemple.fr" required>
                    </div>
                </div>

                <div class="form-row text-center mb-3">
                    <div class="form-group col-md-6 mx-auto">
                        <label for="inputPassword">Mot de passe</label>
                        <input name="password" type="password" class="form-control mt-2" id="inputPassword" required>
                        <small id="emailHelp" class="form-text text-muted">Entre 8 et 15 caractères, minimum 1 lettre, 1 chiffre et 1 caractère spécial</small>
                    </div>
                </div>

                <div class="form-row text-center mb-3">
                    <div class="form-group col-md-6 mx-auto">
                        <label for="adresse">Adresse</label>
                        <input name="adresse" type="text" class="form-control mt-2" id="adresse" placeholder="9 rue de la paix" required>
                    </div>
                </div>

                <div class="form-row text-center mb-3">
                    <div class="form-group col-md-6 mx-auto">
                        <label for="code_postal">Code Postal</label>
                        <input name="code_postal" type="text" class="form-control mt-2" id="code_postal" placeholder="75000" required>
                    </div>
                </div>

                <div class="form-row text-center mb-3">
                    <div class="form-group col-md-6 mx-auto">
                        <label for="ville">Ville</label>
                        <input name="ville" type="text" class="form-control mt-2" id="ville" placeholder="PARIS" required>
                    </div>
                </div>

                <div class="row justify-content-center mt-5">
                    <button type="submit" class="btn btn-dark col-md-6">Valider</button>
                </div>
            </form>
        </div>

    </main>

    <?php
    include('./footer.php');
    ?>

</body>

</html>