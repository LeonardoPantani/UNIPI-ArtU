$(function() {
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