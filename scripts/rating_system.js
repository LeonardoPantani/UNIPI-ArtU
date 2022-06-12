$(function() {
    $(".changerating").on("click", function (e) {
        let current = $(this);
        e.preventDefault();

        sendAjax($(this).attr("data-href"), null, function (result) {
            if (result.startsWith("chngrtng_ok")) {
                let array = result.split(":");
                let newRating = Number.parseInt(array[1]);
                let previousRating = Number.parseInt(array[2]);

                let button1, button2, counter1, counter2;
                if (current.attr("id") === "like_button") { // premuto "like"
                    button1 = $("#like_button");
                    counter1 = $("#like_counter");
                    button2 = $("#dislike_button");
                    counter2 = $("#dislike_counter");
                } else { // premuto "dislike"
                    button1 = $("#dislike_button");
                    counter1 = $("#dislike_counter");
                    button2 = $("#like_button");
                    counter2 = $("#like_counter");
                }

                if (newRating === previousRating) { // ho premuto sullo stesso pulsante (rimuovo rating)
                    button1.removeClass("chosenrating");
                    changeCounter(counter1, -1);
                } else {
                    if (previousRating === -1) { // scelto per la prima volta
                        changeCounter(counter1, 1);
                        button1.addClass("chosenrating");
                    } else { // ho cambiato rating
                        changeCounter(counter1, 1);
                        button1.addClass("chosenrating");
                        changeCounter(counter2, -1);
                        button2.removeClass("chosenrating");
                    }
                }
            } else {
                console.log("Errore durante la modifica del rating #" + current.attr("id") + ": " + result);
            }
        }, false);
    });
});

function changeCounter(element, change) {
    let v = Number.parseInt(element.text());
    let newv = v+change;
    element.text(newv);
}
