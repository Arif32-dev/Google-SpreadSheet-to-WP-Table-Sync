<script>
var HW_config = {
    selector: ".gswpts_changelogs",
    account: "7kPL5J"
}
</script>
<script async src="https://cdn.headwayapp.co/widget.js"></script>

<?php if (isset($_GET['subpage']) && sanitize_text_field($_GET['subpage']) == 'create-tab') {?>
<?php load_template(GSWPTS_BASE_PATH . 'includes/templates/create_tab.php')?>
<?php } else {?>
<div class="gswpts_manage_table_container">


    <div class="ui segment gswpts_loader">
        <div class="ui active inverted dimmer">
            <div class="ui massive text loader"></div>
        </div>
        <p></p>
        <p></p>
        <p></p>
    </div>

    <div class="child_container mt-4 manage_table_content transition hidden">

        <div class="row heading_row">
            <div class="col-12 d-flex justify-content-start p-0 align-items-center">
                <img src="<?php echo esc_url(GSWPTS_BASE_URL . 'assets/public/images/logo_30_30.svg') ?>"
                    alt="sheets-logo">
                <span class="ml-2">
                    <strong><?php echo PlUGIN_NAME; ?></strong>
                </span>
                <span class="gswpts_changelogs"></span>
            </div>
        </div>

        <div id="delete_button_container" class="row">
            <div class="col-12 p-0">
                <button id="tab_delete_button" class="negative ui button mr-0 float-right transition hidden"
                    data-show="false">
                    <span>
                        <?php _e('Delete Selected', 'sheetstowptable');?> &nbsp; <i class="fas fa-trash-alt"></i>
                    </span>
                </button>

                <button id="manage_tab_unselect_btn" class="ui violet button mr-2 float-right transition hidden">
                    <span>
                        <?php _e('Clear Selection', 'sheetstowptable');?> &nbsp; <i class="fas fa-minus"></i>
                    </span>
                </button>

            </div>
        </div>


        <div class="row ml-1">

            <div id="gswpts_tab_container" class="col-12 p-0 mt-3 position-relative">

                <div class="ui segment gswpts_table_loader">
                    <div class="ui active inverted dimmer">
                        <div class="ui large text loader"><?php _e('Loading', 'sheetstowptable');?></div>
                    </div>
                    <p></p>
                    <p></p>
                    <p></p>
                </div>

            </div>

            <!-- Start create table button -->
            <div class="col-12 pl-0">
                <a class="positive ui button mr-2 float-left transition hidden tab_create_btn"
                    style="font-size: 1.03rem; position: relative;top: -45px; margin-left: -15px"
                    href="<?php echo esc_url(admin_url('admin.php?page=gswpts-manage-tab&subpage=create-tab')); ?>">
                    <?php _e('Create Tab', 'sheetstowptable');?> &nbsp; <i class="fas fa-plus"></i>
                </a>
            </div>
            <!-- End of create table button -->

            <!-- Start popup modal -->
            <div class="ui mini modal semntic-popup-modal" style="height: 180px;
                                                                position: absolute;
                                                                top: 40%;
                                                                left: 50%;
                                                                margin: -50px 0 0 -190px;
                                                                ">
                <div class="header">
                    Delete Your Tab
                </div>
                <div class="content">
                    <p><b class="gswpts_popup_content"></b></p>
                </div>
                <div class="actions">
                    <div class="ui negative button yes-btn">
                        Yes
                    </div>
                    <div class="ui positive button cancel-btn">
                        No
                    </div>
                </div>
            </div>

            <!-- End of popup modal-->

        </div>

    </div>


</div>
<?php }?>

<style>
.ui.dimmer.modals {
    background: none !important;
}
</style>