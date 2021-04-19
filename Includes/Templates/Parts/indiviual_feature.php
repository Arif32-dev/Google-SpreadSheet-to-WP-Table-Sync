<?php extract($args) ?>
<div class="col-md-4 mt-3 mb-3">
    <div class="ui cards">
        <div class="card">
            <div class="content">
                <?php
                if (isset($is_pro) && $is_pro) { ?>
                    <span class="pro_feature">
                        <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/medal-solid.svg' ?>
                    </span>
                <?php } ?>
                <div class="header"><?php echo esc_html($feature_title) ?></div>
                <div class="description">
                    <?php echo __($feature_desc) ?>
                </div>
            </div>
            <div class="ui toggle checkbox">
                <input <?php echo $checked ? 'checked' : '' ?> type="checkbox" class="<?php echo isset($is_pro) && $is_pro ? 'pro_feature_input' : '' ?>" name="<?php echo esc_attr($input_name) ?>" id="<?php echo esc_attr($input_name) ?>">
                <label for="<?php echo esc_attr($input_name) ?>"></label>
            </div>
            <?php
            if (isset($is_pro) && $is_pro) {
                load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/promo.php', false);
            } ?>
        </div>
    </div>
</div>