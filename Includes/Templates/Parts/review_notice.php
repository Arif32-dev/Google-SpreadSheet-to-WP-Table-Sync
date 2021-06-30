<style>
.gswpts-review-notice p {
    font-size: 17px;
}

.gswpts-review-notice strong>a {
    color: #2ecc40;
}

.gswpts-review-notice .notice-actions {
    display: flex;
    flex-direction: column;
}

.gswpts-review-notice .notice-overlay {
    position: absolute;
    top: 20%;
    left: 50%;
    transform: translateX(-50%);
    padding: 15px 70px 15px 15px;
    background: #fff;
    border-radius: 4px;
    opacity: 0;
    transition: all 0.5s ease;
}

.gswpts-review-notice .notice-overlay.active {
    opacity: 1;
    z-index: 111;
}

.gswpts-review-notice .notice-overlay-wrap {
    transition: all 0.5s ease;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    pointer-events: none;
    z-index: 99;
}

.gswpts-review-notice .notice-overlay-wrap.active {
    background: #000000a6;
    opacity: 1;
    pointer-events: all;
}

.gswpts-review-notice .notice-overlay-actions {
    display: flex;
    flex-direction: column;
}

.gswpts-review-notice .promo_close_btn {
    position: absolute;
    top: 0;
    right: 0;
    margin: 5px 5px 0 0;
    cursor: pointer;
}
</style>
<div class="notice notice-large is-dismissible notice-info gswpts-review-notice">
    <p>Hi there, it seems like
        <strong>
            <a href="https://wordpress.org/plugins/sheets-to-wp-table-live-sync/" target="_blank">
                <?php echo PlUGIN_NAME; ?>
            </a></strong> is bringing you some
        value, and
        that is pretty awesome!
        Can you please show us some love and rate
        <strong>
            <a href="https://wordpress.org/plugins/sheets-to-wp-table-live-sync/" target="_blank">
                <?php echo PlUGIN_NAME; ?>
            </a></strong> on WordPress? It will take
        two minutes of your time, and
        will
        really help us spread the world.
    </p>

    <div class="notice-actions">
        <a class="hide_notice" data-value="hide_notice"
            href="https://wordpress.org/support/plugin/sheets-to-wp-table-live-sync/reviews/?filter=5#new-post"
            target="_blank">I'd love
            to
            help :)</a>
        <a href="#" class="remind_later">Not this time</a>
        <a href="#" class="hide_notice" data-value="hide_notice">I've already rated you</a>
    </div>

    <div class="notice-overlay-wrap">
        <div class="notice-overlay">
            <h4>Would you like us to remind you about this later?</h4>

            <div class="notice-overlay-actions">
                <a href="#" data-value="3">Remind me in 3 days</a>
                <a href="#" data-value="10">Remind me in 10 days</a>
                <a href="#" data-value="hide_notice">Don't remind me about this</a>
            </div>

            <span class="promo_close_btn">
                <?php require GSWPTS_BASE_PATH.'Assets/Public/Icons/times-circle-solid.svg'?>
            </span>
        </div>
    </div>

</div>


<script>
jQuery(document).ready(function($) {
    $('.gswpts-review-notice .notice-actions>a').click(e => {
        e.preventDefault();
        let target = $(e.currentTarget);

        if (target.hasClass('remind_later')) {
            $('.gswpts-review-notice .notice-overlay-wrap').addClass('active')
            $('.gswpts-review-notice .notice-overlay').addClass('active')
        }

        if (target.hasClass('hide_notice')) {
            $.ajax({
                type: "POST",
                url: "<?php echo admin_url('admin-ajax.php') ?>",
                data: {
                    action: 'gswpts_review_notice',
                    info: {
                        type: 'hide_notice'
                    }
                },
                success: response => {
                    console.log(response)
                    let res = JSON.parse(response);

                    if (res.response_type == 'success') {
                        $('.gswpts-review-notice').slideUp();
                    }
                }
            });
        }
    })
    $('.gswpts-review-notice .promo_close_btn').click(e => {
        e.preventDefault();
        $('.gswpts-review-notice .notice-overlay').removeClass('active')
        $('.gswpts-review-notice .notice-overlay-wrap').removeClass('active')
    })
    $('.gswpts-review-notice .notice-overlay-actions>a').click(e => {
        e.preventDefault();
        $('.gswpts-review-notice .notice-overlay').removeClass('active')
        $('.gswpts-review-notice .notice-overlay-wrap').removeClass('active')

        let target = $(e.currentTarget);
        let dataValue = target.attr('data-value');

        $.ajax({
            type: "POST",
            url: "<?php echo admin_url('admin-ajax.php') ?>",
            data: {
                action: 'gswpts_review_notice',
                info: {
                    type: 'reminder',
                    value: dataValue
                }
            },
            success: response => {
                console.log(response)

                let res = JSON.parse(response);

                if (res.response_type == 'success') {
                    $('.gswpts-review-notice').slideUp();
                }
            }
        });

    })
});
</script>