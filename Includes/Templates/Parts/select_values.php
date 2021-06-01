<?php foreach ($args as $key => $value) {?>

<?php if ($value['isPro'] == false) {?>
<div class="item" data-value="<?php echo esc_attr($key); ?>">
    <?php _e($value['val'], 'sheetstowptable');?>
</div>
<?php } else {?>
<div class="item d-flex justify-content-between align-items-center disabled item"
    data-value="<?php echo esc_attr($key); ?>">
    <span><?php _e($value['val'], 'sheetstowptable');?></span>

    <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/medal-solid.svg';?>
</div>
<?php }?>
<?php }?>