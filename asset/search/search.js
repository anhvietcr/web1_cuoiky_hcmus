var currentFilter;

function openSearchFilter(evt, filterName) {
    var i, x, tablinks;
    currentFilter = filterName;
    console.log(filterName);
    x = document.getElementsByClassName("filter");
    for (i = 0; i < x.length; i++) {
      x[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < x.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" w3-grey", "");
    }
    document.getElementById(filterName).style.display = "block";
    evt.currentTarget.className += " w3-grey";
  }