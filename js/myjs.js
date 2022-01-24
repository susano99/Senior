/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

function myFunctions() {
  document.getElementById("myDropdownn").classList.toggle("show");
}
// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}

$(".showMore").click(function() {
  var tr = $(this).parent().parent().nextAll(':lt(2)');
      $(this).toggleClass('fa-angle-double-right fa-angle-double-down')
  if (tr.is(".display-none")) {
    tr.removeClass('display-none');
  } else {
    tr.addClass('display-none');
  }
})
