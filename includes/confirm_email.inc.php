<?php

    if(isset($_GET['user_id'])){

        $user_id = $_GET['user_id'];

        if(isset($_POST['submit'])){

            $code = trim($_POST['code']);

            require_once 'connect-DB.inc.php';
            require_once 'functions.inc.php';

            if(emptyCode($code) !== false){
                header("location: ../confirm_email.php?user_id=".$user_id."&error=emptycode");
                exit();
            }

            $checkCode = checkCode($conn, $user_id, $code);

            if($checkCode !== false){
                $createUser = createUser($conn, $checkCode);

                if($createUser === true){
                    deleteTempUser($conn, $user_id);
                }
            }
            else{
                header("location: ../confirm_email.php?user_id=".$user_id."&error=wrongcode");
                exit();
            }

            header("location: ../sign_up.php?error=none");
            exit();


        }
        else{
            header("location: ../sign_up.php");
            exit();
        }

    }
    else{
        header("location: ../sign_up.php");
        exit();
    }


?>