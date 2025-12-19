$(document).ready(function () {
    $(".toggle").click(function () {
        $("#navbarSupportedContent").slideToggle();
    })
});

function Scroll() {
    var top = document.getElementById('head');
    var ypos = window.pageYOffset;

    if (ypos > 0) {
        top.style.background = "#fff";
        top.style.transition = ".9s";
        top.style.boxShadow = "3px 4px 6px #ddd";
        top.style.zIndex = "1"
    } else {
        top.style.background = "transparent";
        top.style.boxShadow = "none";
    }
}
window.addEventListener("scroll", Scroll);