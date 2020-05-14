<?php

session_start();

require_once(__DIR__ . '/Subscriptions.php');

$listApp = new \MyApp\Subscriptions();
$lists = $listApp->getAll();
$sum = number_format((int)$listApp->getSum());
$avg = number_format((int)$listApp->getAvg());
$count = number_format((int)$listApp->getCount());

function h($s) {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

?>
 <!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>TEIGAKU</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <header>
      <h1>TEIGAKU</h1>
    </header>
    <div class="calcArea">
      <div class="sum">
        <span>sum&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;¥&nbsp;</span>
        <span id="sum"><?= h($sum); ?></span>
      </div>
      <div class="count-avg">
        <span>(&nbsp;quantity&nbsp;:&nbsp;&nbsp;&nbsp;</span>
        <span id="count"><?= h($count) . "  ," ?></span>
        <span>avarage&nbsp;:&nbsp;&nbsp;&nbsp;¥&nbsp;</span>
        <span id="avg"><?= h($avg); ?></span>
        <span>&nbsp;)</span>
      </div>
    </div>
    <div class="btnArea">
      <button id="addBtn"><i class="fa fa-caret-right"></i> add</button>
      <button id="deleteBtn"><i class="fa fa-caret-right"></i> delete</button>
    </div>
    <div class="listArea">
      <ul id="lists">
        <?php foreach ($lists as $list): ?>
          <li id="subsc_<?= h($list->id); ?>" data-id="<?= h($list->id); ?>">
            <div class="subsc">
              <span class="title"><?= h($list->title); ?></span>
              <span class="price"><?= "¥ " . number_format(h($list->price)); ?></span>
            </div>
            <div class="xxx hidden">×</div>
          </li>
        <?php endforeach; ?>
          <li id="template" data-id="">
            <div class="subsc">
              <span class="title"></span>
              <span class="price"></span>
            </div>
            <div class="xxx hidden">×</div>
          </li>
      </ul>
    </div>
    <form id="new_subsc_form" class="hidden" action="">
      <p>new subscription</p>
      <input id="new_title" type="text" placeholder="title" required><br>
      <input id="new_price" type="number" placeholder="price" required><br>
      <input type="submit" value="add">
    </form>
    <div id="mask" class="hidden"></div>
    <footer>©︎ TEIGAKU</footer>
  </div>
  <input type="hidden" id="token" value="<?= h($_SESSION['token']); ?>">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="subscriptions.js"></script>
  
</body>
</html>
