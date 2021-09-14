<?php global $gswpts;?>
<!-- Popup modal for table style -->
<div class="tableStyleModal">
    <div class="styleModal transition hidden">
        <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/times-circle-solid.svg'?>
        <div class="header">
            <h4>Choose Table Style</h4>
        </div>

        <div class="body">
            <?php $gswpts->tableStylesHtml();?>
        </div>

        <div class="actions">
            <div class="ui black deny button cancelBtn">
                Cancel
            </div>
            <div class="ui positive button selectBtn">
                Select
            </div>
        </div>
    </div>
</div>
<!-- End of table style popup modal -->

<!-- Popup modal for Hide Column feature  -->
<div class="hide-column-modal-wrapper">
    <div class="gswpts-hide-modal transition hidden">
        <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/times-circle-solid.svg'?>
        <div class="header">
            <h4>Choose Column To Hide</h4>
        </div>

        <div class="body">
            <!-- Column values to hide in desktop mode -->
            <div class="desktop-column">
                <span>Hide columns in desktop:</span>
                <div class="ui fluid multiple selection dropdown mt-2" id="desktop-hide-columns">
                    <input type="hidden" name="desktop-hide-column-input" id="desktop-hide-column-input">
                    <i class="dropdown icon"></i>
                    <div class="default text"><?php esc_html_e('Choose Column', 'sheetstowptable')?></div>
                    <div class="menu">
                    </div>
                </div>
            </div>
            <!-- End of desktop column -->

            <!-- Column values to hide in mobile mode -->
            <div class="mobile-column">
                <span>Hide columns in mobile:</span>
                <div class="ui fluid multiple selection dropdown mt-2" id="mobile-hide-columns">
                    <input type="hidden" name="mobile-hide-column-input" id="mobile-hide-column-input">
                    <i class="dropdown icon"></i>
                    <div class="default text"><?php esc_html_e('Choose Column', 'sheetstowptable')?></div>
                    <div class="menu">
                    </div>
                </div>
            </div>
            <!-- End of mobile column -->

        </div>

        <div class="actions">
            <div class="ui black deny button cancelBtn">
                Cancel
            </div>
            <div class="ui positive button selectBtn">
                Select
            </div>
        </div>
    </div>
</div>
<!-- End of Hide Column popup modal -->




<!-- Popup modal for Hide Rows feature  -->
<div class="hide-rows-modal-wrapper">
    <div class="gswpts-hide-modal transition hidden">
        <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/times-circle-solid.svg'?>
        <div class="header">
            <h4>Activate Row Hiding Feature</h4>
        </div>

        <div class="body">
            <div class="column">
                <span>Hidden Rows:</span>
                <div class="ui fluid multiple selection dropdown mt-2" id="hidden_rows">
                    <input type="hidden" name="hidden_rows-input" id="hidden_rows-input">
                    <i class="dropdown icon"></i>
                    <div class="default text"><?php esc_html_e('Hidden Rows', 'sheetstowptable')?></div>
                    <div class="menu">
                    </div>
                </div>
            </div>
        </div>

        <div class="actions">
            <div class="ui black deny button cancelBtn">
                Cancel
            </div>
            <div class="ui toggle checkbox">
                <?php $isPro = $gswpts->tableToolsArray()['hide_rows']['is_pro']?>
                <input type="checkbox"
                    class="<?php echo (isset($isPro) && $isPro) ? 'pro_feature_input' : '' ?> selectBtn"
                    name="active_hide_rows" id="active_hide_rows">
                <label for="active_hide_rows"></label>
            </div>
            <!-- <div class="ui positive button selectBtn">
                Select
            </div> -->
        </div>
    </div>
</div>
<!-- End of Hide Rows popup modal -->