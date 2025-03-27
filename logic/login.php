<?php 
$basepath = "/pocker_planing";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $_SESSION["session_id"] = session_id(); 

    if (!empty($username)) {
        session_start();
        $_SESSION["username"] = $username;

        header("Location: $basepath/lobby");
        exit();
    }
}

header("Location: $basepath/login");
exit();
?>  