<?php

if(isset($_POST['submit']))
{
    $secret = '6LekSvEdAAAAANwRqe8FAjH5EHeFjOuSNZNFjJm8';
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
    $responseData = json_decode($verifyResponse);
    if(!$responseData->success)
    {
        header("location: ../sign_up.php?error=captchafailed");
        exit();
    }
}
    if(isset($_POST['submit'])){
        $nume = trim($_POST['nume']);
        $prenume = trim($_POST['prenume']);
        $email = trim($_POST['email']);
        $pwd = $_POST['pwd'];
        $cpwd = $_POST['cpwd'];
        $tip = 1;

        require_once 'connect-DB.inc.php';
        require_once 'functions.inc.php';

        if(emptyInputSignup($nume, $prenume, $email, $pwd, $cpwd, $tip) !== false){
            header("location: ../sign_up.php?error=emptyinput");
            exit();
        }
        
        if(invalidName($nume) !== false){
            header("location: ../sign_up.php?error=invalidnume");
            exit();
        }

        if(invalidName($prenume) !== false){
            header("location: ../sign_up.php?error=invalidprenume");
            exit();
        }

        if(invalidEmail($email) !== false){
            header("location: ../sign_up.php?error=invalidemail");
            exit();
        }

        if(pwdMatch($pwd, $cpwd) !== false){
            header("location: ../sign_up.php?error=pwddontmatch");
            exit();
        }

        if(userExists($conn, $email) !== false){
            header("location: ../sign_up.php?error=userexists");
            exit();
        }
        $userTempExists = userTempExists($conn, $email);
        if($userTempExists !== false){

            $user_id = $userTempExists['id'];

            updateUserTemp($conn, $nume, $prenume, $pwd, $tip, $user_id);

            header("location: ../confirm_email.php?user_id=".$user_id);
            exit();
        }


        createTempUser($conn, $nume, $prenume, $email, $pwd, $tip);
    }
    else{
        header("location: ../sign_up.php");
        exit();
    }




?>