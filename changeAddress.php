<?php
session_start();

include('functions.php');

if (isset($_POST['addressChanged'])) {
    updateAddress();
}

if (isset($_POST['newAdress'])) {
    createAddress($_SESSION['id']);
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
                <img id="watchPhoto" src="images/watchturquoise.jpg" style="width: 100vw">
            </div>
        </div>

        <div class="container mt-3 text-center">
            <h3>Modifier une adresse</h3>
        </div>

        <?php displayAddresses("changeAddress.php"); ?>

        <div class="container mt-3 text-center">
            <h3>Ajouter une nouvelle adresse</h3>
        </div>

        <div class="container w-50 border border-dark bg-light mb-4 p-5">
            <form action="changeAddress.php" method="post">
                <div class="form-group mb-3">
                    <label for="inputAddress">Adresse</label>
                    <input name="adresse" type="text" class="form-control" id="adresse" placeholder="99 rue de l'horloge" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6 mb-3">
                        <label for="code_postal">Code Postal</label>
                        <input name="code_postal" type="text" class="form-control" id="code_postal" placeholder="12345" required>
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="ville">Ville</label>
                        <input name="ville" type="text" class="form-control" id="ville" placeholder="Clockville" required>
                    </div>
                </div>
                <div class="row justify-content-center mt-2">
                <button type="submit" class="btn btn-dark" name="newAdress">Valider</button>
                </div>
            </form>
        </div>

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

</html>