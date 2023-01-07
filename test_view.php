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
$test_id = $_GET['id'];
$role = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM users WHERE login='$user'"))[3];
echo '<a href="index4.php">Powrót do wyboru lekcji</a><br><br>';

if ($role == 'blocked') {
    echo 'Jesteś zablokowany, nie możesz przeglądać tego testu!';
    exit();
}

if ($role == 'employee') {
    $user_id = mysqli_fetch_array(Database::getConnection()->query("SELECT id FROM users WHERE login='$user'"))[0];
    Database::getConnection()->query("INSERT INTO logs (employee_id, action, details) VALUES ('$user_id', 'test', '$test_id')");
}

$blocked = mysqli_fetch_array(Database::getConnection()->query("SELECT blocked FROM tests WHERE id='$test_id'"))[0];
if ($blocked == 'no') {
    $questions = mysqli_fetch_all(Database::getConnection()->query("SELECT * FROM questions WHERE test_id='$test_id' ORDER BY id asc"));
    echo '<form action="test.php" method="post" enctype="multipart/form-data">';
    echo "<input type='hidden' name='test_id' value='$test_id' />";
    echo '<table class="table table-bordered table-striped">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Informacje</th>';
    echo '<th>Pytanie</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($questions as $question) {
        echo '<tr>';
        echo '<td>';
        if ($role == 'coach') {
            echo 'Usuń pytanie <a href="remove_question.php?id=' . $question[0] . '&lid=' . $test_id . '"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></a><br><br>';
            echo 'Edytuj pytanie <a href="edit_question.php?id=' . $question[0] . '&lid=' . $test_id . '"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a><br><br>';
        }
        echo '</td>';
        if (strlen($question[8]) > 0) {
            echo '<td>';
            echo $question[1];
            echo '<br>';
            echo '<br>';
            $message = 'images/' . $question[5];

            if (strpos($message, '.png')) {
                $message = "<img src='$message'>";
            } else if (strpos($message, '.gif')) {
                $message = "<img src='$message'>";
            } else if (strpos($message, '.jpg')) {
                $message = "<img src='$message'>";
            } else if (strpos($message, '.mp3')) {
                $message = "<audio controls src='$message'> </audio>";
            } else if (strpos($message, '.mp4')) {
                $message = "<video controls width='250' autoplay='true' muted='true'><source src='$message' type='video/mp4'></video>";
            }
            echo $message;

            echo '</td>';
        } else {
            echo '<td>' . $question[2] . '<br>';
            echo '<label>';
            echo '<input type="checkbox" name="wybory_' . $question[0] . '[]" value="a"> ' . $question[3];
            echo '</label>';
            echo '<br>';
            echo '<label>';
            echo '<input type="checkbox" name="wybory_' . $question[0] . '[]" value="b"> ' . $question[4];
            echo '</label>';
            echo '<br>';
            echo '<label>';
            echo '<input type="checkbox" name="wybory_' . $question[0] . '[]" value="c"> ' . $question[5];
            echo '</label>';
            echo '<br>';
            echo '<label>';
            echo '<input type="checkbox" name="wybory_' . $question[0] . '[]" value="d"> ' . $question[6];
            echo '</label>';
            echo '<br>';

            echo '</td>';
        }

        echo '</tr>';
    }
    echo '<tr>';
    if ($role == 'coach') {
        echo '<td colspan="2"><a href="add_question.php?id=' . $test_id . '">Dodaj pytanie</a></td>';
    }
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
    echo '<input type="submit" value="Wyślij arkusz odpowiedzi" name="submit">';
    echo '</form>';
} else {
    echo 'Ten egzamin jest zablokowany!';
}
?>
</BODY>
</HTML>