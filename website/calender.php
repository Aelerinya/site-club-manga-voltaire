<?php
session_start();

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

    //Récupération des évènements
    $answerCalender = $db->prepare("SELECT c.title title, c.description description, DAY(c.start_time) day_event, c.start_time start_time, c.end_time end_time, m.pseudo pseudo
      FROM calender c
      INNER JOIN members m ON m.id = c.user
      WHERE DAY(c.start_time) = DAY(c.end_time) AND MONTH(c.start_time) = ? AND YEAR(c.start_time) = ?
      ORDER BY c.start_time");
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
              $date = preg_replace('#^(.{4})\-(.{2})\-(.{2}) (.{2}):(.{2}):.{2}$#', '$3/$2/$1 de $4h$5 à ', $line["start_time"]) . preg_replace('#^.{11}(.{2}):(.{2}):.{2}$#', '$1h$2', $line['end_time']);
              ?>
              <tr>
                <td><?=$date?></td><td><?=$line['title']?></td><td><?=$line['description']?></td>
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
