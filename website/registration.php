<?php
session_start();

$errSamePwd = false;
$errSameEmail = false;
$errName = false;
$errPwd = false;
$errEmail = false;
$errUsedName = false;
$errUsedEmail = false;

if (isset($_POST['name']) && isset($_POST['email1']) && isset($_POST['email2']) && isset($_POST['pwd1']) && isset($_POST['pwd2']))
{
  $errSamePwd = ($_POST['pwd1'] == $_POST['pwd2']) ? false : true;
  $errSameEmail = ($_POST['email1'] == $_POST['email2']) ? false : true;

  $errName = (strlen($_POST['name']) >= 3 && strlen($_POST['name']) <= 20) ? false : true;
  $errPwd = (strlen($_POST['pwd1']) >= 6 && strlen($_POST['pwd1']) <= 255) ? false : true;
  $errEmail = (preg_match('#^[a-z0-9_.-]+@[a-z0-9_.-]{2,}\.[a-z]{2,4}$#', $_POST['email1'])) ? false : true;

  include_once('db.php');
  $db = dbInit()

  $answer = $db->prepare('SELECT id FROM members WHERE name = ?');
  $answer->execute(array(htmlentities($_POST['name'])));
  $errUsedName = ($answer->fetch()) ? true : false;

  $answer = $db->prepare('SELECT id FROM members WHERE email = ?');
  $answer->execute(array(htmlentities($_POST['email1'])));
  $errUsedEmail = ($answer->fetch()) ? true : false;

  if (!$errSamePwd && !$errSameEmail && !$errName && !$errPwd && !$errEmail && !$errUsedName && !$errUsedEmail)
  {
    $name = htmlentities($_POST['name']);
    $email = htmlentities($_POST['email1']);
    $pwd = password_hash($_POST['pwd1'], PASSWORD_DEFAULT);

    $insert = $db->prepare("INSERT INTO members (name, email, registration_date, password) VALUES (?, ?, NOW(), ?)");
    $insert->execute(array($name, $email, $pwd));
  }
}

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

      <form action="registration.php" method="post">
        <label for="name">Nom :</label><br>
        <input type="text" name="name"><br>

        <label for="email1">Email :</label><br>
        <input type="email" name="email1"><br>

        <label for="email2">Confirmez votre email :</label><br>
        <input type="email" name="email2"><br>

        <label for="pwd1">Mot de passe :</label><br>
        <input type="password" name="pwd1"><br>

        <label for="pwd2">Confirmez votre mot de passe :</label><br>
        <input type="password" name="pwd2"><br>

        <input type="submit" value="S'inscrire"><input type="reset" value="Annuler">
      </form>
    </section>
  </body>
</html>
