<?php

    exit();
    $adress = "BD. SOCOLA nr. 13 bl. D6 sc. C et PARTER, IAŞI, 7001136";
    $judet = "Iasi";
    $nume = "Cinematastic";

    require_once 'includes/connect-DB.inc.php';

    function cautaCinema($conn, $nume){
        $query = "SELECT * FROM cinema WHERE nume = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $nume);
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

    function createCinema($conn, $nume, $judet, $adresa){
        if(cautaCinema($conn, $nume)===false){
            $query = "INSERT INTO cinema (nume, judet, adresa) VALUES (?, ?, ?);";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $query)){
                exit();
            }

            mysqli_stmt_bind_param($stmt, "sss", $nume, $judet, $adresa);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    function createSala($conn, $cinema, $sala_id, $nume){
        $cautaCinema = cautaCinema($conn, $cinema);
        if($cautaCinema !== false){
            $cinema_id = $cautaCinema['cinema_id'];

            $query = "INSERT INTO sali (sala_id, cinema_id, nume) VALUES (?, ?, ?);";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $query)){
                exit();
            }

            mysqli_stmt_bind_param($stmt, "iis", $sala_id, $cinema_id, $nume);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

        }
    }

    function createLocuri($conn, $cinema, $sala_id, $loc_id){
        $cautaCinema = cautaCinema($conn, $cinema);
        if($cautaCinema !== false){
            $cinema_id = $cautaCinema['cinema_id'];

            $query = "INSERT INTO loc (loc_id, sala_id, cinema_id) VALUES (?, ?, ?);";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $query)){
                exit();
            }

            mysqli_stmt_bind_param($stmt, "iii", $loc_id, $sala_id, $cinema_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    if(cautaCinema($conn, $nume) === false){

        createCinema($conn, $nume, $judet, $adress);

        $nrSali = rand(1, 3);
        for($i = 1; $i <= $nrSali; $i++){
            $nume_sala = 'Sala '.$i;
            $nrLocuri = rand(20, 50);
            createSala($conn, $nume, $i, $nume_sala);
            for($j = 1; $j <= $nrLocuri; $j++){
                createLocuri($conn, $nume, $i, $j);
            }
        }
    }

?>