<?php
session_start();
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Inscription - Club manga</title>
    <link rel="stylesheet" href="style/style.css" media="screen" title="no title" charset="utf-8">
  </head>
  <body>
    <?php include('includes/nav.php');?>

    <section id="main-section">
      <h2>Inscription</h2>

      <form action="index.html" method="post">
        <label for="name">Nom :</label><br>
        <input type="text" name="name"><br>

        <label for="email1">Email :</label><br>
        <input type="text" name="email1"><br>

        <label for="email2">Confirmez votre email :</label><br>
        <input type="text" name="email2"><br>

        <label for="pwd1">Mot de passe :</label><br>
        <input type="text" name="pwd1"><br>

        <label for="pwd2">Confirmez votre mot de passe :</label><br>
        <input type="text" name="pwd2"><br>

        <input type="submit" value="S'inscrire"><input type="reset" value="Annuler">
      </form>
    </section>
  </body>
</html>
