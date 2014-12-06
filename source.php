<?php

require 'reloadr.class.php';

$reloadr = new Reloadr;
$reloadr->set_headers();

$dir = (
  $_GET['watch_dir'] != 0 ?
    $_GET['watch_dir']:
    '../../themes'
  );
$reloadr->set_dir($dir);

if ($_GET['ignore'] != 0 && $_GET['ignore'] !== '') {
  $reloadr->set_ignores($_GET['ignore']);
}

$reloadr->set_listener();