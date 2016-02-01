<?php
session_start();

define('rank_add_event', 2);

$msgNotConnected = false;
$msgNotAccess = false;

if (isset($_SESSION['connected']))
{
  include('../db.php');
  $db = dbInit();

  $answer = $db->prepare("SELECT club_manga FROM members WHERE id = ?");
  $answer->execute(array($_SESSION['id']));

  $line = $answer->fetch();

  if ($line['club_manga'])
  {
    //Jour, mois, année
    $curYear = date('Y');
    $curMonth = date('m');
    $curDay = date('d');

    $noEvents = true;

    if ($_SESSION['rank'] >= 2 && isset($_POST['title']) && isset($_POST['date']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['desc']))
    {
      echo $_POST['desc'];
      if (preg_match('#^\d{2}/\d{2}/\d{4}$#', $_POST['date'])
        && preg_match('#^\d{2}h\d{2}$#', $_POST['start'])
        && preg_match('#^\d{2}h\d{2}$#', $_POST['end'])
        && strlen($_POST['title']) > 0
        && strlen($_POST['desc']) > 0)
      {
        echo "2";
        $date = preg_replace('#^(\d{2})/(\d{2})/(\d{4})$#', '$3-$2-$1', $_POST['date']);
        $start = preg_replace('#^(\d{2})h(\d{2})$#', '$1:$2:00', $_POST['start']);
        $end = preg_replace('#^(\d{2})h(\d{2})$#', '$1:$2:00', $_POST['end']);

        $title = htmlentities($_POST['title']);
        $desc = htmlentities($_POST['desc']);

        $insert = $db->prepare('INSERT INTO calender VALUES ("", ?, ?, ?, ?, ?, ?)');
        $insert->execute(array($title, $date, $start, $end, $_SESSION['id'], $desc));

        header('Location: calender.php');
      }
    }

    //Récupération des évènements
    $answerCalender = $db->prepare("SELECT c.title title, c.description description, Day(c.date_event) day_event, c.date_event date_event, c.start start, c.end end, m.pseudo pseudo
      FROM calender c
      INNER JOIN members m ON m.id = c.user
      WHERE  MONTH(c.date_event) = ? AND YEAR(c.date_event) = ?
      ORDER BY c.start");
    $answerCalender->execute(array($curMonth, $curYear));


  }
  else
  {
    $msgNotAccess = true;
  }
}
else {
  $msgNotConnected = true;
}

 ?>
 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <title>Calendrier - Club manga Voltaire</title>
     <?php include_once('includes/head.php'); ?>
     <link rel="stylesheet" href="style/calender.css">
   </head>
   <body>
     <?php include('includes/header.php'); ?>

     <section id="central">
      <?php include('includes/nav.php');?>

      <div id="box-main-section">
        <section id="main-section">
          <h2>Calendrier</h2>

          <?php if ($msgNotConnected)
          { ?>
            <p>Vous devez être connecté pour accéder à cette page.</p>
          <?php }
          else if ($msgNotAccess) { ?>
            <p>Vous devez faire partie du club manga pour accéder à cette page</p>
          <?php }
          //Affichage de la page
          else
          {
            if ($_SESSION['rank'] >= rank_add_event)
            {
              ?>
                <form action="calender.php" method="post">
                  <label for="title">Titre :</label><input type="text" name="title" id="title"><br>
                  <label for="date">Date : </label><input type="text" name="date" placeholder="Ex: 29/02/2016"><br>
                  <label for="start">Heure de début : </label><input type="text" name="start" placeholder="Ex: 18h32"><br>
                  <label for="end">Heure de fin : </label><input type="text" name="end" placeholder="Ex: 18h34"><br>
                  <label for="desc">Description :</label><br>
                  <textarea id="desc" name="desc"></textarea>
                  <input type="submit" value="Envoyer">
                </form>
              <?php
            }
            ?>
              <table id="calender">
                <thead>
                  <tr>
                    <th id="dateH">Date</th><th id="titleH">Titre</th><th>Description</th>
                  </tr>
                </thead>
                <tbody>
            <?php while ($line = $answerCalender->fetch())
            {
              //Affichage des événements
              $noEvents = false;
              $date = "Le ". preg_replace('#^(.{4})\-(.{2})\-(.{2})$#', '$3/$2/$1', $line["date_event"]) . " de " . preg_replace('#^(.{2}):(.{2}):.{2}$#', '$1h$2', $line['start']) . " à " . preg_replace('#^(.{2}):(.{2}):.{2}$#', '$1h$2', $line['end']);
              $desc = preg_replace('#\n#', '<br>', $line['description']);
              ?>
              <tr>
                <td><?=$date?></td><td><?=$line['title']?></td><td><?=$desc?></td>
              </tr>
              <?php
            }
            if ($noEvents)
            {
              ?>
                <tr><td>Aucun événement ce mois-ci.</td></tr>
              <?php
            }?>
                </tbody>
              </table>
          <?php } ?>
        </section>
      </div>
    </section>
  </body>
</html>
