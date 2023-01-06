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
        echo '<td>';
        echo '<a href="remove_user.php?id=' . $usr[0] . '"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></a>';
        echo '       ';
        echo '<a href="block_user.php?id=' . $usr[0] . '"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span></a>';
        echo '</td>';
        echo '<td>' . $usr[1] . '</td>';
        echo '<td>' . $usr[3] . '</td>';
        echo '</tr>';
    }
}
echo '</tbody>';
echo '</table>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';

$tests = mysqli_fetch_all(Database::getConnection()->query("SELECT * FROM tests ORDER BY id"));
echo '<table class="table table-bordered table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Opcje</th>';
echo '<th>Nazwa egzaminu</th>';
echo '<th>Autor</th>';
echo '<th>Maksymalny czas wykonywania (min)</th>';
echo '<th>Zablokowany</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
foreach ($tests as $test) {
    $author = mysqli_fetch_array(Database::getConnection()->query("SELECT login FROM users WHERE id='$test[1]'"))[0];
    echo '<tr>';
    echo '<td>';
    echo '<a href="remove_exam.php?id=' . $test[0] . '"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></a>';
    echo '       ';
    echo '<a href="block_test.php?id=' . $test[0] . '"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span></a>';
    echo '</td>';
    echo '<td>' . $test[2] . '</td>';
    echo '<td>' . $author . '</td>';
    echo '<td>' . $test[3] . '</td>';
    if ($test[4] == 'yes') {
        echo '<td> Tak </td>';
    } else {
        echo '<td> Nie </td>';
    }
    echo '</tr>';

}
echo '</tbody>';
echo '</table>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';

$lessons = mysqli_fetch_all(Database::getConnection()->query("SELECT * FROM lessons ORDER BY id"));
echo '<table class="table table-bordered table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Opcje</th>';
echo '<th>Nazwa lekcji</th>';
echo '<th>Autor</th>';
echo '<th>Zablokowany</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
foreach ($lessons as $lesson) {
    $author = mysqli_fetch_array(Database::getConnection()->query("SELECT login FROM users WHERE id='$lesson[1]'"))[0];
    echo '<tr>';
    echo '<td>';
    echo '<a href="remove_lesson.php?id=' . $lesson[0] . '"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></a>';
    echo '       ';
    echo '<a href="block_lesson.php?id=' . $lesson[0] . '"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span></a>';
    echo '</td>';
    echo '<td>' . $lesson[2] . '</td>';
    echo '<td>' . $author . '</td>';
    if ($lesson[5] == 'yes') {
        echo '<td> Tak </td>';
    } else {
        echo '<td> Nie </td>';
    }
    echo '</tr>';

}
echo '</tbody>';
echo '</table>';
?>
</BODY>
</html>