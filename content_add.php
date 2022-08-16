<?php
require_once "includes/functions.php";
session_start();

if (isAdminConnected()) 
{
    $histoires = getDb()->query('select * from histoire order by HIST_NUM desc'); 
    
    if (isset($_POST['narration'])) 
    {
        // the history form has been posted : retrieve movie parameters
        $histoire = escape($_POST['histoire']);
        $stmt = getDb()->prepare('select * from histoire where HIST_TITRE=?');
        $stmt->execute(array($histoire)); 
        $resultat = $stmt->fetch();          
        $histId = $resultat['HIST_NUM'];
        $narration = escape($_POST['narration']);
        $nbChoix = escape($_POST['nbChoix']);
        
        // insert narration into BD
        $stmt = getDb()->prepare('insert into narration
        (NARR_TEXTE, NARR_NBCHOIX, HIST_NUM)
        values (?, ?, ?)');
        $stmt->execute(array($narration, $nbChoix, $histId));   

        $stmt = getDb()->prepare('select * from narration where NARR_TEXTE=? and NARR_NBCHOIX=? and HIST_NUM=?');
        $stmt->execute(array($narration, $nbChoix, $histId)); 
        $resultat=$stmt->fetch();   
        $narrId = $resultat['NARR_INDEX'];
        $_SESSION['histId']=$histId;
        $_SESSION['narrId']=$narrId;
        $_SESSION['nbChoix']=$nbChoix;

        $narrNonCrées = getDb()->prepare('select * from choix where NARR_INDEX!=ALL(select NARR_INDEX from narration)');
        $narrNonCrées->execute();
        if($nbChoix!=0)
        {
            redirect("choice_add.php");
        }
        else
        {
            redirect("index.php");
        }
    }
}?>

<!doctype html>
<html>

<?php
$pageTitle = "Ajout d'une narration";
require_once "includes/head.php";
?>

<body>
    <?php require_once "includes/header.php"; ?>
        <div class="container">
            <h2 class="text-center">Ajout du contenu</h2>
            <div class="container">
                <div class="well">
                    <form class="form-horizontal" role="form" enctype="multipart/form-data" action="content_add.php" method="post">
                        <div class="form-group">
                            <label for="exampleSelect1" class="form-label mt-4">Sélection d'une histoire : </label>
                            <select name="histoire" class="form-select" id="exampleSelect1">
                                <?php while($numHist = $histoires->fetch()) 
                                {?>
                                    <div class="col-sm">
                                        <option><?=$numHist['HIST_TITRE']?></option>
                                    </div>
                                <?php } ?>
                            </select>
                        </div>
                        </br>
                        <!--
                        <div class="form-group">
                            <label for="exampleSelect1" class="form-label mt-4">Les narrations à créer : </label>
                                <select name="histoire" class="form-select" id="exampleSelect1">
                                    <?php /* while($numNarr = $narrNonCrées->fetch()) 
                                    { ?>
                                        <div class="col-sm">
                                            <option><?=$numNarr['NARR_INDEX']?> - <?=$numNarr['NARR_TEXTE']?></option>
                                        </div>
                                    <?php } */?>
                                </select>
                        </div>
                        -->
                        <div class="form-group">
                            <label class="col-sm">Narration : </label>
                            <div class="col-sm">
                                <textarea name="narration" class="form-control" rows="3" placeholder="Entrez du texte de narration" required></textarea>
                            </div>
                        </div>
                        </br>
                        <div class="form-group">
                                <label for="exampleSelect1" class="form-label mt-4">Nombre de choix possible : </label>
                                <select name="nbChoix" class="form-select" id="exampleSelect1">
                                    <option>0</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
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
        </div>
        <?php require_once "includes/footer.php"; ?>
        <?php require_once "includes/scripts.php"; ?>
    </body>

  </html>
