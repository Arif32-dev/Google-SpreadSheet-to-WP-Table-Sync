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
                    <?php _e('Documentation', 'sheetstowptable');?>
                </h3>
                <span>
                    <a class="ui violet button m-0" href="https://swptls.wppool.dev/" target="_blank">
                        <?php _e('View Demo', 'sheetstowptable');?> <span
                            class="ml-2"><?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/file-alt-solid.svg';?></span>
                    </a>
                </span>
            </div>
        </div>

        <div class="row mt-3 dash_boxes pt-3 pb-3 pl-2 pr-2">

            <div class="col-md-6 col-12" style="display: flex; justify-content: center; flex-direction: column">
                <?php printf('<h2 class="p-0 m-t-0 m-b-4">%s %s</h2', __('Welcome to', 'sheetstowptable'), PlUGIN_NAME);?>
                <p><?php _e('Congratulations! You are about to use the most powerful Google spreadsheet data synchronization', 'sheetstowptable');?>
                </p>

                <iframe style="width: 100%;" height="370" src="https://www.youtube.com/embed/BW3urHKzNP0"
                    title="How to install and use Google Spreadsheets to WP Table Live Sync" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>

            </div>

            <div class="col-md-6 d-flex justify-content-center align-items-center">

                <!-- Start help center -->
                <div class="card p-0 m-0" style="height: 90%; width: 100%">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="title">
                            <span>
                                <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/people-carry-solid.svg';?>
                            </span>
                            <span class="ml-2"><?php _e('Help Center', 'sheetstowptable');?></span>
                        </div>
                        <a target="blank" href="<?php echo esc_url('https://wppool.dev/contact/'); ?>"
                            class="ui inverted green button">
                            <?php _e('Get Help', 'sheetstowptable');?>
                        </a>
                    </div>
                    <div class="card-body" style="background-image: url(<?php echo esc_url(GSWPTS_BASE_URL.'Assets/Public/Images/need_help.svg'); ?>);
                        background-repeat: no-repeat;
                        background-size: cover;
                        background-position: 85px;
                        ">
                        <div class="col-12 d-flex p-0">
                            <div class="col-6">
                                <h4 class="">
                                    <?php _e('Need some help', 'sheetstowptable');?>
                                </h4>
                                <p>
                                    <?php _e('We provide professional support to all our users via our ticketing system', 'sheetstowptable');?>.
                                </p>
                                <a href="<?php echo esc_url('https://wppool.dev/contact/'); ?>" target="_blank">
                                    <?php _e('Visit Support Center', 'sheetstowptable');?>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- End of help center -->

            </div>

        </div>


    </div>


</div>