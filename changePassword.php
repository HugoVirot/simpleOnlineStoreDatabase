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
        <div class="container-fluid pb-3">
            <div class="row text-center">
                <img id="watchPhoto" src="images/watchturquoise.jpg" style="width: 100vw">
            </div>
        </div>

        <div class="container mt-4 mb-5 text-center">
            <h3>Modifier mon mot de passe</h3>
        </div>

        <div class="container w-50 text-center p-5 border border-dark bg-light mb-5 p-5">
            <form action="account.php" method="post" >
                <input type="hidden" name="passwordModified" value="true">
                <input type="hidden" name="clientId" value="<?php echo $_SESSION['id'] ?>">
                <div class="form-row text-center justify-content-center mb-5">
                    <div class="form-group">
                        <label for="inputPassword">Ancien mot de passe</label>
                        <input name="oldPassword" type="password" class="form-control" id="inputPassword" placeholder="ancienmotdepasse" required>
                    </div>
                </div>
                <div class="form-row text-center justify-content-center mb-5">
                    <div class="form-group">
                        <label for="inputPassword">Nouveau mot de passe</label>
                        <input name="newPassword" type="password" class="form-control" id="inputPassword" placeholder="nouveaumotdepasse" required>
                        <small id="emailHelp" class="form-text text-muted">Entre 8 et 15 caractères, minimum 1 lettre, 1 chiffre et 1 caractère spécial</small>
                    </div>
                </div>
                <div class="row justify-content-center mt-2">
                    <button type="submit" class="btn btn-dark">Valider</button>
                </div>
            </form>
        </div>

        <div class="container w-50 text-center">

            <div class="row">
                <div class="col-md-4">
                    <a href="changeInformations.php">
                        <button class="btn btn-dark">Modifier mes infos</button>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="changeAddress.php">
                        <button class="btn btn-dark">Modifier mon adresse</button>
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