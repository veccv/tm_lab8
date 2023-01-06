<?php
include 'Database.php';
$test_id = $_GET['id'];
Database::getConnection()->query("UPDATE tests SET blocked='yes' WHERE id='$test_id'");
header('Location: admin_panel.php');