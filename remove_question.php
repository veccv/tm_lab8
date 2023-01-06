<?php
include 'Database.php';
$question_id = $_GET['id'];
$lesson_id = $_GET['lid'];
Database::getConnection()->query("DELETE FROM questions WHERE id='$question_id'");
header('Location: test_view.php?id=' . $lesson_id);