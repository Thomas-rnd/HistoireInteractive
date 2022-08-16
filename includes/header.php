<?php require_once "includes/functions.php"; ?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"> <span class="glyphicon glyphicon-book"></span> Histoire interactive</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <?php if (isAdminConnected())
            { ?>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Actions administrateur</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="histoire_add.php">Ajouter une histoire</a></li>
                            <li><a class="dropdown-item" href="content_add.php">Ajouter une narration</a></li>
                            <li><a class="dropdown-item" href="bdd_modify.php">Modification histoire</a></li>
                            <li><a class="dropdown-item" href="histoire_delete.php">Suppression histoire</a></li>
                            <li><a class="dropdown-item" href="histoire_hide.php">Visibilité d'une histoire</a></li>
                            <li><a class="dropdown-item" href="histoire_stat.php">Statistiques histoire</a></li>
                        </ul>
                    </li>
                </ul>
            <?php }?>
            <?php 
            if(isUserConnected())
            { ?>
                <ul class="nav navbar-right">
                    <li class="nav-item dropdown">
                        <button class="btn btn-outline-light dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="glyphicon glyphicon-user"></span> Bienvenue, <?= $_SESSION['login'] ?></button>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="logout.php">Se déconnecter</a></li>
                        </ul>
                    </li>
                </ul>
            <?php }
            else{ ?>
                <ul class="nav navbar-right">
                    <li class="nav-item dropdown">
                        <a class="btn btn-outline-light dropdown-toggle float-right" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="glyphicon glyphicon-user"></span>Vous n'êtes pas connecté
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="login.php">Se connecter</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="create_account.php">S'inscrire</a></li>
                        </ul>
                    </li>
                </ul>
            <?php } ?>
        </div>
    </div>
</nav>