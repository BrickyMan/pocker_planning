const socket = new WebSocket("ws://192.168.0.17:8080");
const userList = document.querySelector('.user-card-list');
const rateBar = document.getElementById("rateBar");
const rateBtns = rateBar.querySelectorAll(".rate-bar_btn");
const result = document.querySelector(".result-wrapper h2");
const actionCardBtn = document.getElementById("actionCardBtn");
const currentUserCard = document.querySelector(".user-card.current-user");

// Обработка клика на одну из кнопок выбора оценки
function handleRateChoice(event) {
    if (rateBar.getAttribute("blocked") == "true") return;
    resetChoice();
    event.target.classList.add("active");
    
    let value = event.target.getAttribute("data-value");
    socket.send(JSON.stringify({
        type: 'vote',
        room_id: roomId,
        user_id: userData.session_id,
        vote: value
    }));
    console.log('voted');
}

function setRateChoice(value) {
    for (let i = 0; i < rateBtns.length; i++) {
        if (rateBtns[i].getAttribute("data-value") == value) {
            rateBtns[i].classList.add("active");
        }
    }
}

function resetChoice() {
    for (let i = 0; i < rateBtns.length; i++) {
        rateBtns[i].classList.remove("active");
    }
}

// Подписка на клик кнопок выбора оценки
for (let i = 0; i < rateBtns.length; i++) {
    rateBtns[i].addEventListener("click", handleRateChoice);
}

// Отправка данных при подключении
socket.onopen = () => {
    console.log('Connected');

    socket.send(JSON.stringify({
        type: 'join',
        room_id: roomId,
        user_data: userData
    }));
};

// Обработка клика на кнопку действия
actionCardBtn.addEventListener("click", () => {
    console.log('Вскрываемся!')
    if (actionCardBtn.getAttribute("data-status") === "showdown") {
        socket.send(JSON.stringify({
            type: 'showdown',
            room_id: roomId,
            user_id: userData.session_id,
        }));   
    }
    else if (actionCardBtn.getAttribute("data-status") === "restart") {
        socket.send(JSON.stringify({
            type: 'restart',
            room_id: roomId,
            user_id: userData.session_id,
        }));
    }
});

socket.onmessage = (event) => {
    const data = JSON.parse(event.data);
    console.log("Message received:", data);

    if (data.type === 'join') {
        updateCards(data.users);
        console.log(data);
        if (data.showdown) {
            showdown(data.users, data.avergeVote);
        }
    }

    else if (data.type === 'updateUsers') {
        updateCards(data.users);
    }

    else if (data.type === 'setReady') {
        updateReadyCards(data.users);
    }

    else if (data.type === 'showdown') {
        showdown(data.users, data.avergeVote);
    }

    else if (data.type === 'restart') {
        restart(data.users);
    }
};

function getCurrentUserVote(users) {
    users.forEach(user => {
        if (user.session_id === userData.session_id) {
            return user.vote;
        }
    })
}

function updateUsersCards(data) {
    let users = data.users;
    let isShowdown = data.type === "showdown";

    console.log(isShowdown);

    console.log('Users updated');

    // Создаём Set с id пользователей из новых данных
    const newUserIds = new Set(users.map(user => user.session_id));

    // Получаем текущие карточки
    const currentCards = Array.from(userList.children);

    // Удаляем карточки, которых нет в новых данных
    currentCards.forEach(card => {
        const userId = card.getAttribute("data-id");
        if (!newUserIds.has(userId)) {
            card.remove();
        }
    });

    users.forEach(user => {
        let card = document.querySelector(`.user-card[data-id="${user.session_id}"]`);

        // Если карточки нет, создаём её
        if (!card) {
            card = document.createElement("div");
            card.classList.add("user-card");
            card.setAttribute("data-id", user.session_id);
            card.innerHTML = `
                <h3 class="user-card-name">${user.username}</h3>
                ${user.session_id === userData.session_id ? '<p class="user-card-tag">Это вы</p>' : ''}
                <div class="user-card-inner">
                    <p class="user-card-value">?</p>
                </div>
            `;
            userList.appendChild(card);
        }

        // Добавляем/удаляем класс user-ready в зависимости от голоса
        if (user.vote !== 0 || isShowdown) {
            card.classList.add("user-ready");
        } else {
            card.classList.remove("user-ready");
        }

        // Обновляем имя на случай, если оно поменялось
        card.querySelector(".user-card-name").textContent = user.username;

        if (isShowdown) {
            showdown(data.users);
            actionCardBtn.setAttribute("data-status", "restart");
            actionCardBtn.innerText = "Заново";
        }
    });
}

function updateCards(users) {
    // Создаём Set с id пользователей из новых данных
    const newUserIds = new Set(users.map(user => user.session_id));
    // Получаем текущие карточки
    const currentCards = Array.from(userList.children);

    // Удаляем карточки, которых нет в новых данных
    currentCards.forEach(card => {
        const userId = card.getAttribute("data-id");
        if (!newUserIds.has(userId)) {
            card.remove();
        }
    });

    users.forEach(user => {
        let card = document.querySelector(`.user-card[data-id="${user.session_id}"]`);

        // Если карточки нет, создаём её
        if (!card) {
            card = document.createElement("div");
            card.classList.add("user-card");
            card.setAttribute("data-id", user.session_id);
            card.innerHTML = `
                <h3 class="user-card-name">${user.username}</h3>
                ${user.session_id === userData.session_id ? '<p class="user-card-tag">Это вы</p>' : ''}
                <div class="user-card-inner">
                    <p class="user-card-value">?</p>
                </div>
            `;
            userList.appendChild(card);
        }
    });
}

function setCardReady(card) {
    card.classList.add("user-ready");
}

function setAllCardsReady() {
    const cards = document.querySelectorAll(".user-card");
    cards.forEach(card => {
        setCardReady(card);
    });
}

function updateReadyCards(users) {
    users.forEach(user => {
        let card = document.querySelector(`.user-card[data-id="${user.session_id}"]`);

        if (user.vote !== 0) {
            setCardReady(card);
        }
    });
}

function showdown(users, avergeVote) {
    users.forEach(user => {
        let card = document.querySelector(`.user-card[data-id="${user.session_id}"]`);
        // Отображение голосов на картах и изменение стилей
        card.classList.add("user-card-showdown");
        card.querySelector(".user-card-value").textContent = user.vote;
        if (user.vote === 0) {
            card.classList.add("user-no-vote");
        }
    });
    setAllCardsReady();
    // Обновление кнопки действия
    actionCardBtn.setAttribute("data-status", "restart");
    actionCardBtn.innerText = "Заново";
    rateBar.setAttribute("blocked", "true");
    // Отображение среднего голоса
    showAvergeVote(avergeVote);
}

function showAvergeVote(value) {
    let [integer, decimal] = ['0', '0']
    if (value) {
        [integer, decimal] = value.toString().split('.');
    }
    result.innerHTML = `<span>${integer}</span>.${decimal[0]}`;
    
}

function restart(users) {
    users.forEach(user => {
        let card = document.querySelector(`.user-card[data-id="${user.session_id}"]`);
        // Сброс карт
        card.classList.remove("user-card-showdown");
        card.classList.remove("user-no-vote");
        card.querySelector(".user-card-value").textContent = "?";
        card.classList.remove("user-ready");
    });
    // Обновление кнопки действия
    actionCardBtn.setAttribute("data-status", "showdown");
    actionCardBtn.innerText = "Вскрываемся!";
    // Сброс выбранного голоса
    resetChoice();
    rateBar.setAttribute("blocked", "false");
    // Сброс среднего голоса
    showAvergeVote(0.0);
}


window.onbeforeunload = () => {
    socket.send(JSON.stringify({
        type: 'leave',
        room_id: roomId,
        user_id: userData.session_id
    }))
}