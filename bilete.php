<?php
    session_start();
    if(!isset($_SESSION['user_id'])){

        Header('location: index.php');
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
    <title>Bilete</title>
</head>
<body>

    <?php
        include_once 'navbar.php';

        require_once 'includes/connect-DB.inc.php';
        require_once 'includes/functions.inc.php';

        $user_id = $_SESSION['user_id'];

        $bilete = getBileteByUser_id($conn, $user_id);
        if($bilete !== false){
            for($i = 1; $i <= sizeof($bilete); $i++){
                $bilet_id = $bilete[$i]['bilet_id'];
                $infoBilet = infoBilet($conn, $bilet_id);

                echo 'Film: "'.$infoBilet['titlu_film'].'"<br>Cinema: '.$infoBilet['nume_cinema'].'<br>Sala: '.
                $infoBilet['nume_sala'].'<br>Loc: '.$infoBilet['loc_id'].'<br>Tip bilet: '.$infoBilet['tip_bilet'].
                '<br>Pret: '.$infoBilet['pret'].' lei'
                .'<br>Valabil pentru data: '.$infoBilet['data_start'].' - '.$infoBilet['data_end'];

                echo '<form action="biletFpdf.php?bilet_id='.$bilet_id.'" method="post">
                        <input type="submit" name="submit" value="PDF">
                      </form>';
                echo '<br>';
            }
        }
        else{
            echo 'Nu exista bilete!';
        }
    ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>