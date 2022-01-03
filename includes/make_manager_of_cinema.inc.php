<?php
    if(isset($_POST['submit'])){

        require_once 'connect-DB.inc.php';
        require_once 'functions.inc.php';

        $cinema_id = $_GET['cinema_id'];
        $user_id = $_GET['user_id'];

        if(checkIfManager($conn, $user_id) === false){
            header("location: ../index.php");
            exit();
        }

        makeManagerOfCinema($conn, $user_id, $cinema_id);

        header("location: ../index.php");
        exit();
    }
    else{
        header("location: ../index.php");
        exit();
    }

?>