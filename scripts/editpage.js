$(function() {
    updateCharacters();

    $("#editpage_form").on("submit", function(e) {
        e.preventDefault();
        sendAjax($(this).attr("action"), $(this), function(result) {
            const dialog = $("#result");

            if(result === "editpg_ok") {
                dialog.html("Pagina aggiornata!");
                setTimeout(function() {
                    updateCharacters();
                }, 1500);
            } else {
                dialog.html(result);
            }
        }, false);
    });

    $("#htmeditor").on("input", function() {
        updateCharacters();
    });

    $("#goback").on("click", function() {
        redirect("./profile.php");
    });
});

function updateCharacters() {
    $("#result").html("Caratteri: <b>" + $("#htmeditor").val().length + "</b>");
}