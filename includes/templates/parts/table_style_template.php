<?php extract($args)?>
<div class="styleWrapper">
    <label for="<?php echo esc_attr($key) ?>" class="<?php echo $style['isChecked'] ? 'active' : null; ?>">
        <div class="imgWrapper">
            <img src="<?php echo esc_url($style['imgUrl']) ?>" alt="<?php echo esc_html($key) ?>">
        </div>
        <input type="radio" name="<?php esc_attr($style['inputName'])?>"
            value="<?php echo esc_attr($key) ?><?php echo (isset($isPro) && $isPro) || (isset($isUpcoming) && $isUpcoming) ? ' disabled' : '' ?>"
            id="<?php echo esc_attr($key) ?>"
            class="<?php echo (isset($isPro) && $isPro) || (isset($isUpcoming) && $isUpcoming) ? 'pro_feature_input' : '' ?>">
    </label>

</div>