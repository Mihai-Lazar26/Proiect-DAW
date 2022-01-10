<?php
    function createRandomPassword(int $n) { 

        $chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
        srand((double)microtime()*1000000); 
        $i = 0; 
        $pass = '' ; 

        while ($i <= $n) { 
            $num = rand() % 33; 
            $tmp = substr($chars, $num, 1); 
            $pass = $pass . $tmp; 
            $i++; 
        } 

        return $pass; 

    }


    function emptyInputSignup($nume, $prenume, $email, $pwd, $cpwd, $tip){
        $result;
        if(empty($nume) || empty($prenume) || empty($email) || empty($pwd) || empty($cpwd) || empty($tip)){
            $result = true;
        }
        else{
            $result = false;
        }

        return $result;
    }

    function invalidName($nume){
        $result;
        if(preg_match('/[^a-zA-Z0-9_ăîâșțĂÎÂȘȚ ]/', $nume)){
            $result = true;
        }
        else{
            $result = false;
        }

        return $result;
    }

    function invalidEmail($email){
        $result;
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $result = true;
        }
        else{
            $result = false;
        }

        return $result;
    }

    function pwdMatch($pwd, $cpwd){
        $result;
        if($pwd !== $cpwd){
            $result = true;
        }
        else{
            $result = false;
        }

        return $result;
    }

    function userExists($conn, $email){
        $query = "SELECT * FROM users WHERE email = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../sign_up.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $email);
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

    function userTempExists($conn, $email){
        $query = "SELECT * FROM unconfirmed_users WHERE email = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../sign_up.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $email);
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

    //$query = "UPDATE unconfirmed_users SET nume= ?, prenume= ?, parola= ?, tip= ?,  WHERE id = ?;";

    function updateUserTemp($conn, $nume, $prenume, $pwd, $tip, $id){
        $query = "UPDATE unconfirmed_users SET nume= ? WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../sign_up.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "si", $nume, $id);
        mysqli_stmt_execute($stmt);


        $query = "UPDATE unconfirmed_users SET prenume= ? WHERE id = ?;";
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../sign_up.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "si", $prenume, $id);
        mysqli_stmt_execute($stmt);

        $query = "UPDATE unconfirmed_users SET parola= ? WHERE id = ?;";
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../sign_up.php?error=stmtfailed");
            exit();
        }

        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        mysqli_stmt_bind_param($stmt, "si", $hashedPwd, $id);
        mysqli_stmt_execute($stmt);


        $query = "UPDATE unconfirmed_users SET tip= ? WHERE id = ?;";
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../sign_up.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ii", $tip, $id);
        mysqli_stmt_execute($stmt);


        mysqli_stmt_close($stmt);
    }

    function createTempUser($conn, $nume, $prenume, $email, $pwd, $tip){
        $query = "INSERT INTO unconfirmed_users (nume, prenume, email, parola, tip, date) VALUES (?, ?, ?, ?, ?, NOW());";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../sign_up.php?error=stmtfailed");
            exit();
        }


        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        mysqli_stmt_bind_param($stmt, "ssssi", $nume, $prenume, $email, $hashedPwd, $tip);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $userTempExists = userTempExists($conn, $email);
        $user_id = $userTempExists['id'];

        header("location: ../confirm_email.php?user_id=".$user_id);
        exit();
    }

    function emptyInputLogin($email, $pwd){
        $result;
        if(empty($email) || empty($pwd)){
            $result = true;
        }
        else{
            $result = false;
        }

        return $result;
    }

    function loginUser($conn, $email, $pwd){
        $userExist = userExists($conn, $email);

        if($userExist === false){
            header("location: ../log_in.php?error=wronglogin");
            exit();
        }

        $hashedPwd = $userExist['parola'];
        $checkPwd = password_verify($pwd, $hashedPwd);

        if($checkPwd === false){
            header("location: ../log_in.php?error=wronglogin");
            exit();
        }
        else if($checkPwd === true){
            session_start();
            $_SESSION['user_id'] = $userExist['user_id'];
            $_SESSION['nume'] = $userExist['nume'];
            $_SESSION['prenume'] = $userExist['prenume'];
            $_SESSION['tip'] = $userExist['tip'];

            header("location: ../index.php");
            exit();
        }
    }

    function userTempIdExists($conn, $user_id){
        $query = "SELECT * FROM unconfirmed_users WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $user_id);
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

    function insertConfirmationCode($conn, $user_id, $code){
        $query = "UPDATE unconfirmed_users SET confirmation_code = ? WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../confirm_email.php?error=stmtfailed");
            exit();
        }


        mysqli_stmt_bind_param($stmt, "si", $code, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    function mailCode($email, $code){
        require_once('phpmailer/class.phpmailer.php');
        require_once('phpmailer/mail_config.php');

        $message = '<p>Codul de verificare este: <b>'.$code.'</b></p>';

        $mail = new PHPMailer(true); 

        $mail->IsSMTP();
        try {
        
        $mail->SMTPDebug  = 0;                     
        $mail->SMTPAuth   = true; 

        $to=$email;
        $nume='User';

        $mail->SMTPSecure = "ssl";                 
        $mail->Host       = "smtp.gmail.com";      
        $mail->Port       = 465;                   
        $mail->Username   = $username;  			// GMAIL username
        $mail->Password   = $password;            // GMAIL password
        $mail->AddAddress($to, $nume);
        
        $mail->SetFrom($username, 'Proiect DAW');
        $mail->Subject = 'Codul de verificare';
        $mail->AltBody = strip_tags($message); 
        $mail->MsgHTML($message);
        $mail->Send();
        echo "<p>Codul de confirmare a fost trimis</p>\n";
        } catch (phpmailerException $e) {
        echo $e->errorMessage(); //error from PHPMailer
        } catch (Exception $e) {
        echo $e->getMessage(); //error from anything else!
        }
    }

    function emptyCode($code){
        $result;
        if(empty($code)){
            $result = true;
        }
        else{
            $result = false;
        }

        return $result;
    }

    function checkCode($conn, $user_id, $code){
        $query = "SELECT * FROM unconfirmed_users WHERE id = ? AND confirmation_code = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../confirm_email.php?user_id=".$user_id."&error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "is", $user_id, $code);
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

    function createUser($conn, $checkCode){
        $query = "INSERT INTO users (nume, prenume, email, parola, tip) VALUES (?, ?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../confirm_email.php?user_id=".$user_id."&error=stmtfailed");
            exit();
        }

        $nume = $checkCode['nume'];
        $prenume = $checkCode['prenume'];
        $email = $checkCode['email'];
        $parola = $checkCode['parola'];
        $tip = $checkCode['tip'];


        mysqli_stmt_bind_param($stmt, "ssssi", $nume, $prenume, $email, $parola, $tip);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return true;
    }

    function deleteTempUser($conn, $user_id){
        $query = "DELETE FROM unconfirmed_users WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../confirm_email.php?user_id=".$user_id."&error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    function selectGenuriFilm($conn, $film_id){
        $query = "SELECT g.* FROM genuri g, gen_film gf WHERE gf.gen_id = g.gen_id AND gf.film_id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header('location: cauta_filme.php?error=stmtfailed');
            exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $film_id);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            $i = 1;
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


    function searchFilme($conn, $searchtype, $searchterm){
        if($searchtype === 'gen'){

            
            $query = "SELECT DISTINCT f.* FROM filme f, genuri g, gen_film gf WHERE gf.gen_id = g.gen_id AND gf.film_id = f.film_id AND lower(g.nume_gen) like ?;";

            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $query)){
                header('location: cauta_filme.php?error=stmtfailed');
                    exit();
            }

            $_searchterm = '%'.strtolower($searchterm).'%';
    
            mysqli_stmt_bind_param($stmt, "s", $_searchterm);
            mysqli_stmt_execute($stmt);
    
            $resultData = mysqli_stmt_get_result($stmt);
    
            if($row = mysqli_fetch_assoc($resultData)){
                $i = 1;
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
        else{
            $_searchtype = htmlspecialchars($searchtype);

            $query = "SELECT DISTINCT * FROM filme WHERE lower(".$_searchtype.") like ?;";

            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $query)){
                header('location: cauta_filme.php?error=stmtfailed');
                    exit();
            }
            $_searchterm = '%'.strtolower($searchterm).'%';
    
            mysqli_stmt_bind_param($stmt, "s", $_searchterm);
            mysqli_stmt_execute($stmt);
    
            $resultData = mysqli_stmt_get_result($stmt);
    
            if($row = mysqli_fetch_assoc($resultData)){
                $i = 1;
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
    }


    //----------------------------------------------------------------------------------------


    function selectCinema($conn, $cinema_id){
        $query = "SELECT * FROM cinema WHERE cinema_id = ?";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header('location: index.php?error=stmtfailed');
                exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $cinema_id);
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

    function selectSala($conn, $cinema_id, $sala_id){
        $query = "SELECT * FROM sali WHERE cinema_id = ? AND sala_id = ?";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header('location: index.php?error=stmtfailed');
                exit();
        }

        mysqli_stmt_bind_param($stmt, "ii", $cinema_id, $sala_id);
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


    function selectDifuzareCinema($conn, $film_id){
        $query = "SELECT DISTINCT cinema_id FROM difuzari WHERE film_id = ? AND NOW() < data_start AND difuzare_id not in (SELECT difuzare_id FROM bilet);";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header('location: index.php?error=stmtfailed');
                exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $film_id);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            $i = 1;
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


    function selectDifuzareSala($conn, $film_id, $cinema_id){
        $query = "SELECT DISTINCT sala_id FROM difuzari WHERE film_id = ? AND cinema_id = ? AND NOW() < data_start AND difuzare_id not in (SELECT difuzare_id FROM bilet);";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header('location: index.php?error=stmtfailed');
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ii", $film_id, $cinema_id);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            $i = 1;
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


    function selectDifuzareData($conn, $film_id, $cinema_id, $sala_id){
        $query = "SELECT DISTINCT data_start FROM difuzari WHERE film_id = ? AND cinema_id = ? AND sala_id = ? AND NOW() < data_start AND difuzare_id not in (SELECT difuzare_id FROM bilet);";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header('location: index.php?error=stmtfailed');
                exit();
        }

        mysqli_stmt_bind_param($stmt, "iii", $film_id, $cinema_id, $sala_id);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            $i = 1;
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

    function selectLocuriRamase($conn, $film_id, $cinema_id, $sala_id, $data){
        $query = "SELECT DISTINCT loc_id FROM difuzari WHERE film_id = ? AND cinema_id = ? AND sala_id = ? AND data_start = ? AND NOW() < data_start AND difuzare_id not in (SELECT difuzare_id FROM bilet);";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header('location: index.php?error=stmtfailed');
                exit();
        }

        mysqli_stmt_bind_param($stmt, "iiis", $film_id, $cinema_id, $sala_id, $data);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            $i = 1;
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

    function selectTipBilete($conn){
        $query = "SELECT * FROM tip_bilet;";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header('location: index.php?error=stmtfailed');
                exit();
        }
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            $i = 1;
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

    function selectDifuzareId($conn, $film_id, $cinema_id, $sala_id, $data, $loc){
        $query = "SELECT DISTINCT difuzare_id FROM difuzari WHERE film_id = ? AND cinema_id = ? AND sala_id = ? AND data_start = ? AND loc_id = ? AND NOW() < data_start AND difuzare_id not in (SELECT difuzare_id FROM bilet);";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header('location: index.php?error=stmtfailed');
                exit();
        }

        mysqli_stmt_bind_param($stmt, "iiisi", $film_id, $cinema_id, $sala_id, $data, $loc);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            $i = 1;
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

    function selectFilm($conn, $film_id){
        $query = "SELECT * FROM filme WHERE film_id = ?";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header('location: index.php?error=stmtfailed');
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

    function selectTipBilet($conn, $cod_tip_bilet){
        $query = "SELECT * FROM tip_bilet WHERE cod_tip_bilet = ?";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header('location: index.php?error=stmtfailed');
                exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $cod_tip_bilet);
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

    function biletExist($conn, $difuzare_id){
        $query = "SELECT * FROM bilet WHERE difuzare_id = ?";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header('location: ../index.php?error=stmtfailed');
            exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $difuzare_id);
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

    function insertBilet($conn, $user_id, $difuzare_id, $cod_tip_bilet){
        $query = "INSERT INTO bilet (user_id, difuzare_id, cod_tip_bilet) VALUES (?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../sign_up.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "iii", $user_id, $difuzare_id, $cod_tip_bilet);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    function getUsers($conn, $nume, $prenume, $email){
        $query = "SELECT * FROM users WHERE lower(nume) like ? and lower(prenume) like ? and lower(email) like ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: index.php?error=stmtfailed");
            exit();
        }

        $nume = '%'.$nume.'%';
        $prenume = '%'.$prenume.'%';
        $email = '%'.$email.'%';

        mysqli_stmt_bind_param($stmt, "sss", $nume, $prenume, $email);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            $i = 1;
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

    function getUserById($conn, $user_id){
        $query = "SELECT * FROM users WHERE user_id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: index.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $user_id);
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

    function updateUser($conn, $user_id, $nume, $prenume, $email, $tip){
        $query = "UPDATE users SET nume = ?, prenume = ?, email = ?, tip = ? WHERE user_id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: index.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "sssii", $nume, $prenume, $email, $tip, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    function getCinemas($conn, $nume, $judet, $adresa){
        $query = "SELECT * FROM cinema WHERE lower(nume) like ? and lower(judet) like ? and lower(adresa) like ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: index.php?error=stmtfailed");
            exit();
        }

        $nume = '%'.$nume.'%';
        $judet = '%'.$judet.'%';
        $adresa = '%'.$adresa.'%';

        mysqli_stmt_bind_param($stmt, "sss", $nume, $judet, $adresa);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            $i = 1;
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

    function getManageriOfCinema($conn, $cinema_id){
        $query = "SELECT * FROM users WHERE cinema_id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: index.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $cinema_id);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            $i = 1;
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

    function checkIfManager($conn, $user_id){
        $query = "SELECT * FROM users WHERE user_id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            if($row['tip'] == 2){
                $result = true;
                return $result;
            }
        }
        else{
            $result = false;
            return $result;
        }

        mysqli_stmt_close($stmt);
    }

    function makeManagerOfCinema($conn, $user_id, $cinema_id){
        $query = "UPDATE users SET cinema_id = ? WHERE user_id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ii", $cinema_id, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    function makeManagerOfCinemaNULL($conn, $user_id){
        $query = "UPDATE users SET cinema_id = NULL WHERE user_id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    function getSaliOfCinemaByID($conn, $cinema_id){
        $query = "SELECT DISTINCT * FROM sali WHERE cinema_id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: index.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $cinema_id);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            $i = 1;
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

    function getCinemaById($conn, $cinema_id){
        $query = "SELECT * FROM cinema WHERE cinema_id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: index.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $cinema_id);
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

    function getDifuzari($conn, $cinema_id, $sala_id){
        $query = "SELECT DISTINCT film_id, data_start, data_end FROM difuzari WHERE cinema_id = ? AND sala_id = ? 
                AND data_start > NOW() ORDER BY 2;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: index.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ii", $cinema_id, $sala_id);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            $i = 1;
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

    function getFilmById($conn, $film_id){
        $query = "SELECT * FROM filme WHERE film_id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: index.php?error=stmtfailed");
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

    function getFilme($conn, $titlu, $regizor){
        $query = "SELECT * FROM filme WHERE lower(titlu) like ? and lower(regizor) like ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: index.php?error=stmtfailed");
            exit();
        }

        $titlu = '%'.$titlu.'%';
        $regizor = '%'.$regizor.'%';

        mysqli_stmt_bind_param($stmt, "ss", $titlu, $regizor);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            $i = 1;
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

    function makeDataEnd($conn, $film_id, $data_start){
        $cautaFilm = getFilmById($conn, $film_id);
        $durata = $cautaFilm['durata'];

        $data_interval = new DateInterval('PT'.$durata.'M');
        $_data_start = new DateTime($data_start);
        $_data_end = clone $_data_start;
        $_data_end->add($data_interval);

        $stringData_end = date_format($_data_end, 'Y-m-d H:i:s');

        return $stringData_end;
    }

    function selectLocuri($conn, $cinema_id, $sala_id){
        $query = "SELECT * FROM loc WHERE cinema_id = ? AND sala_id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../index.php?error=stmtfailed");
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

    function checkDataStart($conn, $cinema_id, $sala_id, $data_start, $data_end){
        $query = "select distinct data_start, data_end
                    from difuzari
                    where (? between data_start and data_end 
                    or ? BETWEEN data_start and data_end
                    or data_start between ? and ?
                    or data_end BETWEEN ? and ?)
                    and cinema_id = ? and sala_id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ssssssii",$data_start, $data_end, $data_start, $data_end,
                               $data_start, $data_end, $cinema_id, $sala_id);
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

    function createDifuzare($conn, $cinema_id, $sala_id, $film_id, $data_start){


        $selectLocuri = selectLocuri($conn, $cinema_id, $sala_id);
        if($selectLocuri !== false){

            $data_end = makeDataEnd($conn, $film_id, $data_start);

            $ok = checkDataStart($conn, $cinema_id, $sala_id, $data_start, $data_end);

            if($ok !== false){
                header("location: ../index.php?error=stmtfailed");
                exit();
            }

            $n = sizeof($selectLocuri);
            $stmt = mysqli_stmt_init($conn);
            
            for($i = 1; $i <= $n; $i++){
                $query = "INSERT INTO difuzari (cinema_id, sala_id, loc_id, film_id, data_start, data_end) 
                VALUES (?, ?, ?, ?, ?, ?);";
                
                if(!mysqli_stmt_prepare($stmt, $query)){
                    header("location: ../index.php?error=stmtfailed");
                    exit();
                }

                $loc_id = $selectLocuri[$i]['loc_id'];
                

                mysqli_stmt_bind_param($stmt, "iiiiss", $cinema_id, $sala_id, $loc_id, $film_id, $data_start, $data_end);
                mysqli_stmt_execute($stmt);
                
            }
            mysqli_stmt_close($stmt);
        }

    }

    function getBileteByUser_id($conn, $user_id){
        $query = "SELECT * FROM bilet WHERE user_id = ? ORDER BY bilet_id DESC;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: index.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            $i = 1;
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

    function infoBilet($conn, $bilet_id){
        $query = "SELECT b.user_id, f.titlu as titlu_film, f.regizor as regizor_film, f.durata as durata_film, 
        f.clasificare as clasificare_film, c.nume as nume_cinema, c.judet as judet_cinema, 
        c.adresa adresa_cinema, s.nume as nume_sala, d.loc_id, d.data_start, d.data_end, 
        tb.nume_tip as tip_bilet, tb.pret FROM bilet b, difuzari d, tip_bilet tb, cinema c, sali s, filme f 
        WHERE b.difuzare_id = d.difuzare_id AND b.cod_tip_bilet = tb.cod_tip_bilet AND c.cinema_id = d.cinema_id 
        AND d.cinema_id = s.cinema_id AND d.sala_id = s.sala_id AND d.film_id = f.film_id AND b.bilet_id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: index.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $bilet_id);
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

    function getStatisticaPopularitate($conn){
        $query = "select f.film_id, count(b.bilet_id) as nr from filme f left outer join difuzari d on 
        f.film_id = d.film_id left outer join bilet b on b.difuzare_id = d.difuzare_id group by f.film_id 
        ORDER BY 2 DESC;";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: index.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            $i = 1;
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

    function check($conn, $data_start){
        $query = "select now() < ? as comp;";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $data_start);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        $row = mysqli_fetch_assoc($resultData);
        return $row['comp'];

        mysqli_stmt_close($stmt);
    }

?>