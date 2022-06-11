$(function() {
    let usernameRegex = new RegExp($("#usernameregex").text());

    // -- INVIO
    $(".form_auth").on("submit", function(e) {
        e.preventDefault();

        sendAjax($(this).attr('action'), $(this), function(result) {
            if (result === "login_ok" || result === "register_ok") {
                redirect();
            } else {
                const dialog = $("#dialog");
                dialog.removeClass("gone");
                dialog.text(result);
            }
        }, false);
    });

    $(".register_validation").on("input", function() {
        if(!validateRegistration()) {
            $("#register_submit").prop("disabled", true);
        } else {
            $("#register_submit").prop("disabled", false);
        }
    });

    function validateRegistration() {
        // controllo username
        if(!usernameRegex.test($("#register_username").val())) {
            return false;
        }

        // controlli vari password
        let registerPassword = $("#register_password");
        if(registerPassword.val().length < Number.parseInt(registerPassword.attr("minlength"))) {
            return false;
        }
        return registerPassword.val() === $("#register_repeatpassword").val();


    }

    $(".login_validation").on("input", function() {
        if(!validateLogin()) {
            $("#login_submit").prop("disabled", true);
        } else {
            $("#login_submit").prop("disabled", false);
        }
    });

    function validateLogin() {
        // viene controllata solo la password
        let loginPassword = $("#login_password");
        return loginPassword.val().length >= Number.parseInt(loginPassword.attr("minlength"));


    }

    setInterval(function() {
        changeImageCover();
    }, 4000);
});

let current_cover = 2;
const max_covers = 4;

function changeImageCover() {
    $("#auth_grid_container").css("background-image", 'url("./media/auth' + current_cover + '.webp")');
    current_cover++;
    if(current_cover > max_covers) current_cover = 1;
}