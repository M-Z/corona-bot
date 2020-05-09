<?php
namespace corona_bot;

include_once __DIR__.'/models/click.abstract.php';

/**
 * Handles when the user presses get started.
 */
class Nav extends Send implements Click {

    function __construct($senderID) {
      $this->Send(
        $senderID,
        '"message":{
          "attachment":{
            "type":"template",
            "payload":{
              "template_type":"button",
              "text":"'. WELCOME_MESSAGE .'",
              "buttons":[{
                  "type": "postback",
                  "title": "'. HOTLINES_TITLE .'",
                  "payload": "HOTLINES_POSTBACK"
                }, {
                  "type": "postback",
                  "title": "'. STATISTICS_TITLE .'",
                  "payload": "STATISTICS_POSTBACK"
                }, {
                  "type": "postback",
                  "title": "'. LINKS_TITLE .'",
                  "payload": "LINKS_POSTBACK"
                }]
            }
          }
        }'
      );
    }
}