<?php
require_once "includes/functions.php";
session_start();

if (!empty($_POST['login']) and !empty($_POST['password'])) 
{
    $login = $_POST['login'];
    $password = $_POST['password'];
    $stmt = getDb()->prepare('select * from user where USR_LOGIN=?');
    $stmt->execute(array($login));
    if ($stmt->rowCount() == 0) 
    {
        // Login not already used
        $stmt = getDb()->prepare('insert into user
        (USR_LOGIN, USR_PASSWORD)
        values (?, ?)');
        $stmt->execute(array($login, $password));
        
        $stmt = getDb()->prepare('select * from user where USR_LOGIN=?');
        $stmt->execute(array($login));
        $usr=$stmt->fetch();

        $histoire = getDb()->prepare('select * from histoire order by HIST_NUM');
        $histoire->execute();
        while($initHistoire = $histoire->fetch())
        {
            $stmt = getDb()->prepare('insert into statistiques
            (USR_ID, HIST_NUM, AVANCEMENT, NB_GAGNE, NB_PERDU, NB_JOUE) values (?, ?, ?, ? ,?, ?)');
            $stmt->execute(array($usr['USR_ID'], $initHistoire['HIST_NUM'],1,0,0,0));
        }
        redirect("index.php");
    }
    else 
    {
        $error = "Identifiant déjà utilisé";
    }
}?>

<!doctype html>
<html>

<?php 
$pageTitle = "Inscription";
require_once "includes/head.php";
?>

<body>
    <?php require_once "includes/header.php"; ?>
    <div class="container">
        <h2 class="text-center"><?= $pageTitle ?></h2>
        <?php if (isset($error)) 
        {?>
            <div class="alert alert-danger">
                <strong>Erreur !</strong> <?= $error ?>
            </div>
        <?php } ?>

        <div class="well">
            <form class="form-signin form-horizontal" role="form" action="create_account.php" method="post">
                <div class="form-group">
                    <div class="col-sm col-sm-offset col-md col-md-offset">
                        <input type="text" name="login" class="form-control" placeholder="Entrez votre login" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm col-sm-offset col-md col-md-offset">
                        <input type="password" name="password" class="form-control" placeholder="Entrez votre mot de passe" required>
                    </div>
                </div>
                <p class="message text-center">Déjà un compte ? <a href="login.php">Se connecter</a></p>
                <div class="form-group">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-default btn-primary"><span class="glyphicon glyphicon-log-in"></span> S'inscrire</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php require_once "includes/footer.php"; ?>
    <?php require_once "includes/scripts.php"; ?>
</body>

</html>