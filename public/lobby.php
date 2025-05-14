<?php
session_start();

// Параметры
parse_str($_SERVER['QUERY_STRING'], $params);

// Ошибки
$error_messages = [
    'invalid_room' => 'Комната не найдена',
    'room_locked' => 'Комната закрыта для новых участников',
    'invalid_code' => 'Неверный код комнаты'
];

if (!isset($_SESSION['username'])) {
    header("Location: /pocker_planing/login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BriPocker</title>
    <link rel="stylesheet" href="public/assets/style.css">
    <link rel="stylesheet" href="public/assets/reset.css">
    <link rel="stylesheet" href="public/assets/lobby.css">
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
        <form class="lobby-side" action="logic/create_room.php" method="post">
            <label>Создайте новую комнату</label>
            <input type="submit" value="Создать">
        </form>
        <div class="lobby-divider"></div>
        <form class="lobby-side" action="logic/join_room.php" method="post">
            <?php if (isset($params['error']) && isset($error_messages[$params['error']])): ?>
                <p class="error-msg"><?php echo $error_messages[$params['error']] ?></p>
            <?php endif; ?>
            <label>Присоединитесь к комнате</label>
            <input type="text" name="room-key" placeholder="Введите код комнаты">
            <input type="submit" value="Присоединиться">
        </form>
    </main>
</body>
</html>