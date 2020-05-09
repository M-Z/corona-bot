<?php

  if (isset($_REQUEST['hub_challenge']) && isset($_REQUEST['hub_verify_token'])) {
    $token = TOKEN;
    $verify_token = $_REQUEST['hub_verify_token'];

    if ($verify_token === $token) {
      echo $_REQUEST['hub_challenge'];
    }
  }
