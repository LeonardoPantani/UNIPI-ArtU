let acceptedExtensionsFile;
let acceptedExtensionsThumbnail;

$(function() {
    let validateTitleInput_regex = new RegExp($("#titleregex").text());
    let validateTagsInput_regex = new RegExp($("#tagregex").text());

    // invio richiesta ajax
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

    // imposto valori categoria accettati
    let contentCategory = $("#content_category");
    contentCategory.on("change", function() {
        if(contentCategory.val() === "default") {
            hideAllNext(1);
        } else {
            showNext(1);
            $("#content_category option[value='default']").remove();
            // invio richiesta ajax
            sendAjax($("#backend").text() + "/accval.php?option=" + contentCategory.val(), null, function(result) {
                if (result !== "invalid_data") {
                    acceptedExtensionsFile = result.replaceAll(".", "").split(',');

                    $("#content_file").attr("accept", result    );
                    $("#accepted_types").text(acceptedExtensionsFile.toString());

                    clearFileInput();
                } else {
                    console.log("errore ottenimento accepts: " + result);
                }
            }, false);
        }
    })

    $(".content_label_file").on("click", function() {
        $("#content_file").trigger("click");
    })

    $(".content_label_thumbnail").on("click", function() {
        $("#content_thumbnail").trigger("click");
    })

    $("#content_file").on("change", function() {
        let file = $("#content_file");
        $("#content_label_file").html("☁&nbsp; " + file[0].files[0]['name'])

        if ($.inArray(file.val().split('.').pop(), acceptedExtensionsFile) === -1) {
            clearFileInput();
            alert("Per questa risorsa sono supportati i seguenti formati:\n\n" + acceptedExtensionsFile.join(', '));
        } else {
            showNext(2);
            showNext(3);
        }
    });

    $("#content_thumbnail").on("change", function() {
        let file = $("#content_thumbnail");
        $("#content_label_thumbnail").html("☁&nbsp; " + file[0].files[0]['name'])

        if ($.inArray(file.val().split('.').pop(), acceptedExtensionsThumbnail) === -1) {
            clearThumbnailInput();
            alert("Per le miniature sono supportati i seguenti formati:\n\n" + acceptedExtensionsThumbnail.join(', '));
        }
    });

    $("#content_tags").on("input", function() {
        let val = $("#content_tags").val();
        let where = $("#content_tags_result");
        if(val === "") {
            showInvalidWarning(where, false);
            return;
        }

        if(validateTagsInput_regex.test(val)) {
            let trimmedtags = val.trim();
            let tagslist = trimmedtags.split(",");

            if (tagslist.length > $("#tagmaxnumber").text()) {
                showInvalidWarning(where,true);
                return;
            }

            let error = false;
            tagslist.forEach(function (elem) {
                if (elem.length > $("#tagmaxlength").text()) {
                    showInvalidWarning(where,true);
                    error = true;
                }
            });

            if(!error) showInvalidWarning(where, false);
        } else {
            showInvalidWarning(where, true);
        }
    });

    $("#content_title").on("input", function() {
        let val = $("#content_title").val();
        let where = $("#content_title_result");
        if(val === "") {
            showInvalidWarning(where, true);
            return;
        }

        if(val.length > $("#titlemaxlength").text()) {
            showInvalidWarning(where, true);
            return;
        }

        if(!validateTitleInput_regex.test(val)) {
            showInvalidWarning(where, true);
        } else {
            showInvalidWarning(where, false);
        }
    });

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

function showInvalidWarning(where, error) {
    if(error) {
        where.addClass("color_error")
        where.text("Non valido")
    } else {
        where.removeClass("color_error")
        where.text("")
    }
}

function showNext(currentNumber) {
    let toChange = currentNumber+1;

    $("#step" + toChange).fadeTo( 300, 1, function() {
        $("#step" + toChange).removeClass("step_visibility");
    });
}

function hideAllNext(currentNumber) {
    $(".step_container").each(function(a, b) {
        let currentId = $(b).attr("id");
        if(currentId.charAt(currentId.length-1) > currentNumber) {
            $(b).fadeTo( 300, 0, function() {
                $(b).addClass("step_visibility");
            });
        }
    });
}

function clearFileInput() {
    $("#content_file").val("");
    $("#content_label_file").html("☁&nbsp;Carica risorsa");
    $("#content_tags").text("");
    $("#content_note").text("");

    hideAllNext(2);
}

function clearThumbnailInput() {
    $("#content_thumbnail").val("");
    $("#content_label_thumbnail").html("☁&nbsp;Carica miniatura");
}