<?php

namespace GSWPTS_PRO\Includes\Classes;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable-pro'));

class FilterCallbacks {
    /**
     * @return array
     */
    public function loadIconsUrl(): array{
        $iconUrls = [
            'curlyBrackets' => esc_url(GSWPTS_PRO_BASE_URL.'Assets/Public/Icons/ExportIcons/brackets-curly.svg'),
            'copySolid'     => esc_url(GSWPTS_PRO_BASE_URL.'Assets/Public/Icons/ExportIcons/copy-solid.svg'),
            'fileCSV'       => esc_url(GSWPTS_PRO_BASE_URL.'Assets/Public/Icons/ExportIcons/file-csv-solid.svg'),
            'fileExcel'     => esc_url(GSWPTS_PRO_BASE_URL.'Assets/Public/Icons/ExportIcons/file-excel-solid.svg'),
            'filePdf'       => esc_url(GSWPTS_PRO_BASE_URL.'Assets/Public/Icons/ExportIcons/file-pdf-solid.svg'),
            'printIcon'     => esc_url(GSWPTS_PRO_BASE_URL.'Assets/Public/Icons/ExportIcons/print-solid.svg')
        ];

        return $iconUrls;
    }

    /**
     * @param  $rowsPerPage
     * @return mixed
     */
    public function rowsPerPage($rowsPerPage): array{
        $rowsPerPage = [
            '1'   => [
                'val'   => 1,
                'isPro' => false
            ],
            '5'   => [
                'val'   => 5,
                'isPro' => false
            ],
            '10'  => [
                'val'   => 10,
                'isPro' => false
            ],
            '15'  => [
                'val'   => 15,
                'isPro' => false
            ],
            '25'  => [
                'val'   => 25,
                'isPro' => false
            ],
            '50'  => [
                'val'   => 50,
                'isPro' => false
            ],
            '100' => [
                'val'   => 100,
                'isPro' => false
            ],
            'all' => [
                'val'   => 'All',
                'isPro' => false
            ]
        ];

        return $rowsPerPage;
    }

    /**
     * @param  array   $rowFetching
     * @return array
     */
    public function sheetsRowFetching(array $rowFetching): array{
        $rowFetching['unlimited'] = true;
        return $rowFetching;
    }

    /**
     * @param  array   $settingsArray
     * @return array
     */
    public function displaySettingsArray(array $settingsArray): array{
        $settingsArray['responsive_table']['is_pro'] = false;
        $settingsArray['vertical_scrolling']['is_pro'] = false;
        $settingsArray['cell_format']['is_pro'] = false;
        $settingsArray['table_style']['is_pro'] = false;
        if (get_option('link_support')) {
            $settingsArray['redirection_type']['is_pro'] = false;
        }

        return $settingsArray;
    }

    /**
     * @param  array   $settingsArray
     * @return mixed
     */
    public function tableToolsArray(array $settingsArray): array{
        $settingsArray['table_export']['is_pro'] = false;
        $settingsArray['table_cache']['is_pro'] = false;

        return $settingsArray;
    }

    /**
     * @param  array   $settings
     * @param  array   $table_settings
     * @return array
     */
    public function getTableSettings(
        array $settings,
        array $table_settings
    ): array{
        $settings['responsive_table'] = $table_settings['responsiveTable'];
        $settings['vertical_scroll'] = $table_settings['verticalScroll'];
        $settings['table_export'] = isset($table_settings['tableExport']) && $table_settings['tableExport'] != null && $table_settings['tableExport'] != false ? $table_settings['tableExport'] : 'empty';
        $settings['cell_format'] = $table_settings['cellFormat'];
        $settings['redirection_type'] = $table_settings['redirectionType'];
        $settings['table_cache'] = $table_settings['tableCache'];
        $settings['table_style'] = $table_settings['tableStyle'];
        return $settings;
    }

    /**
     * @param  array   $scrollHeights
     * @return array
     */
    public function scrollHeightArray(array $scrollHeights): array{

        $scrollHeights['default'] = [
            'val'   => 'Default Style',
            'isPro' => true
        ];

        $scrollHeights = array_map(function ($scrollHeight) {
            $scrollHeight['isPro'] = false;
            return $scrollHeight;
        }, $scrollHeights);

        return $scrollHeights;
    }

    /**
     * @param  array   $exportValues
     * @return array
     */
    public function tableExportValues(array $exportValues): array{
        if (!$exportValues) {
            $exportValues;
        }

        $exportValues = array_map(function ($value) {
            $value['isPro'] = false;
            return $value;
        }, $exportValues);

        return $exportValues;
    }

    /**
     * @param  array   $cellFormats
     * @return array
     */
    public function cellFormattingArray(array $cellFormats): array{

        if (!$cellFormats) {
            $cellFormats;
        }

        $cellFormats = array_map(function ($value) {
            $value['isPro'] = false;
            return $value;
        }, $cellFormats);

        return $cellFormats;
    }

    /**
     * @param  array   $settingsArray
     * @return array
     */
    public function generalSettingsArray(array $settingsArray): array{
        if (!$settingsArray) {
            $settingsArray;
        }

        $settingsArray['multiple_sheet_tab']['is_pro'] = false;
        $settingsArray['custom_css']['is_pro'] = false;

        return $settingsArray;
    }

    /**
     * @param  array   $cellFormats
     * @return array
     */
    public function redirectionTypeArray(array $redirectionTypes): array{

        if (!$redirectionTypes) {
            $redirectionTypes;
        }

        $redirectionTypes = array_map(function ($types) {
            if (get_option('link_support')) {
                $types['isPro'] = false;
            }
            return $types;
        }, $redirectionTypes);

        return $redirectionTypes;
    }

    /**
     * @param  array   $constructorArray
     * @param  string  $url
     * @return array
     */
    public function sheetURLConstructor(
        array $constructorArray,
        string $url
    ): array{

        $gID = $this->getGridID($url);

        $constructorArray['gID'] = $gID;

        return $constructorArray;
    }

    /**
     * @param  string  $url
     * @return mixed
     */
    public function getGridID(string $url) {
        $gID = false;
        $pattern = "/gid=(\w+)/i";

        if (!get_option('multiple_sheet_tab')) {
            return $gID;
        }

        if (preg_match_all($pattern, $url, $matches)) {

            $matchedID = $matches[1][0];
            if ($matchedID) {
                $gID = $matchedID;
            }
        }
        return $gID;
    }

    /**
     * @param  array   $stylesArray
     * @return array
     */
    public function tableStylesArray(array $stylesArray): array{

        $stylesArray = array_map(function ($style) {
            $style['isPro'] = false;
            return $style;
        }, $stylesArray);

        return $stylesArray;
    }

    /**
     * @param  array   $stylesArray
     * @return mixed
     */
    public function tableStylesCssFile(array $stylesArray): array{

        if (!$stylesArray) {
            return $stylesArray;
        }

        foreach ($stylesArray as $key => $style) {
            $stylesArray[$key]['cssURL'] = GSWPTS_PRO_BASE_URL.'Assets/Public/Styles/'.$key.'.min.css';
            $stylesArray[$key]['cssPath'] = GSWPTS_PRO_BASE_PATH.'Assets/Public/Styles/'.$key.'.min.css';
        }

        return $stylesArray;
    }

}