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
    <title>Sign up</title>
</head>
<body>
    <?php
        include_once 'navbar.php';
    ?>
    <form action="includes/sign_up.inc.php" method="post">
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
                <td><input type="email" name="email"></td>
            </tr>

            <tr>
                <td>Parola:</td>
                <td><input type="password" name="pwd"></td>
            </tr>

            <tr>
                <td>Confirma parola:</td>
                <td><input type="password" name="cpwd"></td>
            </tr>

            <tr>
                <td hidden><input type="checkbox" name="tip" value="1" checked >Sunt un user</td>
                <td><input type="checkbox" name="tip" value="2">Sunt un manager</td>
            </tr>

            <tr>
                <td><button type="submit" name="submit">Sign up</button></td>
            </tr>
        </table>
    </form>

    <?php
        if(isset($_GET['error'])){
            $error = $_GET['error'];
            if($error == 'emptyinput'){
                echo '<p>Nu au fost completate toate câmpurile!</p>';
            }
            if($error == 'invalidnume'){
                echo '<p>Numele trebuie să conțină doar litere, cifre sau/și spați(în caz de mai multe nume)!</p>';
            }
            if($error == 'invalidprenume'){
                echo '<p>Prenumele trebuie să conțină doar litere, cifre sau/și spați(în caz de mai multe nume)!</p>';
            }
            if($error == 'invalidemail'){
                echo '<p>Emailul nu a fost introdus corect!</p>';
            }
            if($error == 'pwddontmatch'){
                echo '<p>Parolele nu coincid!</p>';
            }
            if($error == 'userexists'){
                echo '<p>Utilizatorul cu adresa introdusă există deja!</p>';
            }
            if($error == 'usertempexists'){
                echo '<p>Utilizatorul cu adresa introdusă există deja!</p>';
            }
            if($error == 'stmtfailed'){
                echo '<p>Ceva nu a mers bine!</p>';
            }
            if($error == 'none'){
                echo '<p>Cont creat cu succes!</p>';
            }
        }

    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>