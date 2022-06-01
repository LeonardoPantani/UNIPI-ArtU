$(function() {
    $("#form_register").on("submit", function(e) {
        e.preventDefault();
        const form = $(this);
        sendData(form.attr('action'), form);
    });

    $("#form_login").on("submit", function(e) {
        e.preventDefault();
        const form = $(this);
        sendData(form.attr('action'), form);
    });

    setInterval(function() {
        changeImageCover();
    }, 4000);
});

let current_cover = 2;
const max_covers = 4;

function changeImageCover() {
    $("#auth_grid_container").css("background-image", 'url("./media/auth' + current_cover + '.webp")');
    current_cover++;
    if(current_cover > max_covers) current_cover = 1;
}

function sendData(actionurl, form) {
    $.ajax({
        type: "POST",
        url: actionurl,
        data: form.serialize(),
        success: function(result) {
            if (result === "login_ok" || result === "register_ok") {
                redirect();
            } else {
                const dialog = $("#dialog");
                dialog.removeClass("gone");
                let dtext;
                switch (result) {
                    case "error_invalid": {
                        dtext = "Dati invalidi."
                        break;
                    }
                    case "wrong_access": {
                        dtext = "Nome utente o email errati."
                        break;
                    }
                    case "wrong_password": {
                        dtext = "Password errata."
                        break;
                    }
                    case "error_registration": {
                        dtext = "Errore interno durante la registrazione."
                        break;
                    }
                    case "invalid_username": {
                        dtext = "Username non valido. Deve essere lungo almeno 6 caratteri e può essere composto solo da lettere, numeri e trattini bassi."
                        break;
                    }
                    case "short_password": {
                        dtext = "Password non valida. Deve essere lunga almeno 6 caratteri."
                        break;
                    }
                    case "invalid_email": {
                        dtext = "Email non valida. Controlla il formato."
                        break;
                    }
                    case "passwords_not_equal": {
                        dtext = "Le password non corrispondono."
                        break;
                    }
                    case "access_already_exists": {
                        dtext = "Il nome utente o l'indirizzo email appartengono ad un utente già registrato."
                        break;
                    }
                    default: {
                        dtext = "Errore non specificato.";
                    }
                }
                dialog.text(dtext);
            }
        }
    });
}