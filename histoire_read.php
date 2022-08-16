<?php
require_once "includes/functions.php";
session_start();

$stmt = getDb()->prepare('select * from user where USR_LOGIN=?');
$stmt->execute(array($_SESSION['login']));
$user=$stmt->fetch();

if (isset($_POST['histId']) or isset($_POST['usrAvancement'])) 
{
    $_SESSION['histId'] = escape($_POST['histId']);
    if (isset($_POST['usrAvancement']))
    {
        $_SESSION['usrAvancement'] = escape($_POST['usrAvancement']);
    }
    elseif (isset($_POST['resetAvancement'])) 
    {
        $_SESSION['usrAvancement'] = escape($_POST['resetAvancement']);
    }
}

$stmt = getDb()->prepare('select * from statistiques where USR_ID=? and HIST_NUM=?');
$stmt->execute(array($user['USR_ID'], $_SESSION['histId']));
$statistiques = $stmt->fetch();

if(isset($_POST['lancement']) or (isset($_POST['resetAvancement'])))
{
    $modify = getDb()->prepare("update statistiques set NB_JOUE=:joue
    where USR_ID=:usrId and HIST_NUM=:histId");
    $modify->execute(array(
    'joue'=>$statistiques['NB_JOUE']+1,
    'usrId'=>$user['USR_ID'],
    'histId'=>$_SESSION['histId']));
}

$nbNarrations = getDb()->prepare('select * from narration where HIST_NUM=? order by NARR_INDEX');
$nbNarrations->execute(array($_SESSION['histId']));
$premièreNarration = $nbNarrations->fetch();

if($_SESSION['usrAvancement']==1 and $nbNarrations->rowCount()>=1)
{
    $_SESSION['usrAvancement']=$premièreNarration['NARR_INDEX'];
}

$nbChoixNarration = getDb()->prepare('select * from narration where HIST_NUM=? and NARR_INDEX=?');
$nbChoixNarration->execute(array($_SESSION['histId'], $_SESSION['usrAvancement']));

if($nbChoixNarration->rowCount() != 0)
{
    $nbChoixNarration = $nbChoixNarration->fetch();
    if($_SESSION['usrAvancement']==$nbNarrations->rowCount())
    {
        $modify = getDb()->prepare("update statistiques set AVANCEMENT=:avancement, NB_GAGNE=:gagne
        where USR_ID=:usrId and HIST_NUM=:histId");
        $modify->execute(array(
        'avancement'=>$_SESSION['usrAvancement'],
        'gagne'=>$statistiques['NB_GAGNE']+1,
        'usrId'=>$user['USR_ID'],
        'histId'=>$_SESSION['histId']));
    }
    elseif($nbChoixNarration['NARR_NBCHOIX']==0)
    {
        $modify = getDb()->prepare("update statistiques set AVANCEMENT=:avancement, NB_PERDU=:perdu
        where USR_ID=:usrId and HIST_NUM=:histId");
        $modify->execute(array(
        'avancement'=>$_SESSION['usrAvancement'],
        'perdu'=>$statistiques['NB_PERDU']+1,
        'usrId'=>$user['USR_ID'],
        'histId'=>$_SESSION['histId']));

        $chemin = getDb()->prepare('select * from chemin');
        $chemin->execute();
    }
    else
    {
        $modify = getDb()->prepare("update statistiques set AVANCEMENT=:avancement
        where USR_ID=:usrId and HIST_NUM=:histId");
        $modify->execute(array(
        'avancement'=>$_SESSION['usrAvancement'],
        'usrId'=>$user['USR_ID'],
        'histId'=>$_SESSION['histId']));
    }
}

$stmt = getDb()->prepare('select * from statistiques where USR_ID=? and HIST_NUM=?');
$stmt->execute(array($user['USR_ID'], $_SESSION['histId']));
$statistiquesMaj = $stmt->fetch();

if($nbNarrations->rowCount()!=0)
{
    $premierIndex = $nbNarrations->fetch();
    $avancement = ($statistiquesMaj['AVANCEMENT']-$premierIndex['NARR_INDEX'])/$nbNarrations->rowCount()*100;
}

$narrations = getDb()->prepare('select * from narration where HIST_NUM=? and NARR_INDEX=?');
$narrations->execute(array($_SESSION['histId'], $_SESSION['usrAvancement']));
$narration = $narrations->fetch();

$titre = getDb()->prepare('select * from histoire where HIST_NUM=?');
$titre->execute(array($_SESSION['histId']));
$histoire = $titre->fetch();?>

<!doctype html>
<html>

<?php
$pageTitle = $histoire['HIST_TITRE'];
require_once "includes/head.php"; 
?>

<body>
    <?php require_once "includes/header.php";?>
    <div class="container">
        <?php if($narrations->rowCount() == 1)
        {?>
            <div class="jumbotron">
                <h1><?= $histoire['HIST_TITRE']?></h1>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= $avancement ?>%;" aria-valuenow="<?= $avancement ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                </br>
                <h5 class="text-center"><?=$narration['NARR_TEXTE']?></h5>
                </br>
                <?php 
                $choix = getDb()->prepare('select * from choix where NARR_INDEX=? and HIST_NUM=?');
                $choix->execute(array($statistiquesMaj['AVANCEMENT'],$_SESSION['histId']));?>
                <div class="row">
                    <?php 
                    /*
                    if($choix->rowCount()==0)
                    {
                        $chemin = getDb()->prepare('select * from chemin');
                        $chemin->execute();
                        while($choix = $chemin->fetch())
                        {?>
                        <p><?=$choix['CH_TEXTE']?> : <?=$choix['CH_INDEX']?></p>
                        <?php }
                        ?>
                    <?php}*/
                    while($numChoix = $choix->fetch()) 
                    { ?>
                        <form method="POST" action="histoire_read.php"> 
                            <div class="col-sm">
                                <input type="hidden" name="histId" value="<?=$_SESSION['histId']?>"/>
                                <input type="hidden" name="usrAvancement" value="<?=$numChoix['CH_INDEX']?>"/>
                                <div class="d-grid gap-2 m-auto">
                                    <button class ="btn btn-outline-secondary btn-rounded" type="submit"> <?=$numChoix['CH_TEXTE'] ?></button>
                                </div>
                                </br>
                            </div>
                        </form>
                    <?php } ?>   
                    <form method="POST" action="histoire_read.php"> 
                        <div class="col-sm">
                            <input type="hidden" name="histId" value="<?=$_SESSION['histId']?>"/>
                            <input type="hidden" name="resetAvancement" value="1"/>
                            <div class="d-grid gap-2">
                                <button class ="btn btn-primary mx-auto" type="submit">Relancer l'histoire</button>
                            <div>
                            </br></br>
                        </div>
                    </form>     
                </div>         
            </div>
        <?php }
        else
        {?>
            <div class="jumbotron">
            <h1><?= $histoire['HIST_TITRE']?></h1>
            <h5 class="text-center">Cette histoire n'a pas de narrations</h5>
        <?php } ?>
    </div>
    <?php require_once "includes/footer.php"; ?>
    <?php require_once "includes/scripts.php"; ?>
</body>
</html>