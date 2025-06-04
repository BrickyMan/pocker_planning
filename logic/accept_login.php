<?php 
$basepath = "/pocker_planing";

// Обработка формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    // Получение данных
    $username = $_POST["username"];
    $_SESSION["session_id"] = session_id(); 

    if (!empty($username)) {
        // Запись в сессию
        $_SESSION["username"] = $username;
        // Запись в кэш на месяц
        setcookie("username", $username, time() + 60 * 60 * 24 * 30, "/");

        // Перенаправление
        if (isset($_SESSION['redirect_after_username'])) {
            // В случае, если пользователь исходно пытался зайти на другую страницу
            $redirect = $_SESSION['redirect_after_username'];
            // Очистка исходного маршрута из сессионного хранилища
            unset($_SESSION['redirect_after_username']);
            header("Location: $redirect");
            exit();
        }
        else {
            header("Location: $basepath/lobby");
            exit();
        }
    }
}

header("Location: $basepath/login");
exit();
?>  