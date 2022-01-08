<?php

    if(isset($_POST['submit']))
    {
        $secret = '6LekSvEdAAAAANwRqe8FAjH5EHeFjOuSNZNFjJm8';
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        if(!$responseData->success)
        {
            header("location: ../log_in.php?error=captchafailed");
            exit();
        }
    }

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