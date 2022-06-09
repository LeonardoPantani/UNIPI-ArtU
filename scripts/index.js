$(function() {
    $(window).on("scroll", function () {
        if ($(window).scrollTop() >= $(document).height() - $(window).height() - 10) {
            console.log("Ciao")
        }
    });
});