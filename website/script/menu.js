var BtMenu = document.getElementById("BtMenu"),
  mainSection = document.getElementById("box-main-section"),
  menu = document.getElementById("box-menu");
var menuDisplayed = false;

BtMenu.addEventListener('click', function () {
  if (menuDisplayed) {
    mainSection.style.display = 'block';
    menu.style.display = 'none';

    menuDisplayed = !menuDisplayed;
  }
  else {
    mainSection.style.display = 'none';
    menu.style.display = 'block';

    menuDisplayed = !menuDisplayed;
  }
}, false);
