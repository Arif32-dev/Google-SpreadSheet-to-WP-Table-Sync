<?php

namespace GSWPTS_PRO\Includes\Classes\Elementor\Includes;

class TableSettings {

    /**
     * @return null
     */
    public function displaySettings() {
        global $gswpts;

        $displaySettingsArray = $gswpts->displaySettingsArray();

        if (!is_array($displaySettingsArray)) {
            return;
        }

        foreach ($displaySettingsArray as $key => $setting) {
            echo $this->individualSettings($setting);
        }
    }

    /**
     * @return null
     */
    public function sortAndFilterSettings() {
        global $gswpts;

        $sortAndFilterSettingsArray = $gswpts->sortAndFilterSettingsArray();

        if (!is_array($sortAndFilterSettingsArray)) {
            return;
        }

        foreach ($sortAndFilterSettingsArray as $key => $setting) {
            echo $this->individualSettings($setting);
        }
    }

    /**
     * @return null
     */
    public function tableToolsSettings() {
        global $gswpts;

        $tableToolsArray = $gswpts->tableToolsArray();

        if (!is_array($tableToolsArray)) {
            return;
        }

        foreach ($tableToolsArray as $key => $setting) {
            echo $this->individualSettings($setting);
        }
    }

    /**
     * @param $setting
     */
    public function individualSettings($setting) {
        if ($setting['type'] == 'checkbox') {
            return '
            <div class="card_container">
                <div class="ui cards">
                    <div class="card">
                        <div class="content">
                            <div class="card-top-header">
                                <span>
                                    ' . $setting['feature_title'] . '
                                </span>
                                <div class="ui toggle checkbox">
                                    <input type="checkbox"
                                        name="' . $setting['input_name'] . '" id="' . $setting['input_name'] . '">
                                    <label for="' . $setting['input_name'] . '"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ';
        }

        if ($setting['type'] === 'select') {
            return '
            <div class="card_container">
                <div class="ui cards">
                    <div class="card">
                        <div class="content">

                            <div class="card-top-header">
                                <span>
                                    ' . $setting['feature_title'] . '
                                </span>
                                <select id="' . $setting['input_name'] . '">
                                    ' . $this->selectValues($setting['values'], $setting['default_value']) . '
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ';
        }
    }

    /**
     * @param  $values
     * @return mixed
     */
    public function selectValues($values, $defaultValue) {
        if (!is_array($values)) {
            return '';
        }

        $htmlValues = '';

        foreach ($values as $key => $value) {
            $htmlValues .= '<option value="' . $key . '" ' . $this->setSeletedValue($defaultValue, $key) . '>' . $value['val'] . '</option>';
        }
        return $htmlValues;
    }

    /**
     * @param $defaultValue
     * @param $key
     */
    public function setSeletedValue($defaultValue, $key) {
        if ($defaultValue == $key) {
            return 'selected';
        } else {
            return '';
        }
    }

}