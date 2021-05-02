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


    <div class="container mt-4 dashboard_content transition hidden">

        <div class="row heading_row">
            <div class="col-12 d-flex justify-content-start p-0 align-items-center">
                <img src="<?php echo esc_url(GSWPTS_BASE_URL . 'Assets/Public/Images/logo_30_30.svg'); ?>" alt="">
                <span class="ml-2">
                    <strong><?php echo PlUGIN_NAME; ?></strong>
                </span>
            </div>
            <div class="col-12 p-0 mt-2 d-flex justify-content-between align-items-center">
                <h3 class="m-0">
                    <?php echo __('Dashboard', 'sheetstowptable') ?>
                </h3>
                <span>
                    <a class="ui violet button m-0" href="https://vimeo.com/526189502" target="blank">
                        <?php echo __('View Documention', 'sheetstowptable') ?> <span class="ml-2"><?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/file-alt-solid.svg'; ?></span>
                    </a>
                </span>
            </div>
        </div>

        <div class="row mt-3 dash_boxes pt-3 pb-3 pl-2 pr-2">

            <div class="col-md-7">
                <?php printf('<h2 class="p-0 m-t-0 m-b-4">%s %s</h2', __('Welcome to', 'sheetstowptable'), PlUGIN_NAME); ?>
                <p><?php echo __('Congratulations! You are about to use the most powerful Google spreadsheet data synchronization', 'sheetstowptable'); ?></p>
                <a href="<?php echo esc_url('https://vimeo.com/526189502'); ?>" target="blank">
                    <button class="ui inverted green button">
                        <?php echo __('Learn how to sync Google spreadsheet', 'sheetstowptable'); ?>
                    </button>
                </a>
            </div>
            <div class="col-md-5">
                <img class="img-responsive wdt-welcomr-img" src="<?php echo esc_url(GSWPTS_BASE_URL . 'Assets/Public/Images/welcome_message.svg'); ?>" alt="Welcome message">
            </div>

        </div>

        <div class="row mt-3  pt-3 pb-3">
            <div class="col-md-6 col-12 p-0 m-0">
                <!-- Latest table start -->
                <div class="col-md-12 p-0 pr-2 latest_table">
                    <div class="card p-0 m-0">
                        <div class="card-header d-flex align-iteml-center justify-content-between">
                            <div class="title">
                                <span><?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/table-solid.svg'; ?></span>
                                <span class="ml-2"><?php echo __('Tables', 'sheetstowptable'); ?></span>
                            </div>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=gswpts-create-tables')); ?>" class="ui inverted green button">
                                <?php echo __('Create Tables', 'sheetstowptable'); ?>
                            </a>
                        </div>
                        <div class="card-body d-flex">
                            <div class="col-3 d-flex justify-content-center align-iteml-center flex-column total_created">
                                <span><?php echo __($gswpts->latest_table_details()['total_table_count']); ?></span>
                                <span><?php echo __('Created', 'sheetstowptable'); ?></span>
                            </div>
                            <div class="col-9 details">
                                <div class="col-12 pl-0">
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=gswpts-create-tables&id=' . esc_attr($gswpts->latest_table_details()['last_table_id']) . '')); ?>"><?php echo __($gswpts->latest_table_details()['last_table_name'], 'sheetstowptable') ?></a>
                                </div>
                                <div class="col-12 mt-2 pl-0">Latest table created</div>
                                <?php if ($gswpts->latest_table_details()['last_table_id']) { ?>
                                    <div class="ui label mt-2">
                                        <i class="clone icon dashboard_sortcode_copy_btn"></i>
                                        <input type="hidden" name="sortcode" value="[gswpts_table id=<?php echo esc_attr($gswpts->latest_table_details()['last_table_id']) ?>]">
                                        [gswpts_table id=<?php echo esc_attr($gswpts->latest_table_details()['last_table_id']) ?>]
                                    </div>
                                <?php } else { ?>
                                    <div class="ui label mt-2"><?php echo __('Empty', 'sheetstowptable') ?></div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Latest end start -->

                <!-- Change Logs start -->
                <div class="col-md-12 p-0 pr-2 mt-3 change_logs">
                    <div class="card p-0 m-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="title">
                                <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/file-alt-solid.svg'; ?>
                                <span class="ml-2"><?php echo __('Change Logs', 'sheetstowptable'); ?></span>
                            </div>
                            <a href="" class="ui inverted green button">
                                <?php echo __('View Logs', 'sheetstowptable'); ?>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="col-12 mb-2">
                                <?php echo __('You are currently using Version', 'sheetstowptable'); ?> <?php echo GSWPTS_VERSION ?>
                            </div>
                            <div class="col-12 mb-2">
                                <?php echo __('The first realease of this plugin with compatibility checking', 'sheetstowptable'); ?>:
                            </div>
                            <div class="col-12 mt-4 d-flex">

                                <div class="col-1 p-0 info_circle">
                                    <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/info-circle-solid.svg'; ?>
                                </div>

                                <div class="col-11 p-0">
                                    <ul class="p-0 m-0">
                                        <?php printf('<li>%s %s %s</li>', __('Plugins version', 'sheetstowptable'), GSWPTS_VERSION, __('released', 'sheetstowptable')); ?>
                                    </ul>
                                </div>

                            </div>

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
                                    <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/blog-solid.svg' ?>
                                </span>
                                <span class="ml-2"><?php echo __('Our Blogs', 'sheetstowptable'); ?></span>
                            </div>
                            <a href="<?php echo esc_url('https://wppool.dev/blog/'); ?>" target="blank" class="ui inverted green button">
                                <?php echo __('Read Blogs', 'sheetstowptable'); ?>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="col-12">
                                <p><?php echo __('Checkout useful articles from', 'sheetstowptable'); ?> <a class="ml-1" href="<?php echo esc_url('https://wppool.dev/blog/'); ?>" target="blank">WPPOOL</a></p>

                                <div class="col-12 p-0 mt-3 useful_links">
                                    <div class="ui fitted divider mt-2 mb-2"></div>
                                </div>

                                <div class="col-12 p-0 mt-4 subscribe_sec">

                                    <div class="ui cards">
                                        <div class="card">
                                            <div class="content">
                                                <div class="header"><?php echo __('Get Subscribed', 'sheetstowptable'); ?></div>
                                                <div class="description">

                                                    <p>
                                                        <?php echo __('Never miss notifications about new cool features, promotions, giveaways or freebies – subscribe to our newsletter!
                                                        We send about 1 message per month and never spam', 'sheetstowptable'); ?>!
                                                    </p>

                                                    <form id="wemail-embedded-subscriber-form" method="post" action="<?php esc_url('https://api.getwemail.io/v1/embed/subscribe/ef1e42ee-2a60-429f-8e80-b5f324540471'); ?>" class="ui right labeled left icon input mt-3">
                                                        <i class="bell icon"></i>
                                                        <input style="width: 250px;" id="wemail-email" type="email" name="email" required placeholder="You email" />
                                                        <button type="submit" class="ui violet tag label" id="subscribe_btn" style="right: 28px;">
                                                            <?php echo __('Get Subscribed', 'sheetstowptable'); ?>
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
                                    <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/people-carry-solid.svg'; ?>
                                </span>
                                <span class="ml-2"><?php echo __('Help Center','sheetstowptable'); ?></span>
                            </div>
                            <a target="blank" href="<?php echo esc_url('https://wppool.dev/contact/'); ?>" class="ui inverted green button">
                                <?php echo __('Get Help','sheetstowptable'); ?>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="col-12 d-flex p-0">
                                <div class="col-6">
                                    <h4 class="">
                                        <?php echo __('Need some help','sheetstowptable'); ?>
                                    </h4>
                                    <p>
                                        <?php echo __('We provide professional support to all our users via our ticketing system','sheetstowptable'); ?>.
                                    </p>
                                    <a href="<?php echo esc_url('https://wppool.dev/contact/'); ?>">
                                        <?php echo __('Visit Support Center','sheetstowptable'); ?>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <img src="<?php echo esc_url(GSWPTS_BASE_URL . 'Assets/Public/Images/need_help.svg'); ?>" alt="" class="img-fluid">
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
                                    <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/medal-solid.svg'; ?>
                                </span>
                                <span class="ml-2"><?php echo __('Go Pro','sheetstowptable'); ?>!</span>
                            </div>
                            <a href="" class="ui inverted green button">
                                <?php echo __('Compare','sheetstowptable'); ?>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="col-12 p-0 m-0 d-flex">
                                <div class="col-6 p-0">
                                    <p>
                                        <?php echo __('Get the most out of this awesome plugin by upgrading to Pro version and unlock all of the powerful features','sheetstowptable'); ?>.
                                    </p>
                                    <ul class="p-0 m-0">
                                        <li class="d-flex align-items-center mb-3">
                                            <span class="mr-2">
                                                <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/check-circle-solid.svg' ?>
                                            </span>
                                            <?php echo __('Responsive Table','sheetstowptable'); ?>: <?php echo PlUGIN_NAME ?> <?php echo __('Plugin is responsive for any device. The plugin allows collapsing on mobile and tablet screens','sheetstowptable'); ?>.
                                        </li>

                                        <li class="d-flex align-items-center mb-3">
                                            <span class="mr-2">
                                                <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/check-circle-solid.svg'; ?>
                                            </span>
                                            <?php echo __('Export table', 'sheetstowptable') ?>: <?php echo __('Table Exporting via CSV, Excel, PDF, JSON, Print, Table Copy is easy on this plugin', 'sheetstowptable') ?>
                                        </li>

                                        <li class="d-flex align-items-center mb-3">
                                            <span class="mr-2">
                                                <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/check-circle-solid.svg'; ?>
                                            </span>
                                            <?php echo __('50 rows fetching from the sheet', 'sheetstowptable') ?>: <?php echo __('Fetch up to 50 row data on this feature.', 'sheetstowptable') ?>
                                        </li>

                                        <li class="d-flex align-items-center mb-3">
                                            <span class="mr-2">
                                                <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/check-circle-solid.svg'; ?>
                                            </span>
                                            <?php echo __('Elementor Widget Support', 'sheetstowptable'); ?>: <?php echo PlUGIN_NAME; ?> <?php echo __('supports elementor widget. Organize your table data effortlessly than ever.', 'sheetstowptable'); ?>
                                        </li>

                                        <li class="d-flex align-items-center mb-3">
                                            <span class="mr-2">
                                                <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/check-circle-solid.svg'; ?>
                                            </span>
                                            <?php echo __('And much more','sheetstowptable'); ?>.
                                        </li>

                                    </ul>
                                </div>
                                <div class="col-6 p-0 m-0">
                                    <img src="<?php echo esc_url(GSWPTS_BASE_URL . 'Assets/Public/Images/premium.svg');; ?>" alt="" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-12">
                                <a class="ui violet button m-0">
                                    <?php echo __('Get Pro Today', 'sheetstowptable'); ?> <span class="ml-2">
                                        <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/medal-solid.svg'; ?>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>

        <div class="row mt-3 dash_boxes pb-3 pt-3" style="background: transparent;">

            <div class="col-sm-12 p-0 m-0">
                <h2 class="text-center text-capitalize">
                    <?php echo __('Our Other Products', 'sheetstowptable'); ?>
                </h2>
                <p class="text-center">
                    <?php echo __('Experience remarkable WordPress products with a new level of power, beauty, and human-centered designs.
                    Think you know WordPress products? Think Deeper!', 'sheetstowptable'); ?>
                </p>
            </div>
        </div>


        <!-- Our other product section -->

        <div class="row mt-3 other_products_section">
        </div>

        <!-- End of our other product section -->

    </div>
</div>