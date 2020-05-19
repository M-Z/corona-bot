<?php

  namespace corona_bot;

  require_once 'models/click.interface.php';

  /**
   * Handles when the user presses get started.
   */
  class GetStarted extends Send implements Click
  {
      public function __construct($senderID)
      {
          $userInfo = $this->getUserInfo($senderID);
          $this->saveUser($userInfo);

          $this->typing($senderID, "on");

          $this->sendText($senderID, str_replace("{{first_name}}", $userInfo['first_name'], WELCOME_MESSAGE_2));
          $this->sendText($senderID, WELCOME_MESSAGE_3);
      }
  }
