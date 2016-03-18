<?php

namespace Magium\Extractors;

class DateTime extends AbstractExtractor
{

    protected $text;
    protected $dateString;

    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    public function getDateString()
    {
        return $this->dateString;
    }

    public function extract()
    {
        $matchedParts = [
            'month'     => null,
            'day'       => null,
            'year'      => null,
            'hour'      => null,
            'minute'    => null,
            'seconds'  =>  null,
            'timezone'  => null,
            'meridiem'  => null,
        ];
        $text = $this->text;
        // replace all non-whitespace with space
        $text = preg_replace('/[^\w:\/\\-]+/', ' ', $text);
        // normalize spaces
        $text = preg_replace('/\s+/', ' ', $text);
        $parts = explode(' ', $text);
        foreach ($parts as $key => $part) {
            foreach ($matchedParts as $term => $value) {
                /*
                 * We re-run the test method each time because Dec 01 01:01:01 could match only the first 01.  So we
                 * have to be a bit greedy here.
                 */
                $fn = 'test' . ucfirst($term);
                if ($this->$fn($part) && $matchedParts[$term] === null) {
                    $matchedParts[$term] = $key;
                }

            }
        }
        $max = false;
        $min = PHP_INT_MAX;
        foreach ($matchedParts as $part) {
            if ($part === null) continue;
            if ($part >= $max) {
                $max = $part;
            }
            if ($part <= $min) {
                $min = $part;
            }
        }
        if ($max === false) {
            // Didn't find a date
            return;
        }
        $foundDate = '';
        for ($i = (int)$min; $i <= (int)$max; $i++) {
            $foundDate .= ' ' . $parts[$i];
        }
        $parse = date_parse(trim($foundDate));
        if (isset($parse['error_count']) && $parse['error_count'] == 0) {
            $start = strpos($this->text, $parts[$min]);
            $end = strpos($this->text, $parts[$max]) + strlen($parts[$max]);
            $this->dateString = substr($this->text, $start, ($end - $start));
        }
    }

    private static $timeZoneList = null;

    protected function testTimezone($part)
    {
        if (self::$timeZoneList === null) {
            $idArray = array_keys(timezone_abbreviations_list());
            self::$timeZoneList = [];
            foreach ($idArray as $id) {
                if (strlen($id) > 1) { // 'a' is really a timezone?
                    self::$timeZoneList[] = strtolower($id);
                    self::$timeZoneList[] = strtoupper($id);
                }
            }
            $idArray = \DateTimeZone::listIdentifiers();
            foreach ($idArray as $id) {
                self::$timeZoneList[] = strtolower($id);
                self::$timeZoneList[] = strtoupper($id);
                self::$timeZoneList[] = $id; // Items like 'America/Chicago' should be left as is.
            }
        }

        return in_array($part, self::$timeZoneList);
    }

    protected function testMeridiem($part)
    {
        $m = ['am', 'AM', 'PM', 'pm'];
        return in_array($part, $m);
    }

    protected function testDay($part)
    {
        if (ctype_digit($part) && $part <= 31) {
            return true;
        }
        $parse = date_parse($part);
        if (!empty($parse) && $parse['day'] !== false && $parse['error_count'] == 0 ) {
            return true;
        } else if (preg_match('/^\d{1,2}[\/\\-]\d{1,2}[\/\\-]\d{1,2}$/', $part)) {
            return true;
        }
        return false;
    }

    protected function testMonth($part)
    {
        if (ctype_digit($part) && $part <= 12) {
            return true;
        }
        $parse = date_parse($part);
        if (!empty($parse) && $parse['month'] !== false && $parse['error_count'] == 0 ) {
            return true;
        } else if (preg_match('/^\d{1,2}[\/\\-]\d{1,2}[\/\\-]\d{1,2}$/', $part)) {
            return true;
        }
        return false;
    }

    protected function testYear($part)
    {
        $parse = date_parse($part);
        if (!empty($parse) && $parse['year'] !== false && $parse['error_count'] == 0) {
            return true;
            // DateTime parses 2015 as 20:15:00, so this is to account for the unique condition.
        } else if (strlen($part) ==4 ) {
            if ($parse['second'] === 0 && $parse['hour'] == substr($part, 0, 2) && $parse['minute'] == substr($part, 2, 2)) {
                return true;
            }
        } else if (preg_match('/^\d{1,2}[\/\\-]\d{1,2}[\/\\-]\d{2,4}$/', $part)) {
            return true;
        }
        return false;
    }

    protected function testHour($part)
    {
        // Sometimes years get parsed as hours
        if (ctype_digit($part) && $part <= 23) {
            return true;
        } else if (preg_match('/^\d{1,2}:\d{1,2}:\d{1,2}$/', $part)) {
            return true;
        } else if (ctype_digit($part) && $part > 24) {
            return false;
        }
        $parse = date_parse($part);
        if (!empty($parse) && $parse['hour'] !== false && $parse['error_count'] == 0 ) {
            return true;
        }
        return false;
    }

    protected function testMinute($part)
    {
        // Sometimes years get parsed as minutes
        if (ctype_digit($part) && $part <= 60) {
            return true;
        } else if (preg_match('/^\d{1,2}:\d{1,2}:\d{1,2}$/', $part)) {
            return true;
        } else if (ctype_digit($part) && $part > 60) {
            return false;
        }
        $parse = date_parse($part);
        if (!empty($parse) && $parse['minute'] !== false && $parse['error_count'] == 0 ) {
            return true;

        }
        return false;
    }

    protected function testSeconds($part)
    {
        // Sometimes years get parsed as seconds
        if (ctype_digit($part) && $part <= 60) {
            return true;
        } else if (preg_match('/^\d{1,2}:\d{1,2}:\d{1,2}$/', $part)) {
            return true;
        } else if (ctype_digit($part) && $part > 60) {
            return false;
        }
        $parse = date_parse($part);
        if (!empty($parse) && $parse['second'] !== false && $parse['error_count'] == 0 ) {
            return true;
        }
        return false;
    }

}
