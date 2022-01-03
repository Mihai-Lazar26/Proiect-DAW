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
    ?>

    <p>Cauta film:</p>
    <?php
        $sala_id = $_GET['sala_id'];
        echo '<form action="planifica.php?sala_id='.$sala_id.'" method="post">';
    ?>
    
        <table>
            <tr>
                <td>Titlu:</td>
                <td><input type="text" name="titlu"></td>
            </tr>

            <tr>
                <td>Regizor:</td>
                <td><input type="text" name="regizor"></td>
            </tr>

            <tr>
                <td><button type="submit" name="submitSearch">search</button></td>
            </tr>
        </table>
    </form>


    <?php
        require_once 'includes/connect-DB.inc.php';
        require_once 'includes/functions.inc.php';

        if(isset($_POST['submitSearch'])){
            echo '<br>';
            echo '<p>Results:</p>';

            $titlu = $_POST['titlu'];
            $regizor = $_POST['regizor'];

            $filme = getFilme($conn, $titlu, $regizor);

            if($filme !== false){
                for($i = 1; $i <= sizeof($filme); $i++){
                    $film_id = $filme[$i]['film_id'];
                    $titlu = $filme[$i]['titlu'];
                    $regizor = $filme[$i]['regizor'];
                    $durata = $filme[$i]['durata'];

                    echo 'Filmul: '.$titlu.'<br>Regizor: '.$regizor.'<br>Durata: '.$durata.' min';

                    echo '<form action="programeaza_film_data.php?sala_id='.$sala_id.'&film_id='.$film_id.'" method="post">
                            <input type="submit" name="submit" value="Select">
                          </form>';

                    echo '<br>';
                }
            }
            else{
                echo 'Nu am gasit filme!';
            }
        }
        
    ?>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>