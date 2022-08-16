<?php
require_once "includes/functions.php";
session_start();

if (isUserConnected()) {

    $histoires = getDb()->query('select * from histoire order by HIST_NUM desc'); 

    if (isset($_POST['histoire']) && isset($_POST['choixModification'])) 
    {
        $choixHistoire = escape($_POST['histoire']);
        $choixModification = escape($_POST['choixModification']);

        $stmt = getDb()->prepare('select * from histoire where HIST_TITRE=?');
        $stmt->execute(array($choixHistoire)); 
        $resultat = $stmt->fetch();          
        $histId = $resultat['HIST_NUM'];

        if($_POST['choixModification'] == "Histoire")
        {
            redirect("histoire_modify.php");
        }
        else if($_POST['choixModification'] == "Contenu")
        {
            redirect("content_modify.php");
        }
        $_SESSION['histId']=$histId;
    }  
}?>

<!doctype html>
<html>

<?php
$pageTitle = "Modification d'une histoire";
require_once "includes/head.php";
?>

<body>
    <?php require_once "includes/header.php"; ?>
    <div class="container">
        <h2 class="text-center">Modification d'une histoire</h2>
            <div class="well">
                <form class="form-horizontal" role="form" enctype="multipart/form-data" action="bdd_modify.php" method="post">
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
                    <div class="form-group">
                            <label for="exampleSelect1" class="form-label mt-4">Choix de la modification : </label>
                            <select name="choixModification" class="form-select" id="exampleSelect1">
                                <option>Histoire</option>
                                <option>Contenu</option>
                            </select>
                    </div>
                    </br></br>
                    <div class="form-group">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-default btn-primary mx-auto"><span class="glyphicon glyphicon-save"></span> Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
    </div>
</br></br>
<?php require_once "includes/footer.php"; ?>
<?php require_once "includes/scripts.php"; ?>
</body>

</html>
