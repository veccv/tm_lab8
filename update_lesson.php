<?php
include "Database.php";

session_start();
$title = $_POST['title'];
$user = $_SESSION['user'];
$user_id = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM users WHERE login='$user'"))[0];
$text = $_POST['editor'];
$file = $_POST['fileToUpload'];
$lesson_id = $_POST['id'];

$file_name = '';
if (strlen($file) > 0) {
    $target_dir = "lekcje";
    $target_file = $target_dir . "/" . basename($_FILES["fileToUpload"]["name"]);
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " uploaded.";
    } else {
        echo "Error uploading file.";
    }

    $file_name = basename($_FILES["fileToUpload"]["name"]);
} else {
    $file_name = mysqli_fetch_array(Database::getConnection()->query("SELECT file FROM lessons WHERE id='$lesson_id'"))[0];
}

Database::getConnection()->query("UPDATE lessons SET title='$title', text='$text', file='$file_name' WHERE id='$lesson_id'");

Database::getConnection()->close();
header('Location: lesson_view.php?id=' . $lesson_id);