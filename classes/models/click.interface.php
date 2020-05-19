<?php
  namespace corona_bot;

  /**
   * The functions when button is clicked
   */
  interface Click {
    function __construct($senderID);  // The return payload when button is click
  }
