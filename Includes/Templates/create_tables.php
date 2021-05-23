<?php
    global $gswpts;
    $table_id = isset($_GET['id']) && !empty($_GET['id']) ? sanitize_text_field($_GET['id']) : null;
?>
<div class="gswpts_create_table_container">

    <div class="ui segment gswpts_loader">
        <div class="ui active inverted dimmer">
            <div class="ui massive text loader"></div>
        </div>
        <p></p>
        <p></p>
        <p></p>
    </div>


    <div class="child_container mt-4 create_table_content transition hidden">

        <div class="row heading_row">
            <div class="col-12 d-flex justify-content-start p-0 align-iteml-center">
                <img src="<?php echo esc_url(GSWPTS_PRO_BASE_URL.'Assets/Public/Images/logo_30_30.svg'); ?>" alt="">
                <span class="ml-2">
                    <strong><?php echo PlUGIN_NAME; ?></strong>
                </span>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 p-0 d-flex align-items-center">

                <div
                    class="ui action input                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          <?php echo isset($table_id) && !empty($table_id) ? 'transition hidden' : ''; ?>">
                    <input                                                     <?php echo isset($table_id) && !empty($table_id) ? 'disabled' : ''; ?> type="text"
                        placeholder="Table Name" id="table_name" name="table_name" value="GSWPTS Table">
                    <button                                                       <?php echo isset($table_id) && !empty($table_id) ? 'disabled' : ''; ?>
                        class="ui button edit_table_name ">
                        <?php _e('Edit Title', 'sheetstowptable');?> &nbsp;
                        <span><i class="edit icon"></i></span>
                    </button>
                </div>

                <div class="col p-0 d-flex align-items-center justify-content-end">
                    <button id="create_button"
                        class="positive ui button m-0 mr-2                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <?php echo isset($table_id) && !empty($table_id) ? '' : 'transition hidden' ?>"
                        style="padding-left: 30px;">
                        <?php _e('Create New', 'sheetstowptable');?> &nbsp; <i class="plus icon"></i>
                    </button>
                    <button class="ui violet button m-0 transition hidden" type="button" id="fetch_save_btn"
                        req-type="<?php echo isset($table_id) && !empty($table_id) ? 'save' : 'fetch' ?>">
                        <?php echo isset($table_id) && !empty($table_id) ? __('Save Table', 'sheetstowptable') : __('Fetch Data', 'sheetstowptable'); ?>
                    </button>
                </div>

            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 p-0" id="gswpts_tabs">

                <div class="tabs">

                    <input type="radio" id="tab1" name="tab-control" checked>
                    <input                                                     <?php echo isset($table_id) && !empty($table_id) ? '' : 'disabled' ?> type="radio" id="tab2"
                        name="tab-control" class="secondary_inputs">
                    <input                                                     <?php echo isset($table_id) && !empty($table_id) ? '' : 'disabled' ?> type="radio" id="tab3"
                        name="tab-control" class="secondary_inputs">
                    <input                                                     <?php echo isset($table_id) && !empty($table_id) ? '' : 'disabled' ?> type="radio" id="tab4"
                        name="tab-control" class="secondary_inputs">
                    <ul>
                        <li title="<?php echo esc_attr('Data Source') ?>" class="tables_settings"
                            data-btn-text="<?php echo isset($table_id) && !empty($table_id) ? esc_attr('Save Table') : esc_attr('Fetch Data') ?>"
                            data-attr-text="<?php echo isset($table_id) && !empty($table_id) ? esc_attr('save') : esc_attr('fetch'); ?>">
                            <label for="tab1" role="button">
                                <span>
                                    <?php require GSWPTS_PRO_BASE_PATH.'Assets/Public/Icons/archive-solid.svg';?>
                                </span>
                                <span><?php _e('Data Source', 'sheetstowptable');?></span>
                            </label>
                        </li>

                        <li title="<?php echo esc_attr('Display Settings'); ?>"
                            class="<?php echo isset($table_id) && !empty($table_id) ? esc_attr('tables_settings') : esc_attr('disabled_checkbox'); ?>"
                            data-btn-text="<?php echo esc_attr('Save Changes'); ?>"
                            data-attr-text="<?php echo esc_attr('save_changes'); ?>">
                            <label for="tab2" role="button">
                                <span>
                                    <?php require GSWPTS_PRO_BASE_PATH.'Assets/Public/Icons/cogs-solid.svg';?>
                                </span>
                                <span><?php _e('Display Settings', 'sheetstowptable');?></span>
                            </label>
                        </li>

                        <li title="<?php echo esc_attr('Delivery Contents'); ?>"
                            class="<?php echo isset($table_id) && !empty($table_id) ? esc_attr('tables_settings') : esc_attr('disabled_checkbox'); ?>"
                            data-btn-text="<?php echo esc_attr('Save Changes'); ?>"
                            data-attr-text="<?php echo esc_attr('save_changes'); ?>">
                            <label for="tab3" role="button">
                                <span>
                                    <?php require GSWPTS_PRO_BASE_PATH.'Assets/Public/Icons/sort-numeric-up-solid.svg';?>
                                </span>
                                <span><?php _e('Sort & Filter', 'sheetstowptable');?></span>
                            </label>
                        </li>

                        <li title="<?php echo esc_attr('Table Tools'); ?>"
                            class="<?php echo isset($table_id) && !empty($table_id) ? esc_attr('tables_settings') : esc_attr('disabled_checkbox'); ?>"
                            data-btn-text="<?php echo esc_attr('Save Changes'); ?>"
                            data-attr-text="<?php echo esc_attr('save_changes'); ?>">
                            <label for="tab4" role="button">
                                <span>
                                    <?php require GSWPTS_PRO_BASE_PATH.'Assets/Public/Icons/tools-solid.svg';?>
                                </span>
                                <span><?php _e('Table Tools', 'sheetstowptable');?></span>
                            </label>
                        </li>

                    </ul>

                    <div class="slider">
                        <div class="indicator"></div>
                    </div>
                    <div class="content">

                        <section>

                            <div class="col-12 p-0">
                                <form id="gswpts_create_table" class="ui form">
                                    <?php $gswpts->nonce_field('gswpts_sheet_nonce_action', 'gswpts_sheet_nonce');?>
                                    <div class="row input_fields">

                                        <div class="col-12 col-md-4">

                                            <div class="ui fluid search selection dropdown" id="table_type">
                                                <input type="hidden" name="source_type">
                                                <i class="dropdown icon"></i>
                                                <div class="default text">
                                                    <?php _e('Choose Source Type', 'sheetstowptable');?></div>
                                                <div class="menu">
                                                    <div class="item" data-value="spreadsheet">
                                                        <?php echo esc_html('Google Spreadsheet'); ?>
                                                    </div>
                                                    <div class="item d-flex justify-content-between align-items-center disabled item"
                                                        data-value="<?php echo esc_attr('csv'); ?>">
                                                        <span><?php echo esc_html('CSV File'); ?></span>
                                                        <?php require GSWPTS_PRO_BASE_PATH.'Assets/Public/Icons/medal-solid.svg';?>
                                                    </div>
                                                    <div class="item d-flex justify-content-between align-items-center disabled item"
                                                        data-value="<?php echo esc_attr('excel'); ?>">
                                                        <span><?php echo esc_html('Excel File'); ?></span>
                                                        <?php require GSWPTS_PRO_BASE_PATH.'Assets/Public/Icons/medal-solid.svg';?>
                                                    </div>
                                                    <div class="item d-flex justify-content-between align-items-center disabled item"
                                                        data-value="<?php echo esc_attr('xml'); ?>">
                                                        <span><?php echo esc_html('XML File'); ?></span>
                                                        <?php require GSWPTS_PRO_BASE_PATH.'Assets/Public/Icons/medal-solid.svg';?>
                                                    </div>
                                                    <div class="item d-flex justify-content-between align-items-center disabled item"
                                                        data-value="<?php echo esc_attr('json'); ?>">
                                                        <span><?php echo esc_html('JSON File'); ?></span>
                                                        <?php require GSWPTS_PRO_BASE_PATH.'Assets/Public/Icons/medal-solid.svg';?>
                                                    </div>
                                                    <div class="item d-flex justify-content-between align-items-center disabled item"
                                                        data-value="<?php echo esc_attr('php_array'); ?>">
                                                        <span><?php echo esc_html('PHP Array'); ?></span>
                                                        <?php require GSWPTS_PRO_BASE_PATH.'Assets/Public/Icons/medal-solid.svg';?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-12 col-md-8 transition hidden file_input">
                                            <div class="ui icon input">
                                                <input required type="text" name="file_input"
                                                    placeholder="Enter URL of spreadsheet to load data">
                                                <span class="ui icon button p-0 m-0 helper_text"
                                                    data-tooltip="Share your sheet publicly. Publish the sheet to web & click the share button at the top of your spreadsheet"
                                                    data-position="left center" data-inverted="">
                                                    <?php require GSWPTS_PRO_BASE_PATH.'Assets/Public/Icons/info-circle-solid.svg';?>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-2 pl-2 transition hidden browse_input">
                                            <button id="browse_input" class="positive ui button m-0">
                                                <?php _e('Browse File', 'sheetstowptable');?>&nbsp;
                                                <i class="fas fa-hand-pointer"></i>
                                            </button>
                                        </div>

                                    </div>
                                </form>

                            </div>

                        </section>

                        <section id="display_settings">
                            <div class="row">

                                <?php load_template(GSWPTS_PRO_BASE_PATH.'Includes/Templates/Parts/indiviual_feature.php', false, [
                                        'feature_title' => __('Table Title', 'sheetstowptable'),
                                        'feature_desc'  => __('Enable this to show the table title in <i>h3</i> tag above the table in your website front-end', 'sheetstowptable'),
                                        'input_name'    => 'show_title',
                                        'checked'       => false
                                ]);?>

                                <div class="col-md-4 mt-3 mb-3">
                                    <div class="ui cards">
                                        <div class="card">
                                            <div class="content">
                                                <div class="header">
                                                    <?php _e('Default rows per page', 'sheetstowptable')?></div>
                                                <div class="description">
                                                    <?php _e('This will show rows per page in the frontend', 'sheetstowptable')?>
                                                </div>

                                                <div class="ui fluid selection dropdown" id="rows_per_page">
                                                    <input type="hidden" name="rows_per_page">
                                                    <i class="dropdown icon"></i>
                                                    <div class="default text">
                                                        <?php _e('Rows Per Page', 'sheetstowptable')?></div>

                                                    <div class="menu">
                                                        <div class="item" data-value="<?php echo esc_attr('1'); ?>">
                                                            <?php _e('1', 'sheetstowptable');?>
                                                        </div>
                                                        <div class="item" data-value="<?php echo esc_attr('5'); ?>">
                                                            <?php _e('5', 'sheetstowptable');?>
                                                        </div>
                                                        <div class="item" data-value="<?php echo esc_attr('10'); ?>">
                                                            <?php _e('10', 'sheetstowptable');?>
                                                        </div>
                                                        <div class="item" data-value="<?php echo esc_attr('15'); ?>">
                                                            <?php _e('15', 'sheetstowptable');?>
                                                        </div>
                                                        <div class="item" data-value="<?php echo esc_attr('25'); ?>">
                                                            <?php _e('25', 'sheetstowptable');?>
                                                        </div>
                                                        <div class="item" data-value="<?php echo esc_attr('50'); ?>">
                                                            <?php _e('50', 'sheetstowptable');?>
                                                        </div>
                                                        <div class="item" data-value="<?php echo esc_attr('100'); ?>">
                                                            <?php _e('100', 'sheetstowptable');?>
                                                        </div>
                                                        <div class="item" data-value="<?php echo esc_attr('all'); ?>">
                                                            <?php _e('All', 'sheetstowptable');?>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php load_template(GSWPTS_PRO_BASE_PATH.'Includes/Templates/Parts/indiviual_feature.php', false, [
                                        'feature_title' => __('Show info block', 'sheetstowptable'),
                                        'feature_desc'  => __('Show <i>Showing X to Y of Z entries</i>block below the table', 'sheetstowptable'),
                                        'input_name'    => 'info_block',
                                        'checked'       => true
                                ]);?>

                                <?php load_template(GSWPTS_PRO_BASE_PATH.'Includes/Templates/Parts/indiviual_feature.php', false, [
                                        'feature_title' => __('Resposive Table', 'sheetstowptable'),
                                        'feature_desc'  => __('Allow collapsing on mobile and tablet screen', 'sheetstowptable'),
                                        'input_name'    => 'responsive',
                                        'checked'       => false,
                                        'is_pro'        => false
                                ]);?>

                                <?php load_template(GSWPTS_PRO_BASE_PATH.'Includes/Templates/Parts/indiviual_feature.php', false, [
                                        'feature_title' => __('Show X entries', 'sheetstowptable'),
                                        'feature_desc'  => __('<i>Show X entries</i> per page dropdown', 'sheetstowptable'),
                                        'input_name'    => 'show_entries',
                                        'checked'       => true
                                ]);?>

                                <?php load_template(GSWPTS_PRO_BASE_PATH.'Includes/Templates/Parts/indiviual_feature.php', false, [
                                        'feature_title' => __('Swap Filters', 'sheetstowptable'),
                                        'feature_desc'  => __('Swap the places of <i> X entries</i> dropdown & search filter input', 'sheetstowptable'),
                                        'input_name'    => 'swap_filter_inputs',
                                        'checked'       => false
                                ]);?>

                                <?php load_template(GSWPTS_PRO_BASE_PATH.'Includes/Templates/Parts/indiviual_feature.php', false, [
                                        'feature_title' => __('Swap Bottom Elements', 'sheetstowptable'),
                                        'feature_desc'  => __('Swap the places of <i>Showing X to Y of Z entries</i> with table pagination filter', 'sheetstowptable'),
                                        'input_name'    => 'swap_bottom_options',
                                        'checked'       => false
                                ]);?>

                                <?php load_template(GSWPTS_PRO_BASE_PATH.'Includes/Templates/Parts/indiviual_feature.php', false, [
                                        'feature_title' => __('Vertical Scrolling', 'sheetstowptable'),
                                        'feature_desc'  => __('Turning ON this feature will enable the table to scroll vertically', 'sheetstowptable'),
                                        'input_name'    => 'vertical_scrolling',
                                        'checked'       => false,
                                        'is_pro'        => true
                                ]);?>

                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <button class="ui violet button m-0" type="button">
                                        <?php _e('Display Documention', 'sheetstowptable')?> <span class="ml-2">
                                            <?php require GSWPTS_PRO_BASE_PATH.'Assets/Public/Icons/cogs-solid.svg';?>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </section>

                        <section id="sort_filter">
                            <div class="row">

                                <?php load_template(GSWPTS_PRO_BASE_PATH.'Includes/Templates/Parts/indiviual_feature.php', false, [
                                        'feature_title' => __('Allow Sorting', 'sheetstowptable'),
                                        'feature_desc'  => __('Enable this feature to sort table data for frontend.', 'sheetstowptable'),
                                        'input_name'    => 'sorting',
                                        'checked'       => true
                                ]);?>

                                <?php load_template(GSWPTS_PRO_BASE_PATH.'Includes/Templates/Parts/indiviual_feature.php', false, [
                                        'feature_title' => __('Search Bar', 'sheetstowptable'),
                                        'feature_desc'  => __('Enable this feature to show a search bar in for the table. It will help user to search data in the table', 'sheetstowptable'),
                                        'input_name'    => 'search_table',
                                        'checked'       => true
                                ]);?>

                                <?php load_template(GSWPTS_PRO_BASE_PATH.'Includes/Templates/Parts/indiviual_feature.php', false, [
                                        'feature_title' => __('Rows Highlight', 'sheetstowptable'),
                                        'feature_desc'  => __('Enable this feature to show highlighted rows of the table in the frontend selected by admin/user', 'sheetstowptable'),
                                        'input_name'    => 'rows_highlight',
                                        'checked'       => false,
                                        'is_pro'        => true
                                ]);?>

                                <?php load_template(GSWPTS_PRO_BASE_PATH.'Includes/Templates/Parts/indiviual_feature.php', false, [
                                        'feature_title' => __('Chart Integration', 'sheetstowptable'),
                                        'feature_desc'  => __('Enable this feature to filter data by various terms in the sheet & is going to show all the filtered data in the table as well as in a chart', 'sheetstowptable'),
                                        'input_name'    => 'chart_integration',
                                        'checked'       => false,
                                        'is_pro'        => true
                                ]);?>

                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <button class="ui violet button m-0" type="button">
                                        <?php _e('Sorting Documention', 'sheetstowptable');?> <span class="ml-2">
                                            <?php require GSWPTS_PRO_BASE_PATH.'Assets/Public/Icons/sort-numeric-up-solid.svg';?>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </section>

                        <section id="table_tools">

                            <div class="row">

                                <div class="col-md-4 mt-3 mb-3">
                                    <div class="ui cards">
                                        <div class="card">
                                            <div class="content">
                                                <span class="pro_feature"><i class="fas fa-medal"></i></span>
                                                <div class="header"><?php _e('Table Exporting', 'sheetstowptable');?>
                                                </div>
                                                <div class="description">
                                                    <?php _e('Enable this feature in order to allow your user to download your table content as various format.');?>
                                                </div>
                                                <select name="skills" multiple="" class="ui fluid dropdown mt-2"
                                                    id="table_exporting">
                                                    <option value=""><?php _e('Select Type', 'sheetstowptable');?>
                                                    </option>
                                                    <option value="<?php echo esc_attr('json'); ?>">
                                                        <?php echo esc_html('JSON'); ?></option>
                                                    <option value="<?php echo esc_attr('pdf'); ?>">
                                                        <?php echo esc_html('PDF'); ?></option>
                                                    <option value="<?php echo esc_attr('csv'); ?>">
                                                        <?php echo esc_html('CSV'); ?></option>
                                                    <option value="<?php echo esc_attr('excel'); ?>">
                                                        <?php echo esc_html('Excel'); ?></option>
                                                    <option value="<?php echo esc_attr('print'); ?>">
                                                        <?php echo esc_html('Print'); ?></option>
                                                    <option value="<?php echo esc_attr('copy'); ?>">
                                                        <?php echo esc_html('Copy'); ?></option>
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <button class="ui violet button m-0" type="button">
                                        <?php _e('Table Tools Doc', 'sheetstowptable')?><span class="ml-2">
                                            <?php require GSWPTS_PRO_BASE_PATH.'Assets/Public/Icons/tools-solid.svg'?>
                                        </span>
                                    </button>
                                </div>
                            </div>

                        </section>
                    </div>
                </div>

            </div>
        </div>


        <div class="row transition hidden" id="sheet_details">
        </div>

        <div class="row mt-4">
            <div id="spreadsheet_container"
                class="col-12 d-flex justify-content-center align-content-center p-relative p-0 position-relative">

                <?php if (isset($table_id) && !empty($table_id)): ?>

                <div class="ui segment gswpts_table_loader" style="z-index: -1;">
                    <div class="ui active inverted dimmer">
                        <div class="ui large text loader"><?php _e('Loading', 'sheetstowptable');?></div>
                    </div>
                    <p></p>
                    <p></p>
                    <p></p>
                </div>

                <?php endif?>


            </div>
        </div>


    </div>

</div>