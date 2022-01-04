<?php
global $gswptsTabFunctions;
$tabID = $gswptsTabFunctions->tabUpdatePage();
$updateOrCreateBtn = $tabID ? 'update' : 'create';
?>

<div class="gswpts_create_tab_container">

    <div class="ui segment gswpts_loader">
        <div class="ui active inverted dimmer">
            <div class="ui massive text loader"></div>
        </div>
        <p></p>
        <p></p>
        <p></p>
    </div>

    <div class="child_container mt-4 create_tab_content transition hidden">

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
            <div class="col-12 col-lg-12 p-0 d-flex align-items-center justify-content-start">

                <a class="ui violet button" href="<?php echo admin_url('admin.php?page=gswpts-manage-tab') ?>">
                    <?php _e('<i class="fas fa-angle-double-left"></i> &nbsp;Back', 'sheetstowptable');?>
                </a>

                <button
                    class="gswpts_save_tab_changes <?php echo esc_attr($updateOrCreateBtn) ?> ui violet button m-0 ml-3">
                    <?php _e('Save Changes', 'sheetstowptable');?>&nbsp; <i class='fas fa-save'></i>
                </button>

            </div>
        </div>

        <div class="row mt-4">

            <div class="col-12 col-md-12 col-lg-8">

                <div class="row left_side_parent">


                    <?php if (!$tabID) {?>

                    <div class="col-12 top_side">
                        <div class="mb-2 mt-2">
                            <div class="mb-3 mt-3">
                                <strong class="mr-3">Tab Name:</strong>
                                <div class="ui icon input mr-3">
                                    <input type="text" class="container_tab_name" value="Tab name">
                                </div>
                            </div>
                            <div class="mb-3 mt-3">
                                <strong class="mr-3">How many tab page:</strong>
                                <div class="ui icon input mr-3">
                                    <input type="number" value="2" class="tab_page_input">
                                </div>
                            </div>
                        </div>

                        <i class="fas fa-plus tab_input_btn"></i>

                    </div>

                    <?php }?>


                    <?php if (!$tabID) {?>
                    <div class="col-12 mt-4 tab_bottom_side demo_template">

                        <div class="ui labeled input tab_name_box">
                            <div class="ui label">
                                Tab Name:
                            </div>
                            <input type="text" class="tab_name" placeholder="Tab name">
                            <span class="tab_positon_btn">
                                <i class="fas fa-arrow-up"></i>
                            </span>
                        </div>

                        <div class="tabs_container">

                            <i class="fas fa-times close_tab_container"></i>

                            <ul class="tabs" role="tablist">
                                <li>
                                    <input type="radio" name="tabs0" id="tab0" class="tab_hidden_input" checked />
                                    <label for="tab0" role="tab">Tab#1</label>
                                </li>
                            </ul>

                            <div class="tab_contents">
                                <div id="tab-content0" class="tab-content active">
                                    <p>Tab content
                                    </p>
                                </div>
                            </div>

                        </div>

                    </div>
                    <?php } else {?>

                    <?php echo $gswptsTabFunctions->getTabByID(['id' => $tabID]) ?>

                    <?php }?>

                </div>

            </div>


            <div class="col-12 col-md-12 col-lg-4 tab_right_side">


                <div class="cards_container">

                    <?php echo $gswptsTabFunctions->showTabCards() ?>

                </div>

            </div>

        </div>

    </div>

</div>