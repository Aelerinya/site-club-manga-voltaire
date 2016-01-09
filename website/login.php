<?php
session_start();

$displayLoginMsg = false;
$displayLogoutMsg = false;

$error = false;

if (!isset($_SESSION['connected']) && isset($_POST['pseudo']) && $_POST['pseudo'] && isset($_POST['pwd']) && $_POST['pwd'])
{
  $pseudo = htmlentities($_POST['pseudo']);
  $pwd = $_POST['pwd'];

  include('../db.php');
  $db = dbInit();

  $answer = $db->prepare("SELECT id, pseudo, password FROM members WHERE pseudo = ?");
  $answer->execute(array($pseudo));

  $error = ($line = $answer->fetch()) ? false : true;

  if (!$error)
  {
    $error = (password_verify($pwd, $line['password'])) ? false : true;

    if (!$error)
    {
      $_SESSION['connected'] = true;
      $_SESSION['id'] = $line['id'];
      $_SESSION['pseudo'] = $line['pseudo'];
      $displayLoginMsg = true;
    }
  }
}
else if (isset($_POST['action']) && $_POST['action'] == 'logout' && isset($_SESSION['connected']))
{
  unset($_SESSION['connected']);
  unset($_SESSION['id']);
  $displayLogoutMsg = true;
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Inscription - Club manga Voltaire</title>

    <?php include_once('includes/head.php'); ?>
  </head>
  <body>
    <?php include('includes/nav.php');?>

    <section id="main-section">
<?php

if ($displayLoginMsg)
{
  ?>
    <p>Vous êtes connecté <span class="name"><?= $_SESSION['pseudo']?></span>.</p>
  <?php
}
else if ($displayLogoutMsg)
{
  ?>
    <p>Au revoir <span class="name"><?= $_SESSION['pseudo']?></span>.</p>
  <?php
  unset($_SESSION['pseudo']);
}
else if (isset($_SESSION['connected']))
{
  ?>
    <p>Vous êtes déjà connecté <span class="name"><?= $_SESSION['pseudo']?></span>.</p>
  <?php
}
else
{
  ?>
    <h2>Connexion</h2>

    <form action="login.php" method="post">
      <label for="pseudo">Pseudo :</label><input type="text" name="pseudo" id="pseudo"><br>
      <label for="pwd">Mot de passe :</label><input type="password" name="pwd" id="pwd"><br>
      <input type="submit" value="Connexion"><input type="reset" value="Annuler">
    </form>
    <?php  if ($error) { ?>
      <p>Mauvais mot de passe ou pseudo.</p>
    <?php } ?>
  <?php
}
?>
    </section>
  </body>
</html>
