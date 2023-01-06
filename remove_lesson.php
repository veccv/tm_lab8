<?php
include 'Database.php';
$lesson_id = $_GET['id'];
Database::getConnection()->query("DELETE FROM lessons WHERE id='$lesson_id'");
header('Location: admin_panel.php');