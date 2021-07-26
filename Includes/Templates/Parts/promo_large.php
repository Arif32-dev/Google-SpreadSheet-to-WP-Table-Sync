<?php

    $url = 'https://wppool.dev/?action=wp_dark_mode_get_offer_json';

    $res = wp_remote_get($url);

    if (!is_wp_error($res)) {
        $json = wp_remote_retrieve_body($res);

        $data = (array) json_decode($json);

        if (array_key_exists('data', $data)) {
            $date = date('Y-m-d-H-i', strtotime($data['data']->counter_time));

            $date_parts = explode('-', $date);

            $countdown_time = [
                'year'   => $date_parts[0],
                'month'  => $date_parts[1],
                'day'    => $date_parts[2],
                'hour'   => $date_parts[3],
                'minute' => $date_parts[4]
            ];
        }
    }

?>


<div class="promo_large" style="opacity: 0">
    <div class="promo_inner">

        <div class="popup-box">

            <span class="large_promo_close">
                <?php require GSWPTS_BASE_PATH.'Assets/Public/Images/promo-close.svg'?>
            </span>

            <div class="popup-header"
                style="background-image: url(<?php echo GSWPTS_BASE_URL.'Assets/Public/Images/header-bg.svg' ?>)">
                <h2>- <span>50</span> %</h2>
            </div>
            <div class="popup-body">
                <img class="layer-image" src="<?php echo GSWPTS_BASE_URL.'Assets/Public/Images/body-bg.svg' ?>"
                    alt="alternate image" />

                <div class="offer">
                    <h2>get <span>50%</span> off</h2>
                </div>

                <div id="offer_limit" data-limit="<?php echo $data['data']->counter_time; ?>">
                    <ul>
                        <li>
                            <p class="time">00 <span>:</span></p>
                            <span>Days</span>
                        </li>
                        <li>
                            <p class="time">00 <span>:</span></p>
                            <span>Hours</span>
                        </li>
                        <li>
                            <p class="time">00 <span>:</span></p>
                            <span>Minutes</span>
                        </li>
                        <li>
                            <p class="time">00</p>
                            <span>Seconds</span>
                        </li>
                    </ul>
                </div>

                <a href="https://wppool.dev/sheets-to-wp-table-live-sync/" target="_blank" class="popup-button">Buy
                    Now</a>
            </div>
        </div>
    </div>
</div>

<style>
#offer_limit ul li {
    background-image: url(<?php echo GSWPTS_BASE_URL.'Assets/Public/Images/timer-background.svg' ?>)
}
</style>


<?php if (!empty($countdown_time)) {?>
<script>
/* ****** new with fixt tiem ****** */
const offerTime = document.querySelector('#offer_limit').getAttribute('data-limit');
// formage days/hours/minutes/seconds
const newInterval = 3 * 24 * 60 * 60 * 1000;
let countDownTime = new Date(offerTime).getTime();
setInterval(() => {
    let now = new Date().getTime();
    let time = countDownTime - now;
    if (time <= 0) {
        countDownTime = countDownTime + newInterval
    }
}, 1);
setInterval(() => {
    let now = new Date().getTime();
    let time = countDownTime - now;
    let days = Math.floor(time / (1000 * 60 * 60 * 24));
    let hours = Math.floor((time % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    let minutes = Math.floor((time % (1000 * 60 * 60)) / (1000 * 60));
    let seconds = Math.floor((time % (1000 * 60)) / 1000);
    const FormatMe = (n) => (n < 10) ? '0' + n : n;
    if (document.getElementById("offer_limit")) {
        document.getElementById("offer_limit").innerHTML = `
    <ul>
        <li><p class='time'>${FormatMe(days)}  <span>:</span></p> <span>Days</span></li>
        <li><p class='time'>${FormatMe(hours)}  <span>:</span></p> <span>Hours</span></li>
        <li><p class='time'>${FormatMe(minutes)}  <span>:</span></p> <span>Minutes</span></li>
        <li><p class='time'>${FormatMe(seconds)}</p> <span>Seconds</span></li>
    </ul>
    `;
    }
}, 1000);
</script>

<?php }?>