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
<?php
$role = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM users WHERE login='$user'"))[3];
if ($role == "admin") {
    echo '<a href="admin_panel.php">Panel administratora</a>';
}
?>
<br>
Wyniki egzaminów:
<br>
<br>
<?php
$role = mysqli_fetch_array(Database::getConnection()->query("SELECT role FROM users WHERE login='$user'"))[0];
if ($role == 'coach') {
    $results = mysqli_fetch_all(Database::getConnection()->query("SELECT * FROM results ORDER BY id"));
} else {
    $user_id = mysqli_fetch_array(Database::getConnection()->query("SELECT id FROM users WHERE login='$user'"))[0];
    $results = mysqli_fetch_all(Database::getConnection()->query("SELECT * FROM results WHERE employee_id='$user_id' ORDER BY id"));
}

echo '<table class="table table-bordered table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Nazwa egzaminu</th>';
echo '<th>Pracownik</th>';
echo '<th>Czas zakończenia</th>';
echo '<th>Punkty</th>';
echo '<th>Plik z wynikami</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
foreach ($results as $result) {
    $test_name = mysqli_fetch_array(Database::getConnection()->query("SELECT title FROM tests WHERE id='$result[2]'"))[0];
    $employee_name = mysqli_fetch_array(Database::getConnection()->query("SELECT login FROM users WHERE id='$result[1]'"))[0];
    $finish_time = $result[3];
    $points = $result[4];
    $pdf_file = 'pdf/' . $result[5];
    $link_to_pdf = '<a href="' . $pdf_file . '">Zobacz pdf</a>';

    echo '<tr>';
    echo '<td>' . $test_name . '</td>';
    echo '<td>' . $employee_name . '</td>';
    echo '<td>' . $finish_time . '</td>';
    echo '<td>' . $points . '</td>';
    echo '<td>' . $link_to_pdf . '</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
?>
<br><br>
</BODY>
</HTML>