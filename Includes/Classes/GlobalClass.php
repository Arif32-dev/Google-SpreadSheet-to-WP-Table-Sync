<?php

namespace GSWPTS_PRO\Includes\Classes;

class GlobalClass {
    /**
     * @param  string  $sheetID
     * @return mixed
     */
    public function getLastUpdatedtime(string $sheetID) {

        if (!$sheetID) {
            return false;
        }

        $restURL = "https://script.google.com/macros/s/AKfycbw1RgrnC9omO8eXklFqGUkYLYK1fICWNcHT_ids9Gc5UvO0el5ZpWigf0MKiaQheXj0mw/exec?sheetID=" . $sheetID . "&action=lastUpdatedTimestamp";

        try {
            $res = wp_remote_get($restURL);

            if (!$res) {
                return false;
            }

            if (isset(json_decode($res['body'])->lastUpdatedTimestamp)) {
                $lastModifiedTime = json_decode($res['body'])->lastUpdatedTimestamp;
            } else {
                return false;
            }

            return $lastModifiedTime;
        } catch (\Throwable $th) {
            throw "Google sheet error: " . $th . "";
        }
    }

}