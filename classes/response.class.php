<?php

  namespace corona_bot;

  include_once __DIR__. '/send.class.php';        // Dispatches messages
  include_once __DIR__. '/message/text.class.php';
  include_once __DIR__. '/message/postback.class.php';
  include_once __DIR__. '/message/quickReply.class.php';

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

        if (isset($input['entry'][0]['messaging'][0]['sender']['id'])) {
            $inputArray     = $input['entry'][0]['messaging'][0];
            $this->sender   = $inputArray['sender']['id'];

            $this->infosUser = $this->getUserInfo($this->sender);

            if (strpos($this->infosUser['locale'], 'ar') !== false) {
                include_once __DIR__. '/../dictionary/ar.lang.php';
            } else {
                include_once __DIR__. '/../dictionary/en.lang.php';
            }

            $this->markSeen();    // Mark message as seen to user

            $this->response = $this->checkType($inputArray);
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
          }

          return new Text($this->sender);
      }

      private function markSeen(): void
      {
          $this->Send($this->sender, '"sender_action":"mark_seen"');
      }
  }
