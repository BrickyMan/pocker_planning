<?php
require_once 'db.php';
session_start();

$env = parse_ini_file(__DIR__ . '/../.env');

$basepath = "";

// Параметры
parse_str($_SERVER['QUERY_STRING'], $params);

// Код комнаты
$roomCode = $params['code'];
// Получение данных комнаты
$roomData = getRoomByCode($roomCode);

// Валидация комнаты
if (!isset($_SESSION['username'])) {
    $_SESSION['redirect_after_username'] = $_SERVER['REQUEST_URI'];
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
// Получение текущего пользователя
$currentUser = getUserData(session_id());

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
    <script>
        const serv_ip = "<?php echo $env["SERV_IP"]; ?>";
        const roomCode = "<?php echo $roomCode; ?>";
        const roomId = "<?php echo $roomData["id"]; ?>";
        const userData = <?php echo json_encode($currentUser); ?>;
    </script>
    <script defer src="public/assets/js/room.js"></script>
    <script defer src="public/assets/js/room_code.js"></script>
    <script defer src="public/assets/js/room_rate.js"></script>
</head>
<body>
    <header>
        <a class="back-to-lobby" href="/lobby">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg>
            В лобби
        </a>
        <p class="logo">BriPocker</p>
        <div class="header-options">
            <div class="invite-wrapper">
                <p>Код комнаты:</p>
                <div class="invite-code">
                    <input type="text" id="roomCodeInput" value="<?php echo $roomCode; ?>" readonly disabled>
                    <button id="copyRoomCodeBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"><path d="M360-240q-33 0-56.5-23.5T280-320v-480q0-33 23.5-56.5T360-880h360q33 0 56.5 23.5T800-800v480q0 33-23.5 56.5T720-240H360Zm0-80h360v-480H360v480ZM200-80q-33 0-56.5-23.5T120-160v-560h80v560h440v80H200Zm160-240v-480 480Z"/></svg>
                    </button>
                </div>
                <div class="invite-msg" id="inviteMsg">
                    Скопировано!
                </div>
            </div>
            <div class="username-wrapper">
                <p><?php echo $_SESSION['username']; ?></p>
                <a href="/login">
                <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="#e3e3e3"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h357l-80 80H200v560h560v-278l80-80v358q0 33-23.5 56.5T760-120H200Zm280-360ZM360-360v-170l367-367q12-12 27-18t30-6q16 0 30.5 6t26.5 18l56 57q11 12 17 26.5t6 29.5q0 15-5.5 29.5T897-728L530-360H360Zm481-424-56-56 56 56ZM440-440h56l232-232-28-28-29-28-231 231v57Zm260-260-29-28 29 28 28 28-28-28Z"/></svg>
                </a>
            </div>
        </div>
    </header>
    <main>
        <div class="main-inner">
            <div class="user-card-list">

            </div>

            <div class="result-wrapper">
                <p>Результат: </p>
                <h2><span>0</span>.0</h2>
            </div>

            <div class="action-btns-wrapper">
                <button class="action-btn" id="actionCardBtn" data-status="showdown">Вскрываемся!</button>
            </div>
        </div>
        <div class="rate-bar" id="rateBar">
            <div class="rate-bar_btn" data-value="1">
                1<div class="rate-bar_plus">+</div>
            </div>
            <div class="rate-bar_btn" data-value="2">
                2<div class="rate-bar_plus">+</div>
            </div>
            <div class="rate-bar_btn" data-value="3">
                3<div class="rate-bar_plus">+</div>
            </div>
            <div class="rate-bar_btn" data-value="5">
                5<div class="rate-bar_plus">+</div>
            </div>
            <div class="rate-bar_btn" data-value="8">
                8<div class="rate-bar_plus">+</div>
            </div>
            <div class="rate-bar_btn" data-value="13">
                13<div class="rate-bar_plus">+</div>
            </div>
        </div>
    </main>
</body>
</html>