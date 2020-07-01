window.onscroll = function() {myFunction()};

// Get the header
var header = document.getElementById("myHeader");

function myFunction() {
    if($(window).scrollTop()){
        header.classList.add("shrink");
    }else{
        header.classList.remove("shrink");
    }
}
