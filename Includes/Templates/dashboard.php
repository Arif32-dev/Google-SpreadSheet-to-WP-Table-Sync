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
                                <span>2</span>
                                <span>Created</span>
                            </div>
                            <div class="col-9 details">
                                <div class="col-12">
                                    <a href="">Table Name</a>
                                    <div class="ui tag label ml-3">Spreadsheet</div>
                                </div>
                                <div class="col-12 mt-2">Latest table created</div>
                                <div class="col-12 mt-2">
                                    <div class="ui label">
                                        <i class="clone icon"></i>
                                        <input type="hidden" name="sortcode" value="">
                                        [gswpts_table id=1]
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
								You are currently using Version 2.1.3
							</div>
							<div class="col-12 mb-2">
								A major update with new table type, and a couple of bug fixes and stability improvements:
							</div>
							<div class="col-12 mt-4 d-flex">
							
								<div class="col-1 p-0 info_circle">
									<i class="fas fa-info-circle"></i>
								</div>
								
								<div class="col-11 p-0">
									<ul>
										<li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum.</li>
										<li>Feature: Added Slovenian translation</li>
										<li>Improvement: Update French translation</li>
										<li>Lorem Ipsum is simply dummy text of the printing</li>
				
									</ul>
								</div>
								
							</div>
							
                        </div>
                    </div>
                </div>
				
				
				 <div class="col-md-12 p-0 pr-2 mt-3 news_blogs">
                    <div class="card p-0 m-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="title">
                                <span><i class="fas fa-blog"></i></span>
                                <span class="ml-2">Plugin Blogs</span>
                            </div>
                            <a href="<?php echo admin_url('admin.php?page=gswpts-tables') ?>" class="ui inverted green button">
                                Read Blogs
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
                    <div class="card p-0 m-0">
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
									Get the most out of wpDataTables by upgrading to Premium and unlocking all of the powerful features.
								</p>
								<ul>
									<li class="d-flex align-items-center mb-3">
										<span class="mr-2"><i class="fas fa-check-circle"></i></span>
										Lorem Ipsum is simply dummy text
									</li>
									<li class="d-flex align-items-center mb-3">
										<span class="mr-2"><i class="fas fa-check-circle"></i></span>
										Lorem Ipsum is simply dummy text of the print
									</li>
									<li class="d-flex align-items-center mb-3">
										<span class="mr-2"><i class="fas fa-check-circle"></i></span>
										Lorem Ipsum is simply dustry
									</li>
									<li class="d-flex align-items-center mb-3">
										<span class="mr-2"><i class="fas fa-check-circle"></i></span>
										Lorem Ipsum is simply dummy text of the printi
									</li>
									<li class="d-flex align-items-center mb-3">
										<span class="mr-2"><i class="fas fa-check-circle"></i></span>
										Lorem Ipsum is simply dustry
									</li>
									<li class="d-flex align-items-center mb-3">
										<span class="mr-2"><i class="fas fa-check-circle"></i></span>
										Lorem Ipsum is simply dummy text
									</li>
									<li class="d-flex align-items-center mb-3">
										<span class="mr-2"><i class="fas fa-check-circle"></i></span>
										Lorem Ipsum is simply dummy text of the printing
									</li>
									<li class="d-flex align-items-center mb-3">
										<span class="mr-2"><i class="fas fa-check-circle"></i></span>
										Create a table manually
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

    </div>
</div>