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

function previewBackground(input, previewID, fullID) {
    //console.log("Preview: " + input + id);
    if (input.files && input.files[0]) {
        //console.log("input.files");
        var reader = new FileReader();

        reader.onload = function (e) {
            //console.log($(id).css("background-image"));
            //console.log(e.target.result)
            $(previewID)
                .css('background-image', "url('" + e.target.result + "')");
            //console.log($(id).css("background-image"));

            $(fullID)
                .attr('src', e.target.result)
        };

        reader.readAsDataURL(input.files[0]);
    }
}

// Confirm before submission
// https://stackoverflow.com/questions/16849117/html-how-to-do-a-confirmation-popup-to-a-submit-button-and-then-send-the-reque
function confirmDelete(event, type) {
    choice = confirm("Are you sure you want to delete this " + type + "?");
    //console.log(choice);
    //alert(1);
    if (!choice) {
        event.preventDefault();
    }
}

// Used on editHoliday.php
function updateLocation(self) {
    realText = $('#loc' + self.value).text();
    $('#preview-location').text(realText);
    
    seperate = realText.split(", ");
    $('#full-location').text(seperate[0]);
    $('#full-country').text(seperate[1]);
}

function updateCategory(self) {
    realText = $('#cat' + self.value).text().toLowerCase();


    $('#full-category').text(aOrAn(realText) + " " + realText + " holiday");
}

// Set text of multiple elements at once
// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/forEach
function setText(elements, text) {
    elements.forEach((element) => {
        $(element).text(text);
    })
}

function aOrAn(string) {
    first = string[0].toLowerCase();
    if ('aeiou'.includes(first)) {
        return 'An';
    }
    else {
        return 'A';
    }
}