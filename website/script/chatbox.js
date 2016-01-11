//Complétion pseudo champ "chuchotter à"

var pseudos = document.getElementsByClassName('pseudo');
var toInput = document.getElementById("to");

for (var i = 0; i < pseudos.length; i++) {
  pseudos[i].addEventListener('click', function (e) {
    toInput.value = e.target.textContent;
  }, false);
}

//Bouton suppression pseudo

document.getElementById("rmTo").addEventListener('click', function () {
  toInput.value = '';
}, false);

//Rechargement automatique

function reload () {
  if (!document.getElementById("msg").value){
    document.location = 'chatbox.php';
  }
  else {
    setTimeout(reload, 15*1000)
  }
}

setTimeout(reload, 15*1000)

document.getElementById("msg").focus();
