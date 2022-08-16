<?php
require_once "includes/functions.php";
session_start();

$stmt = getDb()->prepare('select * from user where USR_LOGIN=?');
$stmt->execute(array($_SESSION['login']));
$user=$stmt->fetch();

if (isAdminConnected()) 
{
    $statistiques = getDb()->prepare('select * from statistiques where USR_ID=? order by HIST_NUM');
    $statistiques->execute(array($user['USR_ID']));
}?>

<!doctype html>
<html>

<?php
$pageTitle = "Statistiques histoire";
require_once "includes/head.php";
?>

<body>
    <?php require_once "includes/header.php"; ?>
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">N° histoire</th>
                    <th scope="col">Avancement</th>
                    <th scope="col">Nombre de parties gagnés</th>
                    <th scope="col">Nombre de parties perdus</th>
                    <th scope="col">Nombre de parties jouées</th>
                </tr>
            </thead>
            <tbody>
                <?php while($histoire=$statistiques->fetch())
                {?>
                    <tr>
                        <th scope="row">[<?=$histoire['HIST_NUM']?>]</th>
                        <td><?=$histoire['AVANCEMENT']?></td>
                        <td><?=$histoire['NB_GAGNE']?></td>
                        <td><?=$histoire['NB_PERDU']?></td>
                        <td><?=$histoire['NB_JOUE']?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php require_once "includes/footer.php"; ?>
    <?php require_once "includes/scripts.php"; ?>
</body>
</html>