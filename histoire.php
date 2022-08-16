<?php
require_once "includes/functions.php";
session_start();

if (isset($_POST['histId'])) 
{
    $histId = escape($_POST['histId']);
    $_SESSION['histId']=$histId;
}

$stmt = getDb()->prepare('select * from histoire where HIST_NUM=?');
$stmt->execute(array($_SESSION['histId']));
$histoire = $stmt->fetch(); 
?>

<!doctype html>
<html>
<?php 
$pageTitle = $histoire['HIST_TITRE'];
require_once "includes/head.php"; 
?>

<body>
    <div class="container">
        <?php require_once "includes/header.php"; ?>
        <div class="card border-light mb-3" style="max-width: 30rem;">
            <img class="img-responsive histImage" src="images/<?= $histoire['HIST_IMAGE'] ?>" title="<?= $histoire['HIST_TITRE'] ?>" />
                <div class="card-body">
                    <h3 class="card-title"><?= $histoire['HIST_TITRE'] ?><?="," ?> <?= $histoire['HIST_AUTEUR'] ?></h3>
                    <h4 class="card-subtitle"><?= $histoire['HIST_DATE'] ?></h4>
                    <p class = "card-text"><?= $histoire['HIST_RESUME'] ?></p>
                    <?php 
                    if (isUserConnected()) { 
                        $stmt = getDb()->prepare('select * from user where USR_LOGIN=?');
                        $stmt->execute(array($_SESSION['login']));
                        $user = $stmt->fetch();          
                        $usrId = $user['USR_ID'];
                        $tmp = getDb()->prepare('select * from statistiques where USR_ID=? and HIST_NUM=?');
                        $tmp->execute(array($usrId,$histoire['HIST_NUM']));
                        $statistiques = $tmp->fetch();          
                        $avancement = $statistiques['AVANCEMENT'];?>
                        <div class = "position-absolute top-50 start-50 translate-middle" >
                            <form method="POST" action="histoire_read.php">
                                <input type="hidden" name="histId" value="<?=$_SESSION['histId']?>"/>
                                <input type="hidden" name="usrAvancement" value="<?=$avancement?>"/>
                                <button class ="btn btn-link" type="submit">Lancer l'histoire</button>
                            </form>
                        </div>
                    <?php } ?>
                </div>
        </div>

    <?php require_once "includes/footer.php"; ?>
    </div>

<?php require_once "includes/scripts.php"; ?>
</body>

</html>