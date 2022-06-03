const options = {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    time: "short",
    hour12: false,
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit"
};

function logout(destination = "./backend/logout.php") {
    if (confirm("Sicuri di voler effettuare il logout?")) {
        redirect(destination);
    }
}

function redirect(destination = "./") {
    window.location.replace(destination);
}

function updateClock() {
    const today = new Date();

    $("#navbar_datetime").text(today.toLocaleString("it-IT", options));

    setTimeout(updateClock, 1000);
}

function validateUsername(username) {
    if(!username.match('/^\w{6,}$/')) return false;

    return true;
}

function validatePassword(password) {
    if(password.length < 6) return false;

    return true;
}

$(function () {
    updateClock();
});

let sas = 1;