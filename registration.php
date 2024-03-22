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

        <div class="container w-50 border border-dark bg-light mb-4 p-5">
            
            <form action="./connection.php" method="post">

                <div class="form-row">

                    <div class="form-group col-md-6">
                        <label for="inputFirstName">Prénom</label>
                        <input name="prenom" type="text" class="form-control" id="inputFirstName" placeholder="Paul" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="inputName">Nom</label>
                        <input name="nom" type="text" class="form-control" id="inputName" placeholder="DUPONT" required>
                    </div>

                </div>

                <div class="form-row">

                    <div class="form-group col-md-6">
                        <label for="inputEmail">Email</label>
                        <input name="email" type="email" class="form-control" id="inputEmail" placeholder="paul.dupont@exemple.fr" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="inputPassword">Mot de passe</label>
                        <input name="password" type="password" class="form-control" id="inputPassword" placeholder="motdepasse" required>
                        <small id="emailHelp" class="form-text text-muted">Entre 8 et 15 caractères, minimum 1 lettre, 1 chiffre et 1 caractère spécial</small>
                    </div>

                </div>

                <div class="form-group">
                    <label for="inputAddress">Adresse</label>
                    <input name="addresse" type="text" class="form-control" id="inputAddress" placeholder="99 rue de l'horloge" required>
                </div>

                <div class="form-row">

                    <div class="form-group col-md-6">
                        <label for="inputZip">Code Postal</label>
                        <input name="code_postal" type="text" class="form-control" id="inputZip" placeholder="12345" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="inputCity">Ville</label>
                        <input name="ville" type="text" class="form-control" id="inputCity" placeholder="Clockcity" required>
                    </div>

                </div>

                <div class="row justify-content-center mt-2">
                <button type="submit" class="btn btn-dark">Valider</button>
                </div>
            </form>
        </div>

    </main>

    <?php
    include('./footer.php');
    ?>

</body>

</html>