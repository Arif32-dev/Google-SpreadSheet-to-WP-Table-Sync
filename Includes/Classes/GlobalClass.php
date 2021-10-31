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

        $restURL = "https://script.google.com/macros/s/AKfycbxvhPZLP7IZ8SPyr73cFY9Qr3K6HgAfp4ihueGM0Lm-ZmE5bg07ne1d0Nw6ZXHzWFZTvA/exec?sheetID=" . $sheetID . "&action=lastUpdatedTimestamp";

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