<?php extract($args) ?>
<div class="ui cards settings_row">

    <div class="card">
        <div class="content">
            <div class="description d-flex justify-content-between align-items-center">
                <h3 class="m-0">
                    <?php echo  $setting_title ?>
                    <span class="ui icon button p-0 m-0" data-tooltip="<?php echo $setting_tooltip ?>" data-position="right center" data-inverted="">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    <span class="p-0 m-0 reveal_btn <?php echo $is_checked ? 'reveal_btn_rotate' : '' ?>">
                        <i class="fas fa-arrow-up"></i>
                    </span>
                </h3>
                <div class="ui toggle checkbox m-0">
                    <input class="<?php echo isset($is_pro) && $is_pro ? 'pro_setting' : '' ?>" type="checkbox" <?php echo $is_checked ?> name="<?php echo $input_name ?>" id="<?php echo $input_name ?>">
                    <label class="m-0" for="<?php echo $input_name ?>"></label>
                </div>
            </div>
        </div>
        <div class="settings_desc <?php echo $is_checked == 'checked' ? '' : 'transition hidden' ?>">
            <p>
                <?php echo $setting_desc ?>
            </p>
        </div>
    </div>

</div>