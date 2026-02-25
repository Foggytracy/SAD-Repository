
function setActive(clickedButton) {
    // remove 'clicked' class from all buttons
    const buttons = document.querySelectorAll("button");
    buttons.forEach(btn => btn.classList.remove("clicked"));

    // add 'clicked' class to the clicked button
    clickedButton.classList.add("clicked");
}

 function setAction(type){
            document.getElementById('action').value = type;
        }
