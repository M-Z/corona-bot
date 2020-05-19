<?php

  namespace corona_bot;

  require_once 'models/click.interface.php';

  /**
   * Handles when the user presses get started.
   */
  class Subscribe extends Send implements Click {

    function __construct($senderID) {

      $this->typing($senderID, "on");

      $this->Send(
        $senderID,
        '"message": {
          "attachment": {
            "type":"template",
            "payload": {
              "template_type":"one_time_notif_req",
              "title":"'. SUBSRIBTION_HELP .'",
              "payload":"CONFIRM_SUBSCRIBE_POSTBACK"
            }
          }
        }'
      );
    }

  }
