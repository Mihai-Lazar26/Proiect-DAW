<?php

    if(isset($_POST['submit'])){
        $email = trim($_POST['email']);
        $pwd = $_POST['pwd'];

        require_once 'connect-DB.inc.php';
        require_once 'functions.inc.php';

        if(emptyInputLogin($email, $pwd) !== false){
            header("location: ../log_in.php?error=emptyinput");
            exit();
        }

        loginUser($conn, $email, $pwd);
    }
    else{
        header("location: ../log_in.php");
        exit();
    }




?>