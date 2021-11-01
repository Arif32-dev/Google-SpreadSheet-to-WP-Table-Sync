<style>
.gswpts-affiliate-notice p {
    font-size: 17px;
}

.tell_me_more {
    padding: 7px 10px;
    background: #4183c4;
    color: #fff;
    border-radius: 3px;
    display: inline-block;
}

.tell_me_more:hover {
    color: #fff;
    text-decoration: underline;
}

.gswpts-affiliate-notice .notice-overlay {
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

.gswpts-affiliate-notice .notice-overlay.active {
    opacity: 1;
    z-index: 111;
}

.gswpts-affiliate-notice .notice-overlay-wrap {
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

.gswpts-affiliate-notice .notice-overlay-wrap.active {
    background: #000000a6;
    opacity: 1;
    pointer-events: all;
}

.gswpts-affiliate-notice .notice-overlay-actions {
    display: flex;
    flex-direction: column;
}

.gswpts-affiliate-notice .promo_close_btn {
    position: absolute;
    top: 0;
    right: 0;
    margin: 5px 5px 0 0;
    cursor: pointer;
}
</style>
<div class="notice notice-large is-dismissible notice-info gswpts-affiliate-notice">
    <p>Hi there, you've been using

        <a href="https://wordpress.org/plugins/sheets-to-wp-table-live-sync/" target="_blank">
            <?php echo PlUGIN_NAME; ?>
        </a> for a while now.
        Do you know that
        <a href="https://wordpress.org/plugins/sheets-to-wp-table-live-sync/" target="_blank">
            <?php echo PlUGIN_NAME; ?>
        </a> has an affiliate program? Join now and get 25% lifetime commission
    </p>

    <a class="tell_me_more" href="https://wppool.dev/affiliates/" target="_blank">Tell me more</a>

</div>

<div class="gswpts-affiliate-notice">
    <div class="notice-overlay-wrap">
        <div class="notice-overlay">
            <h4>Would you like us to remind you about this later?</h4>

            <div class="notice-overlay-actions">
                <a href="#" data-value="3">Remind me in 3 days</a>
                <a href="#" data-value="10">Remind me in 10 days</a>
                <a href="#" data-value="hide_notice">Don't remind me about this</a>
            </div>

            <span class="promo_close_btn">
                <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/times-circle-solid.svg'?>
            </span>
        </div>
    </div>
</div>


<script>
jQuery(document).ready(function($) {
    $(document).on('click', '.gswpts-affiliate-notice .notice-dismiss', (e) => {

        e.preventDefault();

        let target = $(e.currentTarget);

        setTimeout(() => {
            $('.gswpts-affiliate-notice .notice-overlay-wrap').addClass('active')
            $('.gswpts-affiliate-notice .notice-overlay').addClass('active')
        }, 250);
    })

    $('.gswpts-affiliate-notice .promo_close_btn').click(e => {
        e.preventDefault();
        $('.gswpts-affiliate-notice .notice-overlay').removeClass('active')
        $('.gswpts-affiliate-notice .notice-overlay-wrap').removeClass('active')
    })

    $('.gswpts-affiliate-notice .notice-overlay-actions > a').click(e => {
        e.preventDefault();
        $('.gswpts-affiliate-notice .notice-overlay').removeClass('active')
        $('.gswpts-affiliate-notice .notice-overlay-wrap').removeClass('active')

        let target = $(e.currentTarget);
        let dataValue = target.attr('data-value');

        $.ajax({
            type: "POST",
            url: "<?php echo admin_url('admin-ajax.php') ?>",
            data: {
                action: 'gswpts_notice_action',
                info: {
                    type: 'reminder',
                    value: dataValue
                },
                actionType: 'affiliate_notice'
            },
            success: response => {
                console.log(response)

                let res = JSON.parse(response);

                if (res.response_type == 'success') {
                    $('.gswpts-affiliate-notice').slideUp();
                }
            }
        });

    })
});
</script>