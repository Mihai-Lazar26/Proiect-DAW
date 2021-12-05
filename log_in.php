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
    <title>Log in</title>
</head>
<body>

    <?php
        include_once 'navbar.php';
    ?>

    <form action="includes/log_in.inc.php" method="post">
        <table>
            <tr>
                <td>Email:</td>
                <td><input type="email" name="email"></td>
            </tr>

            <tr>
                <td>Parola:</td>
                <td><input type="password" name="pwd"></td>
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
                echo '<p>Nu au fost completate toate câmpurile!</p>';
            }
            if($error == 'wronglogin'){
                echo '<p>Adresa de email sau/și parola sunt incorecte!</p>';
            }
        }

    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>