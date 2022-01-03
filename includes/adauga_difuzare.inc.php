<?php
    session_start();

    if(!(isset($_SESSION['user_id']) && $_SESSION['tip'] == 2)){
        header("location: ../index.php");
        exit();
    }

    if(isset($_POST['submit'])){

        $data_start = $_POST['data_start'];

        $user_id = $_SESSION['user_id'];

        $sala_id = $_GET['sala_id'];

        $film_id = $_GET['film_id'];

        if(empty($data_start)){
            header("location: ../programeaza_film_data.php?sala_id=".$sala_id."&film_id=".$film_id."&error=empty");
            exit();
        }

        require_once 'connect-DB.inc.php';
        require_once 'functions.inc.php';

        $user = getUserById($conn, $user_id);

        $cinema_id = $user['cinema_id'];

        $data_start = explode('T', $data_start);

        $data_start = $data_start[0].' '.$data_start[1].':00';

        $film = getFilmById($conn, $film_id);

        createDifuzare($conn, $cinema_id, $sala_id, $film_id, $data_start);

        header("location: ../index.php");
        exit();

        
    }
    else{
        header("location: ../index.php");
        exit();
    }
?>