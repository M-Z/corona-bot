<?php
  namespace corona_bot;

  require_once __DIR__. '/../models/callback.interface.php';
  require_once __DIR__. '/../getStarted.class.php';
  require_once __DIR__. '/../nav.class.php';
  require_once __DIR__. '/../hotlines.class.php';
  require_once __DIR__. '/../links.class.php';
  require_once __DIR__. '/../statistics.class.php';
  require_once __DIR__. '/../subscribe.class.php';
  require_once __DIR__. '/../help.class.php';

  /**
   * Handles when a button is clicked
   */
  class PostBack extends Send implements CallBack
  {
      private $allowedPayloads;
      private $senderID;

      public function __construct($senderID)
      {
          $this->senderID = $senderID;
          $this->allowedPayloads = array(
            "CORONA_GETTING_STARTED_PAYLOAD", // Getting started
            "HOTLINES_POSTBACK",              // Presistent menu Hot lines
            "STATISTICS_POSTBACK",            // Presistent menu Statistics
            "LINKS_POSTBACK",                 // Important links
            "SUBSCRIBE_POSTBACK",             // Subsribe to statistics
            "CONFIRM_SUBSCRIBE_POSTBACK",     // User confirmed the subscribtion
            "DECLINE_SUBSCRIBE_POSTBACK",     // User declined the subscribtion
            "HELP_POSTBACK"                   // Show all commands
          );
      }

      /*
      * Parses the user message and replied to it.
      */
      public function payload($payload)
      {
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

                case 'SUBSCRIBE_POSTBACK':
                    new Subscribe($this->senderID);
                    return;

                case 'HELP_POSTBACK':
                    new Help($this->senderID);
                    break;

                default:
                    $this->sendText($this->senderID, UNSUPPORTED_BUTTON);
                    break;
            }
        } else {
            $this->sendText($this->senderID, UNSUPPORTED_BUTTON);
        }

        new Nav($this->senderID);
      }
  }
