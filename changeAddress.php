<?php
session_start();

include('functions.php');

if (isset($_POST['addressModified'])) {
    updateAddress();
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte - Arinfo, montres intemporelles <?php?></title>
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
        <div class="container-fluid pb-3">
            <div class="row text-center">
                <img id="watchPhoto" src="images/watchturquoise.jpg" style="width: 100vw">
            </div>
        </div>

        <div class="container mt-3 text-center">
            <h3>Modifier mon adresse</h3>
        </div>

        <?php displayAddress("changeAddress.php");?>

        <div class="container mt-3 text-center">

            <div class="row">
                <div class="col-md-4">
                    <a href="changeInformations.php">
                        <button class="btn btn-dark">Modifier mes informations </button>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="changePassword.php">
                        <button class="btn btn-dark">Modifier mon mot de passe</button>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="orders.php">
                        <button class="btn btn-dark">Voir mes commandes</button>
                    </a>
                </div>
            </div>
            
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