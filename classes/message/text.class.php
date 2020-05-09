<?php
  namespace corona_bot;

  include_once __DIR__.'/../models/callback.interface.php';
  include_once __DIR__.'/../getStarted.class.php';
  include_once __DIR__.'/../nav.class.php';
  include_once __DIR__.'/../hotlines.class.php';
  include_once __DIR__.'/../links.class.php';
  include_once __DIR__.'/../statistics.class.php';
  include_once __DIR__.'/../subscribe.class.php';

  /**
   * Text message to send to the user
   */
  class Text extends Send implements CallBack
  {
      private $senderID;

      public function __construct($senderID)
      {
          $this->senderID = $senderID;
          $allowedKeywords = array(
          KEYWORD_SUBSCRIBE,
          KEYWORD_UNSUBSCRIBE,
          KEYWORD_EXTERNAL,
          KEYWORD_HOTLINES,
          KEYWORD_STATISTICS
      );
      }

      /*
      * Parses the user message and replied to it.
      */
      public function payload($payload)
      {
          try {
              $message = $payload['message']['text'];
          } catch (\Exception $e) {
              $this->sendText($this->senderID, "Sorry, an error occured.");
              return;
          }

          switch ($message) {
            case KEYWORD_SUBSCRIBE:
                $this->Subscribe($this->senderID);
                break;

            case KEYWORD_UNSUBSCRIBE:
                $this->Unsubscribe($this->senderID);
                break;

            case KEYWORD_EXTERNAL:
                new Links($this->senderID);
                break;

            case KEYWORD_HOTLINES:
                new HotLines($this->senderID);
                break;

            case KEYWORD_STATISTICS:
                new Statistics($this->senderID);
                break;

          default:
              $this->sendText($this->senderID, "Did you mean ");
              break;
      }
      }
  }
