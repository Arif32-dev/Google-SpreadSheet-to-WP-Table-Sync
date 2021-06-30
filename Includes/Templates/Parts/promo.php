<?php extract($args)?>
<div class="pro_feature_promo">
    <span class="promo_close_btn">
        <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/times-circle-solid.svg'?>
    </span>
    <strong>
        <?php if ($isPro) {?>
        <?php _e('Unlock this feature in pro', 'sheetstowptable');?>
        <?php } elseif ($isUpcoming) {?>
        <?php _e('This feature is coming soon', 'sheetstowptable')?>
        <?php }?>
    </strong>
    <a class="ui violet button" href="https://wppool.dev/sheets-to-wp-table-live-sync/" target="blank">
        <?php if ($isPro) {?>
        <?php _e('Get Pro', 'sheetstowptable')?>
        <?php } elseif ($isUpcoming) {?>
        <?php _e('Upcoming', 'sheetstowptable')?>
        <?php }?>
        <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/medal-solid.svg';?>
    </a>
</div>