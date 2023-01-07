<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<HEAD>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</HEAD>
<BODY>
<?php
include 'Database.php';

$user = htmlentities($_POST['user'], ENT_QUOTES, "UTF-8");
$pass = htmlentities($_POST['pass'], ENT_QUOTES, "UTF-8");

if (!Database::getConnection()) {
    echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
}


session_start();
Database::getConnection()->query("SET NAMES 'utf8'");
$rekord = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM users WHERE login='$user'"));

if (!$rekord) //Jeśli brak, to nie ma użytkownika o podanym loginie
{
    Database::getConnection()->close();
    header('Location: index3.php');
    exit();
} else { // jeśli $rekord istnieje
    if ($rekord['password'] == $pass) // czy hasło zgadza się z BD
    {
        echo "Logowanie Ok. User: {$rekord['username']}. Hasło: {$rekord['password']}";
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['user'] = $user;

        $user_role = mysqli_fetch_array(Database::getConnection()->query("SELECT role FROM users WHERE login='$user'"))[0];
        $user_id = mysqli_fetch_array(Database::getConnection()->query("SELECT id FROM users WHERE login='$user'"))[0];
        if ($user_role == 'employee') {
            Database::getConnection()->query("INSERT INTO logs (employee_id, action) VALUES ('$user_id', 'login')");
        }

        header('Location: index4.php');
    } else {
        Database::getConnection()->close();
        header('Location: index3.php');
        exit();
    }
}
?>
</BODY>
</HTML>