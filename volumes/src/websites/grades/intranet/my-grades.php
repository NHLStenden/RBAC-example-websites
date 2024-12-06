<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);

if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_Grades_Read_Own_Grades)) {
  echo "You do not have permission to access this page to view your grades.";
  die();
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blackboard - Mijn gegevns</title>
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/my-grades.css" rel="stylesheet">

</head>
<body>
<article>
  <?php echo showheader(Websites::WEBSITE_GRADES,basename(__FILE__), $rbac) ?>
    <section class="grades">
      <?php
      include_once './lib/subjects.php';

      function randomDate()
      {
        $currentDate = new DateTime();
        $startDate   = (clone $currentDate)->modify('-3 years');
        $endDate     = (clone $currentDate)->modify('-2 weeks');

        do {
          $randomTimestamp = mt_rand($startDate->getTimestamp(), $endDate->getTimestamp());
          $randomDate      = (new DateTime())->setTimestamp($randomTimestamp);
        } while ($randomDate->format('m-d') >= '07-15' && $randomDate->format('m-d') <= '09-15');

        return $randomDate->format('d F Y');
      }

      // Functie om een willekeurig cijfer te genereren met meer voldoendes dan onvoldoendes
      function randomCijfer()
      {
        $cijfer = mt_rand(1, 10);
        return $cijfer < 6 ? mt_rand(6, 10) : $cijfer;
      }

      // Functie om het collegejaar te bepalen
      function bepaalCollegejaar($datum)
      {
        $jaar  = (int)date("Y", strtotime($datum));
        $maand = (int)date("m", strtotime($datum));
        return $maand < 9 ? ($jaar - 1) . "/" . $jaar : $jaar . "/" . ($jaar + 1);
      }

      // Genereer de lijst met cijfers
      $cijfers       = [];
      $gekozenVakken = [];
      for ($i = 0; $i < 10; $i++) {
        do {
          $vak = $vakken[array_rand($vakken)];
        } while (in_array($vak, $gekozenVakken));
        $gekozenVakken[] = $vak;
        $studiepunten    = mt_rand(1, 5);
        $cijfer          = randomCijfer();
        $datum           = randomDate();
        $collegejaar     = bepaalCollegejaar($datum);
        $cijfers[]       = [
          "datum" => $datum,
          "vak" => $vak,
          "studiepunten" => $studiepunten,
          "cijfer" => $cijfer,
          "collegejaar" => $collegejaar
        ];
      }

      usort($cijfers, function ($a, $b) {
        return strtotime($a['datum']) - strtotime($b['datum']);
      });


      // Groepeer de cijfers per collegejaar
      $cijfersPerCollegejaar = [];
      foreach ($cijfers as $cijfer) {
        $cijfersPerCollegejaar[$cijfer['collegejaar']][] = $cijfer;
      }

      // Print de lijsten met cijfers per collegejaar
      foreach ($cijfersPerCollegejaar as $collegejaar => $cijfers) {
        echo "<table>";
        echo "<caption>Collegejaar $collegejaar</caption>";
        echo "<tr><th>Datum</th><th>Vak</th><th>Studiepunten</th><th>Cijfer</th></tr>";
        foreach ($cijfers as $cijfer) {
          echo "<tr>";
          echo "<td>{$cijfer['datum']}</td>";
          echo "<td>{$cijfer['vak']}</td>";
          echo "<td>{$cijfer['studiepunten']}</td>";
          echo "<td>{$cijfer['cijfer']}</td>";
          echo "</tr>";
        }
        echo "</table><br>";
      }
      ?>
    </section>
</article>
</body>
</html>
