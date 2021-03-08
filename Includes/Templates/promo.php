<?php

defined('ABSPATH') || exit;

?>

<div class="wp-dark-mode-promo">
    <div class="wp-dark-mode-promo-inner <?php //echo $data['is_offer'] == 'yes' ? 'black-friday' : ''; 
                                            ?>">

        <span class="close-promo">&times;</span>

        <img src="<?php echo GSWPTS_BASE_URL . 'Assets/Public/Images/gift-box.svg'; ?>" class="promo-img">

        <?php

        if (!empty($title)) {
            printf('<h3 class="promo-title">%s</h3>', $title);
        }

        if (!empty($data['discount_text'])) {
            printf('<div class="discount"> <span class="discount-special">SPECIAL</span> <span class="discount-text">%s</span></div>', $data['discount_text']);
        }


        if (!empty($countdown_time)) {
            echo '<div class="simple_timer"></div>';
        }

        ?>

        <a href="https://wppool.dev/wp-dark-mode" target="_blank"><?php echo $is_pro ? 'GET PRO' : 'GET ULTIMATE'; ?></a>

    </div>

    <style>
        .syotimer {
            text-align: center;
            padding: 0 0 10px;
        }

        .syotimer-cell {
            display: inline-block;
            margin: 0 14px;

            width: 50px;
            background: url(<?php echo WP_DARK_MODE_ASSETS . '/images/timer.svg'; ?>) no-repeat 0 0;
            background-size: contain;
        }

        .syotimer-cell__value {
            font-size: 28px;
            color: #fff;

            height: 54px;
            line-height: 54px;

            margin: 0 0 5px;
        }

        .syotimer-cell__unit {
            font-family: Arial, serif;
            font-size: 12px;
            text-transform: uppercase;
            color: #fff;
        }
    </style>


    <script>
        (function($) {
            $(document).ready(function() {

                //show popup
                $(document).on('click', '.image-choose-opt.disabled, .form-table tr.disabled', function(e) {
                    e.preventDefault();

                    if ($(this).closest('tr').hasClass('specific_category')) {
                        $(this).closest('form').find('.wp-dark-mode-promo.ultimate_promo').removeClass('hidden');
                    } else {
                        $(this).closest('table').next('.wp-dark-mode-promo').removeClass('hidden');
                    }

                });

                //close promo
                $(document).on('click', '.close-promo', function() {
                    $(this).closest('.wp-dark-mode-promo').addClass('hidden');
                });

                //close promo
                $(document).on('click', '.wp-dark-mode-promo', function(e) {

                    if (e.target !== this) {
                        return;
                    }

                    $(this).addClass('hidden');
                });

                <?php
                if (!empty($countdown_time)) {

                ?>

                    if (typeof window.timer_set === 'undefined') {
                        window.timer_set = $('.simple_timer').syotimer({
                            year: <?php echo $countdown_time['year']; ?>,
                            month: <?php echo $countdown_time['month']; ?>,
                            day: <?php echo $countdown_time['day']; ?>,
                            hour: <?php echo $countdown_time['hour']; ?>,
                            minute: <?php echo $countdown_time['minute']; ?>,
                            //                      second: <?php // echo $countdown_time['second']; 
                                                            ?>,
                        });
                    }
                <?php } ?>

            })
        })(jQuery);
    </script>

</div>