<?php extract($args) ?>
<div class="col-md-4 mt-3 mb-3">
    <div class="ui cards">
        <div class="card">
            <div class="content">
                <?php
                if (isset($is_pro) && $is_pro) { ?>
                    <span class="pro_feature"><i class="fas fa-medal"></i></span>
                <?php } ?>
                <div class="header"><?php echo $feature_title ?></div>
                <div class="description">
                    <?php echo $feature_desc ?>
                </div>
            </div>
            <div class="ui toggle checkbox">
                <input type="checkbox" class="<?php echo isset($is_pro) && $is_pro ? 'pro_feature_input' : '' ?>" name="<?php echo $input_name ?>" id="<?php echo $input_name ?>">
                <label for="<?php echo $input_name ?>"></label>
            </div>
            <?php
            if (isset($is_pro) && $is_pro) {
                load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/promo.php', false);
            } ?>
        </div>
    </div>
</div>