const roomCodeInput = document.getElementById("roomCodeInput");
const copyRoomCodeBtn = document.getElementById("copyRoomCodeBtn");
const inviteMsg = document.getElementById("inviteMsg");

copyRoomCodeBtn.addEventListener("click", () => {
    roomCodeInput.select();
    roomCodeInput.setSelectionRange(0, 99999);
    inviteMsg.style.display = "flex";

    navigator.clipboard.writeText(roomCodeInput.value)
        .then(() => {
            roomCodeInput.blur();
        })

    setTimeout(() => {
        inviteMsg.style.display = "none";
    }, 2000);
});