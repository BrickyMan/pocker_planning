<?php
// Подключение автозагрузки composer
require 'vendor/autoload.php';
require 'db.php';

// Подключение классов Ratchet для работы с вебсокетами
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

// Класс, реализующий механизм работы с вебсокетами
class RoomServer implements MessageComponentInterface {
    // Данные, с которыми будет работать сервер
    protected $clients;
    protected $rooms = [];

    public function __construct() {
        // Создаем хранилище для объектов - активных соединений
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Добавляем соединение нового клиента в хранилище
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Декодируем сообщение
        $data = json_decode($msg, true);

        // Если это сообщение о входе в комнату
        if ($data['type'] == 'join') {
            $roomId = $data['room_id'];
            $userData = $data['user_data'];

            // Добавляем пользователя в комнату
            $this->rooms[$roomId][$from->resourceId] = $userData;
            addUserToRoom(
                $userData['session_id'],
                $roomId,
                $userData['username']
            );

            // Рассылаем обновлённый список пользователей в комнате всем клиентам
            $this->sendToRoom($roomId, [
                'type' => 'join',
                'users' => getUsersInRoom($roomId),
                'showdown' => isRoomShowdown($roomId),
                'avergeVote' => getAvergeVote($roomId)
            ]);
            // echo "User $from->resourceId joined room $roomId\n";
        }

        // Если это сообщение о выходе из комнаты
        if ($data['type'] == 'leave') {
            $roomId = $data['room_id'];
            $userId = $data['user_id'];


            // Удаляем пользователя из комнаты
            unset($this->rooms[$roomId][$from->resourseId]);
            removeUserFromRoom($userId);

            // echo "User $userId left room $roomId\n";

            // Рассылаем обновлённый список пользователей в комнате всем клиентам
            $this->sendToRoom($roomId, [
                'type' => 'updateUsers',
                'users' => getUsersInRoom($roomId)
            ]);
        }

        // Если это сообщение о голосовании
        if ($data['type'] == 'vote') {
            $roomId = $data['room_id'];
            $userId = $data['user_id'];
            $vote = $data['vote'];
            // echo "Data from client: ".json_encode($data)."\nClient vote: $vote\n";

            // Обновляем голос пользователя в БД
            userVoted($userId, $vote);

            $this->sendToRoom($roomId, [
                'type' => 'setReady',
                'users' => getUsersInRoom($roomId)
            ]);
        }

        // Если это сообщение о вскрытии карт
        if ($data['type'] == 'showdown') {
            $roomId = $data['room_id'];

            toggleRoomShowdown($roomId, 1);

            $this->sendToRoom($roomId, [
                'type' => 'showdown',
                'users' => getUsersInRoom($roomId),
                'avergeVote' => getAvergeVote($roomId)
            ]);
        }

        if ($data['type'] == 'restart') {
            $roomId = $data['room_id'];

            // Сброс голосов
            resetVotes($roomId);
            toggleRoomShowdown($roomId, 0);

            $this->sendToRoom($roomId, [
                'type' => 'restart',
                'users' => getUsersInRoom($roomId)
            ]);
        }

        // Если это ping - отвечаем pong
        if ($data['type'] == 'ping') {
            $from->send(json_encode(['type' => 'pong']));
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // Перебор всех комнат и их пользователей
        foreach ($this->rooms as $roomId => &$users) {
            // Если пользователь есть в комнате
            if (isset($users[$conn->resourceId])) {
                // Удаляем пользователя из комнаты
                unset($users[$conn->resourceId]);

                // Рассылаем обновлённый список пользователей в комнате всем клиентам
                $this->sendToRoom($roomId, [
                    'type' => 'updateUsers',
                    'users' => array_values($users)
                ]);
            }
        }

        // Удаляем соединение
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }

    // Метод, рассылающий сообщение в комнату
    private function sendToRoom($roomId, $data) {
        // Получаем список пользователей в комнате из БД
        $users = getUsersInRoom($roomId);

        // Перебираем всех подключённых клиентов
        foreach ($this->clients as $client) {
            // Если клиент есть в списке пользователей комнаты
            // if (in_array($client->resourceId, $users)) {
            //     // Рассылаем сообщение
            // }
            $client->send(json_encode($data));
        }
    }

}

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

// Создаем вебсокет-сервер
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new RoomServer()
        )
    ), 8080, '127.0.0.1'
);

// Запускаем вебсокет-сервер
$server->run();

?>