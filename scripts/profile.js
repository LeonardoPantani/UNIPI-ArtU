$(function() {
    // cambio immagine profilo
    $("#avatar_edit").on("click",function() {
        $("#avatarimginput").trigger('click');
    });

    $("#avatarimginput").on("change",function() {
        const form = $("#chngimg_form");
        // invio richiesta ajax
        sendAjax(form.attr('action'), form, function(result) {
            if (result === "chngimg_ok") {
                location.reload();
            }
        }, true);
    });

    if($("#defaulturi").text() === "false") { // ottengo il dato dal campo di testo invisibile settato dal php
        const avatarContainer = $("#avatar_container");

        avatarContainer.on("mouseenter", function() {
            $("#avatar_deletebutton").removeClass("invisible")
        });

        avatarContainer.on("mouseleave", function() {
            $("#avatar_deletebutton").addClass("invisible")
        });

        $("#avatar_deletebutton").on("click", function() {
            if (confirm("Sei sicuro di voler eliminare l'immagine profilo?")) {
                // invio richiesta ajax
                sendAjax($("#chngimg_form").attr('action') + "?deleteimg=1", null, function() {
                    location.reload();
                }, false);
            }
        });
    }

    // cambio password
    $("#chngpswd_form").on("submit",function(e) {
        e.preventDefault();
        // invio richiesta ajax
        sendAjax($(this).attr('action'), $(this), function(result) {
            const dialog = $("#chngpswd_warning");
            dialog.removeClass("gone");
            if (result === "chngpswd_ok") {
                dialog.text("Password cambiata con successo!");

                $("#chngpswd_form").trigger('reset');
                updateSubmitButton($("#chngpswd_submitform"), false)
                setTimeout(function() {
                    dialog.addClass("gone");
                    dialog.text("");
                }, 3000);
            } else {
                dialog.text(result);
            }
        }, false);
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

    // numero elementi per pagina
    $("#chngpgntn_form").on("submit",function(e) {
        e.preventDefault();
        // invio richiesta ajax
        sendAjax($(this).attr('action'), $(this), function(result) {
            const dialog = $("#chngpgnt_warning");
            dialog.removeClass("gone");
            if (result === "chngpgnt_ok") {
                dialog.text("Numero elementi cambiato con successo!");

                updateSubmitButton($("#chngpgnt_submitform"), false)
                // tolgo la scritta "Selezionato: " a quello che ce l'aveva prima e invece lo metto a quello nuovo
                $("#chngpgnt_numElemsPerPage > option").each(function() {
                    $(this).text(($(this).text()).replace("Selezionato: ", ""));

                    if($(this).val() === $("#chngpgnt_numElemsPerPage").val()) {
                        $(this).text("Selezionato: " + $(this).text());
                    }
                });
                $("#numelemsperpage").text($("#chngpgnt_numElemsPerPage").val()); // aggiorno il valore attuale della preferenza

                setTimeout(function() {
                    dialog.addClass("gone");
                    dialog.text("");
                }, 3000);
            } else {
                dialog.text(result);
            }
        }, false);
    });

    $("#chngpgnt_numElemsPerPage").on("change", function() {
        let elem = $("#chngpgnt_numElemsPerPage");
        let actual = $("#numelemsperpage").text();

        if(elem.val() !== actual) {
            updateSubmitButton($("#chngpgnt_submitform"), true);
        } else {
            updateSubmitButton($("#chngpgnt_submitform"), false);
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
        // invio richiesta ajax
        sendAjax($(this).attr('action'), $(this), function(result) {
            if (result === "chngprofvis_ok") {
                location.reload();
            } else {
                const dialog = $("#chngprofvis_warning");
                dialog.removeClass("gone");
                dialog.text(result);
            }
        }, false);
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
        // invio richiesta ajax
        if (confirm("Siete DAVVERO sicuri di voler eliminare l'account? E' un'operazione irreversibile.")) {
            sendAjax($(this).attr('action'), $(this), function(result) {
                if (result === "delacc_ok") {
                    logout(true);
                } else {
                    const dialog = $("#delacc_warning");
                    dialog.removeClass("gone");
                    dialog.text(result);
                }
            }, false);
        }
    });
});

// --- generico
function updateSubmitButton(button, newStatus) {
    $(button).prop("disabled", !newStatus);
}