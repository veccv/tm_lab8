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
<a href="index.php">Powrót do menu głównego</a>
<br>
<br>
<br>
<br>
<?php
$logs = mysqli_fetch_all(Database::getConnection()->query("SELECT * FROM logs ORDER BY id"));
echo '<table class="table table-bordered table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Godzina</th>';
echo '<th>Pracownik</th>';
echo '<th>Akcja</th>';
echo '<th>Szczegóły</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
foreach ($logs as $log) {
    echo '<tr>';
    echo '<td>' . $log[4] . '</td>';
    echo '<td>' . mysqli_fetch_array(Database::getConnection()->query("SELECT login FROM users WHERE id='$log[1]'"))[0] . '</td>';
    echo '<td>' . $log[2] . '</td>';
    if ($log[2] == 'lesson') {
        echo '<td>' . mysqli_fetch_array(Database::getConnection()->query("SELECT title FROM lessons WHERE id='$log[3]'"))[0] . '</td>';
    } else if ($log[2] == 'test') {
        echo '<td>' . mysqli_fetch_array(Database::getConnection()->query("SELECT title FROM tests WHERE id='$log[3]'"))[0] . '</td>';
    }
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';
?>
</BODY>
</HTML>