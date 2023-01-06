<?php
include 'Database.php';
$user_id = $_GET['id'];
Database::getConnection()->query("DELETE FROM users WHERE id='$user_id'");
header('Location: admin_panel.php');