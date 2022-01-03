<?php
    exit();
    require_once 'includes/connect-DB.inc.php';

    function selectLocuri($conn, $cinema_id, $sala_id){
        $query = "SELECT * FROM loc WHERE cinema_id = ? AND sala_id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ii", $cinema_id, $sala_id);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);
        $rows;
        $i = 1;

        if($row = mysqli_fetch_assoc($resultData)){
            $rows[$i] = $row;
            $i++;

            while($row = mysqli_fetch_assoc($resultData)){
                $rows[$i] = $row;
                $i++;
            }


            return $rows;
        }
        else{
            $result = false;
            return $result;
        }

        mysqli_stmt_close($stmt);
    }

    // $selectLocuri = selectLocuri($conn, 3, 1);

    // print_r($selectLocuri);



    function cautaFilm($conn, $film_id){
        $query = "SELECT * FROM filme WHERE film_id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $film_id);
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

    function selectDataEnd($conn, $film_id, $data_start){
        $cautaFilm = cautaFilm($conn, $film_id);
        $durata = $cautaFilm['durata'];

        $data_interval = new DateInterval('PT'.$durata.'M');
        $_data_start = new DateTime($data_start);
        $_data_end = clone $_data_start;
        $_data_end->add($data_interval);

        $stringData_end = date_format($_data_end, 'Y-m-d H:i:s');

        return $stringData_end;
    }


    function createDifuzare($conn, $cinema_id, $sala_id, $film_id, $data_start){

        $selectLocuri = selectLocuri($conn, $cinema_id, $sala_id);
        if($selectLocuri !== false){

            $data_end = selectDataEnd($conn, $film_id, $data_start);
            $n = sizeof($selectLocuri);
            $stmt = mysqli_stmt_init($conn);
            
            for($i = 1; $i <= $n; $i++){
                $query = "INSERT INTO difuzari (cinema_id, sala_id, loc_id, film_id, data_start, data_end) 
                VALUES (?, ?, ?, ?, ?, ?);";
                
                if(!mysqli_stmt_prepare($stmt, $query)){
                    exit();
                }

                $loc_id = $selectLocuri[$i]['loc_id'];
                

                mysqli_stmt_bind_param($stmt, "iiiiss", $cinema_id, $sala_id, $loc_id, $film_id, $data_start, $data_end);
                mysqli_stmt_execute($stmt);
                
            }
            mysqli_stmt_close($stmt);
        }

        

    }

    //createDifuzare($conn, $cinema_id, $sala_id, $film_id, $data_start)

    createDifuzare($conn, 5, 2, 7, '2022-02-05 14:00:00');



?>