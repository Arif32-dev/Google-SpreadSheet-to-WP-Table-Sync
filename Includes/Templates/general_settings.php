<?php
global $gswpts;
$gswpts->bootstrap_files();
$gswpts->semantic_files();
settings_errors();
?>
<style>
    .notice {
        padding: 5px 15px;
    }

    table {
        margin: 0;
    }
</style>
<div class="gswpts_general_settings_container">

    <div class="ui segment gswpts_loader">
        <div class="ui active inverted dimmer">
            <div class="ui massive text loader"></div>
        </div>
        <p></p>
        <p></p>
        <p></p>
    </div>


    <div class="container mt-4 settings_content transition hidden">
        <form action="options.php" method="POST">

            <div class="row heading_row">
                <div class="col-12 d-flex justify-content-start p-0 align-items-center">
                    <img src="<?php echo GSWPTS_BASE_URL . 'Assets/Public/Images/logo_30_30.svg' ?>" alt="">
                    <span class="ml-2">
                        <strong><?php echo PlUGIN_NAME ?></strong>
                    </span>
                </div>
                <div class="col-12 p-0 mt-2 d-flex justify-content-between align-items-center">
                    <h3 class="m-0">
                        General Settings
                    </h3>
                    <span>
                        <button type="submit" name="submit" id="submit" class="button ui violet m-0" value="Save Changes">
                            Save Changes
                            <span class="ml-2"><i class="fas fa-file-alt"></i></span>
                        </button>
                    </span>
                </div>
            </div>


            <div class="row mt-3 dash_boxes pt-3 pb-3 position-relative overflow-hidden">

                <div class="col-md-12 pt-2 pb-2 pl-4 pr-4">

                    <div class="gswpts_settings_container">
                        <?php settings_fields('gswpts_general_setting') ?>
                        <?php do_settings_sections('gswpts-general-settings');  ?>
                    </div>


                </div>

                <?php load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/promo_large.php', true) ?>

            </div>
        </form>

    </div>

</div>