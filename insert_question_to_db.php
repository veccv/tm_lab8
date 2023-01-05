<?php
include "Database.php";

session_start();
$title = $_POST['title'];
$user = $_SESSION['user'];
$user_id = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM users WHERE login='$user'"))[0];
$text = $_POST['editor'];
$file = $_POST['fileToUpload'];
$test_id = $_POST['test_id'];

$answer_a_checkbox = $_POST['answer_a_checkbox']; // 'on' - jak haczyk
$answer_b_checkbox = $_POST['answer_b_checkbox']; // 'on' - jak haczyk
$answer_c_checkbox = $_POST['answer_c_checkbox']; // 'on' - jak haczyk
$answer_d_checkbox = $_POST['answer_d_checkbox']; // 'on' - jak haczyk

$good_answers = '';
if ($answer_a_checkbox == 'on') {
    $good_answers .= 'a';
}
if ($answer_b_checkbox == 'on') {
    $good_answers .= 'b';
}
if ($answer_c_checkbox == 'on') {
    $good_answers .= 'c';
}
if ($answer_d_checkbox == 'on') {
    $good_answers .= 'd';
}

$answer_a = $_POST['answer_a'];
$answer_b = $_POST['answer_b'];
$answer_c = $_POST['answer_c'];
$answer_d = $_POST['answer_d'];


$target_dir = "lekcje";
$target_file = $target_dir . "/" . basename($_FILES["fileToUpload"]["name"]);
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " uploaded.";
} else {
    echo "Error uploading file.";
}

$file_name = basename($_FILES["fileToUpload"]["name"]);
Database::getConnection()->query("INSERT INTO questions (test_id, text, answer_a, answer_b, answer_c, answer_d, good_answers, file) VALUES ('$test_id', '$text', '$answer_a', '$answer_b', '$answer_c', '$answer_d', '$good_answers', '$file_name')");

Database::getConnection()->close();
header('Location: test_view.php?id=' . $test_id);