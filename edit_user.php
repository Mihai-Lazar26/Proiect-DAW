<?php
    session_start();
    if(!(isset($_SESSION['user_id']) && $_SESSION['tip'] == 0)){
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
    
        require_once 'includes/connect-DB.inc.php';
        require_once 'includes/functions.inc.php';

        $user_id = $_GET['user_id'];
        $result = getUserById($conn, $user_id);
        $nume = $result['nume'];
        $prenume = $result['prenume'];
        $email = $result['email'];
        $tip = $result['tip'];


        echo '<form action="includes/update_user.inc.php?user_id='.$user_id.'" method="post">
            <table>
                <tr>
                    <td>Nume:</td>
                    <td><input type="text" name="nume" value="'.$nume.'"></td>
                </tr>

                <tr>
                    <td>Prenume:</td>
                    <td><input type="text" name="prenume" value="'.$prenume.'"></td>
                </tr>

                <tr>
                    <td>Email:</td>
                    <td><input type="text" name="email" value="'.$email.'"></td>
                </tr>

            </table>
            Tip:';
            echo '<br>';
                if($tip === 0){
                    echo '<input type="radio" id="admin" name="tip" value="0" checked>
                            <label for="admin">admin</label>';
                }
                else{
                    echo '<input type="radio" id="admin" name="tip" value="0">
                            <label for="admin">admin</label>';
                }
                echo '<br>';
                if($tip === 1){
                    echo '<input type="radio" id="user" name="tip" value="1" checked>
                            <label for="user">user</label>';
                }
                else{
                    echo '<input type="radio" id="user" name="tip" value="1">
                            <label for="user">user</label>';
                }
                echo '<br>';
                if($tip === 2){
                    echo '<input type="radio" id="manager" name="tip" value="2" checked>
                            <label for="manager">manager</label>';
                }
                else{
                    echo '<input type="radio" id="manager" name="tip" value="2">
                            <label for="manager">manager</label>';
                }
            echo '<br>
            <button type="submit" name="submit">Salveaza si mergi mai departe</button>
        </form>';
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>