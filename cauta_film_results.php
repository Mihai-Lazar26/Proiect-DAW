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
    <title>Search results</title>
</head>
<body>

    <?php
        

        if(isset($_POST['submit'])){
            include_once 'navbar.php';
            include_once 'includes/functions.inc.php';
            include_once 'includes/connect-DB.inc.php';

            $searchtype=$_POST['searchtype'];
            $searchterm=trim($_POST['searchterm']);

            if(empty($searchterm)){
                header('location: cauta_filme.php?error=emptyinput');
                exit();
            }

            $searchFilme = searchFilme($conn, $searchtype, $searchterm);

            for($i = 1; $i <= sizeof($searchFilme); $i++){
                $film_id = $searchFilme[$i]['film_id'];
                $titlu = $searchFilme[$i]['titlu'];
                $regizor = $searchFilme[$i]['regizor'];
                $durata = $searchFilme[$i]['durata'];
                $clasificare = $searchFilme[$i]['clasificare'];
                
                $genuri_rows = selectGenuriFilm($conn, $film_id);

                echo 'Titlu: '.$titlu.' Regizor: '.$regizor.' Durata: '.$durata.' Clasificare: '.$clasificare.' Genuri: ';
                
                for($j = 1; $j <= sizeof($genuri_rows); $j++){
                    $nume_gen = $genuri_rows[$j]['nume_gen'];
                    echo $nume_gen.' ';
                }
                if(isset($_SESSION['user_id'])){

                    echo '<form action="rezerva_bilet_cinema.php?film_id='.$film_id.'" method="post">
                    <input type="submit" name="submit" value="Rezerva bilet">
                    </form>';
                }

                echo '</p><br>';
            }
            
        }
        else{
            header('location: cauta_filme.php');
            exit();
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>