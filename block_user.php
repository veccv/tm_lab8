<?php
include 'Database.php';
$user_id = $_GET['id'];
Database::getConnection()->query("UPDATE users SET role='blocked' WHERE id='$user_id'");
header('Location: admin_panel.php');