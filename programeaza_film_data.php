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

        $sala_id = $_GET['sala_id'];
        $film_id = $_GET['film_id'];

        echo  '<form action="includes/adauga_difuzare.inc.php?sala_id='.$sala_id.'&film_id='.$film_id.'" method="post">'
    ?>

   
        <label for="data_start">Alegeti o data:</label>
        <input type="datetime-local" id="data_start" name="data_start">
        <button type="submit" name="submit">Continua</button>
    </form>

    <?php
        if(isset($_GET['error'])){
            $error = $_GET['error'];
            if($error == 'empty'){
                echo 'Campul nu a fost completat!';
            }
        }
    
    ?>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>