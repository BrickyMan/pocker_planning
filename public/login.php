<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BriPocker</title>
    <link rel="stylesheet" href="public/assets/style.css">
    <link rel="stylesheet" href="public/assets/reset.css">
    <link rel="stylesheet" href="public/assets/login.css">
</head>
<body>
    <header>
        <p class="logo">BriPocker</p>
        <form action="logic/clear_session.php" method="post">
            <input type="submit" value="Выход">
        </form>
    </header>
    <main>
        <form class="auth-wrapper" action="logic/login.php" method="post">
            <label for="">Придумайте имя</label>
            <input type="text" name="username" placeholder="Начните вводить...">
            <input type="submit" value="Применить">
        </form>
    </main>
</body>
</html>