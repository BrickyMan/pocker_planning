const username_input = document.querySelector("#username");

username_input.addEventListener("input", () => {
    // Английские, русские буквы и цифры
    username_input.value = username_input.value.replace(/[^a-zA-Zа-яА-ЯёЁ0-9 ]/g, "");
    if (username_input.value.length > 12) username_input.value = username_input.value.slice(0, 12);
});