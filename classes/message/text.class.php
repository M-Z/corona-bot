<?php

namespace corona_bot;

include_once __DIR__.'/../models/callback.interface.php';
include_once __DIR__.'/../getStarted.class.php';
include_once __DIR__.'/../nav.class.php';
include_once __DIR__.'/../hotlines.class.php';
include_once __DIR__.'/../links.class.php';
include_once __DIR__.'/../statistics.class.php';
include_once __DIR__.'/../subscribe.class.php';
include_once __DIR__.'/../help.class.php';

/**
 * Text message to send to the user
 */
class Text extends Send implements CallBack
{
    private $senderID;
    private $allowedKeywords;

    public function __construct($senderID)
    {
        $this->senderID = $senderID;
        $this->allowedKeywords = array(
            KEYWORD_SUBSCRIBE,
            KEYWORD_UNSUBSCRIBE,
            KEYWORD_EXTERNAL,
            KEYWORD_HOTLINES,
            KEYWORD_STATISTICS,
            KEYWORD_HELP
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

            case KEYWORD_HELP:
                new Help($this->senderID);
                break;

            default:
                // Sort the similarities from nearest to farthest
                $similarity = $this->closest($message);
                $this->sendText(
                    $this->senderID,
                    $similarity ?
                    str_replace('{{word}}', "'".$this->closest($message)."'", DID_YOU_MEAN_MESSAGE):
                    DID_YOU_MEAN_INCORRECT
                );
              break;
        }
    }

    private function closest($message)
    {
        $returnVal = $this->allowedKeywords[0];
        $similarity = $this->JaroWinkler($message, $this->allowedKeywords[0]);

        foreach ($this->allowedKeywords as $keyword) {
            $newSimilarity = $this->JaroWinkler($message, $keyword);
            if ($newSimilarity > $similarity) {
                $returnVal = $keyword;
                $similarity = $newSimilarity;
            }
        }

        return $similarity < 0.5 ? false: $returnVal;
    }

    private function getCommonCharacters($string1, $string2, $allowedDistance)
    {
        $str1_len = strlen($string1);
        $str2_len = strlen($string2);
        $temp_string2 = $string2;

        $commonCharacters='';

        for ($i=0; $i < $str1_len; $i++) {
            $noMatch = true;

            // compare if char does match inside given allowedDistance
            // and if it does add it to commonCharacters
            for ($j= max(0, $i-$allowedDistance); $noMatch && $j < min($i + $allowedDistance + 1, $str2_len); $j++) {
                if ($temp_string2[$j] == $string1[$i]) {
                    $noMatch = false;

                    $commonCharacters .= $string1[$i];

                    $temp_string2[$j] = '';
                }
            }
        }

        return $commonCharacters;
    }

    private function Jaro($string1, $string2)
    {
        $str1_len = strlen($string1);
        $str2_len = strlen($string2);

        // theoretical distance
        $distance = (int) floor(min($str1_len, $str2_len) / 2.0);

        // get common characters
        $commons1 = $this->getCommonCharacters($string1, $string2, $distance);
        $commons2 = $this->getCommonCharacters($string2, $string1, $distance);

        if (($commons1_len = strlen($commons1)) == 0) {
            return 0;
        }
        if (($commons2_len = strlen($commons2)) == 0) {
            return 0;
        }

        // calculate transpositions
        $transpositions = 0;
        $upperBound = min($commons1_len, $commons2_len);
        for ($i = 0; $i < $upperBound; $i++) {
            if ($commons1[$i] != $commons2[$i]) {
                $transpositions++;
            }
        }
        $transpositions /= 2.0;

        // return the Jaro distance
        return ($commons1_len/($str1_len) + $commons2_len/($str2_len) + ($commons1_len - $transpositions)/($commons1_len)) / 3.0;
    }

    private function getPrefixLength($string1, $string2, $MINPREFIXLENGTH = 4)
    {
        $n = min(array( $MINPREFIXLENGTH, strlen($string1), strlen($string2) ));

        for ($i = 0; $i < $n; $i++) {
            if ($string1[$i] != $string2[$i]) {
                // return index of first occurrence of different characters
                return $i;
            }
        }

        // first n characters are the same
        return $n;
    }

    private function JaroWinkler($string1, $string2, $PREFIXSCALE = 0.1)
    {
        $JaroDistance = $this->Jaro($string1, $string2);

        $prefixLength = $this->getPrefixLength($string1, $string2);

        return $JaroDistance + $prefixLength * $PREFIXSCALE * (1.0 - $JaroDistance);
    }
}
