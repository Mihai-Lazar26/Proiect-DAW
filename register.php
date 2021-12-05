<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="validate_reg.php" method="post">
        <table>
            <tr>
                <td>Nume:</td>
                <td><input type="text" name="nume"></td>
            </tr>

            <tr>
                <td>Prenume:</td>
                <td><input type="text" name="prenume"></td>
            </tr>

            <tr>
                <td>Email:</td>
                <td><input type="email" name="email"></td>
            </tr>

            <tr>
                <td>Parola:</td>
                <td><input type="password" name="pwd"></td>
            </tr>

            <tr>
                <td>Confirma parola:</td>
                <td><input type="password" name="cpwd"></td>
            </tr>

            <tr>
                <td hidden><input type="checkbox" name="tip" value="1" checked >Sunt un user</td>
                <td><input type="checkbox" name="tip" value="2">Sunt un manager</td>
            </tr>

            <tr>
                <td><input type="submit" value="Register"></td>
            </tr>
        </table>
    </form>
</body>
</html>