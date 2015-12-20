<?php
session_start();
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Chatbox - Club manga</title>
    <link rel="stylesheet" href="style.css" media="screen" title="no title" charset="utf-8">
  </head>
  <body>
    <?php include('includes/nav.php');?>

    <section id="main-section">
      <h2>Chatbox</h2>

      <div id="chatbox">

      </div>

      <form action="chatbox.php" method="post">
        <label for="user">Nom :</label><input type="text" name="user"><br />
        <label for="msg">Message :</label><input type="text" name="msg"><br />
        <input type="submit" value="Envoyer">
      </form>
    </section>
  </body>
</html>
