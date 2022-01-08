<?php
    exit();
    $link = 'https://www.cinemacity.ro/films/familia-addams-2/4522d2r#/buy-tickets-by-film?for-movie=4522d2r&view-mode=list';
    $page = file_get_contents($link);

    $filmDetails = explode('filmDetails = {', $page);
    $filmDetails = $filmDetails[1];

    $filmDetails = explode('var', $filmDetails);
    $filmDetails = $filmDetails[0];

    $filmDetails = explode('"', $filmDetails);

    $titlu = trim($filmDetails[7]);

    //echo $titlu."<br>";

    $regizor = trim($filmDetails[45]);

    //echo $regizor."<br>";

    $durata = explode("DURATĂ", $page);
    $durata = $durata[1];

    $durata = explode("min", $durata);
    $durata = trim($durata[0]);

    //echo $durata."<br>";

    $clasificare = explode("CLASIFICARE", $page);
    $clasificare = $clasificare[1];

    $clasificare = explode("</p>", $clasificare);
    $clasificare = trim($clasificare[0]);

    //echo $clasificare."<br>";

    $genuri = explode("cats =", $page);
    $genuri = $genuri[1];

    $genuri = explode('"', $genuri);
    $genuri = $genuri[1];

    $genuri = explode(',', $genuri);
    //print_r($genuri);

    require_once 'includes/connect-DB.inc.php';

    function cautaFilm($conn, $titlu){
        $query = "SELECT * FROM filme WHERE titlu = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $titlu);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            return $row;
        }
        else{
            $result = false;
            return $result;
        }

        mysqli_stmt_close($stmt);
    }

    function cautaGen($conn, $gen){
        $query = "SELECT * FROM genuri WHERE nume_gen = ? ;";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $gen);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            return $row;
        }
        else{
            $result = false;
            return $result;
        }

        mysqli_stmt_close($stmt);
    }

    function insertFilm($conn, $titlu, $regizor, $durata, $clasificare){
        if(cautaFilm($conn, $titlu)===false){
            $query = "INSERT INTO filme (titlu, regizor, durata, clasificare) VALUES (?, ?, ?, ?);";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $query)){
                exit();
            }

            mysqli_stmt_bind_param($stmt, "ssis", $titlu, $regizor, $durata, $clasificare);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        else{

        }
    }

    function insertGenuri($conn, $gen){
        if(cautaGen($conn, $gen) === false){
            $query = "INSERT INTO genuri (nume_gen) VALUES (?);";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $query)){
                exit();
            }

            mysqli_stmt_bind_param($stmt, "s", $gen);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    function cautaGenFilm($conn, $gen_id, $film_id){
        $query = "SELECT * FROM gen_film WHERE gen_id = ? AND film_id = ?;";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ii", $gen_id, $film_id);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            return $row;
        }
        else{
            $result = false;
            return $result;
        }

        mysqli_stmt_close($stmt);
    }

    function connectFilmGen($conn, $titlu, $gen){
        $cautaFilm = cautaFilm($conn, $titlu);
        $cautaGen = cautaGen($conn, $gen);

        $film_id = $cautaFilm['film_id'];
        $gen_id = $cautaGen['gen_id'];

        if(cautaGenFilm($conn, $gen_id, $film_id) === false){
            $query = "INSERT INTO gen_film (film_id, gen_id) VALUES (?, ?);";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $query)){
                exit();
            }

            mysqli_stmt_bind_param($stmt, "ii", $film_id, $gen_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    insertFilm($conn, $titlu, $regizor, $durata, $clasificare);

    for($i = 0; $i < sizeof($genuri); $i++){
        insertGenuri($conn, $genuri[$i]);
        connectFilmGen($conn, $titlu, $genuri[$i]);
    }

    





?>
