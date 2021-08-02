<?php if (isset($_GET['subpage']) && sanitize_text_field($_GET['subpage']) == 'create-table') {?>
<?php load_template(GSWPTS_BASE_PATH.'Includes/Templates/create_tables.php')?>
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
                <img src="<?php echo esc_url(GSWPTS_BASE_URL.'Assets/Public/Images/logo_30_30.svg') ?>"
                    alt="sheets-logo">
                <span class="ml-2">
                    <strong><?php echo PlUGIN_NAME; ?></strong>
                </span>
            </div>
        </div>

        <div id="delete_button_container" class="row">
            <div class="col-12 p-0">
                <button id="delete_button" class="negative ui button mr-0 float-right transition hidden"
                    data-show="false">
                    <span>
                        <?php _e('Delete Selected', 'sheetstowptable');?>
                    </span>
                </button>
            </div>
        </div>


        <div class="row">

            <div id="gswpts_tables_container" class="col-12 p-0 mt-3 position-relative">

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
            <a class="positive ui button mr-2 float-left" style="font-size: 1.03rem; position: relative;top: -55px;"
                href="<?php echo esc_url(admin_url('admin.php?page=gswpts-dashboard&subpage=create-table')); ?>">
                <?php _e('Create Table', 'sheetstowptable');?>
            </a>
            <!-- End of create table button -->

            <!-- Start popup modal -->
            <div class="ui mini modal semntic-popup-modal" style="height: 180px;
                                                                position: absolute;
                                                                top: 40%;
                                                                left: 50%;
                                                                margin: -50px 0 0 -190px;">
                <div class="header">
                    Delete Your Table
                </div>
                <div class="content">
                    <p>Are you sure you want to delete your this table ?</p>
                </div>
                <div class="actions">
                    <div class="ui negative button cancel-btn">
                        No
                    </div>
                    <div class="ui positive button yes-btn">
                        Yes
                    </div>
                </div>
            </div>

            <!-- End of popup modal-->

        </div>

    </div>


</div>
<?php }?>