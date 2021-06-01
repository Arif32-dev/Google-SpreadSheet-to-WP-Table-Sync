<?php extract($args)?>
<?php global $gswpts;?>
<?php if ($type === 'checkbox') {?>
<div class="col-md-4 mt-3 mb-3">
    <div class="ui cards">
        <div class="card">
            <div class="content">
                <?php
                if (isset($is_pro) && $is_pro) {?>
                <span class="pro_feature">
                    <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/medal-solid.svg';?>
                </span>
                <?php } elseif (isset($is_upcoming) && $is_upcoming) {?>
                <span class="pro_feature">
                    <span style="font-size: 17px;
                                text-align: center;
                                text-transform: uppercase;
                                font-weight: bolder;
                                color: #2ecc40;
                                font-style: italic;">
                        Upcoming
                    </span>
                </span>
                <?php }?>
                <div class="header"><?php echo $feature_title; ?></div>
                <div class="description">
                    <?php echo $feature_desc; ?>
                </div>
            </div>
            <div class="ui toggle checkbox">
                <input <?php echo $checked ? 'checked' : '' ?> type="checkbox"
                    class="<?php echo (isset($is_pro) && $is_pro) || (isset($is_upcoming) && $is_upcoming) ? 'pro_feature_input' : '' ?>"
                    name="<?php echo esc_attr($input_name); ?>" id="<?php echo esc_attr($input_name); ?>">
                <label for="<?php echo esc_attr($input_name); ?>"></label>
            </div>
            <?php
                if ((isset($is_pro) && $is_pro) || (isset($is_upcoming) && $is_upcoming)) {
                    load_template(GSWPTS_BASE_PATH.'Includes/Templates/Parts/promo.php', false, [
                        'isPro'      => (isset($is_pro) && $is_pro) ? true : false,
                        'isUpcoming' => (isset($is_upcoming) && $is_upcoming) ? true : false
                    ]);
            }?>
        </div>
    </div>
</div>

<?php }?>

<?php if ($type === 'select') {?>
<div class="col-md-4 mt-3 mb-3">
    <div class="ui cards">
        <div class="card">
            <div class="content">

                <?php if (isset($is_pro) && $is_pro) {?>
                <span class="pro_feature">
                    <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/medal-solid.svg';?>
                </span>
                <?php } elseif (isset($is_upcoming) && $is_upcoming) {?>
                <span class="pro_feature">
                    <span style="font-size: 17px;
                                text-align: center;
                                text-transform: uppercase;
                                font-weight: bolder;
                                color: #2ecc40;
                                font-style: italic;">
                        Upcoming
                    </span>
                </span>
                <?php }?>

                <div class="header">
                    <?php echo $feature_title; ?>
                </div>
                <div class="description">
                    <?php echo $feature_desc; ?>
                </div>

                <div class="ui fluid selection dropdown" id="<?php echo esc_attr($input_name); ?>">
                    <input type="hidden" name="<?php echo esc_attr($input_name); ?>">
                    <i class="dropdown icon"></i>
                    <div class="default text">
                        <?php _e($default_text, 'sheetstowptable')?></div>

                    <div class="menu">
                        <?php $gswpts->selectFieldHTML($values);?>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
<?php }?>


<?php if ($type === 'multi-select') {?>
<div class="col-md-4 mt-3 mb-3">
    <div class="ui cards">
        <div class="card">
            <div class="content">

                <?php if (isset($is_pro) && $is_pro) {?>
                <span class="pro_feature">
                    <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/medal-solid.svg';?>
                </span>
                <?php } elseif (isset($is_upcoming) && $is_upcoming) {?>
                <span class="pro_feature">
                    <span style="font-size: 17px;
                                text-align: center;
                                text-transform: uppercase;
                                font-weight: bolder;
                                color: #2ecc40;
                                font-style: italic;">
                        Upcoming
                    </span>
                </span>
                <?php }?>

                <div class="header">
                    <?php echo $feature_title; ?>
                </div>
                <div class="description">
                    <?php echo $feature_desc; ?>
                </div>

                <div class="ui fluid
                    <?php echo $gswpts->isProActive() ? 'multiple' : null ?> selection dropdown mt-2"
                    id="table_exporting_container">
                    <input type="hidden" name="<?php echo esc_attr($input_name); ?>"
                        id="<?php echo esc_attr($input_name); ?>">
                    <i class="dropdown icon"></i>
                    <div class="default text"><?php esc_html_e($default_text, 'sheetstowptable')?></div>
                    <div class="menu">
                        <?php $gswpts->selectFieldHTML($values);?>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<?php }?>