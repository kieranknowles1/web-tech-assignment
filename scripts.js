// Hamburger function drawn from KF4009 course materials
// Week 6: Workshop_responsive_menus
// Retrieved from Blackboard 2021-03-05

function ham_toggle(id) {
    console.log("clicked");
    menu = document.getElementById(id);

    if (menu.className == "show") {
        menu.className = "";
    }
    else {
        menu.className = "show";
    }
}

// Returns true if the form contains an empty string
function isEmpty(id) {
    element = document.getElementById(id);
    return element.value == "";
}


// File upload preview
// https://codepen.io/mobifreaks/pen/LIbca
// Retrieved 2021-04-21
function readURL(input, preview) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(preview)
                .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function previewBackground(input, id) {
    //console.log("Preview: " + input + id);
    if (input.files && input.files[0]) {
        //console.log("input.files");
        var reader = new FileReader();

        reader.onload = function (e) {
            //console.log($(id).css("background-image"));
            //console.log(e.target.result)
            $(id)
                .css('background-image', "url('" + e.target.result + "')");
            //console.log($(id).css("background-image"));
        };

        reader.readAsDataURL(input.files[0]);
    }
}