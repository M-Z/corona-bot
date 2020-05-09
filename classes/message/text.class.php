<?php
  namespace corona_bot;

  include_once __DIR__.'/../models/callback.interface.php';

  /**
   * Text message to send to the user
   */
  class Text extends Send implements CallBack {

    private $senderID;

    function __construct($senderID) {
      $this->senderID = $senderID;
    }

    /*
    * Parses the user message and replied to it.
    */
    public function payload($payload) {
      try {
        $message = $payload['message']['text'];
      } catch (\Exception $e) {
        $this->sendText($this->senderID, "Sorry, an error occured.");
        return;
      }

      $this->sendText($this->senderID, "You said, $message");
    }
  }
