<?php
  namespace corona_bot;

  include_once __DIR__.'/../models/callback.interface.php';

  /**
   * Handles when a button is clicked
   */
  class QuickReply extends Send implements CallBack
  {
      private $allowedPayloads;
      private $senderID;

      public function __construct($senderID)
      {
          $this->senderID = $senderID;
          $this->allowedPayloads = array(
            "CONFIRM_SUBSCRIBE_POSTBACK",     // User confirmed the subscribtion
            "DECLINE_SUBSCRIBE_POSTBACK",     // User declined the subscribtion
          );
      }

      /*
      * Parses the user message and replied to it.
      */
      public function payload($payload)
      {
          try {
              $payload = $payload['message']['quick_reply']['payload'];
          } catch (\Exception $e) {
              $this->sendText($this->senderID, "Incorrect button clicked.");
              new Nav($this->senderID);
              return;
          }

          if (in_array($payload, $this->allowedPayloads)) {
              switch ($payload) {
                case 'CONFIRM_SUBSCRIBE_POSTBACK':
                    $this->Subscribe($this->senderID);
                    break;

                case 'DECLINE_SUBSCRIBE_POSTBACK':
                    $this->Unsubscribe($this->senderID);
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
