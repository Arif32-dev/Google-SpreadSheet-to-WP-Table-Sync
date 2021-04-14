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
                <img src="<?php echo esc_url(GSWPTS_BASE_URL . 'Assets/Public/Images/logo_30_30.svg') ?>" alt="">
                <span class="ms-2">
                    <strong><?php echo esc_html(PlUGIN_NAME) ?></strong>
                </span>
            </div>
            <div class="col-12 p-0 mt-2 d-flex justify-content-between align-items-center">
                <h3 class="m-0">
                    Dashboard
                </h3>
                <span>
                    <a class="ui violet button m-0" href="https://vimeo.com/526189502" target="blank">
                        View Documention <span class="ms-2"><?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/file-alt-solid.svg' ?></span>
                    </a>
                </span>
            </div>
        </div>

        <div class="row mt-3 dash_boxes pt-3 pb-3 ps-2 pe-2">

            <div class="col-md-7">
                <h1 class="p-0 m-t-0 m-b-4">
                    Welcome to <?php echo esc_html(PlUGIN_NAME) ?>
                </h1>
                <p> Congratulations! You are about to use the most powerful Google spreadsheet data synchronization</p>
                <a href="https://vimeo.com/526189502" target="blank">
                    <button class="ui inverted green button">
                        Learn how to sync Google spreadsheet
                    </button>
                </a>
            </div>
            <div class="col-md-5">
                <img class="img-responsive wdt-welcome-img" src="<?php echo esc_url(GSWPTS_BASE_URL . 'Assets/Public/Images/welcome_message.svg') ?>" alt="Welcome message">
            </div>

        </div>

        <div class="row mt-3  pt-3 pb-3">
            <div class="col-6 p-0 m-0">
                <!-- Latest table start -->
                <div class="col-md-12 p-0 pe-2 latest_table">
                    <div class="card p-0 m-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="title">
                                <span><?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/table-solid.svg' ?></span>
                                <span class="ms-2">Tables</span>
                            </div>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=gswpts-create-tables')) ?>" class="ui inverted green button">
                                Create Tables
                            </a>
                        </div>
                        <div class="card-body d-flex">
                            <div class="col-3 d-flex justify-content-center align-items-center flex-column total_created">
                                <span><?php echo esc_html($gswpts->latest_table_details()['total_table_count']) ?></span>
                                <span>Created</span>
                            </div>
                            <div class="col-9 details">
                                <div class="col-12 ps-0">
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=gswpts-create-tables&id=' . esc_attr($gswpts->latest_table_details()['last_table_id']) . '')) ?>"><?php echo esc_html($gswpts->latest_table_details()['last_table_name']) ?></a>
                                </div>
                                <div class="col-12 mt-2 ps-0">Latest table created</div>
                                <?php if ($gswpts->latest_table_details()['last_table_id']) { ?>
                                    <div class="ui label mt-2">
                                        <i class="clone icon dashboard_sortcode_copy_btn"></i>
                                        <input type="hidden" name="sortcode" value="[gswpts_table id=<?php echo esc_attr($gswpts->latest_table_details()['last_table_id']) ?>]">
                                        [gswpts_table id=<?php echo esc_attr($gswpts->latest_table_details()['last_table_id']) ?>]
                                    </div>
                                <?php } else { ?>
                                    <div class="ui label mt-2">Empty</div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Latest end start -->

                <!-- Change Logs start -->
                <div class="col-md-12 p-0 pe-2 mt-3 change_logs">
                    <div class="card p-0 m-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="title">
                                <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/file-alt-solid.svg' ?>
                                <span class="ms-2">Change Logs</span>
                            </div>
                            <a href="" class="ui inverted green button">
                                View Logs
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="col-12 mb-2">
                                You are currently using Version <?php echo GSWPTS_VERSION ?>
                            </div>
                            <div class="col-12 mb-2">
                                The first realease of this plugin with compatibility checking:
                            </div>
                            <div class="col-12 mt-4 d-flex">

                                <div class="col-1 p-0 info_circle">
                                    <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/info-circle-solid.svg' ?>
                                </div>

                                <div class="col-11 p-0">
                                    <ul>
                                        <li>Plugins version <?php echo GSWPTS_VERSION ?> released</li>
                                    </ul>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <!-- Change Logs end -->

                <!-- News Blogs start -->
                <div class="col-md-12 p-0 pe-2 mt-3 news_blogs">
                    <div class="card p-0 m-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="title">
                                <span>
                                    <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/blog-solid.svg' ?>
                                </span>
                                <span class="ms-2">Our Blogs</span>
                            </div>
                            <a href="https://wppool.dev/blog/" target="blank" class="ui inverted green button">
                                Read Blogs
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="col-12">
                                <p>Checkout useful articles from <a class="ms-1" href="https://wppool.dev/blog/" target="blank">WPPOOL</a></p>

                                <div class="col-12 p-0 mt-3 useful_links">
                                    <div class="ui fitted divider mt-2 mb-2"></div>
                                </div>

                                <div class="col-12 p-0 mt-4 subscribe_sec">

                                    <div class="ui cards">
                                        <div class="card">
                                            <div class="content">
                                                <div class="header">Get Subscribed</div>
                                                <div class="description">

                                                    <p>
                                                        Never miss notifications about new cool features, promotions, giveaways or freebies – subscribe to our newsletter!
                                                        We send about 1 message per month and never spam!
                                                    </p>

                                                    <form id="wemail-embedded-subscriber-form" method="post" action="https://api.getwemail.io/v1/embed/subscribe/ef1e42ee-2a60-429f-8e80-b5f324540471" class="ui right labeled left icon input mt-3">
                                                        <i class="bell icon"></i>
                                                        <input style="width: 250px;" id="wemail-email" type="email" name="email" required placeholder="You email" />
                                                        <button type="submit" class="ui violet tag label" id="subscribe_btn" style="right: 28px;">
                                                            Get Subscribed
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


            <div class="col-6 p-0 m-0">

                <div class="col-md-12 p-0 ps-2">
                    <div class="card p-0 m-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="title">
                                <span>
                                    <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/people-carry-solid.svg' ?>
                                </span>
                                <span class="ms-2">Help Center</span>
                            </div>
                            <a target="blank" href="https://wppool.dev/contact/" class="ui inverted green button">
                                Get Help
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="col-12 d-flex p-0">
                                <div class="col-6">
                                    <h4 class="">
                                        Need some help
                                    </h4>
                                    <p>
                                        We provide professional support to all our users via our ticketing system.
                                    </p>
                                    <a href="">
                                        Visit Support Center
                                    </a>
                                </div>
                                <div class="col-6">
                                    <img src="<?php echo esc_url(GSWPTS_BASE_URL . 'Assets/Public/Images/need_help.svg') ?>" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12 p-0 ps-2 mt-3 pro_box">
                    <div class="card p-0 m-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="title">
                                <span>
                                    <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/medal-solid.svg' ?>
                                </span>
                                <span class="ms-2">Go Pro!</span>
                            </div>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=gswpts-tables')) ?>" class="ui inverted green button">
                                Compare
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="col-12 p-0 m-0 d-flex">
                                <div class="col-6 p-0">
                                    <p>
                                        Get the most out of this awesome plugin by upgrading to Pro version and unlock all of the powerful features.
                                    </p>
                                    <ul>
                                        <li class="d-flex align-items-center mb-3">
                                            <span class="me-2">
                                                <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/check-circle-solid.svg' ?>
                                            </span>
                                            Product is in under development.
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-6 p-0 m-0">
                                    <img src="<?php echo esc_url(GSWPTS_BASE_URL . 'Assets/Public/Images/premium.svg') ?>" alt="" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-12">
                                <a class="ui violet button m-0">
                                    Get Pro Today <span class="ms-2">
                                        <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/medal-solid.svg' ?>
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
                    Our Other Products
                </h2>
                <p class="text-center">
                    Experience remarkable WordPress products with a new level of power, beauty, and human-centered designs.
                    Think you know WordPress products? Think Deeper!
                </p>
            </div>
        </div>


        <!-- Our other product section -->

        <div class="row mt-3 other_products_section">
        </div>

        <!-- End of our other product section -->

    </div>
</div>