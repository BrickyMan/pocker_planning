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
        <nav>
            <button id="inviteBtn">Пригласить</button>
            <button id="finishBtn">Завершить</button>
        </nav>
    </header>
    <main>
        <div class="main-inner">
            <div class="user-card-wrapper">
                <div class="user-card current-user">
                    <h3 class="user-card-name">Alex</h3>
                    <div class="user-card-inner">
                        <p class="user-card-value">?</p>
                    </div>
                </div>
                <div class="user-card">
                    <h3 class="user-card-name">Ramil</h3>
                    <div class="user-card-inner">
                        <p class="user-card-value">?</p>
                    </div>
                </div>
                <div class="user-card">
                    <h3 class="user-card-name">Artemiy</h3>
                    <div class="user-card-inner">
                        <p class="user-card-value">?</p>
                    </div>
                </div>
                <div class="user-card user-ready">
                    <h3 class="user-card-name">Roman</h3>
                    <div class="user-card-inner">
                        <p class="user-card-value">?</p>
                    </div>
                </div>
            </div>

            <div class="result-wrapper">
                <p>Среднее: 3.14</p>
                <h2>Результат: <span>3</span></h2>
            </div>

            <div class="action-btns-wrapper">
                <button class="action-btn" id="actionCardBtn">Вскрываемся!</button>
                <!-- <button class="action-btn" id="actionRoundBtn">Далее</button> -->
            </div>
        </div>
        <div class="rate-bar" id="rateBar">
            <button>1</button>
            <button>2</button>
            <button>3</button>
            <button>5</button>
            <button>8</button>
            <button>13</button>
            <button>21</button>
        </div>
    </main>
</body>
</html>