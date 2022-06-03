$(function() {
    /* accetta */
    let resAccept = $(".frndreq_accept");

    // TODO rendere il sistema che accetta e rifiuta le richieste di amicizia uno solo (codice ripetuto)

    resAccept.on("click", function(e) {
        e.preventDefault();
        let current = $(this);
        if(resAccept.attr("href") === "") return;

        $.ajax({
            type: "POST",
            url: resAccept.attr("href"),
            cache: false,
            contentType: false,
            processData: false,
            success: function(result) {
                resAccept.attr("href", "");
                if (result === "acptfrndreq_ok") {
                    current.html("☑ Accettata.");
                    setTimeout(function() {
                        $("#frndreq" + current.attr("title")).remove();
                    }, 3000);
                } else {
                    let dtext;

                    switch(result) {
                        case "error_invalid": {
                            dtext = "Dati invalidi.";
                            break;
                        }
                        case "friendrequest_not_found": {
                            dtext = "Richiesta di amicizia non trovata.";
                            break;
                        }
                        case "error_acptfrndreq": {
                            dtext = "Errore interno.";
                            break;
                        }
                        default: {
                            dtext = "Errore non specificato.";
                        }
                    }
                    resAccept.html("❌ " + dtext);
                }
            }
        });
    });

    /* rifiuta */
    let resReject = $(".frndreq_reject");

    resReject.on("click", function(e) {
        e.preventDefault();
        let current = $(this);
        if(resAccept.attr("href") === "") return;

        $.ajax({
            type: "POST",
            url: resReject.attr("href"),
            cache: false,
            contentType: false,
            processData: false,
            success: function(result) {
                resReject.attr("href", "");
                if (result === "rejtfrndreq_ok") {
                    current.html("☑ Rifiutata.");
                    setTimeout(function() {
                        $("#frndreq" + current.attr("title")).remove();
                    }, 3000);
                } else {
                    let dtext;

                    switch(result) {
                        case "error_invalid": {
                            dtext = "Dati invalidi.";
                            break;
                        }
                        case "friendrequest_not_found": {
                            dtext = "Richiesta di amicizia non trovata.";
                            break;
                        }
                        case "error_rejtfrndreq": {
                            dtext = "Errore interno.";
                            break;
                        }
                        default: {
                            dtext = "Errore non specificato.";
                        }
                    }
                    resReject.html("❌ " + dtext);
                }
            }
        });
    });
});