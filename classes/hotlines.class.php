<?php

  namespace corona_bot;

  include_once __DIR__.'/models/click.abstract.php';

  /**
   * Handles when the user presses get started.
   */
  class HotLines extends Send implements Click {

    function __construct($senderID) {

      $this->typing($senderID, "on");

      $this->Send(
        $senderID,
        '"message":{
          "attachment":{
            "type":"template",
            "payload":{
              "template_type":"button",
              "text":"'. HOTLINES_TITLE .'",
              "buttons":[{
                "type":"phone_number",
                "title":"'. MINISTRY_HOTLINE .'",
                "payload":"105"
              },{
                "type":"phone_number",
                "title":"'. MINISTRY_HOTLINE_2 .'",
                "payload":"15335"
              }]
            }
          }
        }'
      );
    }

  }
