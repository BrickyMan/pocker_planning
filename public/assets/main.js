const rateBar = document.getElementById("rateBar");
const rateBtns = rateBar.querySelectorAll("#rateBar button");
const result = document.querySelector(".result-wrapper h2 span");
const actionCardBtn = document.getElementById("actionCardBtn");
const currentUserCard = document.querySelector(".user-card.current-user");

function rateChoice(event) {
    for (let i = 0; i < rateBtns.length; i++) {
        rateBtns[i].classList.remove("active");
    }
    event.target.classList.add("active");
    setCardReady(currentUserCard);
}

function setCardReady(card) {
    card.classList.add("user-ready");
}

for (let i = 0; i < rateBtns.length; i++) {
    rateBtns[i].addEventListener("click", rateChoice);
}