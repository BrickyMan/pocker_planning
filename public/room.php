<?php
require_once 'db.php';
session_start();

$basepath = "/pocker_planing";

// Параметры
parse_str($_SERVER['QUERY_STRING'], $params);

// Код комнаты
$roomCode = $params['code'];
// Получение данных комнаты
$roomData = getRoomByCode($roomCode);

// Валидация комнаты
if (!isset($_SESSION['username'])) {
    header("Location: $basepath/login");
    exit();
}
elseif (empty($roomData)) {
    header("Location: $basepath/lobby?error=invalid_code");
    exit();
}


// Добавление текущего пользователя в комнату
addUserToRoom(session_id(), $roomData["id"], $_SESSION["username"]);
// Получение списка пользователей в комнате
$users = getUsersInRoom($roomData["id"]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BriPocker</title>
    <link rel="stylesheet" href="public/assets/style.css">
    <link rel="stylesheet" href="public/assets/reset.css">
    <link rel="stylesheet" href="public/assets/room.css">
    <script defer src="public/assets/main.js"></script>       
</head>
<body>
    <header>
        <p class="logo">BriPocker</p>
        <div class="username-wrapper">
            <p><?php echo $_SESSION['username']; ?></p>
            <a href="/pocker_planing/login">
            <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="#e3e3e3"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h357l-80 80H200v560h560v-278l80-80v358q0 33-23.5 56.5T760-120H200Zm280-360ZM360-360v-170l367-367q12-12 27-18t30-6q16 0 30.5 6t26.5 18l56 57q11 12 17 26.5t6 29.5q0 15-5.5 29.5T897-728L530-360H360Zm481-424-56-56 56 56ZM440-440h56l232-232-28-28-29-28-231 231v57Zm260-260-29-28 29 28 28 28-28-28Z"/></svg>
            </a>
        </div>
    </header>
    <main>
        <div class="main-inner">
            <div class="user-card-wrapper">
                <?php foreach ($users as $user): ?>
                <div class="user-card <?= $user['session_id'] == session_id() ? 'current-user' : '' ?>">
                    <h3 class="user-card-name"><?php echo $user['username']; ?> </h3>
                    <?php if ($user['session_id'] == session_id()): ?>
                        <p class="user-card-tag">Это вы</p>
                    <?php endif; ?>
                    <div class="user-card-inner">
                        <p class="user-card-value">?</p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="result-wrapper">
                <p>Среднее: 3.14</p>
                <h2>Результат: <span>3</span></h2>
            </div>

            <div class="action-btns-wrapper">
                <button class="action-btn" id="actionCardBtn">Вскрываемся!</button>
            </div>
        </div>
        <div class="rate-bar" id="rateBar">
            <div class="rate-bar_btn">
                1<div class="rate-bar_plus">+</div>
            </div>
            <div class="rate-bar_btn">
                2<div class="rate-bar_plus">+</div>
            </div>
            <div class="rate-bar_btn">
                3<div class="rate-bar_plus">+</div>
            </div>
            <div class="rate-bar_btn">
                5<div class="rate-bar_plus">+</div>
            </div>
            <div class="rate-bar_btn">
                8<div class="rate-bar_plus">+</div>
            </div>
            <div class="rate-bar_btn">
                13<div class="rate-bar_plus">+</div>
            </div>
            <div class="rate-bar_btn">
                21<div class="rate-bar_plus">+</div>
            </div>
        </div>
    </main>
</body>
</html>