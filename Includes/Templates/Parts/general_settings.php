<?php global $gswpts;?>
<?php extract($args)?>
<?php $class = '';?>
<?php if (isset($is_pro) && $is_pro) {?>
<?php $class = 'pro_setting';?>
<?php } elseif (isset($is_upcoming) && $is_upcoming) {?>
<?php $class = 'upcoming_setting pro_setting';?>
<?php }?>


<div class="ui cards settings_row">

    <div class="card">
        <div class="content">
            <div class="description d-flex justify-content-between align-items-center">
                <h5 class="m-0">
                    <?php echo $setting_title ?>
                    <span class="ui icon button p-0 m-0" data-tooltip="<?php echo esc_attr($setting_tooltip) ?>"
                        data-position="right center" data-inverted="">
                        <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/info-circle-solid.svg';?>
                    </span>
                </h5>
                <div class="ui toggle checkbox m-0">
                    <input class="<?php echo $class ?>" type="checkbox" <?php echo $is_checked ?>
                        name="<?php echo esc_attr($input_name) ?>" id="<?php echo esc_attr($input_name) ?>">
                    <label class="m-0" for="<?php echo esc_attr($input_name); ?>"></label>
                </div>
            </div>
        </div>
        <div class="settings_desc">
            <p>
                <?php echo $setting_desc ?>
            </p>
            <?php if ($input_name == 'custom_css') {?>
            <br>

            <textarea name="css_code_value" id="css_code_value"><?php echo get_option('css_code_value'); ?></textarea>

            <?php if ((isset($is_pro) && !$is_pro) && $gswpts->isProActive()) {?>
            <div id="gswptsCSSeditor"
                style="min-height: 70px;<?php echo !get_option('custom_css') ? "opacity: 0.5; pointer-events: none;" : null ?>">
            </div>
            <?php }?>
            <?php }?>
        </div>
    </div>

</div>