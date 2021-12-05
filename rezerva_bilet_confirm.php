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
    <title>Rezerva</title>
</head>
<body>

    <?php
        include_once 'navbar.php';
        include_once 'includes/functions.inc.php';
        include_once 'includes/connect-DB.inc.php';

        if(isset($_SESSION['user_id'])){

            if(isset($_POST['submit'])){

                $film_id = $_GET['film_id'];
                $cinema_id = $_GET['cinema_id'];
                $sala_id = $_GET['sala_id'];
                $data = $_GET['data'];

                $loc = $_POST['loc'];
                $cod_tip_bilet = $_POST['cod_tip_bilet'];

                $selectFilm = selectFilm($conn, $film_id);
                $titlu = $selectFilm['titlu'];

                $selectCinema = selectCinema($conn, $cinema_id);
                $nume_cinema = $selectCinema['nume'];

                $selectSala = selectSala($conn, $cinema_id, $sala_id);
                $nume_sala = $selectSala['nume'];

                $selectTipBilet = selectTipBilet($conn, $cod_tip_bilet);
                $pret = $selectTipBilet['pret'];
                
                

                $selectDifuzareId = selectDifuzareId($conn, $film_id, $cinema_id, $sala_id, $data, $loc);
                if($selectDifuzareId !== false){

                    echo 'Filmul selectat: '.$titlu.'<br>';
                    echo 'Cinemaul selectat: '.$nume_cinema.'<br>';
                    echo 'Sala selectată: '.$nume_sala.'<br>';
                    echo 'Locul selectat: '.$loc.'<br>';
                    echo 'Data selectată: '.$data.'<br>';
                    echo 'Pretul: '.$pret.'<br>';

                    $difuzare_id = $selectDifuzareId[1]['difuzare_id'];

                    echo '<form action="includes/rezerva_bilet.inc.php?difuzare_id='.$difuzare_id.'&cod_tip_bilet='.$cod_tip_bilet.'" method="post">
                    <input type="submit" name="submit" value="Rezerva">
                    </form>';
                }
                else{
                    echo 'Din pacate nu mai sunt bilete valabile!';
                }
                
            }
            else{
                header('location: index.php');
                exit();
            }
        }



        else{
            header('location: index.php');
            exit();
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>