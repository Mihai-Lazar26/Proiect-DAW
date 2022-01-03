<?php
    session_start();
    if(!(isset($_SESSION['user_id']) && $_SESSION['tip'] == 0)){
        header("location: index.php");
        exit();
    }

    if(!(isset($_POST['submit']))){
        header("location: ../index.php");
        exit();
    }

    if(!(isset($_GET['user_id']))){
        header("location: ../index.php");
        exit();
    }

    require_once 'connect-DB.inc.php';
    require_once 'functions.inc.php';

    $user_id = $_GET['user_id'];
    $nume = $_POST['nume'];
    $prenume = $_POST['prenume'];
    $email = $_POST['email'];
    $tip = $_POST['tip'];

    updateUser($conn, $user_id, $nume, $prenume, $email, $tip);

    if($tip == 2){
        header("location: ../make_manager_of.php?user_id=".$user_id);
        exit();
    }
    else{
        makeManagerOfCinemaNULL($conn, $user_id);
        header("location: ../index.php");
        exit();
    }
?>