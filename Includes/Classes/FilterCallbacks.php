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

}