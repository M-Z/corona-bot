<?php
namespace corona_bot;

require_once 'models/click.interface.php';

/**
 * Handles when the user presses get started.
 */
class Links extends Send implements Click
{
    public function __construct($senderID)
    {
        $this->Send(
          $senderID,
          '"message":{
          "attachment":{
            "type":"template",
            "payload":{
              "template_type":"button",
              "text":"'. LINKS_MESSAGE .'",
              "buttons":[{
                  "type": "web_url",
                  "title": "'. FAQ_TITLE .'",
                  "url": "https://www.who.int/emergencies/diseases/novel-coronavirus-2019/question-and-answers-hub/q-a-detail/q-a-coronaviruses",
                  "webview_height_ratio": "full"
                },{
                  "type": "web_url",
                  "title": "'. GOV_SITE_TITLE .'",
                  "url": "https://www.care.gov.eg/EgyptCare/Index.aspx",
                  "webview_height_ratio": "full"
                },{
                  "type": "web_url",
                  "title": "'. COUGH_TITLE .'",
                  "url": "https://www.covid-19-sounds.org/en",
                  "webview_height_ratio": "full"
                }]
            }
          }
        }'
      );
    }
}
