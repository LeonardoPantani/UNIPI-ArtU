$(function() {
    let res = $("#sndfrndreq");

    res.on("click", function(e) {
        e.preventDefault();

        if(res.attr("href") === "") return;

        $.ajax({
            type: "POST",
            url: res.attr("href"),
            cache: false,
            contentType: false,
            processData: false,
            success: function(result) {
                res.attr("href", "");
                if (result === "sndfrndreq_ok") {
                    res.html("☑ Richiesta inviata.");
                } else {
                    let dtext;

                    switch(result) {
                        case "error_invalid": {
                            dtext = "Dati invalidi.";
                            break;
                        }
                        case "same_user": {
                            dtext = "Non potete mandarvi da soli una richiesta di amicizia.";
                            break;
                        }
                        case "already_friends": {
                            dtext = "Siete già amici.";
                            break;
                        }
                        case "already_sent": {
                            dtext = "C'è già una vostra richiesta di amicizia in attesa.";
                            break;
                        }
                        case "error_sndfrndreq": {
                            dtext = "Errore interno.";
                            break;
                        }
                        default: {
                            dtext = "Errore non specificato.";
                        }
                    }
                    res.html("❌ " + dtext);
                }
            }
        });
    });
});