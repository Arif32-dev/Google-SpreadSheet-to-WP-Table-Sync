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

        $restURL = "https://script.google.com/macros/s/AKfycbxFQqs02vfk887crE4jEK_i9SXnFcaWYpb9qNnvDZe09YL-DmDkFqVELaMB2F7EhzXeFg/exec?sheetID=" . $sheetID . "&action=lastUpdatedTimestamp";

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