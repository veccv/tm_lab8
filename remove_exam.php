<?php
include 'Database.php';
$test_id = $_GET['id'];
Database::getConnection()->query("DELETE FROM tests WHERE id='$test_id'");
Database::getConnection()->query("DELETE FROM questions WHERE test_id='$test_id'");
header('Location: index4.php');