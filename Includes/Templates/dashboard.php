<?php
global $gswpts;
$gswpts->bootstrap_files();
$gswpts->semantic_files();
?>
<div class="gswpts_dashboard_container">
    <div class="container mt-4">

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
                    <button class="ui button m-0">
                        View Documention <span><i class="fas fa-file-alt"></i></span>
                    </button>
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
                <img class="img-responsive wdt-welcome-img" src="http://localhost/wordpress/wp-content/plugins/wpdatatables/assets/img/dashboard/dashboard-welcome.svg" alt="Welcome message">
            </div>

        </div>

        <div class="row mt-3  pt-3 pb-3">

            <div class="col-md-6 p-0 pr-2">
                <div class="card p-0 m-0">
                    <div class="card-header">
                        Tables
                    </div>
                    <div class="card-body">
                        This is some text within a card body.
                    </div>
                </div>
            </div>

            <div class="col-md-6 p-0 pl-2">
                <div class="card p-0 m-0">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-sm-12 p-l-0 p-r-0">
                                <div class="col-sm-6 p-l-12 p-b-12 p-t-12 p-r-0 pull-left">
                                    <h4 class="">
                                        Need some help
                                    </h4>
                                    <p class="wpdt-text wpdt-font">
                                        We provide professional support to all our users via our ticketing system.</p>
                                    <a href="http://localhost/wordpress/wp-admin/admin.php?page=wpdatatables-support">
                                        Visit Support Center
                                    </a>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>