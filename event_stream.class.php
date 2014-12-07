<?php

class Event_stream {

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
   * Sends an event to the client
   * The event is parsed by reloadr.js
   *
   * @param string $data Can be anything it will be revieved by reloadr.js
   * @uses ob_flush()
   * @uses flush()
   */
  public function send_event ($data) {
    $data = json_encode($data);
    echo 'data:' . $data . PHP_EOL;
    echo PHP_EOL;
    ob_flush();
    flush();
  }
}