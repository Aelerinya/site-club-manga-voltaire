function reload () {
  if (!document.getElementById("msg").value){
    document.location = 'chatbox.php';
  }
  else {
    setTimeout(reload, 15*1000)
  }
}

setTimeout(reload, 15*1000)
