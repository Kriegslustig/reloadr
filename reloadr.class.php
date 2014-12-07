<?php

class Reloadr {
  private $filemtime_index = [];
  private $check_this_dir = '../';
  private $ignores = ['/.*\.DS_Store/', '/.*\.git.*/'];

  /*
   * Adds the needed headers for event-streams
   * Caching can intefeere with event-streams
   *
   * @uses header()
   * @param bool $caching Wether or not caching should be active
   */
  public function set_headers ($caching = false) {
    header('Content-Type: text/event-stream');
    if(!$caching) {
      header('Cache-Control: no-cache');
    }
  }

  /*
   * Sets the directory to watch
   *
   * @param string $dir The path (relative or absolute) to the dir to watch
   *
   * @uses reloadr::check_this_dir
   */
  public function set_dir ($dir) {
    $this->check_this_dir = $dir;
  }

  /*
   * Sets the file watcher
   *
   * @uses reloadr::create_index
   * @uses reloadr::set_interval
   * @uses reloadr::check_for_updates
   */
  public function set_listener () {
    $this->create_index($this->check_this_dir);
    $this->set_interval(500, function () {
      $this->check_for_updates($this->check_this_dir);
    });
  }

  /*
   * Set the ignore array
   *
   * @param regex array $string Contains all regex to be ignored
   * @var regex array Structure: /regex/, /regex/
   * @uses reloadr::ignores
   * @uses explode()
   */
  public function set_ignores($string) {
    $this->ignores = explode(', ', $string);
  }

  /*
   * Sends an event to the client
   * The event is parsed by reloadr.js
   *
   * @param string $data Can be anything it will be revieved by reloadr.js
   * @uses ob_flush()
   * @uses flush()
   */
  public function send_event ($data) {
    echo 'data:' . $data . PHP_EOL;
    echo PHP_EOL;
    ob_flush();
    flush();
  }

  /*
   * This sends the reload command to the client
   *
   * @uses reloadr::send_event
   */
  private function reload () {
    $msg = 'reloadr: reload';
    $this->send_event($msg);
  }

  /*
   * Indexes all files in the directory and writes it into reloadr::filemtime_index
   *
   * @param path $where Defines the directory to index
   * @type path A relative or absolute path
   * @uses reloadr::for_every_file
   * @uses reloadr::$filemtime_index
   * @uses filemtime()
   */
  private function create_index ($where) {
    $this->for_every_file ($where, function ($abs_file_path) {
      $this->filemtime_index[$abs_file_path] = filemtime($abs_file_path);
    });
  }

  /*
   * Checks all filemtimes against the recorded ones
   *
   * @param path $where Defines the directory to check
   * @type path A relative or absolute path
   * @uses reloadr::for_every_file
   * @uses reloadr::reload
   * @uses reloadr::$filemtime_index
   * @uses filemtime
   */
  private function check_for_updates ($where) {
    $this->for_every_file ($where, function ($file) {
      $this_filemtime = filemtime($file);
      if($this_filemtime !== $this->filemtime_index[$file]) {
        $this->reload();
      }
    });
  }

  /*
   * Loops through a directory recursively and executes a callback on every file
   *
   * @param path $dir The directory to loop through
   * @param anonymous function $callback Is executed on every file
   *
   *     $callback($abs_path);
   *
   * @uses reloadr::should_ignore
   * @uses reloadr::for_every_file
   * @uses scandir()
   * @uses realpath()
   * @uses is_dir()
   * @uses is_file()
   * @uses $callback()
   */
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

  /*
   * Checks if a file or directory should be ignored
   *
   * @param path $filepath An absolute filepath
   *
   * @uses this::$ignores
   * @uses preg_match()
   * @return bool
   */
  private function should_ignore ($filepath) {
    foreach ($this->ignores as $regex) {
      $preg_match_ret = preg_match($regex, $filepath);
      if($preg_match_ret === 1) {
        return true;
      }
    }
    return false;
  }

  /*
   * Sets an infinite loop in which a callback is calle
   *
   * @param miliseconds $timeout
   * @param anonymous function $callback
   * @uses $callback()
   * @uses usleep()
   */
  private function set_interval ($timeout, $callback) {
    $timeout = $timeout * 1000;
    while (true) {
      usleep($timeout);
      $callback();
    }
  }
}