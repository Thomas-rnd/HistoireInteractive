<?php
require_once "includes/functions.php";
session_start();

if (!empty($_POST['login']) and !empty($_POST['password'])) 
{
    $login = $_POST['login'];
    $password = $_POST['password'];
    $stmt = getDb()->prepare('select * from user where USR_LOGIN=? and USR_PASSWORD=?');
    $stmt->execute(array($login, $password));
    if ($stmt->rowCount() == 1) 
    {
        // Authentication successful
        $_SESSION['login'] = $login;
        redirect("index.php");
    }
    else 
    {
        $error = "Utilisateur non reconnu";
    }
}
?>

<!doctype html>
<html>

<?php 
$pageTitle = "Connexion";
require_once "includes/head.php";
?>

<body>
    <?php require_once "includes/header.php"; ?>
    <div class="container">
        <h2 class="text-center"><?= $pageTitle ?></h2>
        <?php if (isset($error)) 
        { ?>
            <div class="alert alert-danger">
                <strong>Erreur !</strong> <?= $error ?>
            </div>
        <?php } ?>
        <div class="well">
            <form class="form-signin form-horizontal" role="form" action="login.php" method="post">
                <div class="form-group">
                    <div class="col-sm col-sm-offset col-md col-md-offset">
                        <input type="text" name="login" class="form-control" placeholder="Entrez votre login" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <div class="d-grid gap-2">
                        <input type="password" name="password" class="form-control" placeholder="Entrez votre mot de passe" required>
                    </div>
                </div>
                <p class="message text-center">Pas de compte ? <a href="create_account.php">Se créer un compte</a></p>
                <div class="form-group">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-default btn-primary mx-auto"><span class="glyphicon glyphicon-log-in"></span> Se connecter</button>
                    </div>
                </div>
            </form>
        </div> 
    </div>
    <?php require_once "includes/footer.php"; ?>
    <?php require_once "includes/scripts.php"; ?>
</body>
</html>