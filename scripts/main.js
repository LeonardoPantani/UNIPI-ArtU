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

/**
 * Invia un form via XHR all'indirizzo specificato
 * @param actionURL url su cui inviare il form
 * @param form il form da inviare
 * @param onFinish(result) funzione chiamata una volta finita la richiesta
 * @param sendingFile vero se si sta inviando un file, falso altrimenti
 */
function sendAjax(actionURL, form, onFinish, sendingFile) {
    if(sendingFile) {
        $.ajax({
            type: "POST",
            url: actionURL,
            data: form ? new FormData(form[0]) : null,
            cache: false,
            contentType: false,
            processData: false,
            success: function(result) {
                onFinish(result);
            }
        });
    } else {
        $.ajax({
            type: "POST",
            url: actionURL,
            data: form ? form.serialize() : null,
            success: function(result) {
                onFinish(result);
            }
        });
    }
}

$(function () {
    updateClock();
});