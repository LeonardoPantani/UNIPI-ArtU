$(function() {
    $(".frndreq_edit").on("click", function(e) {
        e.preventDefault();
        let current = $(this);
        if(current.attr("href") === "") return;

        sendAjax(current.attr("href"), null, function(result) {
            current.attr("href", "");
            if (result === "editfrndreq_ok") {
                current.html("☑ Fatto");
                setTimeout(function() {
                    // -2 perché una è quella che rimuoverò e l'altra è l'header
                    if($('#table_frndreq tr').length-2 > 1)
                        $("#frndreq" + current.attr("title")).remove();
                    else
                        location.reload();
                }, 3000);
            } else {
                setTimeout(function() {
                    current.html("❌ " + result);
                }, 3000);
            }
        },false);
    });

    $(".frndreq_del").on("click", function(e) {
        e.preventDefault();

        if(!confirm("Sicuri di voler eseguire questa azione?")) {
            return;
        }

        let current = $(this);
        if(current.attr("href") === "") return;

        sendAjax(current.attr("href"), null, function(result) {
            current.attr("href", "");
            if (result === "delfrnd_ok") {
                current.html("☑ Fatto");
                setTimeout(function() {
                    // -2 perché una è quella che rimuoverò e l'altra è l'header
                    if($('#table_friends tr').length-2 > 1)
                        $("#friendid" + current.attr("title")).remove();
                    else
                        location.reload();
                }, 3000);
            } else {
                setTimeout(function() {
                    current.html("❌ " + result);
                }, 3000);
            }
        },false);
    })
});