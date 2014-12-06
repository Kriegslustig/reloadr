<?php

require 'reloadr.class.php';

$reloadr = new Reloadr;
$reloadr->set_headers();
$reloadr->set_dir('../../themes');
$reloadr->set_listener(__FILE__);