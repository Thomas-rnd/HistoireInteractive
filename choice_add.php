<?php
require_once "includes/functions.php";
session_start();

if (isAdminConnected()) 
{
    if (isset($_POST['choix'])) 
    {
        for($i=0;$i<count($_POST['choix']);$i++)
        {
            $choix = escape($_POST['choix'][$i]);  
            $indexChoix = escape($_POST['indexChoix'][$i]);
            
            // insert choice into BD
            $stmt = getDb()->prepare('insert into choix
            (CH_TEXTE, CH_INDEX, NARR_INDEX, HIST_NUM)
            values (?, ?, ?, ?)');
            $stmt->execute(array($choix, $indexChoix, $_SESSION['narrId'], $_SESSION['histId']));
        }
        redirect("index.php");
    }
}?>

<!doctype html>
<html>

<?php
$pageTitle = "Ajout d'une histoire";
require_once "includes/head.php";
?>

<body>
    <?php require_once "includes/header.php"; ?>
    <div class="container">
        <h2 class="text-center">Ajout d'un choix</h2>
        <div class="well">
            <form class="form-horizontal" role="form" enctype="multipart/form-data" action="choice_add.php" method="post">
                <?php for($i=0;$i<$_SESSION['nbChoix'];$i++)
                {?>
                    <div class="form-group">
                        <label class="col-sm-auto">Choix : </label>
                        <div class="col-sm-6">
                            <textarea name="choix[]" class="form-control" rows="3" placeholder="Définition du choix" required></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleSelect1" class="form-label mt-4">Index du choix : </label>
                        <div class="col-sm-6">
                            <input type="number" name="indexChoix[]" class="form-control" placeholder="Entrez l'index de retour du choix" min="0" required>
                        </div>
                    </div>
                <?php } ?>
                </br></br>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-4">
                        <button type="submit" class="btn btn-default btn-primary"><span class="glyphicon glyphicon-save"></span> Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php require_once "includes/footer.php"; ?>
<?php require_once "includes/scripts.php"; ?>
</body>
</html>
