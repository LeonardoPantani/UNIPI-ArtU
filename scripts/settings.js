$(function() {
    // --- event handlers
    // cambio immagine profilo
    $("#avatar_edit").on("click",function() {
        $("#avatarimginput").trigger('click');
    });

    $("#avatarimginput").on("change",function() {
        const form = $("#chngimg_form");
        changeProfileImage(form.attr('action'), form);
    });

    if($("#defaulturi").text() === "false") { // ottengo il dato dal campo di testo invisibile settato dal php
        const avatarmain = $("#avatar_main");

        avatarmain.on("mouseenter", function() {
            console.log("ciao");
            $("#avatar_overlay").removeClass("invisible")
        });

        avatarmain.on("mouseleave", function() {
            $("#avatar_overlay").addClass("invisible")
        });

        $("#avatar_deletebutton").on("click", function() {
            if (confirm("Sei sicuro di voler eliminare l'immagine profilo?")) {
                deleteProfileImage();
            }
        });
    }

    // cambio password
    $("#chngpswd_form").on("submit",function(e) {
        e.preventDefault();
        changePassword($(this).attr('action'), $(this));
    });

    $("#oldpassword").on('input', function() {
        if ($("#oldpassword").val().length < 6 || $("#newpassword").val().length < 6) {
            updateSubmitButton($("#chngpswd_submitform"), false)
        } else {
            updateSubmitButton($("#chngpswd_submitform"), true)
        }
    });

    $("#newpassword").on('input', function() {
        if ($("#oldpassword").val().length < 6 || $("#newpassword").val().length < 6) {
            updateSubmitButton($("#chngpswd_submitform"), false)
        } else {
            updateSubmitButton($("#chngpswd_submitform"), true)
        }
    });

    // cambio visibilità profilo
    $("#chngprofvis_button").on("click",function() {
        $("#chngprofvis_button").addClass("gone");
        $("#chngprofvis_div").removeClass("gone");
    });

    $("#chngprofvis_cancel").on("click",function() {
        $("#chngprofvis_button").removeClass("gone");
        $("#chngprofvis_div").addClass("gone");
    });

    $("#chngprofvis_text").on('input', function() {
        if ($("#chngprofvis_text").val() === $("#username").text()) {
            updateSubmitButton($("#chngprofvis_submitform"), true)
        } else {
            updateSubmitButton($("#chngprofvis_submitform"), false)
        }
    });

    $("#chngprofvis_form").on("submit",function(e) {
        e.preventDefault();
        changeProfileVisibility($(this).attr('action'), $(this));
    });


    // eliminazione account
    $("#delacc_button").on("click",function() {
        $("#delacc_button").addClass("gone");
        $("#delacc_div").removeClass("gone");
    });

    $("#delacc_cancel").on("click",function() {
        $("#delacc_button").removeClass("gone");
        $("#delacc_div").addClass("gone");
    });

    $("#delacc_text").on('input', function() {
        if ($("#delacc_text").val() === $("#username").text()) {
            updateSubmitButton($("#delacc_submitform"), true)
        } else {
            updateSubmitButton($("#delacc_submitform"), false)
        }
    });

    $("#delacc_form").on("submit",function(e) {
        e.preventDefault();

        if (confirm("Sei DAVVERO sicuro di voler eliminare l'account? E' un'operazione irreversibile.")) {
            deleteAccount($(this).attr('action'), $(this));
        }
    });
});

// --- funzioni xhr
function deleteProfileImage() {
    $.ajax({
        type: "POST",
        url: $("#chngimg_form").attr('action') + "?deleteimg=1",
        success: function() {
            location.reload();
        }
    });
}

function changeProfileImage(actionurl) {
    const formData = new FormData($("#chngimg_form")[0]);

    $.ajax({
        type: "POST",
        url: actionurl,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            if (result === "chngimg_ok") {
                location.reload();
            }
        }
    });
}

function changePassword(actionurl, form) {
    $.ajax({
        type: "POST",
        url: actionurl,
        data: form.serialize(),
        success: function(result) {
            const dialog = $("#chngpswd_warning");
            dialog.removeClass("gone");

            if (result === "chngpswd_ok") {
                form.trigger('reset');
                updateSubmitButton($("#chngpswd_submitform"), false)
                dialog.text("Password modificata con successo!");
                setTimeout(function() {
                    dialog.text("");
                    updateSubmitButton($("#chngpswd_submitform"), true)
                }, 3000);
            } else {
                let dtext;
                switch (result) {
                    case "error_invalid": {
                        dtext = "Dati invalidi."
                        break;
                    }
                    case "short_password": {
                        dtext = "Password non valida. Deve essere lunga almeno 6 caratteri."
                        break;
                    }
                    case "error_nouser": {
                        dtext = "Errore interno durante la modifica della password (nouser)."
                        break;
                    }
                    case "wrong_old_password": {
                        dtext = "La vecchia password non corrisponde."
                        break;
                    }
                    case "error_chngpswd": {
                        dtext = "Errore interno durante la modifica della password."
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

function changeProfileVisibility(actionurl, form) {
    $.ajax({
        type: "POST",
        url: actionurl,
        data: form.serialize(),
        success: function(result) {
            if (result === "chngprofvis_ok") {
                location.reload();
            } else {
                const dialog = $("#chngprofvis_warning");
                dialog.removeClass("gone");
                let dtext;
                switch (result) {
                    case "error_invalid": {
                        dtext = "Dati invalidi."
                        break;
                    }
                    case "username_not_equal": {
                        dtext = "Nome utente errato"
                        break;
                    }
                    case "error_chngprofvis": {
                        dtext = "Errore interno durante la modifica della visibilità."
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

function deleteAccount(actionurl, form) {
    $.ajax({
        type: "POST",
        url: actionurl,
        data: form.serialize(),
        success: function(result) {
            if (result === "delacc_ok") {
                logout();
            } else {
                const dialog = $("#delacc_warning");
                dialog.removeClass("gone");

                let dtext;
                switch (result) {
                    case "error_invalid": {
                        dtext = "Dati invalidi."
                        break;
                    }
                    case "username_not_equal": {
                        dtext = "Nome utente errato"
                        break;
                    }
                    case "error_delacc": {
                        dtext = "Errore interno durante l'eliminazione del tuo account."
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

// --- generico
function updateSubmitButton(button, newStatus) {
    $(button).prop("disabled", !newStatus);
}