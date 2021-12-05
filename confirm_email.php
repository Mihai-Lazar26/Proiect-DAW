<?php
    session_start();
?>



<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel = "stylesheet" href = "CSS/stil.css" type = "text/css">
    <title>Confirm email</title>
</head>
<body>
    <?php
        include_once 'navbar.php';
        require_once 'includes/connect-DB.inc.php';
        require_once 'includes/functions.inc.php';

        if(isset($_GET['user_id'])){

            $user_id = $_GET['user_id'];
            $userTempIdExists = userTempIdExists($conn, $user_id);
            if($userTempIdExists !== false){
                $email = $userTempIdExists['email'];
                $code = createRandomPassword(5);
                insertConfirmationCode($conn, $user_id, $code);
                mailCode($email, $code);
            }

            echo '<form action="includes/confirm_email.inc.php?user_id='.$user_id.'" method="post">';
            
        }
        else{
            header("location: ../index.php");
            exit();
        }


    ?>
        <table>
            <tr>
                <td>Code:</td>
                <td><input type="text" name="code"></td>
            </tr>

            <tr>
                <td><button type="submit" name="submit">Log in</button></td>
            </tr>
        </table>
    </form>

    <?php
        if(isset($_GET['error'])){
            $error = $_GET['error'];
            if($error == 'emptyinput'){
                echo '<p>Nu a fost completat câmpul!</p>';
            }
            if($error == 'wrongcode'){
                echo '<p>Codul introdus este greșit!</p>';
            }
            if($error == 'stmtfailed'){
                echo '<p>Ceva nu a mers bine, încercați iar mai târziu!</p>';
            }
        }

    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>