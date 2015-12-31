<?php
session_start();

$displayWelcomeMsg = false;

$errSamePwd = false;
$errSameEmail = false;
$errPseudo = false;
$errPwd = false;
$errEmail = false;
$errUsedPseudo = false;
$errUsedEmail = false;

if (!isset($_SESSION['connected']) && isset($_POST['pseudo']) && isset($_POST['email1']) && isset($_POST['email2']) && isset($_POST['pwd1']) && isset($_POST['pwd2']))
{
  $errSamePwd = ($_POST['pwd1'] == $_POST['pwd2']) ? false : true;
  $errSameEmail = ($_POST['email1'] == $_POST['email2']) ? false : true;

  $errPseudo = (strlen($_POST['pseudo']) >= 3 && strlen($_POST['pseudo']) <= 20) ? false : true;
  $errPwd = (strlen($_POST['pwd1']) >= 6 && strlen($_POST['pwd1']) <= 255) ? false : true;
  $errEmail = (preg_match('#^[a-z0-9_.-]+@[a-z0-9_.-]{2,}\.[a-z]{2,4}$#', $_POST['email1'])) ? false : true;

  include_once('db.php');
  $db = dbInit();

  $answer = $db->prepare('SELECT id FROM members WHERE pseudo = ?');
  $answer->execute(array(htmlentities($_POST['pseudo'])));
  $errUsedPseudo = ($answer->fetch()) ? true : false;

  $answer = $db->prepare('SELECT id FROM members WHERE email = ?');
  $answer->execute(array(htmlentities($_POST['email1'])));
  $errUsedEmail = ($answer->fetch()) ? true : false;

  if (!$errSamePwd && !$errSameEmail && !$errPseudo && !$errPwd && !$errEmail && !$errUsedPseudo && !$errUsedEmail)
  {
    $pseudo = htmlentities($_POST['pseudo']);
    $email = htmlentities($_POST['email1']);
    $pwd = password_hash($_POST['pwd1'], PASSWORD_DEFAULT);

    $insertUser = $db->prepare("INSERT INTO members (pseudo, email, registration_date, password) VALUES (?, ?, NOW(), ?)");
    $insertUser->execute(array($pseudo, $email, $pwd));

    $id = $db->lastInsertId();
    $key = md5(uniqid(rand(), true));
    $activationUrl = "http://exemple.exemple/activation.php?key=$key";

    $insertEmailActivation = $db->prepare("INSERT INTO email_activation VALUES (?, 0, ?)");
    $insertEmailActivation->execute(array($id, $key));

    /*mail($email, 'Validation de votre adresse email',
    "Cliquez sur ce lien pour activer votre adresse email.\n$activationUrl");**/

    $displayWelcomeMsg = true;
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

<?php if ($displayWelcomeMsg)
{
 ?>
      <p>Inscription réussie. <?php /* Activez votre adresse email avec le lien présent dans le mail qui vous a été envoyé. */ ?></p>
<?php
}
else if (isset($_SESSION['connected']))
{
  ?>
  <p>Vous êtes déjà connecté. Vous n'avez donc pas besoin de vous créer un compte ;)<p>
  <?php
}
else
{ ?>
      <h2>Inscription</h2>

      <form action="registration.php" method="post">
        <label for="pseudo">Pseudo :</label><br>
        <input type="text" name="pseudo" maxlength="20"><?php if ($errPseudo) {echo "<span class=\"error\">Votre pseudo doit faire entre 3 et 20 caractères.</span>";} else if ($errUsedPseudo) {echo "<span class=\"error\">Ce pseudo est déjà utilisé.</span>";}?><br>

        <label for="email1">Email :</label><br>
        <input type="email" name="email1" maxlength="255"><?php if ($errEmail) {echo "<span class=\"error\">Votre adresse email n'est pas conforme.</span>";} else if ($errUsedEmail) {echo "<span class=\"error\">Cet adresse email est déjà utilisée.</span>";}?><br>

        <label for="email2">Confirmez votre email :</label><br>
        <input type="email" name="email2" maxlength="255" autocomplete="off"><?php if ($errSameEmail) {echo "<span class=\"error\">Les deux adresses emails ne sont pas identiques.</span>";}?><br>

        <label for="pwd1">Mot de passe :</label><br>
        <input type="password" name="pwd1" maxlength="255"><?php if ($errPwd) {echo "<span class=\"error\">Votre mot de passe doit faire au moins 6 caractères.</span>";}?><br>

        <label for="pwd2">Confirmez votre mot de passe :</label><br>
        <input type="password" name="pwd2" maxlength="255" autocomplete="off"><?php if ($errSamePwd) {echo "<span class=\"error\">Les deux mots de passe ne sont pas identiques.</span>";}?><br>

        <input type="submit" value="S'inscrire" maxlength="255"><input type="reset" value="Annuler">
      </form>
<?php
}
 ?>
    </section>
  </body>
</html>
