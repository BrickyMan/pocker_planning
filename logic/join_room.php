<?php
session_start();
$basepath = "";

$roomKey = $_POST['room-key'];
header("Location: $basepath/room?code=$roomKey");
exit();
?>