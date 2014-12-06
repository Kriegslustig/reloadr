<?php

require 'reloadr.class.php';

$reloadr = new Reloadr;
$reloadr->set_headers();

$dir = (
  $_GET['watch_dir'] !== 0 ?
    $_GET['watch_dir']:
    '../../themes'
  );
$reloadr->set_dir($dir);

$reloadr->set_listener();