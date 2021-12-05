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
    <title>Search</title>
</head>
<body>

    <?php
        include_once 'navbar.php';

        echo 'Note: Filmele care au bilete valabile sunt "Hai sa cantam din nou!" si "Resident Evil: Bun venit în Raccoon City"'
    ?>

    <form action="cauta_film_results.php" method="post">
    Choose Search Type:<br />
    <select name="searchtype">
        <option value="titlu">Titlu
        <option value="regizor">Regizor
        <option value="gen">Gen
    </select>
    <br />
    Enter Search Term:<br />
    <input name="searchterm" type="text">
    <br />
    <input type="submit" name="submit" value="Search">
    </form>

    <?php
        if(isset($_GET['error'])){
            $error = $_GET['error'];
            if($error === 'emptyinput'){
                echo 'Campul nu a fost completat';
            }

            if($error === 'stmtfailed'){
                echo 'Ceva nu a mers bine, incearca din nou';
            }
            
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>