<?php
include "Database.php";

session_start();
$title = $_POST['title'];
$user = $_SESSION['user'];
$user_id = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM users WHERE login='$user'"))[0];
$text = $_POST['editor'];
$file = $_POST['fileToUpload'];

$target_dir = "lekcje";
$target_file = $target_dir . "/" . basename($_FILES["fileToUpload"]["name"]);
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " uploaded.";
} else {
    echo "Error uploading file.";
}

$file_name = basename($_FILES["fileToUpload"]["name"]);
Database::getConnection()->query("INSERT INTO lessons (creator_id, title, text, file) VALUES ('$user_id', '$title', '$text', '$file_name')");

$lesson_id = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM lessons ORDER BY id DESC LIMIT 1"))[0];
Database::getConnection()->close();
header('Location: lesson_view.php?id=' . $lesson_id);