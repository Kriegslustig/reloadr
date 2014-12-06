<?php

class Reloadr {
  private $filemtime_index = [];
  private $check_this_dir = '../';
  private $ignores = ['/.*\.DS_Store/', '/.*\.git.*/'];

  function __construct () {

  }

  public function set_headers ($caching = false) {
    header('Content-Type: text/event-stream');
    if(!$caching) {
      header('Cache-Control: no-cache');
    }
  }

  public function set_dir ($dir) {
    $this->check_this_dir = $dir;
  }

  public function set_listener () {
    $this->create_index($this->check_this_dir);
    $this->set_interval(500, function () {
      $this->check_for_updates($this->check_this_dir);
    });
  }

  public function set_ignores($string) {
    $this->ignores = explode(' ,', $string);
  }

  public function send_event ($data) {
    echo 'data:' . $data . PHP_EOL;
    echo PHP_EOL;
    ob_flush();
    flush();
  }

  private function reload () {
    $msg = 'reloadr: reload';
    $this->send_event($msg);
  }

  private function create_index ($where) {
    $this->for_every_file ($where, function ($abs_file_path) {
      $this->filemtime_index[$abs_file_path] = filemtime($abs_file_path);
    });
  }

  private function check_for_updates ($where) {
    $this->for_every_file ($where, function ($file) {
      $this_filemtime = filemtime($file);
      if($this_filemtime !== $this->filemtime_index[$file]) {
        $this->reload();
      }
    });
  }

  private function for_every_file ($dir, $callback) {
    $dir_scan = scandir($dir);
    foreach($dir_scan as $value) {
      if($value !== '.' && $value !== '..') {
        if(!$this->should_ignore($value)) {
          $abs_path = realpath($dir.'/'.$value);
          if(is_dir($abs_path)) {
            $this->for_every_file($abs_path, $callback);
          } else if (is_file($abs_path)) {
            $callback($abs_path);
          }
        }
      }
    }
  }

  private function should_ignore ($filepath) {
    foreach ($this->ignores as $regex) {
      $preg_match_ret = preg_match($regex, $filepath);
      if($preg_match_ret === 1) {
        return true;
      }
    }
    return false;
  }

  private function set_interval ($timeout, $callback) {
    $timeout = $timeout * 1000;
    usleep($timeout);
    while (true) {
      $callback();
      usleep($timeout);
    }
  }
}