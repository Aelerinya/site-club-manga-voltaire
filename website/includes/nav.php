<img src="pics/button.png" alt="Button menu" id="BtMenu">

<div id="box-menu">
  <nav id="menu">
    <h4>Menu</h4>

    <ul>
      <li><a href="index.php">Acceuil</a></li>
      <li><a href="chatbox.php">Chatbox</a></li>
      <?php if (isset($_SESSION['connected'])) { ?>
      <li><a href="calender.php">Calendrier</a></li>
      <li><a href="#" id="logout">Déconnexion</a></li>
      <form id="logoutForm" action="login.php" method="post">
        <input type="hidden" name="action" value="logout">
      </form>
      <?php } else { ?>
      <li><a href="login.php">Connexion</a></li>
      <li><a href="registration.php">Inscription</a></li>
      <?php } ?>
    </ul>
  </nav>
</div>
