<?php
    session_start();

    include_once 'functions.inc.php';
    include_once 'connect-DB.inc.php';

    if(isset($_SESSION['user_id'])){
        if(isset($_POST['submit'])){
            $user_id = $_SESSION['user_id'];
            $difuzare_id = $_GET['difuzare_id'];
            $cod_tip_bilet = $_GET['cod_tip_bilet'];

            echo $user_id.'<br>';
            echo $difuzare_id.'<br>';
            echo $cod_tip_bilet.'<br>';

            if(biletExist($conn, $difuzare_id) !== false){
                header('location: ../index.php?error=biletexists');
                exit();
            }

            insertBilet($conn, $user_id, $difuzare_id, $cod_tip_bilet);



            header('location: ../index.php');
            exit();

        }
        else{
            header('location: ../index.php');
            exit();
        }
    }
    else{
        header('location: index.php');
        exit();
    }
?>
