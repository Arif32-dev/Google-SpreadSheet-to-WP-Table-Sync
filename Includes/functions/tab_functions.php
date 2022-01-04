<?php

class TabFunctions {
    /**
     * @var mixed
     */
    public $id;
    /**
     * @var mixed
     */
    public $includeBootrstap = true;
    /**
     * @var mixed
     */
    public $isShortcode = false;

    /**
     * Show all the tables as tab card
     * @return mixed
     */
    public function showTabCards() {
        global $gswpts;

        $tables = $gswpts->fetch_gswpts_tables();

        if (!$tables) {
            return null;
        }

        $cardHTML = '';

        foreach ($tables as $key => $table) {
            $cardHTML .= '
            <div class="ui cards table_cards">
                <div class="card draggable" data-table_id="' . esc_attr($table->id) . '">

                    <div class="content">
                        <i class="fas fa-times card_remover"></i>

                        <div class="description d-flex justify-content-center align-items-center">
                            <strong>
                                #' . esc_html($table->id) . ' ' . esc_html($table->table_name) . '
                            </strong>
                        </div>
                    </div>

                </div>
            </div>
            ';
        }

        return $cardHTML;
    }

    // Check if the current page is create-tab page and also and also its a update page
    public function tabUpdatePage() {
        if (isset($_GET['page']) &&
            isset($_GET['subpage']) &&
            isset($_GET['id']) &&
            $_GET['page'] == 'gswpts-manage-tab' &&
            $_GET['subpage'] &&
            $_GET['id']
        ) {
            return intval(sanitize_text_field($_GET['id']));
        } else {
            return false;
        }
    }

    /**
     * Get the html template by ID
     * @param  $id
     * @return null
     */
    public function getTabByID($args) {

        if (!isset($args['id']) || !$args['id']) {
            return;
        }

        $this->id = $args['id'];

        if (isset($args['includeBootrstap'])) {
            $this->includeBootrstap = $args['includeBootrstap'];
        }

        if (isset($args['isShortcode'])) {
            $this->isShortcode = $args['isShortcode'];
        }

        global $gswpts;

        if (!$this->id) {
            return;
        }

        $tab = $gswpts->getTab($this->id);

        if (!$tab) {
            return;
        }

        $tabName = $tab[0]->tab_name;
        $reverseMode = $tab[0]->reverse_mode;
        $tabSettings = unserialize($tab[0]->tab_settings);

        $col = 'col-12';

        if (!$this->includeBootrstap) {
            $col = null;
        }

        $arrowPostion = null;
        $containerPostion = null;

        if ($reverseMode) {
            $arrowPostion = 'down';
            $containerPostion = 'reverse';
        }

        $tabNameHtml = '
            <div class="ui labeled tab_name_box">
                <div class="ui label">
                    ' . esc_html($tabName) . '
                </div>
                <span class="tab_positon_btn ' . esc_attr($arrowPostion) . '">
                    <i class="fas fa-arrow-up"></i>
                </span>
            </div>
        ';

        if ($this->isShortcode) {
            if ($tab[0]->show_name) {
                $tabNameHtml = '
                <div class="ui labeled tab_name_box">
                    <h3 class="ui label">
                        ' . esc_html($tabName) . '
                    </h3>
                </div>
            ';
            } else {
                $tabNameHtml = null;
            }
        }

        return '<div class="tab_bottom_side ' . $col . '" data-tabID="' . esc_attr($this->id) . '">

                ' . $tabNameHtml . '

                <div class="tabs_container ' . esc_attr($containerPostion) . '">

                    <ul class="tabs" role="tablist">
                        ' . $this->listItems($tabSettings, $this->id) . '
                    </ul>

                    <div class="tab_contents">
                        ' . $this->getTabContents($tabSettings) . '
                    </div>

                </div>
        </div>';
    }

    /**
     * get the li items of tab
     * @param  array   $tabSettings
     * @return mixed
     */
    public function listItems(array $tabSettings, $tabID) {

        if (count($tabSettings) < 1) {
            return;
        }

        $itemHtml = '';

        foreach ($tabSettings as $key => $item) {
            $checked = $key == 0 ? "checked" : '';
            $itemHtml .= '
            <li>
                <input ' . $checked . ' type="radio" name="tabs' . esc_attr($tabID) . '" id="tab' . esc_attr($item['id']) . '" data-id="' . esc_attr($item['id']) . '" class="tab_hidden_input"/>
                <label class="tab_name_label unselectable" for="tab' . esc_attr($item['id']) . '" role="tab">
                    <span class="tab_page_name">' . esc_attr($item['name']) . '</span>
                    <div class="ui input">
                        <input type="text" class="hidden_tab_name" value="' . esc_attr($item['name']) . '"
                            placeholder="Tab name...">
                    </div>
                </label>
            </li>
        ';
        }

        return $itemHtml;
    }

    /**
     * @param  array   $tabSettings
     * @return mixed
     */
    public function getTabContents(array $tabSettings) {
        if (count($tabSettings) < 1) {
            return;
        }

        $itemHtml = '';

        $innerContent = null;

        foreach ($tabSettings as $key => $item) {
            $active = $key == 0 ? "active" : '';

            $tableIDs = isset($item['tableID']) ? $item['tableID'] : [];
            $tabPageID = $item['id'];

            if ($this->isShortcode) {
                $innerContent = $this->getTablesShortcode($tableIDs, $tabPageID);
            } else {
                $innerContent = $this->getTableCards($tableIDs, $tabPageID);
            }

            $itemHtml .= '
                <div id="tab-content' . esc_attr($item['id']) . '" class="tab-content droppable ' . $active . '">

                    ' . $innerContent . '

                </div>
            ';
        }

        return $itemHtml;
    }

    /**
     * @param  array         $tableIDs
     * @param  $tabPageID
     * @return mixed
     */
    public function getTablesShortcode(array $tableIDs, $tabPageID) {
        if (count($tableIDs) < 1) {
            return '
            <b class="demo_content">
                Tab content #' . esc_html($tabPageID) . '
            </b>
        ';
        }

        $shotcodes = null;

        foreach ($tableIDs as $key => $tableID) {
            $shotcodes .= do_shortcode('[gswpts_table id=' . intval($tableID) . ']');
        }

        return $shotcodes;
    }

    /**
     * Get the table card for the tab content
     * @param  array   $tableIDs
     * @return mixed
     */
    public function getTableCards(array $tableIDs, $tabPageID) {

        if (count($tableIDs) < 1) {
            return '
            <b class="demo_content">
                Tab content #' . esc_html($tabPageID) . '
            </b>
        ';
        }

        $cardHtml = '';

        foreach ($tableIDs as $key => $tableID) {
            $cardHtml .= '
            <div class="card draggable ui-draggable ui-draggable-handle dragging" data-table_id="' . esc_attr($tableID) . '" style="z-index: 2; min-width: 230px;">

                <div class="content">
                    <i class="fas fa-times card_remover" style="display: block;margin: 6px 8px 0px 0px;"></i>

                    <div class="description d-flex justify-content-center align-items-center">
                        <strong>
                            #' . esc_html($tableID) . ' Table #2
                        </strong>
                    </div>
                </div>

            </div>
        ';
        }

        return $cardHtml;
    }
}