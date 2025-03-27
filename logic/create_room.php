<?php
require '../db.php';

session_start();
$basepath = "/pocker_planing";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Создание комнаты
    $roomCode = createRoom(session_id());

    header("Location: $basepath/room?code=$roomCode");
    exit();
}
else {
    header("Location: $basepath/lobby");
    exit();
}
?>