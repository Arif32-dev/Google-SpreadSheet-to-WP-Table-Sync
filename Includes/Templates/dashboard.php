<?php
    global $gswpts;
?>


<div class="gswpts_dashboard_container" id="toplevel_page_gswpts-dashboard">

    <div class="ui segment gswpts_loader">
        <div class="ui active inverted dimmer">
            <div class="ui massive text loader"></div>
        </div>
        <p></p>
        <p></p>
        <p></p>
    </div>


    <div class="child_container mt-4 dashboard_content transition hidden">

        <div class="row heading_row">
            <div class="col-12 d-flex justify-content-start p-0 align-items-center">
                <img src="<?php echo esc_url(GSWPTS_BASE_URL.'Assets/Public/Images/logo_30_30.svg'); ?>" alt="">
                <span class="ml-2">
                    <strong><?php echo PlUGIN_NAME; ?></strong>
                </span>
            </div>
            <div class="col-12 p-0 mt-2 d-flex justify-content-between align-items-center">
                <h3 class="m-0">
                    <?php _e('Dashboard', 'sheetstowptable');?>
                </h3>
            </div>
        </div>

        <div class="row mt-3 dash_boxes pt-3 pb-3 pl-2 pr-2">

            <div class="col-md-7 col-12" style="display: flex; justify-content: center; flex-direction: column">
                <?php printf('<h2 class="p-0 m-t-0 m-b-4">%s %s</h2', __('Welcome to', 'sheetstowptable'), PlUGIN_NAME);?>
                <p><?php _e('Congratulations! You are about to use the most powerful Google spreadsheet data synchronization', 'sheetstowptable');?>
                </p>

                <iframe style="width: 100%;" height="370" src="https://www.youtube-nocookie.com/embed/_LWcaErh8jw"
                    title="How to install and use Google Spreadsheets to WP Table Live Sync" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>

            </div>
            <div class="col-md-5 d-flex justify-content-center align-items-center">
                <img class="img-responsive wdt-welcomr-img"
                    src="<?php echo esc_url(GSWPTS_BASE_URL.'Assets/Public/Images/welcome_message.svg'); ?>"
                    alt="Welcome message">
            </div>

        </div>

        <div class="row mt-3  pt-3 pb-3">
            <div class="col-md-6 col-12 p-0 m-0">
                <!-- Latest table start -->
                <div class="col-md-12 p-0 pr-2 latest_table">
                    <div class="card p-0 m-0">
                        <div class="card-header d-flex align-iteml-center justify-content-between">
                            <div class="title">
                                <span><?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/table-solid.svg';?></span>
                                <span class="ml-2"><?php _e('Tables', 'sheetstowptable');?></span>
                            </div>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=gswpts-create-tables')); ?>"
                                class="ui inverted green button">
                                <?php _e('Create Tables', 'sheetstowptable');?>
                            </a>
                        </div>
                        <div class="card-body d-flex">
                            <div
                                class="col-3 d-flex justify-content-center align-item-center flex-column text-center total_created">
                                <span><?php _e($gswpts->latest_table_details()['total_table_count']);?></span>
                                <span><?php _e('Created', 'sheetstowptable');?></span>
                            </div>
                            <div class="col-9 details d-flex flex-wrap">
                                <?php $gswpts->showCreatedTables()?>
                                <br>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Latest end start -->
            </div>

        </div>

        <div class="row mt-3 dash_boxes pb-3 pt-3" style="background: transparent;">

            <div class="col-sm-12 p-0 m-0">
                <h2 class="text-center text-capitalize">
                    <?php _e('Our Other Products', 'sheetstowptable');?>
                </h2>
                <p class="text-center">
                    <?php _e('Experience remarkable WordPress products with a new level of power, beauty, and human-centered designs.
                    Think you know WordPress products? Think Deeper!', 'sheetstowptable');?>
                </p>
            </div>
        </div>


        <!-- Our other product section -->

        <div class="row mt-3 other_products_section">
        </div>

        <!-- End of our other product section -->

    </div>
</div>