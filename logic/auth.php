<?php 
$basepath = "/pocker_planing";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST["username"];
    // echo $basepath;
    // exit;
    session_start();
    $_SESSION["username"] = $username;

    header("Location: $basepath/lobby");
    exit();
}
else {
    header("Location: $basepath/login");
    exit();
}
?>