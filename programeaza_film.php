<?php
    session_start();

    if(!(isset($_SESSION['user_id']) && $_SESSION['tip'] == 2)){
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
    <title>Manager</title>
</head>
<body>

    <?php
        include_once 'navbar.php';
        require_once 'includes/connect-DB.inc.php';
        require_once 'includes/functions.inc.php';

        $user_id = $_SESSION['user_id'];

        $user = getUserById($conn, $user_id);

        $cinema_id = $user['cinema_id'];

        $cinema = getCinemaById($conn, $cinema_id);

        echo 'Cinema: '.$cinema['nume'].'<br>Judet: '.$cinema['judet'].'<br>Adresa: '.$cinema['adresa'];
        echo '<br>';
        echo '<br>';
        $sali = getSaliOfCinemaByID($conn, $cinema_id);

        if($sali !== false){
            for($i = 1; $i <= sizeof($sali); $i++){
                $sala_id = $sali[$i]['sala_id'];
                $nume = $sali[$i]['nume'];

                echo 'Sala: '.$nume;
                    
                echo '<form action="sala_programari.php?sala_id='.$sala_id.'" method="post">
                        <input type="submit" name="submit" value="Planificari">
                      </form>';
                echo '<form action="planifica.php?sala_id='.$sala_id.'" method="post">
                        <input type="submit" name="submit" value="Planifica">
                      </form>';
                
                echo '<br>';
            }
        }

    ?>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>