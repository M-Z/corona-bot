<?php
  namespace corona_bot;

  include_once __DIR__.'/../models/callback.interface.php';
  include_once __DIR__.'/../getStarted.class.php';
  include_once __DIR__.'/../nav.class.php';
  include_once __DIR__.'/../hotlines.class.php';
  include_once __DIR__.'/../links.class.php';
  include_once __DIR__.'/../statistics.class.php';

  /**
   * Handles when a button is clicked
   */
  class PostBack extends Send implements CallBack {

    private $allowedPayloads;
    private $senderID;

    function __construct($senderID) {
      $this->senderID = $senderID;
      $this->allowedPayloads = array(
        "CORONA_GETTING_STARTED_PAYLOAD", // Getting started
        "HOTLINES_POSTBACK",              // Presistent menu Hot lines
        "STATISTICS_POSTBACK",            // Presistent menu Statistics
        "LINKS_POSTBACK"                  // Important links
      );
    }

    /*
    * Parses the user message and replied to it.
    */
    public function payload($payload) {

      try {
        $payload = $payload['postback']['payload'];
      } catch (\Exception $e) {
        $this->sendText($this->senderID, "Incorrect button clicked.");
        new Nav($this->senderID);
        return;
      }

      if (in_array($payload, $this->allowedPayloads)) {
        switch ($payload) {
          case 'CORONA_GETTING_STARTED_PAYLOAD':
            new GetStarted($this->senderID);
            break;

          case 'HOTLINES_POSTBACK':
            new HotLines($this->senderID);
            break;

          case 'LINKS_POSTBACK':
            new Links($this->senderID);
            break;

          case 'STATISTICS_POSTBACK':
            new Statistics($this->senderID);
            break;

          default:
            $this->sendText($this->senderID, UNSUPPORTED_BUTTON);
            break;
        }
      } else {
        $this->sendText($this->senderID, UNSUPPORTED_BUTTON);
      }

      // new Nav($this->senderID);
    }
  }
