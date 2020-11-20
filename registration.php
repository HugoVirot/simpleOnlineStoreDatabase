<?php
session_start();
include('functions.php');
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer mon compte - Arinfo, montres intemporelles<?php?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

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
            <form action="connection.php" method="post">
                <input type="hidden" name="userCreated" value="true">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputFirstName">Prénom</label>
                        <input name="firstName" type="text" class="form-control" id="inputFirstName" placeholder="Paul" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputName">Nom</label>
                        <input name="lastName" type="text" class="form-control" id="inputName" placeholder="DUPONT" required>
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
                    <input name="address" type="text" class="form-control" id="inputAddress" placeholder="99 rue de l'horloge" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputZip">Code Postal</label>
                        <input name="zipCode" type="text" class="form-control" id="inputZip" placeholder="12345" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputCity">Ville</label>
                        <input name="city" type="text" class="form-control" id="inputCity" placeholder="Clockcity" required>
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
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

</html>