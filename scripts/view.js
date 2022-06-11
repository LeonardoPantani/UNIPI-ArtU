$(function() {
    let commentNumberIndicator = $("#comment_number");
    let numberOfComments = Number.parseInt(commentNumberIndicator.text());

    $("#comment_form").on("submit", function(e) {
        e.preventDefault();
        let current = $(this);
        let dialog = $("#addcmnt_result");

        sendAjax(current.attr("action"), current, function(result) {
            if(result === "addcmnt_ok") {
                dialog.removeClass("color_error");
                dialog.html("Commento aggiunto! <a href='#' onClick='location.reload();'>🔄 Aggiorna</a>");
            } else {
                dialog.addClass("color_error");
                dialog.html(result);
            }
            dialog.removeClass("gone");
        }, false);
    });

    $(".deletecomment").on("click", function(e) {
        e.preventDefault();
        let current = $(this);
        let dialog = current.parent();
        let id = current.attr("data-id");

        sendAjax(current.attr("href"), null, function(result) {
            if(result === "delcmnt_ok") {
                numberOfComments--;
                dialog.text("✔ Commento eliminato.");
                setTimeout(function() {
                    commentNumberIndicator.text(numberOfComments);
                    $(".comment" + id).remove();
                    if(numberOfComments === 0) {
                        $("#nocomments").removeClass("gone");
                    }
                }, 1500);
            } else {
                console.log(result);
            }
        });
    });
});