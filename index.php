<?php
$basepath = "pocker_planing";
$uri = trim(str_replace($basepath, '', $_SERVER['REQUEST_URI']), '/'); // Убираем начальный и конечный слеш

// Роутинг
switch ($uri) {
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
