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
    </header>
    <main>
        <form class="lobby-side" action="logic/create_room.php" method="post">
            <label>Создайте новую комнату</label>
            <input type="submit" value="Создать">
        </form>
        <div class="lobby-divider"></div>
        <form class="lobby-side" action="process.php" method="post">
            <label>Присоединитесь к комнате</label>
            <input type="text" name="room-key" placeholder="Введите код комнаты">
            <input type="submit" value="Присоединиться">
        </form>
    </main>
</body>
</html>