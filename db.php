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
    $roomCode = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 5);
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

// Функция для получения списка пользователей в комнате
function getUsersInRoom($roomId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE room_id = ?");
    $stmt->execute([$roomId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
