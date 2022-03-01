<?php

namespace GSWPTS\includes\classes;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

class GlobalClass {
    public function dataTableStyles() {
        wp_enqueue_style('GSWPTS-semanticui-css', GSWPTS_BASE_URL . 'assets/public/common/semantic/semantic.min.css', [], GSWPTS_VERSION, 'all');
        wp_enqueue_style('GSWPTS-dataTable-semanticui-css', GSWPTS_BASE_URL . 'assets/public/common/datatables/tables/css/datatables.semanticui.min.css', [], GSWPTS_VERSION, 'all');
    }

    public function dataTableScripts() {
        wp_enqueue_script('GSWPTS-jquery-dataTable-js', GSWPTS_BASE_URL . 'assets/public/common/datatables/tables/js/jquery.datatables.min.js', ['jquery'], GSWPTS_VERSION, true);
        wp_enqueue_script('GSWPTS-dataTable-semanticui-js', GSWPTS_BASE_URL . 'assets/public/common/datatables/tables/js/datatables.semanticui.min.js', ['jquery'], GSWPTS_VERSION, true);
    }

    /**
     * @param $nonce_action
     * @param $nonce_name
     */
    public function nonceField(
        $nonce_action,
        $nonce_name
    ) {
        wp_nonce_field($nonce_action, $nonce_name);
    }

    public function semanticFiles() {
        wp_enqueue_style('GSWPTS-semanticui-css', GSWPTS_BASE_URL . 'assets/public/common/semantic/semantic.min.css', [], GSWPTS_VERSION, 'all');
        wp_enqueue_script('GSWPTS-semantic-js', GSWPTS_BASE_URL . 'assets/public/common/semantic/semantic.min.js', ['jquery'], GSWPTS_VERSION, false);
    }

    public function frontendTablesAssets() {
        wp_enqueue_script('GSWPTS-frontend-table', GSWPTS_BASE_URL . 'assets/public/common/datatables/tables/js/jquery.datatables.min.js', ['jquery'], GSWPTS_VERSION, false);
        wp_enqueue_script('GSWPTS-frontend-semantic', GSWPTS_BASE_URL . 'assets/public/common/datatables/tables/js/datatables.semanticui.min.js', ['jquery'], GSWPTS_VERSION, false);
    }

    /**
     * @param  string  $string
     * @return mixed
     */
    public function getSheetID(string $string) {
        $pattern = "/\//";
        $components = preg_split($pattern, $string);
        if ($components) {
            if (array_key_exists(5, $components)) {
                return $components[5];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param  array   $reqData
     * @return mixed
     */
    public function get_table(array $reqData) {

        $queryData = [];

        // set the query arguments to fetch styles from google sheet
        if (isset($reqData['url']) && $reqData['url']) {
            $queryData['sheetID'] = $this->getSheetID($reqData['url']);
            $queryData['gID'] = $this->getGridID($reqData['url']);
        } else {
            $queryData['sheetID'] = $this->getSheetID($reqData['dbResult'][0]->source_url);
            $queryData['gID'] = $this->getGridID($reqData['dbResult'][0]->source_url);
        }

        if ($reqData['isAjaxReq'] && $reqData['sheetResponse']) {

            $reqData['queryData'] = $queryData;
            $reqData['importStyles'] = isset($reqData['importStyles']) ? $reqData['importStyles'] : false;

            return $this->the_table($reqData);
        }

        if (isset($reqData['tableID']) && $reqData['tableID'] !== '') {

            if ($reqData['dbResult']) {

                $tableSettings = unserialize($reqData['dbResult'][0]->table_settings);

                $tableCache = false;

                if (isset($tableSettings['table_cache']) && $tableSettings['table_cache'] == 'true') {
                    $tableCache = true;
                }

                $isUpdated = $this->isSheetUpdated(intval($reqData['tableID']), $reqData['dbResult'][0]->source_url);

                if ($isUpdated & $tableCache) {
                    $this->setLastUpdatedTime($reqData['tableID'], $reqData['dbResult'][0]->source_url);
                }

                $sheet_response = $this->loadDataByCondition([
                    'tableID'      => $reqData['tableID'],
                    'url'          => $reqData['dbResult'][0]->source_url,
                    'tableCache'   => $tableCache,
                    'importStyles' => $reqData['importStyles'],
                    'isUpdated'    => $isUpdated
                ]);

                if (!$sheet_response) {
                    return false;
                }

                $args = [
                    'sheetResponse' => $sheet_response,
                    'hiddenValues'  => $reqData['hiddenValues'],
                    'queryData'     => $queryData,
                    'importStyles'  => $reqData['importStyles'],
                    'tableCache'    => $tableCache,
                    'url'           => $reqData['dbResult'][0]->source_url,
                    'tableID'       => $reqData['tableID'],
                    'isUpdated'     => $isUpdated
                ];

                $table = $this->the_table($args);

                $output = [
                    'id'             => $reqData['tableID'],
                    'table'          => $table,
                    'table_settings' => $tableSettings,
                    'table_name'     => $reqData['dbResult'][0]->table_name,
                    'total_rows'     => $table['count']
                ];
                return $output;
            }
        }
        return false;
    }

    /**
     * @param  $sheet_response
     * @return mixed
     */
    public function the_table(array $args) {

        $sheet_response = $args['sheetResponse'];

        $hiddenValues = isset($args['hiddenValues']) ? $args['hiddenValues'] : [];

        $importStyles = $args['importStyles'];

        $table = '<table id="create_tables" class="ui celled display table gswpts_tables" style="width:100%">';
        $i = 0;

        $stream = fopen('php://memory', 'r+');

        fwrite($stream, $sheet_response);
        rewind($stream);

        $sheetStyles = null;

        if ($importStyles) {
            $sheetStyles = $this->loadStylesByCondition($args);
        }

        // Organize the sheet styles as an object
        if ($sheetStyles) {
            $styles = [
                'bgColors'             => property_exists($sheetStyles, 'bgColors') ? $sheetStyles->bgColors : '',
                'fontColors'           => property_exists($sheetStyles, 'fontColors') ? $sheetStyles->fontColors : '',
                'fontFamily'           => property_exists($sheetStyles, 'fontFamily') ? $sheetStyles->fontFamily : '',
                'fontSize'             => property_exists($sheetStyles, 'fontSize') ? $sheetStyles->fontSize : '',
                'fontWeights'          => property_exists($sheetStyles, 'fontWeights') ? $sheetStyles->fontWeights : '',
                'fontStyles'           => property_exists($sheetStyles, 'fontStyles') ? $sheetStyles->fontStyles : '',
                'textDecoration'       => property_exists($sheetStyles, 'textDecoration') ? $sheetStyles->textDecoration : '',
                'horizontalAlignments' => property_exists($sheetStyles, 'horizontalAlignments') ? $sheetStyles->horizontalAlignments : ''
            ];
        }

        // Get the images
        $imagesData = (array) $this->loadImagesByCondition($args);

        $tableHeadValues = [];

        while (!feof($stream)) {

            $styles['rowIndex'] = $i;

            if ($i <= 0) {
                $table .= '<thead><tr>';

                foreach (fgetcsv($stream) as $index => $cell_value) {

                    array_push($tableHeadValues, $cell_value);

                    $styles['cellIndex'] = $index;

                    if ($cell_value) {
                        $table .= '<th style="' . $this->embedCellStyle($styles) . '" class="' . $this->embedCellFormatClass() . '">' . stripslashes(esc_html__($cell_value, 'sheetstowptable')) . '</th>';
                    } else {
                        $table .= '<th style="' . $this->embedCellStyle($styles) . '" class="' . $this->embedCellFormatClass() . '"></th>';
                    }
                }
                $table .= '</tr></thead>';
            } else {

                $allowRowFetching = apply_filters('gswpts_allow_sheet_rows_fetching', [
                    'unlimited' => false,
                    'totalRows' => 51
                ]);

                if (!$allowRowFetching['unlimited']) {
                    if ($i == $allowRowFetching['totalRows']) {
                        break;
                    }
                }

                $hiddenRows = isset($hiddenValues['hiddenRows']) && !empty($hiddenValues['hiddenRows']) ? $hiddenValues['hiddenRows'] : [];
                $hiddenCells = isset($hiddenValues['hiddenCells']) && !empty($hiddenValues['hiddenCells']) ? $hiddenValues['hiddenCells'] : [];

                $table .= '<tr class="gswpts_rows row_' . $i . '" data-index="' . $i . '"
                                style="' . $this->hideRows($hiddenRows, $i) . '">';

                foreach (fgetcsv($stream) as $columnIndex => $cell_value) {

                    $styles['cellIndex'] = $columnIndex;

                    $convertedValue = '';
                    $cellIndex = '[' . ($columnIndex + 1) . ',' . $i . ']';

                    $cell_value = $this->getOrganizedImageData(
                        [
                            'cellIndex'  => $columnIndex,
                            'rowIndex'   => $i,
                            'imagesData' => $imagesData
                        ],
                        $cell_value
                    );

                    if ($cell_value) {

                        $cell_value = $this->checkLinkExists($cell_value);
                        $cell_value = $this->transformBooleanValues($cell_value);

                        // Convert the cell value to use inside td tag
                        $convertedValue = __(
                            stripslashes($cell_value),
                            'sheetstowptable'
                        );

                        $table .= '<td data-index="' . $cellIndex . '"
                                        style="' . $this->embedCellStyle($styles) . '"
                                        data-content="' . $this->addTableHeaderToCell($tableHeadValues[$columnIndex]) . '"
                                        class="cell_index_' . ($columnIndex + 1) . '-' . $i . ' ' . $this->embedCellFormatClass() . '">
                                            <div class="cell_div"
                                                 style="' . $this->hideCells($hiddenCells, $cellIndex) . '">
                                                    ' . nl2br($convertedValue) . '
                                            </div>
                                    </td>';
                    } else {
                        $table .= '<td data-index="' . $cellIndex . '"
                                    style="' . $this->embedCellStyle($styles) . '"
                                    data-content="' . $this->addTableHeaderToCell($tableHeadValues[$columnIndex]) . '"
                                    class="cell_index_' . ($columnIndex + 1) . '-' . $i . ' ' . $this->embedCellFormatClass() . '">
                                        <div class="cell_div"
                                            style="' . $this->hideCells($hiddenCells, $cellIndex) . '">
                                            ' . $cell_value . '
                                        </div>
                                    </td>';
                    }
                }
                $table .= '</tr>';
            }
            $i++;
        }

        fclose($stream);

        $table .= '</table>';

        $response = [
            'table'        => $table,
            'count'        => $i,
            'tableColumns' => $tableHeadValues
        ];

        return $response;
    }

    /**
     * @param $args
     */
    public function getOrganizedImageData($args, $cell_value) {
        $imagesData = isset($args['imagesData']) ? $args['imagesData'] : null;
        $rowIndex = isset($args['rowIndex']) ? $args['rowIndex'] : null;
        $cellIndex = isset($args['cellIndex']) ? $args['cellIndex'] : null;

        if (isset($imagesData['row_' . $rowIndex . '_col_' . $cellIndex . '']) &&
            $imagesData['row_' . $rowIndex . '_col_' . $cellIndex . '']) {

            $imgUrl = $imagesData['row_' . $rowIndex . '_col_' . $cellIndex . '']->imgUrl[0];
            $width = $imagesData['row_' . $rowIndex . '_col_' . $cellIndex . '']->width;
            $height = $imagesData['row_' . $rowIndex . '_col_' . $cellIndex . '']->height;

            return '<img src="' . $imgUrl . '" style="width: ' . (floatval($width) + 50) . 'px; height: ' . (floatval($height) + 50) . 'px" />';
        }

        return $cell_value;
    }

    /**
     * Return css inline style so that it can be added as inline style value
     * @param  $style
     * @return mixed
     */
    public function embedCellStyle($style) {

        $styleText = '';

        if (!$this->isProActive()) {
            return $styleText;
        }

        if (!empty($style['bgColors'])) {
            $styleText .= 'background-color: ' . $style['bgColors'][$style['rowIndex']][$style['cellIndex']] . ';';
        }

        if (!empty($style['fontColors'])) {
            $styleText .= 'color: ' . $style['fontColors'][$style['rowIndex']][$style['cellIndex']] . ';';
        }

        if (!empty($style['fontFamily'])) {
            $styleText .= 'font-family: ' . $style['fontFamily'][$style['rowIndex']][$style['cellIndex']] . ';';
        }

        if (!empty($style['fontSize'])) {
            $styleText .= 'font-size: ' . (intval($style['fontSize'][$style['rowIndex']][$style['cellIndex']]) + 7) . 'px;';
        }

        if (!empty($style['fontWeights'])) {
            $styleText .= 'font-weight: ' . $style['fontWeights'][$style['rowIndex']][$style['cellIndex']] . ';';
        }

        if (!empty($style['fontStyles'])) {
            $styleText .= 'font-style: ' . $style['fontStyles'][$style['rowIndex']][$style['cellIndex']] . ';';
        }

        if (!empty($style['textDecoration'])) {
            $styleText .= 'text-decoration: ' . $style['textDecoration'][$style['rowIndex']][$style['cellIndex']] . ';';
        }

        if (!empty($style['horizontalAlignments'])) {
            $styleText .= 'text-align: ' . $this->getCellAlignment($style['horizontalAlignments'][$style['rowIndex']][$style['cellIndex']]) . ';';
        }

        return $styleText;
    }

    /**
     * @param $alignment
     */
    public function getCellAlignment($alignment) {
        switch ($alignment) {
        case 'general-right':
            return 'right';
            break;

        case 'general-left':
            return 'left';
            break;

        case 'center':
            return 'center';
            break;

        default:
            return $alignment;
            break;
        }
    }

    /**
     * @param  $url
     * @return mixed
     */
    public function getSheetStyles(array $args) {

        $queryData = $args['queryData'];

        $sheetStyles = [];

        if (!$this->isProActive()) {
            return $sheetStyles;
        }

        if (!isset($queryData['sheetID']) || !$queryData['sheetID']) {
            return $sheetStyles;
        }

        if (!isset($queryData['gID'])) {
            return $sheetStyles;
        }

        $restURL = "https://script.google.com/macros/s/AKfycbxFQqs02vfk887crE4jEK_i9SXnFcaWYpb9qNnvDZe09YL-DmDkFqVELaMB2F7EhzXeFg/exec?sheetID=" . $queryData['sheetID'] . "&gID=" . $queryData['gID'] . "&action=getStyles";

        try {
            $response = wp_remote_get($restURL);

            if ($response['response']['code'] == 200) {
                return json_decode($response['body']);
            } else {
                return $sheetStyles;
            }
        } catch (\Throwable $th) {
            throw $th;
        }

        return $sheetStyles;
    }

    /**
     * Get the images from google sheet
     * @param  array   $args
     * @return mixed
     */
    public function getImages(array $args) {
        $queryData = $args['queryData'];

        $imagesData = [];

        if (!$this->isProActive()) {
            return $imagesData;
        }

        if (!isset($queryData['sheetID']) || !$queryData['sheetID']) {
            return $imagesData;
        }

        if (!isset($queryData['gID'])) {
            return $imagesData;
        }

        $restURL = "https://script.google.com/macros/s/AKfycbxFQqs02vfk887crE4jEK_i9SXnFcaWYpb9qNnvDZe09YL-DmDkFqVELaMB2F7EhzXeFg/exec?sheetID=" . $queryData['sheetID'] . "&gID=" . $queryData['gID'] . "&action=getImages";

        try {
            $response = wp_remote_get($restURL);

            if ($response['response']['code'] == 200) {
                return json_decode($response['body']);
            } else {
                return $imagesData;
            }
        } catch (\Throwable $th) {
            throw $th;
        }

        return $imagesData;
    }

    /**
     * @param array $savedHiddenRows
     * @param int   $rowIndex
     */
    public function hideRows(array $savedHiddenRows, int $rowIndex): string {

        if (!$this->isProActive()) {
            return '';
        }

        if (!$savedHiddenRows) {
            return '';
        }

        if (in_array($rowIndex, $savedHiddenRows)) {
            return 'display: none;';
        }

        return '';
    }

    /**
     * @param array $savedHiddenRows
     */
    public function hideCells(array $savedHiddenCells, $cellIndex): string {

        if (!$this->isProActive()) {
            return '';
        }

        if (!$savedHiddenCells) {
            return '';
        }

        if (in_array($cellIndex, $savedHiddenCells)) {
            return 'display: none;';
        }

        return '';
    }

    /**
     * @param  $headerData
     * @return mixed
     */
    public function addTableHeaderToCell($headerData) {
        if ($headerData) {
            return $headerData . '&#x0003A &nbsp;';
        } else {
            return null;
        }
    }

    /**
     * @return mixed
     */
    public function embedCellFormatClass(): string {
        $cellFormat = '';

        if ($this->isProActive()) {
            $cellFormat = 'expanded_style';
        }

        return $cellFormat;
    }

    /**
     * @param  string  $cellValue
     * @return mixed
     */
    public function transformBooleanValues(string $cellValue): string {

        if (!$this->isProActive()) {
            return $cellValue;
        }

        $filteredCellValue = '';

        switch ($cellValue) {
        case 'TRUE':
            $filteredCellValue = '&#10004;';
            break;
        case 'FALSE':
            $filteredCellValue = '&#10006;';
            break;
        default:
            $filteredCellValue = $cellValue;
            break;
        }

        return $filteredCellValue;
    }

    /**
     * @param  string  $string
     * @return mixed
     */
    public function checkLinkExists(string $string): string {

        if (!$this->isProActive()) {
            return $string;
        }

        $imgMatchingRegex = "/(https?:\/\/.*\.(?:png|jpg|jpeg|gif|svg))/i";

        // if the string is url and contains image property than return the string image tag
        if (filter_var($string, FILTER_VALIDATE_URL) && preg_match_all($imgMatchingRegex, $string)) {
            return '<img src="' . $string . '" alt="' . $string . '"/>';
        }

        if (preg_match_all('/iframe/', $string, $matches)) {
            return $string;
        }

        // If a img tag is found return that image tag and don't proceed further
        if (preg_match_all('/img/', $string, $matches)) {
            return $string;
        }

        // Link text and link pattern combined
        $pattern = '/(\[.+\]).*(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])/i';
        // This is only link pattern
        $linkPattern = '/(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])/i';

        if (preg_match_all($pattern, $string, $matches)) {
            if ($matches) {

                $transformedLinks = '';

                foreach ($matches[0] as $key => $singleMatch) {

                    if (preg_match_all($linkPattern, $singleMatch, $linkMatch)) {
                        // link text with bracket
                        $holderText = $matches[1][$key];
                        $linkText = '';

                        if ($matches[1] && $matches[1][$key]) {
                            $linkText = $this->extractBracketText($matches[1][$key])[0];
                        }

                        if ($key > 0) {
                            $transformedLinks = $this->transformLinks($linkMatch[0], $transformedLinks, $linkText, $holderText);
                        } else {
                            $transformedLinks = $this->transformLinks($linkMatch[0], $string, $linkText, $holderText);
                        }

                    }
                }

                return $transformedLinks;

            } else {
                return $string;
            }
        } elseif (preg_match_all($linkPattern, $string, $matches)) {
            if ($matches) {
                return $this->transformLinks($matches[0], $string);
            } else {
                return $string;
            }
        } else {
            return $string;
        }
    }

    /**
     * Extract the text only from inside the brackets
     * @param $string
     */
    public function extractBracketText($string) {
        $textOutside = [];
        $textInside = [];
        $t = "";
        for ($i = 0; $i < strlen($string); $i++) {
            if ($string[$i] == '[') {
                $textOutside[] = $t;
                $t = "";
                $t1 = "";
                $i++;
                while ($string[$i] != ']') {
                    $t1 .= $string[$i];
                    $i++;
                }
                $textInside[] = $t1;

            } else {
                if ($string[$i] != ']') {
                    $t .= $string[$i];
                } else {
                    continue;
                }

            }
        }
        if ($t != "") {
            $textOutside[] = $t;
        }

        return $textInside;
    }

    /**
     * @param  array    $string
     * @return string
     */
    public function transformLinks(
        array $matchedLink,
        string $string,
        $linkText = '',
        $holderText = ''
    ): string {
        $replacedString = $string;

        // if linktext is emply load default link as link text
        if ($linkText == '') {
            $linkText = $this->checkHttpsInString($matchedLink[0], true);
        }
        $replacedString = str_replace($holderText, "", $replacedString);
        $replacedString = str_replace($matchedLink[0], '<a href="' . $this->checkHttpsInString($matchedLink[0], true) . '" target="_self">' . $linkText . '</a>', $replacedString);

        return (string) $replacedString;
    }

    /**
     * @param  string  $string
     * @return array
     */
    public function checkHttpsInString(string $string, $addHttp = false): string {

        $pattern = '/((https|ftp|file)):\/\//i';
        if (!preg_match_all($pattern, $string, $matches)) {
            if ($addHttp) {
                return 'http://' . $string;
            } else {
                return $string;
            }
        } else {
            return $string;
        }
        return $string;
    }

    /**
     * @param  int     $tableID
     * @param  string  $url
     * @return mixed
     */
    public function loadDataByCondition($args) {

        $sheetResponse = '';

        if (!$args['tableCache']) {
            return $this->get_csv_data($args['url']);
        }

        if ($args['isUpdated']) {

            $sheetResponse = $this->get_csv_data($args['url']);
            // save sheet data to local storage
            $this->saveSheetData($args['tableID'], $sheetResponse);
        } else {
            $sheetResponse = $this->getSavedSheetData($args['tableID'], $args['url']);
        }

        return $sheetResponse;
    }

    /**
     * Load the sheet styles from wp transient if table caching is on or load it from sheet
     * @param  $args
     * @return mixed
     */
    public function loadStylesByCondition($args) {

        $sheetStyles = null;

        if (!$args['tableCache']) {
            return $this->getSheetStyles($args);
        }

        if ($args['isUpdated']) {

            $sheetStyles = $this->getSheetStyles($args);
            // save sheet data to wp transient
            $this->saveSheetStyles($args['tableID'], $sheetStyles);
        } else {
            $sheetStyles = $this->getSavedSheetStyles($args);
        }

        return $sheetStyles;
    }

    /**
     * Load the images from wp transient if table caching is on or load it from sheet
     * @param  $args
     * @return mixed
     */
    public function loadImagesByCondition($args) {

        $imagesData = null;

        if (!isset($args['tableCache'])) {
            return $this->getImages($args);
        }

        if ($args['isUpdated']) {

            $imagesData = $this->getImages($args);
            // save sheet data to wp trnasient
            $this->saveSheetImages($args['tableID'], $imagesData);
        } else {
            $imagesData = $this->getSavedSheetImages($args);
        }

        return $imagesData;
    }

    /**
     * @param  array   $url
     * @return mixed
     */
    public function getLastUpdatedtime(string $url): string {

        if (!$this->isProActive()) {
            return false;
        }

        if (!$url) {
            return false;
        }

        $sheetID = (string) $this->getSheetID((string) $url);

        global $gswptsPro;
        $modifiedTime = $gswptsPro->getLastUpdatedtime($sheetID);

        if (!$modifiedTime) {
            return false;
        }

        $lastUpdatedTimestamp = strtotime($modifiedTime);

        return $lastUpdatedTimestamp;
    }

    /**
     * @param int   $tableID
     * @param array $jsonData
     */
    public function setLastUpdatedTime(
        int $tableID,
        string $url
    ) {

        if (!$url) {
            return false;
        }

        $lastUpdatedTimestamp = $this->getLastUpdatedtime($url);

        if (!$lastUpdatedTimestamp) {
            return false;
        }

        if (get_option('gswpts_sheet_updated_time_' . $tableID . '')) {
            if (get_option('gswpts_sheet_updated_time_' . $tableID . '') !== $lastUpdatedTimestamp) {
                update_option('gswpts_sheet_updated_time_' . $tableID . '', $lastUpdatedTimestamp);
            }
        } else {
            add_option('gswpts_sheet_updated_time_' . $tableID . '', $lastUpdatedTimestamp);
        }
    }

    /**
     * @param  int       $tableID
     * @param  array     $jsonData
     * @return boolean
     */
    public function isSheetUpdated(
        int $tableID,
        string $url
    ): bool {
        $isUpdated = false;
        $lastUpdatedTimestamp = $this->getLastUpdatedtime($url);

        if (!$lastUpdatedTimestamp) {
            return false;
        }

        if ($lastUpdatedTimestamp !== get_option('gswpts_sheet_updated_time_' . $tableID . '')) {
            $isUpdated = true;
        }

        return $isUpdated;
    }

    /**
     * Get the data from wordpres transient
     * @param  int     $tableID
     * @return mixed
     */
    public function getSavedSheetData(
        int $tableID,
        string $url
    ) {
        $sheetData = null;

        $sheetData = get_transient('gswpts_sheet_data_' . $tableID . '') ? get_transient('gswpts_sheet_data_' . $tableID . '') : null;

        if (!$sheetData) {
            $sheetData = $this->get_csv_data($url);
            // save sheet data to local storage
            $this->saveSheetData($tableID, $sheetData);
            // update the last updated time
            $this->setLastUpdatedTime($tableID, $url);
        }
        return $sheetData;
    }

    /**
     * @param  int               $tableID
     * @param  $sheetResponse
     * @return boolean
     */
    public function saveSheetData(
        int $tableID,
        $sheetResponse
    ) {
        set_transient('gswpts_sheet_data_' . $tableID . '', $sheetResponse, (time() + 86400 * 30), '/');
    }

    /**
     * @param  int     $tableID
     * @param  string  $url
     * @return mixed
     */
    public function getSavedSheetStyles($args) {
        $sheetStyles = null;

        $sheetStyles = get_transient('gswpts_sheet_styles_' . $args['tableID'] . '') ? get_transient('gswpts_sheet_styles_' . $args['tableID'] . '') : null;

        if (!$sheetStyles) {
            $sheetStyles = $this->getSheetStyles($args);

            // save sheet data to local storage
            $this->saveSheetStyles($args['tableID'], $sheetStyles);
            // update the last updated time
            $this->setLastUpdatedTime($args['tableID'], $args['url']);
        }

        return $sheetStyles;
    }

    /**
     * @param  $args
     * @return mixed
     */
    public function getSavedSheetImages($args) {
        $imagesData = null;

        $imagesData = get_transient('gswpts_sheet_images_' . $args['tableID'] . '') ? get_transient('gswpts_sheet_images_' . $args['tableID'] . '') : null;

        if (!$imagesData) {
            $imagesData = $this->getImages($args);

            // save sheet data to local storage
            $this->saveSheetImages($args['tableID'], $imagesData);
            // update the last updated time
            $this->setLastUpdatedTime($args['tableID'], $args['url']);
        }

        return $imagesData;
    }

    /**
     * Save the table styles in wordpress transient
     * @param  int               $tableID
     * @param  $sheetResponse
     * @return boolean
     */
    public function saveSheetStyles(
        int $tableID,
        $sheetStyles
    ) {
        set_transient('gswpts_sheet_styles_' . $tableID . '', $sheetStyles, (time() + 86400 * 30), '/');
    }

    /**
     * @param int           $tableID
     * @param $imagesData
     */
    public function saveSheetImages(int $tableID, $imagesData) {
        set_transient('gswpts_sheet_images_' . $tableID . '', $imagesData, (time() + 86400 * 30), '/');
    }

    /**
     * @param  string   $url
     * @return string
     */
    public function get_csv_data(string $url) {
        $sheet_id = $this->getSheetID($url);

        if (!$sheet_id) {
            return;
        }

        $url = $this->sheetURLConstructor($sheet_id, $url);

        try {
            $response = wp_remote_get($url)['body'];
            if (preg_match_all("/((<!DOCTYPE html>)|(<head>))/i", $response)) {
                return false;
            }

            return $response;

        } catch (\Throwable $th) {
            throw "Fetching data was a problem" . $th;
        }

    }

    /**
     * @param  string   $sheetID
     * @param  string   $gID
     * @return string
     */
    public function sheetURLConstructor(
        string $sheetID,
        string $url
    ): string {
        $constructorArray = [
            'sheetID' => $sheetID,
            'gID'     => null
        ];

        $constructorArray = apply_filters('gswpts_url_constructor', $constructorArray, $url);

        $constructedURL = '';

        if ($constructorArray['gID'] && $this->isProActive()) {
            $constructedURL = "https://docs.google.com/spreadsheets/d/" . $constructorArray['sheetID'] . "/export?format=csv&id=" . $constructorArray['sheetID'] . "&gid=" . $constructorArray['gID'] . "";
        } else {
            $constructedURL = "https://docs.google.com/spreadsheets/d/" . $constructorArray['sheetID'] . "/export?format=csv&id=" . $constructorArray['sheetID'] . "";
        }

        return $constructedURL;
    }

    /**
     * @param  string  $url
     * @return mixed
     */
    public function getGridID(string $url) {
        $gID = false;
        $pattern = "/gid=(\w+)/i";

        if (!$this->isProActive()) {
            return $gID;
        }

        if (preg_match_all($pattern, $url, $matches)) {
            $matchedID = $matches[1][0];
            if ($matchedID || $matchedID == '0') {
                $gID = '' . $matchedID . '';
            }
        }

        return $gID;
    }

    /**
     * Fetch table with specific ID
     * @param  $id
     * @return mixed
     */
    public function fetchDbByID($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'gswpts_tables';

        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $table . " WHERE id=%d", sanitize_text_field($id)));
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    // Get the tab by its id value
    /**
     * @param  $id
     * @return mixed
     */
    public function getTab($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'gswpts_tabs';

        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $table . " WHERE id=%d", sanitize_text_field($id)));
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Fetch all the saved tables
     * @return mixed
     */
    public function fetchTables() {
        global $wpdb;
        $table = $wpdb->prefix . 'gswpts_tables';
        $result = $wpdb->get_results("SELECT * FROM " . $table . "");
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * @param  int     $id
     * @return mixed
     */
    public function input_values(int $id) {
        $db_result = $this->fetchDbByID($id);
        if ($db_result) {
            $input_values = [
                'source_url' => $db_result[0]->source_url,
                'table_name' => $this->outputTableName($db_result)
            ];
            return $input_values;
        } else {
            return false;
        }
        return false;
    }

    /**
     * @param $db_result
     */
    public function outputTableName($db_result) {
        $table_settings = unserialize($db_result[0]->table_settings);
        if ($table_settings['table_title'] == 'true') {
            return '<h3>' . esc_html__($db_result[0]->table_name, 'sheetstowptable') . '</h3>';
        } else {
            return null;
        }
    }

    /**
     * Check if pro plugin is active or not
     * @return boolean
     */
    public function isProActive(): bool {
        $returnValue = false;
        if (class_exists('SheetsToWPTableLiveSyncPro') && get_option('is-sheets-to-wp-table-pro-active')) {
            $returnValue = true;
        } else {
            $returnValue = false;
        }
        return $returnValue;
    }

    /**
     * @return boolean
     */
    public function checkProPluginExists(): bool {
        $isProExits = false;
        $plugins = get_plugins();
        if (!$plugins) {
            return false;
        }

        foreach ($plugins as $plugin) {
            if ($plugin['TextDomain'] == 'sheetstowptable-pro') {
                $isProExits = true;
                break;
            }
        }
        return $isProExits;
    }

    /**
     * @return array
     */
    public function rowsPerPage(): array{
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
                'isPro' => true
            ],
            '50'  => [
                'val'   => 50,
                'isPro' => true
            ],
            '100' => [
                'val'   => 100,
                'isPro' => true
            ],
            'all' => [
                'val'   => 'All',
                'isPro' => true
            ]
        ];

        $rowsPerPage = apply_filters('gswpts_rows_per_page', $rowsPerPage);

        return $rowsPerPage;
    }

    /**
     * @param  array  $values
     * @return null
     */
    public function selectFieldHTML(array $values) {
        if (!$values) {
            return;
        }
        load_template(GSWPTS_BASE_PATH . 'includes/templates/parts/select_values.php', false, $values);
    }

    /**
     * @return array
     */
    public function scrollHeightArray(): array{
        $scrollHeights = [
            '200'  => [
                'val'   => '200px',
                'isPro' => true
            ],
            '400'  => [
                'val'   => '400px',
                'isPro' => true
            ],
            '500'  => [
                'val'   => '500px',
                'isPro' => true
            ],
            '600'  => [
                'val'   => '600px',
                'isPro' => true
            ],
            '700'  => [
                'val'   => '700px',
                'isPro' => true
            ],
            '800'  => [
                'val'   => '800px',
                'isPro' => true
            ],
            '900'  => [
                'val'   => '900px',
                'isPro' => true
            ],
            '1000' => [
                'val'   => '1000px',
                'isPro' => true
            ]
        ];

        $scrollHeights = apply_filters('gswpts_table_scorll_height', $scrollHeights);

        return $scrollHeights;
    }

    /**
     * @return array
     */
    public function displaySettingsArray(): array{
        $settingsArray = [
            'table_title'          => [
                'feature_title' => __('Table Title', 'sheetstowptable'),
                'feature_desc'  => __('Enable this to show the table title in <i>h3</i> tag above the table in your website front-end', 'sheetstowptable'),
                'input_name'    => 'show_title',
                'checked'       => false,
                'type'          => 'checkbox',
                'show_tooltip'  => true
            ],
            'show_info_block'      => [
                'feature_title' => __('Show info block', 'sheetstowptable'),
                'feature_desc'  => __('Show <i>Showing X to Y of Z entries</i>block below the table', 'sheetstowptable'),
                'input_name'    => 'info_block',
                'checked'       => true,
                'type'          => 'checkbox',
                'show_tooltip'  => true

            ],
            'show_x_entries'       => [
                'feature_title' => __('Show X entries', 'sheetstowptable'),
                'feature_desc'  => __('<i>Show X entries</i> per page dropdown', 'sheetstowptable'),
                'input_name'    => 'show_entries',
                'checked'       => true,
                'type'          => 'checkbox',
                'show_tooltip'  => true

            ],
            'swap_filters'         => [
                'feature_title' => __('Swap Filters', 'sheetstowptable'),
                'feature_desc'  => __('Swap the places of <i> X entries</i> dropdown & search filter input', 'sheetstowptable'),
                'input_name'    => 'swap_filter_inputs',
                'checked'       => false,
                'type'          => 'checkbox',
                'show_tooltip'  => true

            ],
            'swap_bottom_elements' => [
                'feature_title' => __('Swap Bottom Elements', 'sheetstowptable'),
                'feature_desc'  => __('Swap the places of <i>Showing X to Y of Z entries</i> with table pagination filter', 'sheetstowptable'),
                'input_name'    => 'swap_bottom_options',
                'checked'       => false,
                'type'          => 'checkbox',
                'show_tooltip'  => true

            ],
            'responsive_style'     => [
                'feature_title' => __('Responsive Style', 'sheetstowptable'),
                'feature_desc'  => __('Allow the table to collapse or scroll on mobile and tablet screen.', 'sheetstowptable'),
                'input_name'    => 'responsive_style',
                'is_pro'        => true,
                'type'          => 'select',
                'values'        => $this->responsiveStyle(),
                'default_text'  => 'Collapsible Table',
                'default_value' => 'default_style',
                'show_tooltip'  => true

            ],
            'rows_per_page'        => [
                'feature_title' => __('Rows per page', 'sheetstowptable'),
                'feature_desc'  => __('This will show rows per page. The feature will allow you how many rows you want to show to your user by default.', 'sheetstowptable'),
                'input_name'    => 'rows_per_page',
                'type'          => 'select',
                'values'        => $this->rowsPerPage(),
                'default_text'  => 'Rows Per Page',
                'default_value' => 10,
                'show_tooltip'  => true

            ],
            'vertical_scrolling'   => [
                'feature_title' => __('Table Height', 'sheetstowptable'),
                'feature_desc'  => __('Choose the height of the table to scroll vertically. Activating this feature will allow the table to behave as sticky header', 'sheetstowptable'),
                'input_name'    => 'vertical_scrolling',
                'checked'       => false,
                'is_pro'        => true,
                'type'          => 'select',
                'values'        => $this->scrollHeightArray(),
                'default_text'  => 'Choose Height',
                'default_value' => $this->isProActive() ? 'default' : null,
                'show_tooltip'  => false
            ],
            'cell_format'          => [
                'feature_title' => __('Format Cell', 'sheetstowptable'),
                'feature_desc'  => __('Format the table cell as like google sheet cell formatting. Format your cell as Wrap OR Expanded style', 'sheetstowptable'),
                'input_name'    => 'cell_format',
                'checked'       => false,
                'is_pro'        => true,
                'type'          => 'select',
                'values'        => $this->cellFormattingArray(),
                'default_text'  => 'Cell Format',
                'default_value' => $this->isProActive() ? 'expand' : null,
                'show_tooltip'  => true

            ],
            'redirection_type'     => [
                'feature_title' => __('Link Type', 'sheetstowptable'),
                'feature_desc'  => __('Choose the redirection type of all the links in this table.', 'sheetstowptable'),
                'input_name'    => 'redirection_type',
                'is_pro'        => true,
                'type'          => 'select',
                'values'        => $this->redirectionTypeArray(),
                'default_text'  => 'Redirection Type',
                'default_value' => $this->isProActive() ? '_self' : null,
                'show_tooltip'  => true

            ],
            'table_style'          => [
                'feature_title' => __('Table Style', 'sheetstowptable'),
                'feature_desc'  => __('Choose your desired table style for this table. This will change the design & color of this table according to your selected table design', 'sheetstowptable'),
                'input_name'    => 'table_style',
                'checked'       => false,
                'is_pro'        => true,
                'type'          => 'custom-type',
                'default_text'  => 'Choose Style',
                'show_tooltip'  => false,
                'icon_url'      => GSWPTS_BASE_URL . 'assets/public/icons/table_style.svg'
            ],
            'import_styles'        => [
                'feature_title' => __('Import Sheet Styles', 'sheetstowptable'),
                'feature_desc'  => __('Import cell backgorund color & cell font color from google sheet. If you activate this feature it will overrider <i>Table Style</i> setting', 'sheetstowptable'),
                'input_name'    => 'import_styles',
                'is_pro'        => true,
                'type'          => 'checkbox',
                'checked'       => false,
                'show_tooltip'  => true
            ]
        ];

        $settingsArray = apply_filters('gswpts_display_settings_arr', $settingsArray);

        return $settingsArray;
    }

    /**
     * @return array
     */
    public function responsiveStyle() {
        $responsiveStyles = [
            'default_style'  => [
                'val'   => 'Default Style',
                'isPro' => false
            ],
            'collapse_style' => [
                'val'   => 'Collapsible Style',
                'isPro' => true
            ],
            'scroll_style'   => [
                'val'   => 'Scrollable Style',
                'isPro' => true
            ]
        ];

        $responsiveStyles = apply_filters('gswpts_responsive_styles', $responsiveStyles);

        return $responsiveStyles;
    }

    /**
     * @return mixed
     */
    public function redirectionTypeArray(): array{
        $redirectionTypes = [
            '_blank' => [
                'val'   => 'Blank Type',
                'isPro' => true
            ],
            '_self'  => [
                'val'   => 'Self Type',
                'isPro' => true
            ]
        ];

        $redirectionTypes = apply_filters('gswpts_redirection_types', $redirectionTypes);

        return $redirectionTypes;
    }

    /**
     * @return array
     */
    public function cellFormattingArray(): array{
        $cellFormats = [
            'wrap'   => [
                'val'   => 'Wrap Style',
                'isPro' => true
            ],
            'expand' => [
                'val'   => 'Expanded Style',
                'isPro' => true
            ]
        ];

        $cellFormats = apply_filters('gswpts_cell_format', $cellFormats);

        return $cellFormats;
    }

    /**
     * @return null
     */
    public function displaySettingsHTML() {
        $settingsArray = $this->displaySettingsArray();
        if (!$settingsArray) {
            return;
        }

        foreach ($settingsArray as $key => $setting) {
            load_template(GSWPTS_BASE_PATH . 'includes/templates/parts/indiviual_feature.php', false, $setting);
        }
    }

    /**
     * @return array
     */
    public function sortAndFilterSettingsArray(): array
    {
        $settingsArray = [
            'allow_sorting' => [
                'feature_title' => __('Allow Sorting', 'sheetstowptable'),
                'feature_desc'  => __('Enable this feature to sort table data for frontend.', 'sheetstowptable'),
                'input_name'    => 'sorting',
                'checked'       => true,
                'type'          => 'checkbox',
                'show_tooltip'  => true
            ],
            'search_bar'    => [
                'feature_title' => __('Search Bar', 'sheetstowptable'),
                'feature_desc'  => __('Enable this feature to show a search bar in for the table. It will help user to search data in the table', 'sheetstowptable'),
                'input_name'    => 'search_table',
                'checked'       => true,
                'type'          => 'checkbox',
                'show_tooltip'  => true
            ]
        ];

        $settingsArray = apply_filters('gswpts_sortfilter_settings_arr', $settingsArray);

        return $settingsArray;
    }

    /**
     * @return null
     */
    public function sortAndFilterHTML() {
        $settingsArray = $this->sortAndFilterSettingsArray();

        if (!$settingsArray) {
            return;
        }

        foreach ($settingsArray as $key => $setting) {
            load_template(GSWPTS_BASE_PATH . 'includes/templates/parts/indiviual_feature.php', false, $setting);
        }
    }

    /**
     * @return array
     */
    public function tableToolsArray(): array{
        $settingsArray = [
            'table_export' => [
                'feature_title' => __('Table Exporting', 'sheetstowptable'),
                'feature_desc'  => __('Enable this feature in order to allow your user to download your table content as various format.', 'sheetstowptable'),
                'input_name'    => 'table_exporting',
                'is_pro'        => true,
                'type'          => 'multi-select',
                'values'        => $this->tableExportValues(),
                'default_text'  => 'Choose Type',
                'show_tooltip'  => true
            ],
            'table_cache'  => [
                'feature_title' => __('Table Caching', 'sheetstowptable'),
                'feature_desc'  => __('Enabling this feature would cache the Google sheet data & therefore the table will load faster than before.
                                        Also it will load the updated data when there is a change in your Google sheet.', 'sheetstowptable'),
                'input_name'    => 'table_cache',
                'checked'       => false,
                'is_pro'        => true,
                'type'          => 'checkbox',
                'show_tooltip'  => true
            ],
            'hide_column'  => [
                'feature_title' => __('Hide Column', 'sheetstowptable'),
                'feature_desc'  => __('Hide your table columns based on multiple screen sizes.', 'sheetstowptable'),
                'input_name'    => 'hide_column',
                'checked'       => false,
                'is_pro'        => true,
                'type'          => 'custom-type',
                'default_text'  => 'Hide Column',
                'show_tooltip'  => false,
                'icon_url'      => GSWPTS_BASE_URL . 'assets/public/icons/hide_column.svg'
            ],
            'hide_rows'    => [
                'feature_title' => __('Hide Row\'s', 'sheetstowptable'),
                'feature_desc'  => __('Hide your table rows based on your custom selection', 'sheetstowptable'),
                'input_name'    => 'hide_rows',
                'checked'       => false,
                'is_pro'        => true,
                'type'          => 'custom-type',
                'default_text'  => 'Hide Row',
                'show_tooltip'  => false,
                'icon_url'      => GSWPTS_BASE_URL . 'assets/public/icons/hide_column.svg'
            ],
            'hide_cell'    => [
                'feature_title' => __('Hide Cell', 'sheetstowptable'),
                'feature_desc'  => __('Hide your specific table cell that is not going to visibile to your user\'s.', 'sheetstowptable'),
                'input_name'    => 'hide_cell',
                'checked'       => false,
                'is_pro'        => true,
                'type'          => 'custom-type',
                'default_text'  => 'Hide Cell',
                'show_tooltip'  => false,
                'icon_url'      => GSWPTS_BASE_URL . 'assets/public/icons/hide_column.svg'
            ]
        ];

        $settingsArray = apply_filters('gswpts_table_tools_settings_arr', $settingsArray);

        return $settingsArray;
    }

    /**
     * @return array
     */
    public function tableExportValues(): array{
        $exportValues = [
            'json'  => [
                'val'   => 'JSON',
                'isPro' => true
            ],
            'pdf'   => [
                'val'   => 'PDF',
                'isPro' => true
            ],
            'csv'   => [
                'val'   => 'CSV',
                'isPro' => true
            ],
            'excel' => [
                'val'   => 'Excel',
                'isPro' => true
            ],
            'print' => [
                'val'   => 'Print',
                'isPro' => true
            ],
            'copy'  => [
                'val'   => 'Copy',
                'isPro' => true
            ]
        ];

        $exportValues = apply_filters('gswpts_table_export_values', $exportValues);

        return $exportValues;
    }

    /**
     * @return null
     */
    public function tableToolsHTML() {
        $settingsArray = $this->tableToolsArray();

        if (!$settingsArray) {
            return;
        }

        foreach ($settingsArray as $key => $setting) {
            load_template(GSWPTS_BASE_PATH . 'includes/templates/parts/indiviual_feature.php', false, $setting);
        }
    }

    /**
     * @return array
     */
    public function generalSettingsArray(): array{
        $optionValues = $this->getOptionValues();

        $settingsArray = [
            'asynchronous_loading' => [
                'template_path'   => GSWPTS_BASE_PATH . 'includes/templates/parts/general_settings.php',
                'setting_title'   => __('Asynchronous Loading', 'sheetstowptable'),
                'setting_tooltip' => __('Enable this feature for loading table asynchronously', 'sheetstowptable'),
                'is_checked'      => $optionValues['asynchronous_loading'],
                'input_name'      => 'asynchronous_loading',
                'setting_desc'    => __("Enable this feauture to load the table in the frontend after loading all content with a pre-loader.
                                                This will help your website load fast.
                                                If you don't want to enable this feature than the table will load with the reloading of browser every time.", 'sheetstowptable'),
                'is_pro'          => false

            ],
            'custom_css'           => [
                'template_path'   => GSWPTS_BASE_PATH . 'includes/templates/parts/general_settings.php',
                'setting_title'   => __('Custom CSS', 'sheetstowptable'),
                'setting_tooltip' => __('Write your own custom CSS to design the table.', 'sheetstowptable'),
                'is_checked'      => $optionValues['custom_css'],
                'input_name'      => 'custom_css',
                'setting_desc'    => __("Write your own custom CSS to design the table or the page itself. Your custom written CSS will be applied to front-end of the website.
                                        Activate the Pro extension to enable custom CSS option", 'sheetstowptable'),
                'is_pro'          => true
            ]
        ];

        $settingsArray = apply_filters('gswpts_general_settings', $settingsArray);

        return $settingsArray;
    }

    /**
     * @return array
     */
    public function getOptionValues() {
        $optionValues = [];

        $generalSettingsOptions = $this->generalSettingsOptions();

        if (!$generalSettingsOptions) {
            return [];
        }

        foreach ($generalSettingsOptions as $key => $value) {
            $optionValue = get_option($value) ? 'checked' : '';
            $optionValues[$value] = $optionValue;
        }

        return $optionValues;
    }

    /**
     * @return array
     */
    public function generalSettingsOptions(): array{
        $generalSettingsOptions = [
            'asynchronous_loading',
            'custom_css',
            'css_code_value'
        ];
        return $generalSettingsOptions;
    }

    /**
     * @return mixed
     */
    public function tableStylesArray(): array{
        $stylesArray = [
            'default-style' => [
                'imgUrl'    => GSWPTS_BASE_URL . 'assets/public/images/tablestyle/default-style.png',
                'inputName' => 'tableStyle',
                'isPro'     => false,
                'isChecked' => $this->isProActive() ? false : true,
                'label'     => 'Default Style'
            ],
            'style-1'       => [
                'imgUrl'    => GSWPTS_BASE_URL . 'assets/public/images/tablestyle/style-2.png',
                'inputName' => 'tableStyle',
                'isPro'     => true,
                'isChecked' => false,
                'label'     => 'Style 1'
            ],
            'style-2'       => [
                'imgUrl'    => GSWPTS_BASE_URL . 'assets/public/images/tablestyle/style-3.png',
                'inputName' => 'tableStyle',
                'isPro'     => true,
                'isChecked' => false,
                'label'     => 'Style 2'
            ],
            'style-3'       => [
                'imgUrl'    => GSWPTS_BASE_URL . 'assets/public/images/tablestyle/style-4.png',
                'inputName' => 'tableStyle',
                'isPro'     => true,
                'isChecked' => false,
                'label'     => 'Style 3'
            ],
            'style-4'       => [
                'imgUrl'    => GSWPTS_BASE_URL . 'assets/public/images/tablestyle/style-1.png',
                'inputName' => 'tableStyle',
                'isPro'     => true,
                'isChecked' => false,
                'label'     => 'Style 4'
            ],
            'style-5'       => [
                'imgUrl'    => GSWPTS_BASE_URL . 'assets/public/images/tablestyle/style-5.png',
                'inputName' => 'tableStyle',
                'isPro'     => true,
                'isChecked' => false,
                'label'     => 'Style 5'
            ]
        ];

        $stylesArray = apply_filters('gswpts_table_styles', $stylesArray);
        return $stylesArray;
    }

    // Load the html markup for backend admin panal
    public function tableStylesHtml() {
        $stylesArray = $this->tableStylesArray();

        foreach ($stylesArray as $key => $style) {
            load_template(GSWPTS_BASE_PATH . 'includes/templates/parts/table_style_template.php', false, [
                'isPro' => $style['isPro'],
                'style' => $style,
                'key'   => $key
            ]);
        }
    }
}