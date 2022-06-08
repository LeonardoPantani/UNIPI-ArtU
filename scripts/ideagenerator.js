let types = {
    "drawing": null,
    "music": null,
    "text": null,
    "poetry": null
};
let selected;
const tentativi = randomGenerator(5, 20);
//const slots = 3;

$(function() {
    let form_idea = $("#form_idea");
    form_idea.on("submit", function(e) {
        e.preventDefault();

        if (selected !== "") {
            $("#button_generate").removeAttr("disabled");

            selected = $("#type").val();

            if(types[selected] === null) {
                sendAjax(form_idea.attr("action"), form_idea, function(result) {
                    types[selected] = JSON.parse(result);

                    gioca()
                }, false);
            } else {
                gioca();
            }
        } else {
            $("#button_generate").attr("disabled", "disabled");
        }
    });
});

function gioca() {
    $("#button_generate").attr("disabled", "disabled");

    let t1 = 0;
    let array1 = types[selected][0];
    let slot1 = setInterval(function () {
        let numeroRandom = randomGenerator(0, array1.length - 1);
        document.getElementById("slot1").innerHTML = array1[numeroRandom];
        t1++;
        if (t1 === tentativi) {
            clearInterval(slot1);
            return null;
        }
    }, 100);

    let t2 = 0;
    let array2 = types[selected][1];
    let slot2 = setInterval(function () {
        t2++;
        if (t2 === tentativi) {
            clearInterval(slot2);
            return null;
        }
        let numeroRandom = randomGenerator(0, array2.length-1);
        document.getElementById("slot2").innerHTML = array2[numeroRandom];
    }, 100);

    let t3 = 0;
    let array3 = types[selected][2];
    let slot3 = setInterval(function () {
        t3++;
        if (t3 === tentativi) {
            clearInterval(slot3);
            document.getElementById("button_generate").disabled = false;
            return null;
        }
        let numeroRandom = randomGenerator(0, array3.length-1);
        document.getElementById("slot3").innerHTML = array3[numeroRandom];
    }, 100);
}


function randomGenerator(min, max) {
    return Math.floor(Math.random() * max) + min;
}