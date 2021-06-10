<?php
    global $gswpts;
?>


<div class="gswpts_dashboard_container">

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
                    <a class="ui violet button m-0" href="https://youtu.be/_LWcaErh8jw" target="blank">
                        <?php _e('View Documention', 'sheetstowptable');?> <span
                            class="ml-2"><?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/file-alt-solid.svg';?></span>
                    </a>
                </span>
            </div>
        </div>


        <div class="row mt-3  pt-3 pb-3">
            <div class="col-md-6 col-12 p-0 m-0">

                <!-- Change Logs start -->
                <div class="col-md-12 p-0 pr-2 change_logs">
                    <div class="card p-0 m-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="title">
                                <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/file-alt-solid.svg';?>
                                <span class="ml-2"><?php _e('Change Logs', 'sheetstowptable');?></span>
                            </div>
                            <a href="<?php echo self_admin_url('plugin-install.php?tab=plugin-information&plugin=sheets-to-wp-table-live-sync&section=changelog&TB_iframe=true&width=600&height=800') ?>"
                                class="ui inverted green button">
                                <?php _e('View All Logs', 'sheetstowptable');?>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="col-12 mb-2">
                                <?php _e('You are currently using Version: ', 'sheetstowptable');?><?php echo GSWPTS_VERSION ?>
                            </div>
                            <div class="col-12 mb-2">
                                <?php _e('The first realease of this plugin with compatibility checking', 'sheetstowptable');?>:
                            </div>

                            <?php echo $gswpts->changeLogs(); ?>

                        </div>
                    </div>
                </div>
                <!-- Change Logs end -->

                <!-- News Blogs start -->
                <div class="col-md-12 p-0 pr-2 mt-3 news_blogs">
                    <div class="card p-0 m-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="title">
                                <span>
                                    <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/blog-solid.svg'?>
                                </span>
                                <span class="ml-2"><?php _e('Our Blogs', 'sheetstowptable');?></span>
                            </div>
                            <a href="<?php echo esc_url('https://wppool.dev/blog/'); ?>" target="blank"
                                class="ui inverted green button">
                                <?php _e('Read Blogs', 'sheetstowptable');?>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="col-12">
                                <p><?php _e('Checkout useful articles from', 'sheetstowptable');?> <a class="ml-1"
                                        href="<?php echo esc_url('https://wppool.dev/blog/'); ?>"
                                        target="blank">WPPOOL</a></p>

                                <div class="col-12 p-0 mt-3 useful_links">
                                    <div class="ui fitted divider mt-2 mb-2"></div>
                                </div>

                                <div class="col-12 p-0 mt-4 subscribe_sec">

                                    <div class="ui cards">
                                        <div class="card">
                                            <div class="content">
                                                <div class="header"><?php _e('Get Subscribed', 'sheetstowptable');?>
                                                </div>
                                                <div class="description">

                                                    <p>
                                                        <?php _e('Never miss notifications about new cool features, promotions, giveaways or freebies – subscribe to our newsletter!
                                                        We send about 1 message per month and never spam', 'sheetstowptable');?>!
                                                    </p>

                                                    <form id="subscriber-form" method="post" action=""
                                                        class="ui right labeled left icon input mt-3">
                                                        <i class="bell icon"></i>
                                                        <input style="width: 250px;" id="wemail-email" type="email"
                                                            name="email" required placeholder="You email" />
                                                        <input style="width: 250px;" id="full_name" type="hidden"
                                                            name="full_name" required
                                                            value="<?php esc_html_e(wp_get_current_user()->data->display_name);?>" />
                                                        <input style="width: 250px;" id="url" type="hidden" name="url"
                                                            required
                                                            value="<?php echo esc_url('https://wppool.dev/wp-admin/?fluentcrm=1&route=contact&hash=8297bc37-59a2-4fd8-b77b-220d673642bb'); ?>" />
                                                        <?php wp_nonce_field('user_subscription_action', 'user_subscription')?>
                                                        <button type="submit" class="ui violet tag label"
                                                            id="subscribe_btn" style="right: 28px;">
                                                            <?php _e('Get Subscribed', 'sheetstowptable');?>
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- News Blog End -->
            </div>


            <div class="col-md-6 col-12 p-0 m-0">

                <div class="col-md-12 p-0">
                    <div class="card p-0 m-0">
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
                        <div class="card-body">
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
                                <div class="col-6">
                                    <img src="<?php echo esc_url(GSWPTS_BASE_URL.'Assets/Public/Images/need_help.svg'); ?>"
                                        alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12 p-0 mt-3 pro_box">
                    <div class="card p-0 m-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="title">
                                <span>
                                    <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/medal-solid.svg';?>
                                </span>
                                <span class="ml-2"><?php _e('Go Pro', 'sheetstowptable');?>!</span>
                            </div>
                            <a class="ui inverted green button" href="https://wppool.dev/sheets-to-wp-table-live-sync/"
                                target="blank">
                                <?php _e('Compare', 'sheetstowptable');?>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="col-12 p-0 m-0 d-flex">
                                <div class="col-6 p-0">
                                    <p>
                                        <?php _e('Get the most out of this awesome plugin by upgrading to Pro version and unlock all of the powerful features', 'sheetstowptable');?>.
                                    </p>
                                    <ul class="p-0 m-0">
                                        <li class="d-flex align-items-center mb-3">
                                            <span class="mr-2">
                                                <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/check-circle-solid.svg'?>
                                            </span>
                                            <span><b><?php _e('Responsive Table: ', 'sheetstowptable');?></b><?php echo PlUGIN_NAME ?><?php _e('Plugin is responsive for any device. The plugin allows collapsing on mobile and tablet screens', 'sheetstowptable');?></span>.
                                        </li>

                                        <li class="d-flex align-items-center mb-3">
                                            <span class="mr-2">
                                                <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/check-circle-solid.svg';?>
                                            </span>
                                            <span><b><?php _e('Export table: ', 'sheetstowptable')?></b><?php _e('Table Exporting via CSV, Excel, PDF, JSON, Print, Table Copy is easy on this plugin', 'sheetstowptable')?></span>
                                        </li>

                                        <li class="d-flex align-items-center mb-3">
                                            <span class="mr-2">
                                                <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/check-circle-solid.svg';?>
                                            </span>
                                            <span><b><?php _e('Unlimited rows fetching from the sheet: ', 'sheetstowptable')?></b><?php _e('Fetch as many row data you want to show it as WordPress table.', 'sheetstowptable')?></span>
                                        </li>

                                        <li class="d-flex align-items-center mb-3">
                                            <span class="mr-2">
                                                <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/check-circle-solid.svg';?>
                                            </span>
                                            <span><b><?php _e('Elementor Widget Support: ', 'sheetstowptable');?></b><?php echo PlUGIN_NAME; ?><?php _e('supports elementor widget. Organize your table data effortlessly than ever.', 'sheetstowptable');?></span>
                                        </li>

                                        <li class="d-flex align-items-center mb-3">
                                            <span class="mr-2">
                                                <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/check-circle-solid.svg';?>
                                            </span>
                                            <span><b><?php _e('Vertical Scroll: ', 'sheetstowptable');?></b><?php echo PlUGIN_NAME; ?><?php _e('Choose the height of the table to scroll vertically. This feature will allow the table to behave as sticky header', 'sheetstowptable');?></span>
                                        </li>

                                        <li class="d-flex align-items-center mb-3">
                                            <span class="mr-2">
                                                <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/check-circle-solid.svg';?>
                                            </span>
                                            <?php _e('And much more', 'sheetstowptable');?>.
                                        </li>

                                    </ul>
                                </div>
                                <div class="col-6 p-0 m-0">
                                    <img src="<?php echo esc_url(GSWPTS_BASE_URL.'Assets/Public/Images/premium.svg'); ?>"
                                        alt="" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-12">
                                <a class="ui violet button m-0" href="https://wppool.dev/sheets-to-wp-table-live-sync/"
                                    target="blank">
                                    <?php _e('Get Pro Today', 'sheetstowptable');?> <span class="ml-2">
                                        <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/medal-solid.svg';?>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>

    </div>
</div>