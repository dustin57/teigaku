<?php

namespace MyApp;

class Calendar {
  public $prev;
  public $next;
  public $yearMonth;

  public function __construct() {
    try {
      if (!isset($_GET['t']) || !preg_match('/\A\d{4}-\d{2}\z/', $_GET['t'])) {
        throw new Exception();
      }
      $thisMonth = new DateTime($_GET['t']);
    } catch (Exception $e) {
      $thisMonth = new DateTime('first day of this month');
    }
    $this->prev = $this->_createPrevlink();
    $this->next = $this->_createNextlink();
    $this->yearMonth = $this->_thisMonth->format()
  }

  public function show() {

  }
}




$dt = clone $thisMonth;
$prev = $dt->modify(' -1 month')->format('Y-m');
$dt = clone $thisMonth;
$next = $dt->modify(' +1 month')->format('Y-m');

$yearMonth = $thisMonth->format('F Y');

$tail = '';
$lastDayOfPrevMonth = new DateTime('last day of' . $yearMonth . ' -1 month');
while ($lastDayOfPrevMonth->format('w') < 6) {
  $tail = sprintf('<td class="gray">%d</td>', $lastDayOfPrevMonth->format('d')) . $tail;
  $lastDayOfPrevMonth->sub(new DateInterval('P1D'));
}

$body = '';
$period = new DatePeriod(
  new DateTime('first day of' . $yearMonth),
  new DateInterval('P1D'),
  new DateTime('first day of' . $yearMonth . '+1 month')
);

$today = new DateTime('today');
foreach ($period as $day) {
  if ($day->format('w') % 7 === 0) {
    $body .= '</tr><tr>';
  }
  $todayClass = ($day->format('Y-m-d') === $today->format('Y-m-d')) ? 'today' : '';
  $body .= sprintf('<td class="youbi_%d %s">%d</td>', $day->format('w'), $todayClass, $day->format('d'));
}

$head = '';
$firstDayOfNextMonth = new DateTime('first day of' . $yearMonth . '+1 month');
while ($firstDayOfNextMonth->format('w') > 0) {
  $head .= sprintf('<td class="gray">%d</td>', $firstDayOfNextMonth->format('d'));
  $firstDayOfNextMonth->add(new DateInterval('P1D'));
}

$html = '<tr>' . $tail . $body . $head . '</tr>';
