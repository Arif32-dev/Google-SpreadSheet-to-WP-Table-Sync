import Base_Class from '../Base/base_class';

jQuery(document).ready(function ($) {
    class UD_tables extends Base_Class {
        constructor() {
            super();
            this.updateCheck = false;
            this.events();
        }
        events() {
            $(document).on('click', '.gswpts_edit_table', (e) => {
                this.edit_table_name(e)
            })
            $(document).on('click', '.gswpts_popup_edit', (e) => {
                this.edit_tag_value(e)
            })
            $(document).on('click', '.gswpts_table_update_btn', (e) => {
                this.update_table_name(e)
            })
            $(document).on('click', '.gswpts_table_delete_btn', (e) => {
                this.delete_table(e);
            })
        }
        update_table_name(e) {
            let table_name = $(e.currentTarget).parent().parent().find('.table_name').text();
            if (this.updateCheck == false) {
                this.call_alert('Warning &#9888;&#65039;', "<b>Table name haven't changed to update</b>", 'warning', 3)
                return;
            }
            let data = {
                reqType: 'update',
                table_id: $(e.currentTarget).attr('id'),
                table_name: table_name,
            }
            this.ajax_request(data, e)
        }

        delete_table(e) {
            let data = {
                reqType: 'delete',
                table_id: $(e.currentTarget).attr('id'),
            }
            this.ajax_request(data, e)
        }

        ajax_request(data, e) {
            let currentTarget = $(e.currentTarget);
            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action: 'gswpts_ud_table',
                    data: data
                },
                type: 'post',

                beforeSend: () => {
                    if (data.reqType == 'update') {
                        $(e.currentTarget).html(`
                        Updating &nbsp;
                        <div class="ui active mini inline loader"></div>
                    `)
                    }
                    if (data.reqType == 'delete') {
                        $(e.currentTarget).html(`
                        Deleting &nbsp;
                        <div class="ui active mini inline loader"></div>
                    `)
                    }
                },

                success: res => {
                    console.log(res);
                    if (JSON.parse(res).response_type == 'invalid_action' || JSON.parse(res).response_type == 'invalid_request') {
                        this.call_alert('Error &#128683;', JSON.parse(res).output, 'error', 4)
                    }
                    if (JSON.parse(res).response_type == 'updated') {
                        currentTarget.html('Update')
                        this.call_alert('Successfull &#128077;', JSON.parse(res).output, 'success', 3)
                        this.updateCheck = false;
                    }
                    if (JSON.parse(res).response_type == 'deleted') {
                        currentTarget.html('Deleted')
                        currentTarget.parent().parent().transition('fade');
                        this.call_alert('Successfull &#128077;', JSON.parse(res).output, 'success', 3)
                    }
                },

                error: err => {
                    this.call_alert('Error &#128683;', '<b>Something went wrong</b>', 'error', 3)
                }

            })
        }

        edit_table_name(e) {
            if ($('.gswpts_modal').hasClass('hidden')) {
                $('.gswpts_input_table_name').val(this.get_table_name(e));
                $('.gswpts_modal').transition('fade up');
                this.set_target_class(e)
            } else {
                $('.gswpts_input_table_name').val(this.get_table_name(e));
                this.set_target_class(e)
            }
        }

        get_table_name(e) {
            let table_name = $(e.currentTarget).siblings('a').text();
            return table_name;
        }

        set_target_class(e) {
            $('.table_name').removeClass('edit_tag')
            $(e.currentTarget).siblings('a').addClass('edit_tag');
        }

        edit_tag_value(e) {
            let input_value = $(e.currentTarget).siblings('input').val();
            let anchor_tag = $('.edit_tag');
            if (input_value != anchor_tag.html()) {
                this.updateCheck = true;
            } else {
                this.updateCheck = false;
            }
            $('.edit_tag').html(input_value);
            $('.edit_tag').attr('data-updated', "" + this.updateCheck + "");
            $(e.currentTarget).parent().parent().transition('fade up');
            console.log(this.updateCheck);
        }
    }
    new UD_tables;
})
