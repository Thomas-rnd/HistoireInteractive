<?php
require_once "includes/functions.php";
session_start();

if (isAdminConnected()) 
{
    $histoires = getDb()->query('select * from histoire order by HIST_NUM asc'); 
    
    if (isset($_POST['histoire'])) 
    {
        $histoire = escape($_POST['histoire']);
        $stmt = getDb()->prepare('select * from histoire where HIST_TITRE=?');
        $stmt->execute(array($histoire)); 
        $resultat = $stmt->fetch();          
        $histId = $resultat['HIST_NUM'];

        $supprimerChoix = getDb()->prepare('delete from choix where HIST_NUM=?');
        $supprimerChoix->execute(array($histId));  
        $supprimerNarrations = getDb()->prepare('delete from narration where HIST_NUM=?');
        $supprimerNarrations->execute(array($histId)); 
        $supprimerStatistiques = getDb()->prepare('delete from statistiques where HIST_NUM=?');
        $supprimerStatistiques->execute(array($histId));
        $supprimerHistoire = getDb()->prepare('delete from histoire where HIST_NUM=?');
        $supprimerHistoire->execute(array($histId));
        redirect("index.php");
    }
}?>

<!doctype html>
<html>

<?php
$pageTitle = "Suppression d'une histoire";
require_once "includes/head.php";
?>

<body>
    <?php require_once "includes/header.php"; ?>
    <div class="container"> 
        <h2 class="text-center">Supression d'une histoire</h2>
        <div class="container">
            <div class="well">
                <form class="form-horizontal" role="form" enctype="multipart/form-data" action="histoire_delete.php" method="post">
                    <div class="form-group">
                        <label for="exampleSelect1" class="form-label mt-4">Sélection d'une histoire : </label>
                            <select name="histoire" class="form-select" id="exampleSelect1">
                                <?php while($numHist = $histoires->fetch()) 
                                { ?>
                                    <div class="col-sm">
                                        <option ><?=$numHist['HIST_TITRE']?></option>
                                    </div>
                                <?php } ?>
                            </select>
                    </div>
                    </br></br>
                    <div class="form-group">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-default btn-primary mx-auto"><span class="glyphicon glyphicon-save"></span>Supprimer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php require_once "includes/footer.php"; ?>
    <?php require_once "includes/scripts.php"; ?>
</body>
</html>
