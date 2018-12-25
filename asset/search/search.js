function filterSelection(c) {
  var items, i;
  items = document.getElementsByClassName("content");
  for (i = 0; i < items.length; i++) {
    if (c == "all") {
      items[i].style.display = 'block';
      continue;
    }
    if (items[i].id != "" && items[i].id != c) {
      items[i].style.display = 'none';
    } else {
      items[i].style.display = 'block';
    }
  }
}


var btnContainer = document.getElementById("btnFilters");

if (location.href.indexOf('search.php') >= 0) {
  var btns = btnContainer.getElementsByClassName("btn");
  for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener("click", function () {
      var current = document.getElementsByClassName("active");
      current[0].className = current[0].className.replace(" active", "");
      this.className += " active";
    });
  }
}
