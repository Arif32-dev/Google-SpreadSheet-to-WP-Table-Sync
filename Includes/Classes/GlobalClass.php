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

        $apiKey = $this->randomAPIKey();
        $sheet_url = "https://www.googleapis.com/drive/v3/files/".$sheetID."?fields=modifiedTime&key=".$apiKey."";

        try {
            $res = wp_remote_get($sheet_url);

            if (!$res) {
                return false;
            }

            if (isset(json_decode($res['body'])->modifiedTime)) {
                $lastModifiedTime = json_decode($res['body'])->modifiedTime;
            } else {
                return false;
            }

            return $lastModifiedTime;
        } catch (\Throwable $th) {
            throw "Google sheet error: ".$th."";
        }
    }

    /**
     * @return string
     */
    public function randomAPIKey(): string {
        $apiKeys = [
            'AIzaSyBnJ-9YVp50qmTFdIvY-Ju77VwFcSmAffs',
            'AIzaSyAJ87KAzxeFjSsmDmxnQJCBJZ3-QKJB96A',
            'AIzaSyBy2ErW299Hr-We2DT_ZeAJ7qHHhbY8bng',
            'AIzaSyCw3ibZa-hAwyeKuzQgF0oSLepfzST9-4g',
            'AIzaSyDSASAJFni_qd004glabvVQTMq8sJZhOQA',
            'AIzaSyCR8Tlc3vcAvFAUDFYlm1dWauhP3FXNbw4',
            'AIzaSyAUksW5gIWSSes8v7AA2kIwEA1mB2tTojg',
            'AIzaSyBjRxEaIyd1_7PzGj8Pt5f7wscYNat7kOY',
            'AIzaSyBrLiBhxlk99hUVJO4UlHIVNKyIN-bwpTE',
            'AIzaSyBDWnoXsziG2uQGTTV6oyaorsNnVvygRlA'
        ];

        return $apiKeys[rand(0, 9)];
    }
}