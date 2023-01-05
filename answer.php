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

$test_id = $_POST['test_id'];
$user_id = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM users WHERE login='$user'"))[0];
$role = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM users WHERE login='$user'"))[3];
echo '<a href="index4.php">Powrót do wyboru lekcji</a><br><br>';

$questions = mysqli_fetch_all(Database::getConnection()->query("SELECT * FROM questions WHERE test_id='$test_id' ORDER BY id asc"));
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

        $user_answers = [];
        if(isset($_POST["wybory_" . $question[0]])) {
            $user_answers = $_POST["wybory_" . $question[0]];
        }

        if (in_array("a", $user_answers)) {
            if (strpos($question[7], "a") !== false) {
                echo '<p style="color: green;"><span class="glyphicon glyphicon-remove"></span>' . $question[3] . '</p>';
            } else {
                echo '<p style="color: red;"><span class="glyphicon glyphicon-remove"></span>' . $question[3] . '</p>';
            }
        } else {
            echo '<span class="glyphicon glyphicon-unchecked"></span>' . $question[3] . '<br><br>';
        }

        if (in_array("b", $user_answers)) {
            if (strpos($question[7], "b") !== false) {
                echo '<p style="color: green;"><span class="glyphicon glyphicon-remove"></span>' . $question[4] . '</p>';
            } else {
                echo '<p style="color: red;"><span class="glyphicon glyphicon-remove"></span>' . $question[4] . '</p>';
            }
        } else {
            echo '<span class="glyphicon glyphicon-unchecked"></span>' . $question[4] . '<br><br>';
        }

        if (in_array("c", $user_answers)) {
            if (strpos($question[7], "c") !== false) {
                echo '<p style="color: green;"><span class="glyphicon glyphicon-remove"></span>' . $question[5] . '</p>';
            } else {
                echo '<p style="color: red;"><span class="glyphicon glyphicon-remove"></span>' . $question[5] . '</p>';
            }
        } else {
            echo '<span class="glyphicon glyphicon-unchecked"></span>' . $question[5] . '<br><br>';
        }

        if (in_array("d", $user_answers)) {
            if (strpos($question[7], "d") !== false) {
                echo '<p style="color: green;"><span class="glyphicon glyphicon-remove"></span>' . $question[6] . '</p>';
            } else {
                echo '<p style="color: red;"><span class="glyphicon glyphicon-remove"></span>' . $question[6] . '</p>';
            }
        } else {
            echo '<span class="glyphicon glyphicon-unchecked"></span>' . $question[6] . '<br><br>';
        }



        echo '</td>';
    }

    echo '</tr>';
}
echo '</tbody>';
echo '</table>';

?>
</BODY>
</HTML>