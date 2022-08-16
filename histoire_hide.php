<?php
require_once "includes/functions.php";
session_start();

if (isAdminConnected()) 
{
    $histoires = getDb()->prepare('select * from histoire');
    $histoires->execute();

    if (isset($_POST['histoire'])) 
    {
        $action=escape($_POST['action']);
        $stmt = getDb()->prepare('select * from histoire where HIST_TITRE=?');
        $stmt->execute(array(escape($_POST['histoire'])));
        $histoire=$stmt->fetch();
        if($action=="Rendre invisible")
        {
            $modify = getDb()->prepare("update histoire set HIST_ACCES=:acces where HIST_NUM=:histId");
            $modify->execute(array('acces'=>0,'histId'=>$histoire['HIST_NUM']));
        }
        else
        {
            $modify = getDb()->prepare("update histoire set HIST_ACCES=:acces where HIST_NUM=:histId");
            $modify->execute(array('acces'=>1,'histId'=>$histoire['HIST_NUM']));
        }
        redirect("index.php");
    }
}?>

<!doctype html>
<html>

<?php
$pageTitle = "Cacher une histoire";
require_once "includes/head.php";
?>

<body>
    <?php require_once "includes/header.php"; ?>
    <div class="container">
        <h2 class="text-center">Cacher une histoire</h2>
        <div class="well">
            <form class="form-horizontal" role="form" enctype="multipart/form-data" action="histoire_hide.php" method="post">
                <div class="form-group">
                    <label for="exampleSelect1" class="form-label mt-4">Sélection d'une histoire : </label>
                    <select name="histoire" class="form-select" id="exampleSelect1">
                        <?php while($numHist = $histoires->fetch()) 
                        { ?>
                            <div class="col-sm">
                                <option><?=$numHist['HIST_TITRE']?></option>
                            </div>
                        <?php } ?>
                    </select>
                </div>
                </br>
                <div class="form-group">
                    <label for="exampleSelect1" class="form-label mt-4">Action à réaliser : </label>
                    <select name="action" class="form-select" id="exampleSelect1">
                        <option>Rendre invisible</option>
                        <option>Rendre public</option>
                    </select>
                </div>
                </br>
                <div class="form-group">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-default btn-primary mx-auto"><span class="glyphicon glyphicon-save"></span>Valider</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php require_once "includes/footer.php"; ?>
    <?php require_once "includes/scripts.php"; ?>
</body>
</html>