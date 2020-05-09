<?php

  namespace corona_bot;

  include_once __DIR__.'/models/click.abstract.php';

  /**
   * Handles when the user presses get started.
   */
  class Statistics extends Send implements Click {

    function __construct($senderID) {

      $this->typing($senderID, "on");

      $egyptStats = $this->getStats();

      $this->sendText(
        $senderID,
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
        "Tests per 1 million: "  . $egyptStats['testsPerOneMillion']
      );
    }

    /*
    *   Retrieves a JSON payload of Egypt's today statistics
    */
    private function getStats() {
      $ch = curl_init("https://corona.lmao.ninja/v2/countries/egypt?yesterday=false&strict=true&query");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $result = curl_exec($ch);
      curl_close($ch);
      return json_decode($result, true);
    }

  }
