let acceptedExtensionsFile;
let acceptedExtensionsThumbnail;

/*
    spiegazione regex:
    ^ inizio valutazione
    $ fine valutazione
    [a-zA-Z_]+ insieme di 1 o più caratteri dell'insieme:
        lettere tra la a e z (maiusc. e minusc.)
        trattini bassi
    (?=(,?\s*)) fa match una virgola seguita da 0 o più spazi
    (?:\1[a-zA-Z_]+)+ se il gruppo precedente ha successo, fa match con lettere e trattini bassi solo se ne viene definito un insieme di 1 o più

    graficamente visibile su:
    https://jex.im/regulex/#!embed=true&flags=&re=%5E%5Ba-zA-Z_%5D%2B(%3F%3D(%2C%3F%5Cs*))(%3F%3A%5C1%5Ba-zA-Z_%5D%2B)%2B%24
 */
let validateTagsInput_regex = new RegExp("^[a-zA-Z_]+(?=(,?\\s*))(?:\\1[a-zA-Z_]+)+$");

$(function() {
    setAcceptedTypesThumbnail();

    let contentCategory = $("#content_category");

    contentCategory.on("change", function() {
        if(contentCategory.val() === "default") {
            hideAllNext(1);
        } else {
            showNext(1);
            $("#content_category option[value='default']").remove();

            setAcceptedFiles($("#content_category").val())
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
        if(val === "") {
            showTagWarning(false);
            return;
        }

        if(validateTagsInput_regex.test(val)) {
            let trimmedtags = val.trim();
            let tagslist = trimmedtags.split(",");

            if (tagslist.length > $("#tagmaxnumber").text()) {
                showTagWarning(true);
                return;
            }

            let error = false;
            tagslist.forEach(function (elem) {
                if (elem.length > $("#tagmaxlength").text()) {
                    showTagWarning(true);
                    error = true;
                }
            });

            if(!error) showTagWarning(false);
        } else {
            showTagWarning(true);
        }
    });

    $("#uploadcontent_form").on("submit", function(e) {
        e.preventDefault();
        const form = $(this);
        uploadContent(form.attr('action'), form);
    });
});

function showTagWarning(error) {
    let contenttagresult = $("#content_tags_result");

    if(error) {
        contenttagresult.addClass("color_error")
        contenttagresult.text("Non valido")
    } else {
        contenttagresult.removeClass("color_error")
        contenttagresult.text("")
    }
}

function uploadContent(actionurl) {
    const formData = new FormData($("#uploadcontent_form")[0]);

    $.ajax({
        type: "POST",
        url: actionurl,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            const dialog = $("#uploadcontent_warning");
            dialog.removeClass("gone");
            
            if (result.startsWith("uploadcontent_ok")) {
                redirect("./view.php?id=" + result.split(":").pop())
            } else {
                let dtext;
                
                switch(result) {
                    case "error_invalid": {
                        dtext = "Dati invalidi.";
                        break;
                    }
                    case "invalid_content_type": {
                        dtext = "Categoria contenuto non valida.";
                        break;
                    }
                    case "invalid_content_file": {
                        dtext = "Errore upload file principale.";
                        break;
                    }
                    case "content_file_too_big": {
                        dtext = "File troppo grande.";
                        break;
                    }
                    case "invalid_content_file_extensions": {
                        dtext = "Estensione file non valida per il tipo specificato.";
                        break;
                    }
                    case "invalid_content_thumbnail_extensions": {
                        dtext = "Estensione miniatura non valida.";
                        break;
                    }
                    case "content_thumbnail_too_big": {
                        dtext = "File miniatura troppo grande.";
                        break;
                    }
                    case "invalid_tags": {
                        dtext = "Formato tag non valido.";
                        break;
                    }
                    case "tag_toolong": {
                        dtext = "I singoli tag non devono superare i 20 caratteri di lunghezza";
                        break;
                    }
                    case "tag_toomany": {
                        dtext = "Troppi tag! Al massimo ne puoi specificare 30.";
                        break;
                    }
                    case "note_toolong": {
                        dtext = "Campo note troppo lungo.";
                        break;
                    }
                    case "setting_private_invalid": {
                        dtext = "Valore del campo impostazioni 'privato' non valido.";
                        break;
                    }
                    case "error_uploadcontent": {
                        dtext = "Errore durante l'upload del file.";
                        break;
                    }
                    case "error_movecontent": {
                        dtext = "Errore interno.";
                        break;
                    }
                    default: {
                        dtext = "Errore non specificato.";
                    }
                }
                dialog.text(dtext);
            }
        }
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

function showNext(currentNumber) {
    let toChange = currentNumber+1;

    $("#step" + toChange).fadeTo( 300, 1, function() {
        $("#step" + toChange).removeClass("step_visibility");
    });
}

function setAcceptedFiles(option) {
    $.ajax({
        type: "POST",
        url: $("#backend").text() + "/accval.php?option=" + option,
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            if (result !== "invalid_data") {
                acceptedExtensionsFile = result.replaceAll(".", "").split(',');

                $("#content_file").attr("accept", result    );
                $("#accepted_types").text(acceptedExtensionsFile.toString());

                clearFileInput();
            } else {
                console.log("errore ottenimento accepts: " + result);
            }
        }
    });
}

function setAcceptedTypesThumbnail() {
    $.ajax({
        type: "POST",
        url: $("#backend").text() + "/accval.php?option=thumbnail",
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            if (result !== "invalid_data") {
                acceptedExtensionsThumbnail = result.replaceAll(".", "").split(',');

                $("#content_thumbnail").attr("accept", result);
                $("#accepted_types_thumbnail").text(acceptedExtensionsThumbnail.toString());

                clearThumbnailInput();
            } else {
                console.log("errore ottenimento accepts thumbnail: " + result);
            }
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