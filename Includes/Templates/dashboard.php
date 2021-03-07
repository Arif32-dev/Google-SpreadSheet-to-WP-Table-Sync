<?php
global $gswpts;
$gswpts->bootstrap_files();
$gswpts->semantic_files();
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
                <img src="<?php echo GSWPTS_BASE_URL . '/Assets/Public/Images/Google_Sheets_logo.svg' ?>" alt="">
                <span class="ml-2">
                    <strong>Google Spredsheet to WP Table Sync</strong>
                </span>
            </div>
            <div class="col-12 p-0 mt-2 d-flex justify-content-between align-items-center">
                <h3 class="m-0">
                    Dashboard
                </h3>
                <span>
                    <a class="ui violet button m-0">
                        View Documention <span class="ml-2"><i class="fas fa-file-alt"></i></span>
                    </a>
                </span>
            </div>
        </div>

        <div class="row mt-3 dash_boxes pt-3 pb-3 pl-2 pr-2">

            <div class="col-md-7">
                <h1 class="p-0 m-t-0 m-b-4">
                    Welcome to Google Spreadsheet to WP Table Sync
                </h1>
                <p> Congratulations! You are about to use the most powerful Google spreadsheet data syncronization</p>
                <a href="#">
                    <button class="ui inverted green button">
                        Learn how to sync Google spreadsheet
                    </button>
                </a>
            </div>
            <div class="col-md-5">
                <img class="img-responsive wdt-welcome-img" src="<?php echo GSWPTS_BASE_URL . 'Assets/Public/Images/welcome_message.svg' ?>" alt="Welcome message">
            </div>

        </div>

        <div class="row mt-3  pt-3 pb-3">
            <div class="col-6 p-0 m-0">
                <!-- Latest table start -->
                <div class="col-md-12 p-0 pr-2 latest_table">
                    <div class="card p-0 m-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="title">
                                <span><i class="fas fa-table"></i></span>
                                <span class="ml-2">Tables</span>
                            </div>
                            <a href="<?php echo admin_url('admin.php?page=gswpts-tables') ?>" class="ui inverted green button">
                                Browse Tables
                            </a>
                        </div>
                        <div class="card-body d-flex">
                            <div class="col-3 d-flex justify-content-center align-items-center flex-column total_created">
                                <span><?php echo $gswpts->latest_table_details()['total_table_count'] ?></span>
                                <span>Created</span>
                            </div>
                            <div class="col-9 details">
                                <div class="col-12">
                                    <a href="<?php echo admin_url('admin.php?page=gswpts-create-tables&id=' . $gswpts->latest_table_details()['last_table_id'] . '') ?>"><?php echo $gswpts->latest_table_details()['last_table_name'] ?></a>
                                    <div class="ui tag label ml-3">Spreadsheet</div>
                                </div>
                                <div class="col-12 mt-2">Latest table created</div>
                                <div class="col-12 mt-2">
                                    <div class="ui label">
                                        <i class="clone icon dashboard_sortcode_copy_btn"></i>
                                        <input type="hidden" name="sortcode" value="[gswpts_table id=<?php echo $gswpts->latest_table_details()['last_table_id'] ?>]">
                                        [gswpts_table id=<?php echo $gswpts->latest_table_details()['last_table_id'] ?>]
                                    </div>
                                </div>
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
                                <i class="fas fa-file-alt"></i>
                                <span class="ml-2">Change Logs</span>
                            </div>
                            <a href="<?php echo admin_url('admin.php?page=gswpts-tables') ?>" class="ui inverted green button">
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
                                    <i class="fas fa-info-circle"></i>
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
                <div class="col-md-12 p-0 pr-2 mt-3 news_blogs">
                    <div class="card p-0 m-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="title">
                                <span><i class="fas fa-blog"></i></span>
                                <span class="ml-2">Our Blogs</span>
                            </div>
                            <a href="https://wppool.dev/" target="blank" class="ui inverted green button">
                                Read Blogs
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="col-12">
                                <p>Checkout useful articles from <a class="ml-1" href="https://wppool.dev/" target="blank">WPPOOL</a></p>
                                <div class="col-12 p-0 mt-3 useful_links">

                                    <a class="col-12 p-0 d-flex justify-content-between align-items-center" href="" target="blank">
                                        <p class="m-0 p-0">All You Need to Know About the WooCommerce Grouped Product Option</p>
                                        <span><i class="fas fa-link"></i></span>
                                    </a>
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

                <div class="col-md-12 p-0 pl-2">
                    <div class="card p-0 m-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="title">
                                <span><i class="fas fa-people-carry"></i></span>
                                <span class="ml-2">Help Center</span>
                            </div>
                            <a href="<?php echo admin_url('admin.php?page=gswpts-tables') ?>" class="ui inverted green button">
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
                                    <a href="http://localhost/wordpress/wp-admin/admin.php?page=wpdatatables-support">
                                        Visit Support Center
                                    </a>
                                </div>
                                <div class="col-6">
                                    <img src="<?php echo GSWPTS_BASE_URL . 'Assets/Public/Images/need_help.svg' ?>" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12 p-0 pl-2 mt-3 pro_box">
                    <div class="card p-0 m-0" style="background-image: url(<?php echo GSWPTS_BASE_URL . 'Assets/Public/Images/premium.svg' ?>);">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="title">
                                <span><i class="fas fa-medal"></i></span>
                                <span class="ml-2">Go Pro!</span>
                            </div>
                            <a href="<?php echo admin_url('admin.php?page=gswpts-tables') ?>" class="ui inverted green button">
                                Compare
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="col-12">
                                <p>
                                    Get the most out of this awesome plugin by upgrading to Pro version and unlock all of the powerful features.
                                </p>
                                <ul>
                                    <li class="d-flex align-items-center mb-3">
                                        <span class="mr-2"><i class="fas fa-check-circle"></i></span>
                                        Product is in under development.
                                    </li>
                                </ul>
                            </div>
                            <div class="col-12">
                                <a class="ui violet button m-0">
                                    Get Pro Today <span class="ml-2"><i class="fas fa-medal"></i></span>
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

        <div class="row mt-3 dash_boxes  d-flex justify-content-start">

            <div class="col-sm-12 col-md-6 col-lg-4 p-4 gswpts_other_products wp-dark-mode">
                <div class="card mt-0 pt-4 pb-4">
                    <div class="gswpts_thumbnail">
                        <div class="text-center">
                            <img class="img-responsive" src="<?php echo GSWPTS_BASE_URL . 'Assets/Public/Images/Other_Products/Dark Mode.svg' ?>" alt="">
                        </div>
                        <h4 class="text-center">
                            WP Dark Mode
                        </h4>
                        <div class="mt-3 mb-3">
                            <p class="text-center">
                                Help your website visitors spend more time and an eye-pleasing reading experience.
                                Personal preference rules always king. WP Dark Mode can be a game-changer for your website.
                            </p>
                        </div>
                        <div class="text-center">
                            <a class="ui violet button m-0" href="https://wppool.dev/wp-dark-mode/" target="blank">
                                Learn More <span class="ml-2"><i class="fas fa-external-link-alt"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-4 p-4 gswpts_other_products flash-icon">
                <div class="card mt-0 pt-4 pb-4">
                    <div class="gswpts_thumbnail">
                        <div class="text-center">
                            <img class="img-responsive" src="<?php echo GSWPTS_BASE_URL . 'Assets/Public/Images/Other_Products/flash-icon.svg' ?>" alt="">
                        </div>
                        <h4 class="text-center">
                            Flash Social Share
                        </h4>
                        <div class="mt-3 mb-3">
                            <p class="text-center">
                                Barry Allen might be the fastest man alive but when it comes to WordPress – there’s no competition which is the fastest, most optimized, and performance-friendly social sharing plugin on the planet.
                            </p>
                        </div>
                        <div class="text-center">
                            <a class="ui violet button m-0" href="https://wordpress.org/plugins/flash-social-share/" target="blank">
                                Learn More <span class="ml-2"><i class="fas fa-external-link-alt"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-4 p-4 gswpts_other_products flexi-addons">
                <div class="card mt-0 pt-4 pb-4">
                    <div class="gswpts_thumbnail">
                        <div class="text-center">
                            <img class="img-responsive" src="<?php echo GSWPTS_BASE_URL . 'Assets/Public/Images/Other_Products/Flexiaddons.svg' ?>" alt="">
                        </div>
                        <h4 class="text-center">
                            Flexi Addons
                        </h4>
                        <div class="mt-3 mb-3">
                            <p class="text-center">
                                The Most Flexible Widgets For Elementor
                                Flexi Addons Helps you Save Time and Build Better Sites
                            </p>
                        </div>
                        <div class="text-center">
                            <a class="ui violet button m-0" href="https://wordpress.org/plugins/flexiaddons/" target="blank">
                                Learn More <span class="ml-2"><i class="fas fa-external-link-alt"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-4 p-4 gswpts_other_products markdown">
                <div class="card mt-0 pt-4 pb-4">
                    <div class="gswpts_thumbnail">
                        <div class="text-center">
                            <img class="img-responsive" src="<?php echo GSWPTS_BASE_URL . 'Assets/Public/Images/Other_Products/Markdown.svg' ?>" alt="">
                        </div>
                        <h4 class="text-center">
                            WP Markdown Editor
                        </h4>
                        <div class="mt-3 mb-3">
                            <p class="text-center">
                                Effectively insulate your mind from distractions and set up a direct line between your thoughts and your words.
                                Hear your inner voice clearer than ever and delve deeper into your creative process.
                            </p>
                        </div>
                        <div class="text-center">
                            <a class="ui violet button m-0" href="https://wppool.dev/wp-markdown-editor/" target="blank">
                                Learn More <span class="ml-2"><i class="fas fa-external-link-alt"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>