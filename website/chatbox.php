<?php
session_start();
define('msg_limit', 40);
define('max_length_msg', 255);

include('../db.php');
$db = dbInit();

// Envoi d'un message

if (isset($_SESSION['connected']) && isset($_POST['msg']) && strlen($_POST['msg']) > 0)
{
  $toValid = true;
  $msg = substr(htmlentities($_POST['msg']), 0, max_length_msg);

  if (isset($_POST['to']) && strlen($_POST['to']) > 0)
  {
    setcookie('to_pseudo', $_POST['to']);
    $answer = $db->prepare('SELECT id FROM members WHERE pseudo = ?');
    $answer->execute(array(htmlentities($_POST['to'])));

    if ($line = $answer->fetch())
    {
      $to = $line['id'];
    }
    else
    {
      $toValid = false;
    }
  }
  else
  {
    $to = '';
    setcookie('to_pseudo', '');
  }

  if ($toValid)
  {
    $insert = $db->prepare("INSERT INTO chatbox VALUES ('', ?, NOW(), ?, ?)");
    $insert->execute(array($_SESSION['id'], $msg, $to));

    header('Location: chatbox.php');
  }
}

//Récupération des messages

if (isset($_SESSION['connected']))
{
  $ansCount = $db->prepare("SELECT COUNT(*) count
                            FROM chatbox
                            WHERE to_user = '' OR to_user = ? OR user = ?
                            ORDER BY post_date");
  $ansCount->execute(array($_SESSION['id'], $_SESSION['id']));
}
else
{
  $ansCount = $db->prepare("SELECT COUNT(*) count FROM chatbox ORDER BY post_date");
  $ansCount->execute(array());
}

$line = $ansCount->fetch();
$count = $line['count'];
$max = $count;
$min = ($count > msg_limit) ? $min = $count - msg_limit : 0;

if (isset($_SESSION['connected']))
{
  $ansChat = $db->prepare("SELECT c.post_date post_date, c.message message, m.pseudo pseudo, c.to_user to_id, mTo.pseudo to_pseudo
                           FROM chatbox c
                           LEFT JOIN members mTo ON c.to_user = mTo.id
                           INNER JOIN members m ON c.user = m.id
                           WHERE c.to_user = '' OR c.to_user = ? OR c.user = ?
                           ORDER BY c.post_date
                           LIMIT $min, $max");
  $ansChat->execute(array($_SESSION['id'], $_SESSION['id']));
}
else
{
  $ansChat = $db->prepare("SELECT c.post_date post_date, c.message message, m.pseudo pseudo
                           FROM chatbox c
                           INNER JOIN members m ON c.user = m.id
                           ORDER BY c.post_date
                           LIMIT $min, $max");
  $ansChat->execute(array());
}

 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Chatbox - Club manga Voltaire</title>
    <link rel="stylesheet" href="style/chatbox.css" media="screen" title="no title">

    <?php include_once('includes/head.php'); ?>

    <script src="script/chatbox.js" charset="utf-8" defer></script>
  </head>
  <body>
    <?php include('includes/header.php'); ?>

    <section id="central">
      <?php include('includes/nav.php');?>

      <div id="box-main-section">
        <section id="main-section">
          <h2>Chatbox</h2>

          <div id="chatbox">
            <?php //Affichage des messages

            while ($line = $ansChat->fetch()) {
              $date = preg_replace('#^.{11}(.{2}):(.{2}):.{2}$#', '$1:$2', $line['post_date']);

              if (isset($_SESSION['connected']) && $line['to_id']) {
                if ($line['to_id'] == $_SESSION['id']) {
                  $pseudo = '<span class="name pseudo">'.$line["pseudo"].'</span> vous chuchotte';
                } else {
                  $pseudo = 'Vous chuchottez à <span class="name pseudo">'.$line["to_pseudo"].'</span>';
                }

              }
              else {
                $pseudo = '<span class="name pseudo">'.$line["pseudo"].'</span>';
              }
            ?>
            <p><?=$date?> : <?=$pseudo?> : <?=$line['message']?></p>
            <?php } ?>
          </div>

          <?php //Affichage du formulaire d'envoi d'un message si connecté

          if (isset($_SESSION['connected'])) { ?>
          <form action="chatbox.php" method="post">
            <label for="msg">Message :</label><input type="text" name="msg" id="msg"><br/>
            <label for="to">Chuchotter à :</label><input type="text" name="to" id="to"<?php echo (isset($_COOKIE['to_pseudo']) && $_COOKIE['to_pseudo'] != '') ? 'value="'.$_COOKIE['to_pseudo'].'"' : ''; ?>><span id="rmTo">[x]</span>
            <?php if (isset($toValid) && !$toValid) {echo "Cette personne n'existe pas.";}?><br>
            <input type="submit" value="Envoyer">
          </form>
          <?php } else { ?>
            <p>Vous devez être connecté pour utiliser la chatbox.</p>
          <?php } ?>
        </section>
      </div>
    </section>
  </body>
</html>
