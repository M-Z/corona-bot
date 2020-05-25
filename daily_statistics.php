<?php
    require_once __DIR__.'/config/secrets.php';
    require_once __DIR__.'/classes/send.class.php';
    require_once __DIR__.'/classes/subscribe.class.php';
    require_once __DIR__. '/dictionary/en.lang.php';

    /**
     * Automated cron job
     */
    class Cron extends \corona_bot\Send
    {
        public function __construct()
        {
            $this->connect();
            $this->isConnected = true;

            $subsribedUsers = $this->query("SELECT * FROM `fb_users`");
            $egyptStats = $this->getStats();
            $message = "Today's Egypt statistics 📊 "                                . "\\n" .
            "Cases: "                . $egyptStats['cases']               . "\\n" .
            "Today cases: "          . $egyptStats['todayCases']          . "\\n" .
            "Deaths: "               . $egyptStats['deaths']              . "\\n" .
            "Today Deaths: "         . $egyptStats['todayDeaths']         . "\\n" .
            "Recovered: "            . $egyptStats['recovered']           . "\\n" .
            "Active: "               . $egyptStats['active']              . "\\n" .
            "Critical: "             . $egyptStats['critical']            . "\\n" .
            "Cases per 1 million: "  . $egyptStats['casesPerOneMillion']  . "\\n" .
            "Deaths per 1 million: " . $egyptStats['deathsPerOneMillion'] . "\\n" .
            "Tests: "                . $egyptStats['tests']               . "\\n" .
            "Tests per 1 million: "  . $egyptStats['testsPerOneMillion']  . "\\n" .
            "\\nStay safe, stay home 🏡";

            $notifyMe = '"message": {
              "attachment": {
                "type":"template",
                "payload": {
                  "template_type":"one_time_notif_req",
                  "title":"'. SUBSRIBTION_HELP .'",
                  "payload":"CONFIRM_SUBSCRIBE_POSTBACK"
                }
              }
            }';

            foreach ($subsribedUsers as $user) {
                if ($user['is_subscribed']) {
                  $ID = is_null($user['fb_token']) ? $user['fb_id']: $user['fb_token'];

                  // One-time notification
                  $this->notify($ID, $message);
                  $this->notify($ID, $notifyMe);
                  $this->unsubscribe($user['fb_id']);
                  new \corona_bot\Subscribe($user['fb_id']);
                }
            }
        }

        private function getStats() {
            $ch = curl_init("https://corona.lmao.ninja/v2/countries/egypt?yesterday=false&strict=true&query");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            curl_close($ch);
            return json_decode($result, true);
        }
    }

    new Cron;
