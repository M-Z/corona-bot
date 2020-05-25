<?php
  namespace corona_bot;

  require_once __DIR__. '/../models/callback.interface.php';

  /**
   * Handles when a button is clicked
   */
  class Notification extends Send implements CallBack
  {
      private $allowedPayloads;
      private $senderID;

      public function __construct($senderID)
      {
          $this->senderID = $senderID;
          $this->allowedPayloads = array(
            "CONFIRM_SUBSCRIBE_POSTBACK",     // User confirmed the subscribtion
          );
      }

      /*
      * Parses the user message and replied to it.
      */
      public function payload($payload)
      {
          try {
              $payload = $payload['optin'];
          } catch (\Exception $e) {
              $this->sendText($this->senderID, "Incorrect button clicked.");
              new Nav($this->senderID);
              return;
          }

          if (in_array($payload['payload'], $this->allowedPayloads)) {
              switch ($payload['payload']) {
                case 'CONFIRM_SUBSCRIBE_POSTBACK':
                    $this->Subscribe($this->senderID, $payload['one_time_notif_token']);
                    break;

                default:
                    $this->sendText($this->senderID, UNSUPPORTED_BUTTON);
                    break;
            }
          } else {
            $this->sendText($this->senderID, "2");

              $this->sendText($this->senderID, UNSUPPORTED_BUTTON);
          }

          new Nav($this->senderID);
      }
  }
