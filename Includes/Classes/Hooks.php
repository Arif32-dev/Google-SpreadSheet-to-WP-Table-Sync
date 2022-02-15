<?php

namespace GSWPTS\includes\classes;

use GSWPTS\Includes\Classes\DbTables;

class Hooks {
    public function __construct() {
        add_action('upgrader_process_complete', [$this, 'upgraderCallback'], 10, 2);
    }

    /**
     * @param  $updgraderClass
     * @param  $extra
     * @return null
     */
    public function upgraderCallback($updgraderClass, $extra) {
        if (!isset($extra['type']) || $extra['type'] != 'plugin') {
            return;
        }

        if (!isset($extra['action']) || $extra['action'] != 'update') {
            return;
        }

        /* Removing these code for v2.12.1 */
        // update_option('gswptsReviewNotice', false);
        // update_option('deafaultNoticeInterval', (time() + 7 * 24 * 60 * 60));
        // add_option('deafaultNoticeInterval', (time() + 10 * 24 * 60 * 60));

        // Update the tab database on plugin update
        new DbTables();
    }
}