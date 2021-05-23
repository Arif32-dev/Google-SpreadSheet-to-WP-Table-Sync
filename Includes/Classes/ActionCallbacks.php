<?php

namespace GSWPTS_PRO\Includes\Classes;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable-pro'));

class ActionCallbacks {
    public function tableExportDependencies() {
        wp_enqueue_script('GSWPTS-buttons', GSWPTS_PRO_BASE_URL.'Assets/Public/Common/DataTables/DownlodableAssets/buttons.min.js', ['jquery'], GSWPTS_PRO_VERSION, true);
        wp_enqueue_script('GSWPTS-buttons-flash', GSWPTS_PRO_BASE_URL.'Assets/Public/Common/DataTables/DownlodableAssets/button-flesh.min.js', ['jquery'], GSWPTS_PRO_VERSION, true);
        wp_enqueue_script('GSWPTS-jszip', GSWPTS_PRO_BASE_URL.'Assets/Public/Common/DataTables/DownlodableAssets/jszip.min.js', ['jquery'], GSWPTS_PRO_VERSION, true);
        wp_enqueue_script('GSWPTS-pdfmake', GSWPTS_PRO_BASE_URL.'Assets/Public/Common/DataTables/DownlodableAssets/pdfmake.min.js', ['jquery'], GSWPTS_PRO_VERSION, true);
        wp_enqueue_script('GSWPTS-vfs_fonts', GSWPTS_PRO_BASE_URL.'Assets/Public/Common/DataTables/DownlodableAssets/vfs_fonts.js', ['jquery'], GSWPTS_PRO_VERSION, true);
        wp_enqueue_script('GSWPTS-buttons-html5', GSWPTS_PRO_BASE_URL.'Assets/Public/Common/DataTables/DownlodableAssets/buttons.html5.min.js', ['jquery'], GSWPTS_PRO_VERSION, true);
        wp_enqueue_script('GSWPTS-buttons-print', GSWPTS_PRO_BASE_URL.'Assets/Public/Common/DataTables/DownlodableAssets/buttons.print.min.js', ['jquery'], GSWPTS_PRO_VERSION, true);
    }
}