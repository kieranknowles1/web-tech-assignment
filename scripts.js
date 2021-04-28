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

// Set text of multiple elements at once
// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/forEach
function setText(elements, text) {
    elements.forEach((element) => {
        $(element).text(text);
    })
}

// Returns true if the form contains an empty string
function isEmpty(id) {
    element = document.getElementById(id);
    return element.value == "";
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