<?php
$host = 'localhost';  // Или 127.0.0.1
$dbname = 'pocker_planning';
$username = 'root';  // Укажи свой логин
$password = '';  // Если XAMPP, пароль пустой, иначе свой

try {
    // Установка соединения
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Установка режима ошибок
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}


// Функция для создания комнаты
function createRoom($ownerSession) {    
    global $pdo;
    // Генерация случайного кода
    $roomCode = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 2).substr(str_shuffle("0123456789"), 0, 2);
    // Подготовка SQL-запроса для добавления комнаты
    $stmt = $pdo->prepare("INSERT INTO rooms (room_code, owner_session) VALUES (?, ?)");
    // Выполнение запроса с передачей парметров
    $stmt->execute([$roomCode, $ownerSession]);
    return $roomCode;
}

// Функция для поиска комнаты по коду
function getRoomByCode($roomCode) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE room_code = ?");
    $stmt->execute([$roomCode]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Функция для проверки существования комнаты 
function roomExists($roomCode) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE room_code = ?");
    $stmt->execute([$roomCode]);
    return $stmt->rowCount() > 0;
}

// Функция для добавления пользователя в комнату
function addUserToRoom($sessionId, $roomId, $username) {
    global $pdo;
    
    // Проверяем, есть ли уже такой пользователь в этой комнате
    $stmt = $pdo->prepare("SELECT id FROM users WHERE session_id = ? AND room_id = ?");
    $stmt->execute([$sessionId, $roomId]);
    $user = $stmt->fetch();

    if ($user) {
        // Если пользователь уже есть, обновляем его имя
        $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE session_id = ? AND room_id = ?");
        $stmt->execute([$username, $sessionId, $roomId]);
    } else {
        // Если нет, добавляем нового пользователя
        $stmt = $pdo->prepare("INSERT INTO users (session_id, room_id, username) VALUES (?, ?, ?)");
        $stmt->execute([$sessionId, $roomId, $username]);
    }
}

// Функция для удаления пользователя из комнаты
function removeUserFromRoom($sessionId) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM users WHERE session_id = ?");
    $stmt->execute([$sessionId]);
}

// Функция для получения списка пользователей в комнате
function getUsersInRoom($roomId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE room_id = ?");
    $stmt->execute([$roomId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Функция для получения данных пользователя
function getUserData($sessionId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE session_id = ?");
    $stmt->execute([$sessionId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Запись голоса пользователя
function userVoted($sessionId, $vote) {
    global $pdo;
    echo "db test, vote: $vote";
    $stmt = $pdo->prepare("UPDATE users SET vote = ? WHERE session_id = ?");
    $stmt->execute([$vote, $sessionId]);
}

// Сброс голосов
function resetVotes($roomId) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET vote = NULL where room_id = ?");
    $stmt->execute([$roomId]);
}

// Функция для получения данных комнаты
function getRoomData($roomId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->execute([$roomId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Функция для проверки статуса вскрытия карт в комнате
function isRoomShowdown($roomId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT is_showdown FROM rooms WHERE id = ?");
    $stmt->execute([$roomId]);
    return $stmt->fetchColumn();
}

// Функция для изменения статуса вскрытия карт в комнате
function toggleRoomShowdown($roomId, $value) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE rooms SET is_showdown = ? WHERE id = ?");
    $stmt->execute([$value, $roomId]);
}

// Функция для получения среднего голоса
function getAvergeVote($roomId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT AVG(vote) FROM users WHERE room_id = ? AND vote != 0");
    $stmt->execute([$roomId]);
    return $stmt->fetchColumn();
}