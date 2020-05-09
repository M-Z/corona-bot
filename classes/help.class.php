<?php

  namespace corona_bot;

  include_once __DIR__.'/models/click.abstract.php';

  /**
   * Handles when the user presses get started.
   */
  class Help extends Send implements Click {

    function __construct($senderID) {

      $this->typing($senderID, "on");

      $this->sendText(
          $senderID,
          HELP_DESCRIPTION.'\\n'.
          '\''.KEYWORD_SUBSCRIBE.'\': '.SUBSRIBTION_HELP.' \\n'.
          '\''.KEYWORD_UNSUBSCRIBE.'\': '.UNSUBSRIBTION_HELP.' \\n'.
          '\''.KEYWORD_EXTERNAL.'\': '.EXTERNAL_HELP.' \\n'.
          '\''.KEYWORD_HOTLINES.'\': '.HOTLINES_HELP.' \\n'.
          '\''.KEYWORD_STATISTICS.'\': '.STATISTICS_HELP.' \\n'
      );
    }

  }
