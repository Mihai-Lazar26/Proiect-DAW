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
                $sala_id = $_POST['sala_id'];


                //SELECT * FROM `difuzari` WHERE film_id = 4 AND NOW() < data_start AND difuzare_id not in (SELECT difuzare_id FROM `bilet`);
                $selectDifuzareData = selectDifuzareData($conn, $film_id, $cinema_id, $sala_id);
                if($selectDifuzareData !== false){
                    
                    echo '<form action="rezerva_bilet_loc.php?film_id='.$film_id.'&cinema_id='.$cinema_id.'&sala_id='.$sala_id.'" method="post">
                        Selecteaza data: 
                        <select name="data">';
                            for($i = 1; $i <= sizeof($selectDifuzareData); $i++){
                                $data = $selectDifuzareData[$i]['data_start'];
                                echo '<option value="'.$data.'">'.$data;
                            }
                    echo '</select>
                        <input type="submit" name="submit" value="Rezerva Loc">
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