<?php
global $gswpts;
$table_id = isset($_GET['id']) && !empty($_GET['id']) ? sanitize_text_field($_GET['id']) : null;
?>

<div class="gswpts_create_table_container dt-buttons">



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
                <img src="<?php echo esc_url(GSWPTS_BASE_URL . 'Assets/Public/Images/logo_30_30.svg'); ?>" alt="">
                <span class="ml-2">
                    <strong><?php echo PlUGIN_NAME; ?></strong>
                </span>
                <span class="gswpts_changelogs" style="margin-top: -5px;"></span>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 p-0 d-flex align-items-center">

                <a class="ui violet button" href="<?php echo admin_url('admin.php?page=gswpts-dashboard') ?>">
                    <?php _e('<i class="fas fa-angle-double-left"></i> &nbsp;Back', 'sheetstowptable');?>
                </a>

                <div class="col p-0 d-flex align-items-center justify-content-end">
                    <button id="create_button"
                        class="positive ui transition button m-0 mr-2                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     <?php echo isset($table_id) && !empty($table_id) ? '' : 'transition hidden' ?>"
                        style="padding-left: 30px;">
                        <?php _e('Create New', 'sheetstowptable');?> &nbsp; <i class="fas fa-plus"></i>
                    </button>
                    <button class="ui violet button m-0 transition hidden fetch_save_btn" type="button"
                        req-type="<?php echo isset($table_id) && !empty($table_id) ? 'save' : 'fetch' ?>">
                        <span class="btn_text">
                            <?php echo isset($table_id) && !empty($table_id) ? __('Save Table', 'sheetstowptable') : __('Fetch Data', 'sheetstowptable'); ?>
                        </span>
                        &nbsp;
                        <?php if (isset($table_id) && !empty($table_id)) {?>
                        <i class='fas fa-save'></i>
                        <?php }?>
                    </button>
                </div>

            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 p-0" id="gswpts_tabs">

                <div class="tabs">

                    <input type="radio" id="tab1" name="tab-control" checked>
                    <input <?php echo isset($table_id) && !empty($table_id) ? '' : 'disabled' ?> type="radio" id="tab2"
                        name="tab-control" class="secondary_inputs">
                    <input <?php echo isset($table_id) && !empty($table_id) ? '' : 'disabled' ?> type="radio" id="tab3"
                        name="tab-control" class="secondary_inputs">
                    <input <?php echo isset($table_id) && !empty($table_id) ? '' : 'disabled' ?> type="radio" id="tab4"
                        name="tab-control" class="secondary_inputs">
                    <ul>
                        <li title="<?php echo esc_attr('Data Source') ?>" class="tables_settings"
                            data-btn-text="<?php echo isset($table_id) && !empty($table_id) ? esc_attr('Save Table') : esc_attr('Fetch Data') ?>"
                            data-attr-text="<?php echo isset($table_id) && !empty($table_id) ? esc_attr('save') : esc_attr('fetch'); ?>">
                            <label for="tab1" role="button">
                                <i class="fas fa-box-open"></i>
                                <span><?php _e('Data Source', 'sheetstowptable');?></span>
                            </label>
                        </li>

                        <li title="<?php echo esc_attr('Display Settings'); ?>"
                            class="<?php echo isset($table_id) && !empty($table_id) ? esc_attr('tables_settings') : esc_attr('disabled_checkbox'); ?>"
                            data-btn-text="<?php echo esc_attr('Save Changes'); ?>"
                            data-attr-text="<?php echo esc_attr('save_changes'); ?>">
                            <label for="tab2" role="button">
                                <i class="fas fa-cogs"></i>
                                <span><?php _e('Display Settings', 'sheetstowptable');?></span>
                            </label>
                        </li>

                        <li title="<?php echo esc_attr('Sort & Filter'); ?>"
                            class="<?php echo isset($table_id) && !empty($table_id) ? esc_attr('tables_settings') : esc_attr('disabled_checkbox'); ?>"
                            data-btn-text="<?php echo esc_attr('Save Changes'); ?>"
                            data-attr-text="<?php echo esc_attr('save_changes'); ?>">
                            <label for="tab3" role="button">
                                <i class="fas fa-sort-amount-up-alt"></i>
                                <span><?php _e('Sort & Filter', 'sheetstowptable');?></span>
                            </label>
                        </li>

                        <li title="<?php echo esc_attr('Table Tools'); ?>"
                            class="<?php echo isset($table_id) && !empty($table_id) ? esc_attr('tables_settings') : esc_attr('disabled_checkbox'); ?>"
                            data-btn-text="<?php echo esc_attr('Save Changes'); ?>"
                            data-attr-text="<?php echo esc_attr('save_changes'); ?>">
                            <label for="tab4" role="button">
                                <i class="fas fa-tools"></i>
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

                                        <div class="col-12 col-md-3">

                                            <div class="ui fluid search selection dropdown" id="table_type">
                                                <input type="hidden" name="source_type" value="spreadsheet">
                                                <i class="dropdown icon"></i>
                                                <div class="default text">
                                                    <?php _e('Choose Source Type', 'sheetstowptable');?></div>
                                                <div class="menu">
                                                    <div class="item" data-value="spreadsheet">
                                                        <?php echo esc_html('Google Spreadsheet'); ?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div
                                            class="<?php echo isset($table_id) && !empty($table_id) ? 'hide-column' : ''; ?> col-12 col-md-3">
                                            <div style="width: 100%;"
                                                class="ui input                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             <?php echo isset($table_id) && !empty($table_id) ? 'transition hidden' : ''; ?>">
                                                <input
                                                    <?php echo isset($table_id) && !empty($table_id) ? 'disabled' : ''; ?>
                                                    type="text" placeholder="Table Name" id="table_name"
                                                    name="table_name" value="GSWPTS Table">
                                            </div>

                                        </div>

                                        <div
                                            class="col-12 file_input
                                            <?php echo isset($table_id) && !empty($table_id) ? 'col-md-9' : 'col-md-6'; ?>">
                                            <div class="ui icon input">
                                                <input required type="text" name="file_input" id="file_input"
                                                    placeholder="Enter URL of spreadsheet to load data">
                                                <span class="ui icon button p-0 m-0 helper_text"
                                                    data-tooltip="Share your sheet publicly. Publish the sheet to web & click the share button at the top of your spreadsheet"
                                                    data-position="left center" data-inverted="">
                                                    <i class="fas fa-info-circle" style="font-size: 15.5px;"></i>
                                                </span>
                                            </div>
                                        </div>


                                    </div>
                                </form>

                            </div>

                        </section>

                        <section id="display_settings">

                            <div class="feature-container">
                                <?php $gswpts->displaySettingsHTML();?>
                            </div>

                        </section>

                        <section id="sort_filter">
                            <div class="feature-container">
                                <?php $gswpts->sortAndFilterHTML();?>
                            </div>

                        </section>

                        <section id="table_tools">

                            <div class="feature-container">
                                <?php $gswpts->tableToolsHTML()?>
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

            <?php if (!isset($table_id) && empty($table_id)) {?>
            <div class="bottom_next_btn">
                <button class="ui orange button m-0 transition hidden next-setting bottom_btn" type="button"
                    req-type="<?php echo isset($table_id) && !empty($table_id) ? 'save' : 'fetch' ?>">
                    <span class="btn_text">
                        Next
                    </span>
                    &nbsp;<i class='fas fa-angle-double-right'></i>
                </button>
            </div>
            <?php }?>

        </div>


    </div>

    <!-- Load all the modals here -->
    <?php load_template(GSWPTS_BASE_PATH . 'includes/templates/parts/popup_modals.php')?>
    <!-- End of all modals -->

    <!-- Popup modal for pro feature -->
    <?php load_template(GSWPTS_BASE_PATH . 'includes/templates/parts/promo_large.php')?>
    <!-- End of pro feature popup modal -->

</div>