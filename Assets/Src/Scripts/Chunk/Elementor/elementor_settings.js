jQuery(document).ready(function ($) {
    class Elementor_Table_Settings {
        constructor() {
            this.events()
        }
        events() {
            $(document).on('click', '.block_initializer #create_button', (e) => {
                this.show_create_new_table_elem()
            })
            $(document).on('click', '.block_initializer #choose_table', (e) => {
                this.choose_table_event_func()
            })
            $(document).on('click', (e) => {
                console.log('hello from elementor edit page')
            })
        }

        choose_table_event_func() {
            $('.gswpts_create_table_container').html(`
                        <div id="spreadsheet_container">
                            <h4>Choose table from widget settings</h4>
                        </div>
                    `)
            console.log(document.querySelector("#elementor-panel-page-editor"))
        }

        show_create_new_table_elem() {
            $('.gswpts_create_table_container').html(`
                             <div class="create_table_input">
                                    <div class="ui icon input">
                                        <input required type="text" name="table_name" placeholder="Table Name" />
                                    </div>
                                    <div class="ui icon input">
                                        <input required type="text" name="file_input" placeholder="Enter the google spreadsheet public url." />
                                        <i class="file icon"></i>
                                    </div>
                                    <button class="ui violet button" type="button" id="fetch_save_btn">Fetch Data</button>
                                </div>
                    `)
        }


    }
    new Elementor_Table_Settings;

});