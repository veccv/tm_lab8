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
$topic_id = $_GET['id'];
$role = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM users WHERE login='$user'"))[3];

echo '<a href="index4.php">Powrót do wyboru lekcji</a><br><br>';
$lesson_text = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM lessons WHERE id='$topic_id'"))[3];
$lesson_author_id = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM lessons WHERE id='$topic_id'"))[1];
$lesson_author_name = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM users WHERE id='$lesson_author_id'"))[1];
$lesson_file = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM lessons WHERE id='$topic_id'"))[4];

echo '<table class="table table-bordered table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Informacje</th>';
echo '<th>Lekcja</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
echo '<tr>';
echo '<td>Autor lekcji: ' . $lesson_author_name . '</td>';
echo '<td>' . $lesson_text . '<br><br>';

if (strlen($lesson_file) > 0) {
    $message = 'lekcje/' . $lesson_file;

    if (strpos($message, '.png')) {
        $message = "<img src='$message'>";
    } else if (strpos($message, '.gif')) {
        $message = "<img src='$message'>";
    } else if (strpos($message, '.jpg')) {
        $message = "<img src='$message'>";
    } else if (strpos($message, '.mp3')) {
        $message = "<audio controls src='$message' autoplay='true'> </audio>";
    } else if (strpos($message, '.mp4')) {
        $message = "<video controls width='250' autoplay='true'><source src='$message' type='video/mp4'></video>";
    }
    echo $message;
}
echo '</td>';
echo '</tr>';


echo '</tbody>';
echo '</table>';
?>
</BODY>
</HTML>