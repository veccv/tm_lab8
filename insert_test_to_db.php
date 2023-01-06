<?php
include "Database.php";

session_start();
$title = $_POST['title'];
$max_time = $_POST['max_time'];
$user = $_SESSION['user'];
$user_id = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM users WHERE login='$user'"))[0];

Database::getConnection()->query("INSERT INTO tests (creator_id, title, max_time) VALUES ('$user_id', '$title', '$max_time')");

$lesson_id = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM lessons ORDER BY id DESC LIMIT 1"))[0];
Database::getConnection()->close();
header('Location: index4.php');