<?php
session_start();

$displayLoginMsg = false;

if ($displayLoginMsg)
{
  
}
else
{
  ?>
    <h2>Connexion</h2>

    <form action="login.php" method="post">
      <label for="pseudo">Pseudo :</label><input type="text" name="pseudo">
      <label for="pwd">Mot de passe :</label><input type="password" name="pwd">
      <input type="submit" value="Connexion"><input type="reset" value="Annuler">
    </form>
  <?php
}
?>
