<?php declare(strict_types=1);
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: index3.php');
    exit();
}

$user = $_SESSION['user'];

include 'Database.php';
if (!Database::getConnection()) {
    echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
}
Database::getConnection()->query("SET NAMES 'utf8'");
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<BODY style="padding: 15px">
<a href="logout.php">Wyloguj się</a>
<br>
<a href="index4.php">Powrót</a>
<br>
<br>
<?php
echo "Stwórz nowego szkoleniowca " . '<a href="coach_register.php"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a><br><br>';

$users = mysqli_fetch_all(Database::getConnection()->query("SELECT * FROM users ORDER BY id"));
echo '<table class="table table-bordered table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Opcje</th>';
echo '<th>Pracownik</th>';
echo '<th>Rola</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
foreach ($users as $usr) {
    if ($usr[3] != 'admin') {
        echo '<tr>';
        echo '<td></td>';
        echo '<td>' . $usr[1] . '</td>';
        echo '<td>' . $usr[3] . '</td>';
        echo '</tr>';
    }
}

echo '</tbody>';
echo '</table>';
?>
</BODY>
</html>