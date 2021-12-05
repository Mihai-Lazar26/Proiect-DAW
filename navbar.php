<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Proiect DAW</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navmenu">
            <ul class="navbar-nav ms-auto">

                <?php
                    if(isset($_SESSION['user_id'])){
                        echo '<li class="nav-item">
                                    <a href="profil.php" class="nav-link">Profil</a>
                                </li>
                                <li class="nav-item">
                                    <a href="includes/log_out.inc.php" class="nav-link">Log out</a>
                                </li>';
                    }
                    else{
                        echo '<li class="nav-item">
                                    <a href="log_in.php" class="nav-link">Log in</a>
                                </li>
                                <li class="nav-item">
                                    <a href="sign_up.php" class="nav-link">Sign up</a>
                                </li>';
                    }
                ?>

                <li class="nav-item">
                    <a href="cauta_filme.php" class="nav-link">Cauta filme</a>
                </li>


                
                
            </ul>
        </div>
    </div>

</nav>
