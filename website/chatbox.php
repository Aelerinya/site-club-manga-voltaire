<?php
session_start();

include('db.php');
$db = dbInit();

if (isset($_POST['user']) && isset($_POST['msg']) && strlen($_POST['user']) > 0 && strlen($_POST['msg']) > 0) {

}

$ansChat = $db->prepare("SELECT * FROM chatbox ORDER BY post_date LIMIT 40");
$ansChat->execute(array());

 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Chatbox - Club manga</title>
    <link rel="stylesheet" href="style/style.css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="style/chatbox.css" media="screen" title="no title" charset="utf-8">
  </head>
  <body>
    <?php include('includes/nav.php');?>

    <section id="main-section">
      <h2>Chatbox</h2>

      <div id="chatbox">
        <?php while ($line = $ansChat->fetch()) {
          $date = preg_replace('#^.{11}(.{2}):(.{2}):.{2}$#', '$1:$2', $line['post_date']);
        ?>
        <p><?=$date?> : <span class="name"><?=$line['user']?></span> : <?=$line['message']?></p>
        <?php } ?>
      </div>

      <form action="chatbox.php" method="post">
        <label for="user">Nom :</label><input type="text" name="user"><br />
        <label for="msg">Message :</label><input type="text" name="msg"><br />
        <input type="submit" value="Envoyer">
      </form>
    </section>
  </body>
</html>
