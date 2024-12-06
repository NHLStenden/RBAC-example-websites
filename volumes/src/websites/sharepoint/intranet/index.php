<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_SharePoint_All_Users)) {
  echo "Not allowed to open the SharePoint intranet\n";
  die();
}

include_once 'lib/news-items.php';

// Sorteer de nieuwsartikelen op datum (nieuwste eerst)
usort($newsItems, function ($a, $b) {
  return strtotime($b['date']) - strtotime($a['date']);
});

// Selecteer willekeurig 6 unieke nieuwsartikelen
$randomKeys = array_rand($newsItems, 6);

$randomNews = [];
if ($rbac->has(Permission_SharePoint_News)) {
  $randomNews = array_map(function ($key) use ($newsItems) {
    return $newsItems[$key];
  }, $randomKeys);

}


?>
<html lang="NL">
<head>
    <title>Intranet - SharePoint</title>
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">

</head>
<body>
<main class="container-fluid">

    <article>
      <?= showheader(Websites::WEBSITE_SHAREPOINT, '', $rbac) ?>
        <section class="welcome" aria-label="Welcome section">
            <h1>Welkom bij ons Intranet</h1>
            <p>
                Kijk in de navigatie balk hierboven om naar de verschillende applicaties te gaan.
            </p>
        </section>
        <section class="news">
            <header>
                <h3>Nieuws</h3>
            </header>
            <div class="items">
              <?php foreach ($randomNews as $news): ?>
                  <div class="card">
                      <h2><?php echo htmlspecialchars($news['title']); ?></h2>
                      <p><span  class="datetime"><?php echo htmlspecialchars($news['date']); ?> </span>| <span class="audience"><?php echo htmlspecialchars($news['audience']); ?></span></p>
                      <div class="story-container">
                          <p class="story"><?php echo htmlspecialchars($news['content']); ?></p>
                          <?php if ($news['img'] !== '') {
                            echo  "<img src='" . $news['img'] . "' alt='" . $news['title'] . "'>";
                          }
                          ?>

                      </div>
                  </div>
              <?php endforeach; ?>
            </div>
        </section>
    </article>

</main>
</body>
</html>
