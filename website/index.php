<?php  session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Club manga du lycée Voltaire</title>

    <?php include_once('includes/head.php'); ?>
  </head>
  <body>
    <?php include('includes/header.php'); ?>

    <section id="central">
      <?php include('includes/nav.php');?>

      <div id="box-main-section">
        <section id="main-section">
          <h2>Club manga</h2>

          <p>Bienvenue sur le futur site du club manga du lycée Voltaire.</p>

          <p>
            Les membres ont pour l'instant accès à :
            <ul>
              <li>Une chatbox</li>
              <li>Un calendrier des événements du club</li>
              <li><em>Bientôt</em> un forum</li>
            </ul>
          </p>
        </section>
      </div>
    </section>
  </body>
</html>
