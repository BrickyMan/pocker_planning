<?php
require 'db.php';

$basepath = "pocker_planing";

// Маршрут, его очистка от и слэшей
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');


// Роутинг
switch ($path) {
    case '':
        require 'public/login.php';
        break;
    case 'login':
        require 'public/login.php'; // Страница авторизации
        break;
    case 'lobby':
        require 'public/lobby.php';
        break;
    case 'room':
        require 'public/room.php'; // Комната для покер-планирования
        break;
    default:
        http_response_code(404);
        require 'public/404.php'; // Страница 404
        break;
}
?>
