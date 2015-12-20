<?php
session_start();
define('msg_limit', 40);
define('max_length_user', 20);
define('max_length_msg', 80);

include('db.php');
$db = dbInit();

if (isset($_POST['user']) && isset($_POST['msg']) && strlen($_POST['user']) > 0 && strlen($_POST['msg']) > 0) {
  setcookie('user', $_POST['user']);

  $user = substr(htmlentities($_POST['user']), 0, max_length_user);
  $msg = substr(htmlentities($_POST['msg']), 0, max_length_msg);

  $insert = $db->prepare("INSERT INTO chatbox VALUES ('', ?, NOW(), ?)");
  $insert->execute(array($user, $msg));

  header('Location: chatbox.php');
}
$ansCount = $db->prepare('SELECT COUNT(*) count FROM chatbox');
$ansCount->execute(array());
$line = $ansCount->fetch();
$count = $line['count'];
$max = $count;
$min = ($count > msg_limit) ? $min = $count - msg_limit : 0;

$ansChat = $db->prepare("SELECT * FROM chatbox ORDER BY post_date LIMIT $min, $max");
$ansChat->execute(array());

 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Chatbox - Club manga</title>
    <link rel="stylesheet" href="style/style.css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="style/chatbox.css" media="screen" title="no title" charset="utf-8">

    <script src="script/chatbox.js" charset="utf-8" defer></script>
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
        <label for="user">Nom :</label><input type="text" name="user"<?php if (isset($_COOKIE['user'])) {echo ' value="'.$_COOKIE['user'].'"';} ?>><br />
        <label for="msg">Message :</label><input type="text" name="msg" id="msg"><br />
        <input type="submit" value="Envoyer">
      </form>
    </section>
  </body>
</html>
