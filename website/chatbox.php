<?php
session_start();
define('msg_limit', 40);
define('max_length_msg', 80);

include('db.php');
$db = dbInit();

// Envoi d'un message

if (isset($_SESSION['connected']) && isset($_POST['msg']) && strlen($_POST['msg']) > 0)
{
  $msg = substr(htmlentities($_POST['msg']), 0, max_length_msg);

  $insert = $db->prepare("INSERT INTO chatbox VALUES ('', ?, NOW(), ?)");
  $insert->execute(array($_SESSION['id'], $msg));

  header('Location: chatbox.php');
}

//Récupération des messages

$ansCount = $db->prepare('SELECT COUNT(*) count FROM chatbox');
$ansCount->execute(array());
$line = $ansCount->fetch();
$count = $line['count'];
$max = $count;
$min = ($count > msg_limit) ? $min = $count - msg_limit : 0;

$ansChat = $db->prepare("SELECT c.post_date post_date, c.message message, m.pseudo pseudo
                         FROM chatbox c
                         INNER JOIN members m ON c.user = m.id
                         ORDER BY c.post_date
                         LIMIT $min, $max");
$ansChat->execute(array());

 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Chatbox - Club manga</title>
    <link rel="stylesheet" href="style/style.css" media="screen" title="no title">
    <link rel="stylesheet" href="style/chatbox.css" media="screen" title="no title">

    <script src="script/chatbox.js" charset="utf-8" defer></script>
  </head>
  <body>
    <?php include('includes/nav.php');?>

    <section id="main-section">
      <h2>Chatbox</h2>

      <div id="chatbox">
        <?php //Affichage des messages

        while ($line = $ansChat->fetch()) {
          $date = preg_replace('#^.{11}(.{2}):(.{2}):.{2}$#', '$1:$2', $line['post_date']);
        ?>
        <p><?=$date?> : <span class="name"><?=$line['pseudo']?></span> : <?=$line['message']?></p>
        <?php } ?>
      </div>

      <?php //Affichage du formulaire d'envoi d'un message si connecté

      if (isset($_SESSION['connected'])) { ?>
      <form action="chatbox.php" method="post">
        <label for="msg">Message :</label><input type="text" name="msg" id="msg"><br />
        <input type="submit" value="Envoyer">
      </form>
      <?php } else { ?>
        <p>Vous devez être connecté pour utiliser la chatbox.</p>
      <?php } ?>
    </section>
  </body>
</html>
