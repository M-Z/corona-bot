<?php
  namespace corona_bot;

  /**
   * The functions that the callback classes must provide
   */
  interface CallBack {
    function __construct($senderID);
    function payload($payload);  // The payload handler
  }
