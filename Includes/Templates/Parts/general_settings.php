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
                    <span class="<?php echo $is_checked ? 'reveal_btn_rotate' : '' ?> p-0 m-0 reveal_btn">
                        <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/arrow-up-solid.svg';?>
                    </span>
                </h5>
                <div class="ui toggle checkbox m-0">
                    <input class="<?php echo $class ?>" type="checkbox" <?php echo $is_checked ?>
                        name="<?php echo esc_attr($input_name) ?>" id="<?php echo esc_attr($input_name) ?>">
                    <label class="m-0" for="<?php echo esc_attr($input_name); ?>"></label>
                </div>
            </div>
        </div>
        <div class="<?php echo $is_checked == 'checked' ? '' : 'transition hidden'; ?> settings_desc">
            <p>
                <?php echo $setting_desc ?>
            </p>
        </div>
    </div>

</div>