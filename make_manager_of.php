<?php
    session_start();
    if(!(isset($_SESSION['user_id']) && $_SESSION['tip'] == 0)){
        header("location: index.php");
        exit();
    }
    if(!isset($_GET['user_id'])){
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
    <title>Edit</title>
</head>
<body>
    <?php
        include_once 'navbar.php'; 
    ?>

    <p>Search cinema:</p>

    <?php
        $user_id = $_GET['user_id'];
        echo '<form action="make_manager_of.php?user_id='.$user_id.'" method="post">';
    ?>
        <table>
            <tr>
                <td>Cinema:</td>
                <td><input type="text" name="nume"></td>
            </tr>

            <tr>
                <td>Judet:</td>
                <td><input type="text" name="judet"></td>
            </tr>

            <tr>
                <td>Adresa:</td>
                <td><input type="text" name="adresa"></td>
            </tr>

            <tr>
                <td><button type="submit" name="submitCinema">search</button></td>
            </tr>
        </table>
    </form>

    <?php
        if(isset($_POST['submitCinema'])){
            require_once 'includes/connect-DB.inc.php';
            require_once 'includes/functions.inc.php';
            echo '<p>Results:</p>';

            $nume = $_POST['nume'];
            $judet = $_POST['judet'];
            $adresa = $_POST['adresa'];

            $results = getCinemas($conn, $nume, $judet, $adresa);

            if($results === false){
                echo 'Nu am gasit cinemauri!';
            }
            else{
                for($i = 1; $i <= sizeof($results); $i++){
                    $cinema_id = $results[$i]['cinema_id'];
                    $nume = $results[$i]['nume'];
                    $judet = $results[$i]['judet'];
                    $adresa = $results[$i]['adresa'];

                    echo 'Nume: '.$nume.'<br>Judet: '.$judet.'<br>Adresa: '.$adresa.'<br>Manageri: ';
                    $manageri = getManageriOfCinema($conn, $cinema_id);

                    if($manageri !== false){
                        for($j = 1; $j <= sizeof($manageri); $j++){
                            $email = $manageri[$j]['email'];
                            echo $email.' ';
                        }
                    }
                    
                    echo '<form action="includes/make_manager_of_cinema.inc.php?cinema_id='.$cinema_id.'&user_id='.$user_id.'" method="post">
                            <input type="submit" name="submit" value="Make manager">
                          </form>';

                    echo '<br>';
                    echo '<br>';
                }
            }
        }
        
       
    ?>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>