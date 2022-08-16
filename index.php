<?php
require_once "includes/functions.php";
session_start();

// return al history
$histoires = getDb()->query('select * from histoire where HIST_ACCES=1 order by HIST_NUM'); 
?>

<!doctype html>
<html>

<?php require_once "includes/head.php"; ?>

<body>
    <?php require_once "includes/header.php"; ?>
    <div class="container">
        </br>
        <div class="d-flex justify-content-center row row-cols-4">
            <?php foreach ($histoires as $histoire) 
            { ?>
            <div class="col card border border-dark" style="width: 19rem;">
                <img src="images/<?=$histoire['HIST_IMAGE']?>" class="card-img-top" alt="Image de l'histoire : <?=$histoire['HIST_TITRE']?>">
                <div class="card-body">
                    <h5 class="card-title"><?=$histoire['HIST_TITRE']?></h5>
                    <h6 class="card-title"><?=$histoire['HIST_AUTEUR']?>, <?=$histoire['HIST_DATE']?></h6>
                    <p class="card-text"><?=$histoire['HIST_RESUME']?></p>
                    <?php
                    if (isUserConnected()) 
                    { 
                        $stmt = getDb()->prepare('select * from user where USR_LOGIN=?');
                        $stmt->execute(array($_SESSION['login']));
                        $user = $stmt->fetch();          
                        $usrId = $user['USR_ID'];
                        $tmp = getDb()->prepare('select * from statistiques where USR_ID=? and HIST_NUM=?');
                        $tmp->execute(array($usrId,$histoire['HIST_NUM']));
                        $statistiques = $tmp->fetch();          
                        $avancement = $statistiques['AVANCEMENT'];?>
                        <form method="POST" action="histoire_read.php">
                            <input type="hidden" name="histId" value="<?=$histoire['HIST_NUM']?>"/>
                            <input type="hidden" name="usrAvancement" value="<?=$avancement?>"/>
                            <input type="hidden" name="lancement" value="oui"/>
                            <div class="d-grid gap-2 m-auto">
                                <button class ="btn btn-outline-success btn-rounded" type="submit">Lancer l'histoire</button>
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
        </br>
    </div>
    <?php require_once "includes/footer.php"; ?>
    <?php require_once "includes/scripts.php"; ?>
</body>
</html>