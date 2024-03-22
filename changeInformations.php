<?php
session_start();

include('functions.php');

if (isset($_POST['userModified'])) {
    updateUser();
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

        <div class="container mt-4 text-center">
            <h3>Modifier mes informations</h3>
        </div>

        <?php displayInformations("changeInformations.php");?>

        <div class="container w-50 text-center">

            <div class="row">
                <div class="col-md-4">
                    <a href="changeAddress.php">
                        <button class="btn btn-dark">Modifier mon adresse</button>
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