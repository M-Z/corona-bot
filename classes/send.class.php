<?php

  namespace corona_bot;

  require_once 'db.class.php';

  /**
   * Sends the request
   */
  class Send extends dbQuery {

    private $webhookURL = 'https://graph.facebook.com/v7.0/me/messages?access_token=' . ACCESS_TOKEN;
    private $profileURL = 'https://graph.facebook.com/v7.0/{{sender_ID}}?fields=first_name,locale&access_token=' . ACCESS_TOKEN;

    protected function Send($senderID, $message): bool {

      $ch = curl_init($this->webhookURL);

      $jsonData = '{
        "recipient":{"id":"' . $senderID . '"},
        '. $message .'
      }';

      /* curl setting to send a json post data */
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_exec($ch); // user will get the message
      if (curl_errno($ch)) {
        return false;
      } else {
        // check the HTTP status code of the request
        $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($resultStatus !== 200) {
          return false;
        }
      }

      curl_close($ch);
      return true;
    }

    /*
    * Typing animation
    */
    protected function typing($senderID, $status): void {
      $this->Send($senderID, '"sender_action":"typing_'. $status .'"');
    }

    protected function sendText($senderID, $message) {
      $this->Send($senderID, '"message":{"text":"'.$message.'"}');
    }

    public function Notify($tokenID, $message): bool {

      $ch = curl_init($this->webhookURL);

      $jsonData = '{
        "recipient":{"one_time_notif_token":"' . $tokenID . '"},
        "message":{"text":"'. $message .'"}
      }';

      /* curl setting to send a json post data */
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_exec($ch); // user will get the message
      if (curl_errno($ch)) {
        return false;
      } else {
        // check the HTTP status code of the request
        $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($resultStatus !== 200) {
          return false;
        }
      }

      curl_close($ch);
      return true;
    }


    protected function getUserInfo($senderID) {
      $ch = curl_init(str_replace('{{sender_ID}}', $senderID, $this->profileURL));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $result = curl_exec($ch);
      curl_close($ch);
      return json_decode($result,true);
    }

    /*
    *   Saves or updates user info into our DB
    */
    protected function saveUser($userInfo) {
        $this->connect();

        if (!empty($this->query("SELECT `id` FROM `fb_users` WHERE `fb_id`=?", $userInfo['id']))) {
            $this->query(
                "UPDATE `fb_users` SET `first_name`=? AND `locale`=? WHERE `fb_id`=?",
                $userInfo['first_name'],
                $userInfo['locale'],
                $userInfo['id']
            );
        } else {
            $this->query(
                "INSERT INTO `fb_users`
                    (`fb_id`, `first_name`,`locale`)
                    VALUES(?, ?, ?)
                ",
                $userInfo['id'],
                $userInfo['first_name'],
                $userInfo['locale']
            );
        }
    }

    protected function subscribe($ID, $token) {
        $this->connect();
        $this->query("UPDATE `fb_users` SET `is_subscribed`=1, `fb_token`=? WHERE `fb_id`=?", $token, $ID);
        $this->sendText($ID, SUCCESSFULL_SUBSCRIBE);
    }

    protected function unsubscribe($ID) {
        $this->connect();
        $this->query("UPDATE `fb_users` SET `is_subscribed`=0 WHERE `fb_id`=?", $ID);
        $this->sendText($ID, SUCCESSFULL_UNSUBSCRIBE);
    }

  }
