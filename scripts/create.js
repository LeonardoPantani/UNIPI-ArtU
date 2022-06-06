let acceptedExtensionsFile;
let acceptedExtensionsThumbnail;

let isCategoryOk = false;
let isTitleOk = false;
let isContentOk = false;
let isTagOk = false;

$(function() {
    let validateTitleInput_regex = new RegExp($("#titleregex").text());
    let validateTagsInput_regex = new RegExp($("#tagregex").text());

    /**
     * Ottengo i valori accettati dal backend per il campo input file della thumbnail. Nota. I valori accettati del
     * campo input principale vengono richiesti solamente quando l'utente effettua la selezione.
     */
    sendAjax($("#backend").text() + "/accval.php?option=thumbnail", null, function(result) {
        if (result !== "invalid_data") {
            acceptedExtensionsThumbnail = result.replaceAll(".", "").split(',');

            $("#content_thumbnail").attr("accept", result);
            $("#accepted_types_thumbnail").text(acceptedExtensionsThumbnail.toString());

            clearThumbnailInput();
        } else {
            console.log("errore ottenimento accepts thumbnail: " + result);
        }
    }, false);

    // ----------- PASSAGGIO 1
    /**
     * Al cambio della categoria nella select, cambio il meta tag "accept" nell'input file principale, e cambio anche
     * il contenuto del testo da mostrare all'utente. Inoltre, se il valore non è "", e il campo "title" è valido,
     * mostro il passaggio successivo.
     */
    let contentCategory = $("#content_category");
    contentCategory.on("change", function() {
        if(contentCategory.val() === "") {
            isCategoryOk = false; toggleSteps("category"); // chiamo funzione modifica steps
        } else {
            $("#content_category option[value='']").remove();
            // invio richiesta ajax
            sendAjax($("#backend").text() + "/accval.php?option=" + contentCategory.val(), null, function(result) {
                if (result !== "invalid_data") {
                    acceptedExtensionsFile = result.replaceAll(".", "").split(',');

                    $("#content_file").attr("accept", result    );
                    $("#accepted_types").text(acceptedExtensionsFile.toString());

                    clearFileInput();

                    isCategoryOk = true; toggleSteps("category"); // chiamo funzione modifica steps
                } else {
                    console.log("errore ottenimento accepts: " + result);
                }
            }, false);
        }
    });

    let typingTimerTitle;
    $("#content_title").on("input", function() {
        clearTimeout(typingTimerTitle);
        typingTimerTitle = setTimeout(function() {
            let val = $("#content_title").val();
            let where = $("#content_title_result");

            if(val === "") {
                showInvalidWarning(where, true);
                isTitleOk = false; toggleSteps("title"); // chiamo funzione modifica steps
                return;
            }

            if(val.length > $("#titlemaxlength").text()) {
                showInvalidWarning(where, true);
                isTitleOk = false; toggleSteps("title"); // chiamo funzione modifica steps
                return;
            }

            if(!validateTitleInput_regex.test(val)) {
                showInvalidWarning(where, true);
                isTitleOk = false; toggleSteps("title"); // chiamo funzione modifica steps
            } else {
                showInvalidWarning(where, false);
                isTitleOk = true; toggleSteps("title"); // chiamo funzione modifica steps
            }
        }, 500);
    });

    // ----------- PASSAGGIO 2
    /**
     * Cambio il contenuto del div contenuto principale quando inserisco un file, mostro un errore in caso l'estensione
     * non corrisponda ad una valida per la sua categoria. Se invece va bene, mostro le sezioni successive.
     */
    $("#content_file").on("change", function() {
        let file = $("#content_file");
        $("#content_label_file").html("☁&nbsp; " + file[0].files[0]['name'])

        if ($.inArray(file.val().split('.').pop(), acceptedExtensionsFile) === -1) {
            isContentOk = false;
            toggleSteps("mainfile"); // chiamo funzione modifica steps
            clearFileInput();
            alert("Per questa risorsa sono supportati i seguenti formati:\n\n" + acceptedExtensionsFile.join(', '));
        } else {
            isContentOk = true;
            toggleSteps("mainfile"); // chiamo funzione modifica steps
        }
    });

    /**
     * Cambio il contenuto del div thumbnail quando inserisco un file, e mostro un errore in caso l'estensione
     * non corrisponda ad una valida per le miniature.
     */
    $("#content_thumbnail").on("change", function() {
        let file = $("#content_thumbnail");
        $("#content_label_thumbnail").html("☁&nbsp; " + file[0].files[0]['name'])

        if ($.inArray(file.val().split('.').pop(), acceptedExtensionsThumbnail) === -1) {
            clearThumbnailInput();
            alert("Per le miniature sono supportati i seguenti formati:\n\n" + acceptedExtensionsThumbnail.join(', '));
        }
    });

    /**
     * Al click sul div simulo il click sul campo di input reale.
     */
    $("#content_label_file").on("click", function() {
        $("#content_file").trigger("click");
    })
    $("#content_label_thumbnail").on("click", function() {
        $("#content_thumbnail").trigger("click");
    })

    // ----------- PASSAGGIO 3
    let typingTimerTags;
    /**
     * Al cambio del campo tags, devo validarlo. Se la validazione non ha successo, mostro un avviso.
     */
    $("#content_tags").on("input", function() {
        clearTimeout(typingTimerTags);
        typingTimerTags = setTimeout(function() {
            let val = $("#content_tags").val();
            let where = $("#content_tags_result");
            if(val === "") {
                showInvalidWarning(where, false);
                isTagOk = false; toggleSteps("tags"); // chiamo funzione modifica steps
                return;
            }

            if(validateTagsInput_regex.test(val)) {
                let trimmedtags = val.trim();
                let tagslist = trimmedtags.split(",");

                if (tagslist.length > $("#tagmaxnumber").text()) {
                    showInvalidWarning(where,true);
                    isTagOk = false; toggleSteps("tags"); // chiamo funzione modifica steps
                    return;
                }

                let error = false;
                tagslist.forEach(function (elem) {
                    if (elem.length > $("#tagmaxlength").text()) {
                        showInvalidWarning(where,true);
                        isTagOk = false; toggleSteps("tags"); // chiamo funzione modifica steps
                        error = true;
                    }
                });

                if(!error) isTagOk = true; showInvalidWarning(where, false);
            } else {
                isTagOk = false; showInvalidWarning(where, true);
            }
            toggleSteps("tags"); // chiamo funzione modifica steps
        }, 500);
    });

    // ----------- PASSAGGIO 4
    /**
     * Al submit del form lo invio con richiesta AJAX, in modo da mostrare a schermo gli errori, se si verificano.
     */
    $("#uploadcontent_form").on("submit", function(e) {
        e.preventDefault();
        // invio richiesta ajax
        sendAjax($(this).attr('action'), $(this), function(result) {
            const dialog = $("#uploadcontent_warning");

            dialog.removeClass("gone");
            if (result.startsWith("uploadcontent_ok")) {
                redirect("./view.php?id=" + result.split(":").pop())
            } else {
                dialog.text(result);
            }
        }, true);
    });
});

/**
 * Mostra o nasconde il testo "non valido" su un paragrafo.
 * @param where dove mostrare l'errore
 * @param error se mostrare o nascondere l'errore
 */
function showInvalidWarning(where, error) {
    if(error) {
        where.addClass("color_error")
        where.text("Non valido")
    } else {
        where.removeClass("color_error")
        where.text("")
    }
}

let reachedStep = 1;
/**
 * Aggiorna la visibilità degli elementi del form di creazione.
 */
function toggleSteps(changed) {
    console.log("changed " + changed);
    if(changed === "category") {
        if(isCategoryOk) {
            if(isTitleOk) {
                reachedStep = 2;
            }
        } else {
            reachedStep = 1;
        }
        validateUntil(reachedStep);
    }

    if(changed === "title") {
        if(isTitleOk) {
            if(isCategoryOk) {
                reachedStep = 2;
            }
        } else {
            reachedStep = 1;
        }
        validateUntil(reachedStep);
    }

    if(changed === "mainfile") {
        if(isContentOk) {
            validateUntil(4);
        } else {
            validateUntil(2);
        }
    }

    if(changed === "tags") {
        if(isTagOk) {
            reachedStep = 4;
        } else {
            reachedStep = 3;
        }
        validateUntil(reachedStep);
    }
}

/**
 * Mostra i passaggi raggiunti durante la compilazione del form fino ad until
 */
function validateUntil(until) {
    let containerSelector = $(".step_container");

    containerSelector.each(function(a, b) {
        let currentId = $(b).attr("id");
        if(currentId.charAt(currentId.length-1) <= until) {
            $(b).fadeTo( 300, 1, function() {
                $(b).removeClass("step_visibility");
            });
        }
    });

    containerSelector.each(function(a, b) {
        let currentId = $(b).attr("id");
        if(currentId.charAt(currentId.length-1) > until) {
            $(b).fadeTo( 300, 0, function() {
                $(b).addClass("step_visibility");
            });
        }
    });
}

/**
 * Reimposta alcuni campi del form.
 */
function clearFileInput() {
    $("#content_file").val("");
    $("#content_label_file").html("☁&nbsp;Carica risorsa");
    $("#content_tags").text("");
    $("#content_note").text("");
}

/**
 * Ripulisce l'input della thumbnail (in caso sia inserito un file non valido).
 */
function clearThumbnailInput() {
    $("#content_thumbnail").val("");
    $("#content_label_thumbnail").html("☁&nbsp;Carica miniatura");
}