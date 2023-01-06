<?php
include 'Database.php';
$lesson_id = $_GET['id'];
Database::getConnection()->query("UPDATE lessons SET blocked='yes' WHERE id='$lesson_id'");
header('Location: admin_panel.php');