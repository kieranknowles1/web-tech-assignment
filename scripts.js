// Hamburger function drawn from KF4009 course materials
// Week 6: Workshop_responsive_menus
// Retrieved from Blackboard 2021-03-05

/* Usage:
    Create an <a> with id="ham_menu" and href="javascript:void(0)"
    use &#9776; for the icon

    Add id="ham_nav" to the element that will be toggled

    The class of ham_nav will be empty by default and "show" when the
    menu should be displayed
*/

/*window.addEventListener('load', function () {
    "use strict";

    const nav_menu = document.getElementById('nav_menu');
    const ham_button = document.getElementById('ham_button');
    let menu_clicked = false;

    function toggle() {	
        if (menu_clicked) {
            nav_menu.className = '';
        }
        else {
            nav_menu.className = 'show';
        }

        menu_clicked = !menu_clicked;
    } 

    ham_button.addEventListener("click", toggle);
});*/

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