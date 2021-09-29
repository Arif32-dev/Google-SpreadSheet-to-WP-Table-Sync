<?php foreach ($args as $key => $value) {?>

<?php if ($value['isPro'] == false) {?>
<div class="item" data-value="<?php echo esc_attr($key); ?>">
    <?php _e($value['val'], 'sheetstowptable');?>
</div>
<?php } else {?>
<div class="item d-flex justify-content-between align-items-center item pro_feature_input pro_input_select"
    data-value="<?php echo esc_attr($key); ?>">
    <span><?php _e($value['val'], 'sheetstowptable');?></span>

    <i class="fas fa-star pro_star_icon"></i>
</div>
<?php }?>
<?php }?>