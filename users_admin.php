<?php
    session_start();

    if(!(isset($_SESSION['user_id']) && $_SESSION['tip'] == 0)){
        header("location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel = "stylesheet" href = "CSS/stil.css" type = "text/css">
    <title>Admin</title>
</head>
<body>

    <?php
        include_once 'navbar.php';
    ?>
    <p>Search user:</p>
    <form action="users_admin.php" method="post">
        <table>
            <tr>
                <td>Nume:</td>
                <td><input type="text" name="nume"></td>
            </tr>

            <tr>
                <td>Prenume:</td>
                <td><input type="text" name="prenume"></td>
            </tr>

            <tr>
                <td>Email:</td>
                <td><input type="text" name="email"></td>
            </tr>
            <tr>
                <td><button type="submit" name="submit">search</button></td>
            </tr>
        </table>
    </form>

    <?php

        if(isset($_POST['submit'])){
            $nume = $_POST['nume'];
            $prenume = $_POST['prenume'];
            $email = $_POST['email'];

            require_once 'includes/connect-DB.inc.php';
            require_once 'includes/functions.inc.php';

            $users = getUsers($conn, $nume, $prenume, $email);

            echo '<br>Results:<br><br>';

            if($users === false){
                echo 'Nu am gasit utilizatori!';
            }
            else{
                for($i = 1; $i <= sizeof($users); $i++){
                    $nume = $users[$i]['nume'];
                    $prenume = $users[$i]['prenume'];
                    $email = $users[$i]['email'];
                    $tip = $users[$i]['tip'];
                    $nume_tip = '';
                    $user_id = $users[$i]['user_id'];
                    if($tip === 0){
                        $nume_tip = 'admin';
                    }
                    else if($tip === 1){
                        $nume_tip = 'user';
                    }
                    else if($tip === 2){
                        $nume_tip = 'manager';
                    }

                    echo 'Nume: '.$nume.'<br>Prenume: '.$prenume.'<br>Email: '.$email.'<br>Tip: '.$nume_tip;
                    
                    echo '<form action="edit_user.php?user_id='.$user_id.'" method="post">
                            <input type="submit" name="submit" value="Edit">
                          </form>';

                    echo '<br>';
                    echo '<br>';
                }
            }
        }


        if(isset($_GET['error'])){
        }

    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>