<?php session_start();

include('../db.php');
$db = dbInit();

//Récupération catégories
if (isset($_SESSION['connected']))
{
  $ansCategory = $db->prepare("SELECT id FROM forum_category
                               WHERE only_cm = 0
                                OR only_cm = (SELECT club_manga FROM members WHERE id = ?)");
  $ansCategory->execute(array($_SESSION['id']));
}
else
{
  $ansCategory = $db->query("SELECT id FROM forum_category WHERE only_cm = 0");
}

//Préparation requête thread
$ansForumPrepared  = $db->prepare("SELECT ft.id id, ft.title title, MAX(fp.post_date) lm_post_date, m.id lm_mid, m.pseudo lm_pseudo
                           FROM forum_thread ft
                           INNER JOIN forum_post fp ON fp.thread = ft.id
                           INNER JOIN members m ON fp.author = m.id
                           WHERE ft.category = ?");
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Forum - Club manga Voltaire</title>

    <?php include_once('includes/head.php'); ?>

    <link rel="stylesheet" href="style/forum.css" media="screen">
  </head>
  <body>
    <?php include('includes/header.php'); ?>

    <section id="central">
      <?php include('includes/nav.php');?>

      <div id="box-main-section">
        <section id="main-section">
            <?php
              while ($lineCategory = $ansCategory->fetch())
              {
                $ansForum = $ansForumPrepared;
                $ansForum->execute(array($lineCategory['id']));

                ?><section class="category">
                  <?php

                while ($line = $ansForum->fetch())
                {
                  ?><p><?=$line['title']?></p>
                    <aside><p><?=$line['lm_pseudo']?> :: <?=$line['lm_post_date']?></p></aside>
                    <?php
                }

                ?><section>
                  <?php
              }
            ?>
        </section>
      </div>
    </section>
  </body>
</html>
