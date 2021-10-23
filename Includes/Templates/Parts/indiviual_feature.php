<?php extract($args)?>
<?php global $gswpts;?>
<?php if (isset($type) && $type === 'checkbox') {?>
<div class="">
    <div class="ui cards">
        <div
            class="card <?php echo (isset($is_pro) && $is_pro) || (isset($is_upcoming) && $is_upcoming) ? 'gswpts_pro_card' : '' ?>">
            <div class="content">
                <div class="card-top-header">
                    <span>
                        <?php if ((isset($is_pro) && $is_pro) || (isset($is_upcoming) && $is_upcoming)) {?>
                        <i class="fas fa-star pro_star_icon mr-2"></i>
                        <?php }?>
                        <?php echo $feature_title; ?>
                        <div class="input-tooltip">
                            <i class="fas fa-info-circle" style="font-size: 15.5px;"></i>
                            <span class="tooltiptext">
                                <span>
                                    <?php echo $feature_desc ?>
                                </span>
                                <?php if ($show_tooltip) {?>
                                <img src="<?php echo GSWPTS_BASE_URL . 'Assets/Public/Images/feature-gif/' . $input_name . '.gif' ?>"
                                    height="200" alt="<?php echo esc_attr($input_name) ?>">
                                <?php }?>
                            </span>
                        </div>
                    </span>
                    <div class="ui toggle checkbox">
                        <input <?php echo $checked ? 'checked' : '' ?> type="checkbox"
                            class="<?php echo (isset($is_pro) && $is_pro) || (isset($is_upcoming) && $is_upcoming) ? 'pro_feature_input' : '' ?>"
                            name="<?php echo esc_attr($input_name); ?>" id="<?php echo esc_attr($input_name); ?>">
                        <label for="<?php echo esc_attr($input_name); ?>"></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php }?>

<?php if (isset($type) && $type === 'select') {?>
<div class="">
    <div class="ui cards">
        <div
            class="card <?php echo (isset($is_pro) && $is_pro) || (isset($is_upcoming) && $is_upcoming) ? 'gswpts_pro_card' : '' ?>">
            <div class="content">

                <div class="card-top-header">
                    <span>
                        <?php if ((isset($is_pro) && $is_pro) || (isset($is_upcoming) && $is_upcoming)) {?>
                        <i class="fas fa-star pro_star_icon mr-2"></i>
                        <?php }?>
                        <?php echo $feature_title; ?>
                        <div class="input-tooltip">
                            <i class="fas fa-info-circle" style="font-size: 15.5px;"></i>
                            <span class="tooltiptext" style="width: 400px; min-height: 65px;">
                                <span>
                                    <?php echo $feature_desc ?>
                                </span>

                                <?php if ($show_tooltip) {?>
                                <img src="<?php echo GSWPTS_BASE_URL . 'Assets/Public/Images/feature-gif/' . $input_name . '.gif' ?>"
                                    height="200" alt="<?php echo esc_attr($input_name) ?>">
                                <?php }?>
                            </span>
                        </div>
                    </span>
                    <div class="ui fluid selection dropdown" id="<?php echo esc_attr($input_name); ?>">
                        <input type="hidden" name="<?php echo esc_attr($input_name); ?>"
                            value="<?php echo $default_value ? esc_attr($default_value) : null ?>">
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
</div>
<?php }?>


<?php if (isset($type) && $type === 'multi-select') {?>
<div class="">
    <div class="ui cards">
        <div class="card <?php echo (isset($is_pro) && $is_pro) || (isset($is_upcoming) && $is_upcoming) ? 'gswpts_pro_card' : '' ?>"
            style="height: unset; min-height: 60px; max-height: 110px;">
            <div class="content">

                <div class="card-top-header">
                    <span>
                        <?php if ((isset($is_pro) && $is_pro) || (isset($is_upcoming) && $is_upcoming)) {?>
                        <i class="fas fa-star pro_star_icon mr-2"></i>
                        <?php }?>
                        <?php echo $feature_title; ?>
                        <div class="input-tooltip">
                            <i class="fas fa-info-circle" style="font-size: 15.5px;"></i>
                            <span class="tooltiptext" style="width: 400px; min-height: 65px;">
                                <span>
                                    <?php echo $feature_desc ?>
                                </span>
                                <img src="<?php echo GSWPTS_BASE_URL . 'Assets/Public/Images/feature-gif/' . $input_name . '.gif' ?>"
                                    height="200" alt="<?php echo esc_attr($input_name) ?>">
                            </span>
                        </div>
                    </span>
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
</div>
<?php }?>

<?php if (isset($type) && $type === 'custom-type') {?>
<div class="">
    <div class="ui cards">
        <div class="card <?php echo (isset($is_pro) && $is_pro) || (isset($is_upcoming) && $is_upcoming) ? 'gswpts_pro_card' : '' ?>"
            style="cursor: pointer;">
            <div class="content">

                <div class="card-top-header">
                    <span>
                        <?php if ((isset($is_pro) && $is_pro) || (isset($is_upcoming) && $is_upcoming)) {?>
                        <i class="fas fa-star pro_star_icon mr-2"></i>
                        <?php }?>
                        <?php echo $feature_title; ?>
                        <div class="input-tooltip">
                            <i class="fas fa-info-circle" style="font-size: 15.5px;"></i>
                            <span class="tooltiptext" style="width: 400px; min-height: 65px;">
                                <span><?php echo $feature_desc ?></span>
                            </span>
                        </div>
                    </span>
                    <div class="modal-handler">
                        <img src="<?php echo $icon_url ?>" class="chooseStyle" alt="chooseStyle">

                        <input type="hidden" name="<?php echo esc_attr($input_name); ?>"
                            id="<?php echo esc_attr($input_name); ?>" value="">
                    </div>

                </div>

            </div>
        </div>

    </div>

</div>

<?php }?>