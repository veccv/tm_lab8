<?php
include 'Database.php';
$test_id = $_GET['id'];
Database::getConnection()->query("DELETE FROM tests WHERE id='$test_id'");
header('Location: admin_panel.php');