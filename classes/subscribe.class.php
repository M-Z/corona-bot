<?php

  namespace corona_bot;

  include_once __DIR__.'/models/click.abstract.php';

  /**
   * Handles when the user presses get started.
   */
  class Subscribe extends Send implements Click {

    function __construct($senderID) {

      $this->typing($senderID, "on");

      $this->Send(
          $senderID,
          '"messaging_type": "RESPONSE",
            "message":{
                "text": "Subsribe to daily statistics?",
                "quick_replies":[{
                    "content_type":"text",
                    "title":"'. SUBSCRIBE_TITLE .'",
                    "payload":"CONFIRM_SUBSCRIBE_POSTBACK",
                    "image_url":"https://corona-bot.000webhostapp.com/assets/images/ok.png"
                  },{
                    "content_type":"text",
                    "title":"'. UNSUBSCRIBE_TITLE .'",
                    "payload":"DECLINE_SUBSCRIBE_POSTBACK",
                    "image_url":"https://corona-bot.000webhostapp.com/assets/images/incorrect.png"
                }]
            }'
      );
    }

  }
