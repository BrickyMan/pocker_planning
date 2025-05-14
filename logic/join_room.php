<?php
session_start();

$roomKey = $_POST['room-key'];
header("Location: /pocker_planing/room?code=$roomKey");
exit();
?>