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

        $sala_id = $_GET['sala_id'];

        $difuzari = getDifuzari($conn, $cinema_id, $sala_id);
        echo '<p>Planificari:</p>';  
        if($difuzari !== false){
            for($i = 1; $i <= sizeof($difuzari); $i++){
                $film_id = $difuzari[$i]['film_id'];
                $data_start = $difuzari[$i]['data_start'];
                $data_end = $difuzari[$i]['data_end'];
                $film = getFilmById($conn, $film_id);

                $titlu = $film['titlu'];
                $regizor = $film['regizor'];
                $durata = $film['durata'];
                $clasificare = $film['clasificare'];

                echo 'Filmul: '.$titlu.'<br>Regizor: '.$regizor.'<br>Durata: '.$durata.' min<br>Clasificare: '.
                $clasificare.'<br>Data Start: '.$data_start.'<br>Data End: '.$data_end;

                echo '<br>';
                echo '<br>';
            }
        }
        else{
            echo 'Nu exista difuzari planificate!';
        }

    ?>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>