$(function() {
    let ressnd = $("#sndfrndreq");

    ressnd.on("click", function(e) {
        e.preventDefault();

        if(ressnd.attr("href") === "") return;

        sendAjax(ressnd.attr("href"), null, function(result) {
            ressnd.attr("href", "");
            if (result === "sndfrndreq_ok") {
                ressnd.html("☑ Richiesta inviata.");
            } else {
                ressnd.html("❌ " + result);
            }
        }, false);
    });

    let resdel = $("#delfrndreq");
    resdel.on("click", function(e) {
        e.preventDefault();

        if(!confirm("Sicuri di voler eseguire questa azione?")) {
            return;
        }

        sendAjax(resdel.attr("href"), null, function(result) {
            resdel.attr("href", "");
            if (result === "delfrnd_ok") {
                resdel.html("☑ Fatto");
                setTimeout(function() {
                    location.reload();
                }, 3000);
            } else {
                setTimeout(function() {
                    resdel.html("❌ " + result);
                }, 3000);
            }
        },false);
    })
});