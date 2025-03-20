<?php 
$basepath = "/pocker_planing";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Location: $basepath/room");
    exit();
}
else {
    header("Location: $basepath/lobby");
    exit();
}
?>