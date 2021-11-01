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

        $restURL = "https://script.google.com/macros/s/AKfycbzn5B4Np5A6FLa55Z626arTHeXElb606QlApWtYHlS37TL-wh8aIEFmZplNuAK691eF_Q/exec?sheetID=" . $sheetID . "&action=lastUpdatedTimestamp";

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