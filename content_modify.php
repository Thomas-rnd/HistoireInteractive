<?php
require_once "includes/functions.php";
session_start();

if (isAdminConnected()) 
{  
    $stmt = getDb()->prepare('select * from histoire where HIST_NUM=?');
    $stmt->execute(array($_SESSION['histId']));
    $histoire = $stmt->fetch();

    $narrations = getDb()->prepare('select * from narration where HIST_NUM=?');
    $narrations->execute(array($_SESSION['histId']));

    $stmt = getDb()->prepare('select * from choix where HIST_NUM=?');
    $stmt->execute(array($_SESSION['histId']));
    $premierChoix = $stmt->fetch();

    $stmt = getDb()->prepare('select * from narration where HIST_NUM=?');
    $stmt->execute(array($_SESSION['histId']));
    $premièreNarration = $stmt->fetch();

    $stmt = getDb()->prepare('select * from narration where HIST_NUM=? order by NARR_INDEX desc');
    $stmt->execute(array($_SESSION['histId']));
    $dernièreNarration = $stmt->fetch();

    if (isset($_POST['narrations'])) 
    {
        for($i=0;$i<count($_POST['narrations']);$i++)
        {
            $texteNarration = escape($_POST['narrations'][$i]);  
            $nbChoix = escape($_POST['nbChoix'][$i]);
            $texteChoix = escape($_POST['choix'][$i]);  
            $chIndex = escape($_POST['indexChoix'][$i]);
            
            // modification in BD
            $modifyNarration = getDb()->prepare("update narration set NARR_TEXTE=:texte, NARR_NBCHOIX=:nbChoix
            where NARR_INDEX=:narrId and HIST_NUM=:histId");
            $modifyNarration->execute(array(
            'texte'=>$texteNarration,
            'nbChoix'=>$nbChoix,
            'narrId'=>$i+$premièreNarration['NARR_INDEX'],
            'histId'=>$_SESSION['histId']));

            $modifyChoix = getDb()->prepare("update choix set CH_TEXTE=:texte, CH_INDEX=:index
            where CH_NUM=:num");
            $modifyChoix->execute(array(
            'texte'=>$texteChoix,
            'index'=>$chIndex,
            'num'=>$premierChoix['CH_NUM']+$i));
        }     
        redirect("index.php");
    }
}
    ?>

<!doctype html>
<html>

<?php
$pageTitle = "Modification narration";
require_once "includes/head.php";
?>

<body>
    <?php require_once "includes/header.php"; ?>
    <div class="container">
        <h2 class="text-center">Modification narrations : </h2>
        <div class="container">
            <?php require_once "includes/header.php"; ?>
            <div class="well">
                <form class="form-horizontal" role="form" enctype="multipart/form-data" action="content_modify.php" method="post">
                    <?php while($narration = $narrations->fetch()) 
                    { ?>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Narration <?=$narration['NARR_INDEX']?> :</label>
                            <textarea name="narrations[]" class="form-control" rows="3" required><?=$narration['NARR_TEXTE']?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleSelect1" class="form-label mt-4">Nombre de choix possible : </label>
                            <select name="nbChoix[]" class="form-select" id="exampleSelect1" required >
                                <?php if($narration['NARR_NBCHOIX']==0){?>
                                    <option selected="selected">0</option>
                                <?php }
                                else{?>
                                   <option>0</option>
                                <?php }
                                if($narration['NARR_NBCHOIX']==1){?>
                                    <option selected="selected">1</option>
                                <?php }
                                else{?>
                                    <option>1</option>
                                <?php }
                                if($narration['NARR_NBCHOIX']==2){?>
                                    <option selected="selected">2</option>
                                <?php }
                                else{?>
                                    <option>2</option>
                                <?php }
                                if($narration['NARR_NBCHOIX']==3){?>
                                    <option selected="selected">3</option>
                                <?php }
                                else{?>
                                    <option>3</option>
                                <?php }?>
                            </select>
                        </div> 
                        </br>
                        <?php
                        $choix = getDb()->prepare('select * from choix where HIST_NUM=? and NARR_INDEX=?');
                        $choix->execute(array($_SESSION['histId'],$narration['NARR_INDEX']));   
                        ?>
                        <?php while($choice=$choix->fetch()) 
                        {?>
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Choix <?=$choice['CH_NUM']?> :</label>
                                <textarea name="choix[]" class="form-control" rows="3" required><?=$choice['CH_TEXTE']?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleSelect1" class="form-label mt-4">Index de renvoie : </label>
                                <input type="number" name="indexChoix[]" class="form-control" value="<?=$choice['CH_INDEX']?>" min="0" max="<?=$dernièreNarration['NARR_INDEX']?>">
                            </div>                      
                            </br>
                        <?php } ?>                
                        </br></br>
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