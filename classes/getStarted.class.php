<?php

  namespace corona_bot;

  include_once __DIR__.'/models/click.abstract.php';

  /**
   * Handles when the user presses get started.
   */
  class GetStarted extends Send implements Click {

    function __construct($senderID) {

      $this->typing($senderID, "on");

      $userInfo = $this->getUserInfo($senderID);
      $this->sendText($senderID, str_replace("{{first_name}}", $userInfo['first_name'], WELCOME_MESSAGE_2));
      $this->sendText($senderID, WELCOME_MESSAGE_3);
    }

  }
