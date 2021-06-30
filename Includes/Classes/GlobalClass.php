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

        $sheet_url = "https://www.googleapis.com/drive/v3/files/".$sheetID."?fields=modifiedTime&key=AIzaSyBnJ-9YVp50qmTFdIvY-Ju77VwFcSmAffs";
        $res = wp_remote_get($sheet_url);

        if (!$res) {
            return false;
        }

        $lastModifiedTime = json_decode($res['body'])->modifiedTime;

        return $lastModifiedTime;
    }
}