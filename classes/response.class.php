<?php

  namespace corona_bot;

  require_once 'send.class.php';            // Dispatches messages
  require_once 'message/text.class.php';
  require_once 'message/postback.class.php';
  require_once 'message/notification.class.php';
  require_once 'message/quickReply.class.php';

  /**
   * Receives the payload and parses it
   */
  class Response extends Send
  {
    protected $sender; // sender's fb ID
    private $message; // the returned message

    public function __construct()
    {

      /* receive messages */
        try {
            $input = json_decode(file_get_contents('php://input'), true);
        } catch (\Exception $e) {
            die("Error occured.");  // Error occured while parsing the JSON
        }

        if (isset($input['entry'][0]['messaging'][0]['sender']['id']) || isset($input['sender']['id'])) {
            $inputArray =
              isset($input['entry'][0]['messaging'][0]['sender']['id']) ?
                $input['entry'][0]['messaging'][0]:
                $input['sender']['id'];

            $this->sender     = $inputArray['sender']['id'];

            // Try loading the user's preferred language
            try {
              $this->infosUser = $this->getUserInfo($this->sender);

              if (strpos($this->infosUser['locale'], 'ar') !== false) {
                  require_once __DIR__. '/../dictionary/ar.lang.php';
              } else {
                  require_once __DIR__. '/../dictionary/en.lang.php';
              }

            } catch (\Exception $e) {
              require_once __DIR__. '/../dictionary/en.lang.php';
            }

            $this->markSeen();    // Mark message as seen to user

            $this->response   = $this->checkType($inputArray);
            $this->response->payload($inputArray);
        }
    }

      /*
      * Checks type of webhook received
      */
      private function checkType($type)
      {
          if (isset($type["postback"])) {
              return new PostBack($this->sender);
          } elseif (isset($type['message']['quick_reply'])) {
              return new QuickReply($this->sender);
          } elseif (isset($type['optin']['type']) && $type['optin']['type'] == 'one_time_notif_req') {
              return new Notification($this->sender);
          }

          return new Text($this->sender);
      }

      private function markSeen(): void
      {
          $this->Send($this->sender, '"sender_action":"mark_seen"');
      }
  }
