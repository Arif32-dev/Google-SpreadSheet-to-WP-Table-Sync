import Base_Class from './../Base/base_class';

jQuery(document).ready(function($) {
    class Dashboard extends Base_Class {
        constructor() {
            super($);
            this.sortcode_copy_btn = $('.dashboard_sortcode_copy_btn');
            this.form = $('#subscriber-form');
            this.events();
        }
        events() {
            this.sortcode_copy_btn.on('click', (e) => {
                this.copy_shorcode(e)
            })
            if (this.get_slug_parameter('page') == 'gswpts-dashboard') {
                this.retrieve_wppool_post()
                this.retrieve_other_products()
            }
            this.form.on('submit', (e) => {
                e.preventDefault();
                this.send_subscriber(e);
            })

        }
        retrieve_wppool_post() {
            $.ajax({
                url: file_url.admin_ajax,
                type: 'GET',
                data: {
                    action: 'gswpts_get_posts',
                },
                beforeSend: () => {
                    $('.useful_links').html(`
                            <div class="ui segment" style="min-height: 150px;">
                                <div class="ui active inverted dimmer">
                                    <div class="ui medium text loader">Loading</div>
                                </div>
                                <p></p>
                                <p></p>
                            </div>
                    `);
                },
                success: res => {

                    if (!res) return;
                    let responseType = JSON.parse(res).response_type
                    let posts = JSON.parse(res).output

                    if (responseType == 'invalid_action' ||
                        responseType == 'error') {
                        this.call_alert('Error &#128683;', JSON.parse(res).output, 'error', 4);
                        $('.useful_links').html('');
                    }

                    if (responseType == 'success') {
                        let blogs = ''
                        posts.forEach(blog => {
                            blogs += ` <a class="col-12 p-0 d-flex justify-content-between align-items-center"  href="${blog.link}" target="blank">
                                                    <p class="m-0 p-0">${blog.title.rendered}</p>
                                            </a>
                                            <div class="ui fitted divider mt-2 mb-2"></div>`
                        });
                        $('.useful_links').html(blogs);
                    } else {
                        $('.useful_links').html('');
                    }
                },
                error: (xhr, status, error) => {
                    this.call_alert('Error &#128683;', error, 'error', 3)
                    $('.useful_links').html('');
                },
            })
        }

        send_subscriber(e) {
            let form_data = this.form.serialize();
            let button = $('#subscribe_btn');
            let buttonText = button.text();
            $.ajax({
                type: "POST",
                url: file_url.admin_ajax,
                data: {
                    action: 'gswpts_user_subscribe',
                    form_data: form_data,
                },
                beforeSend: () => {
                    button.text('Submitting')
                },
                success: (res) => {
                    console.log(res);

                    if (!res) return;

                    let responseType = JSON.parse(res).response_type;

                    if (responseType == 'invalid_action' ||
                        responseType == 'empty' ||
                        responseType == 'error') {
                        this.call_alert('Error &#128683;', JSON.parse(res).output, 'error', 4)
                    }

                    if (responseType == 'success') {
                        this.call_alert('Success &#128077;', JSON.parse(res).output, 'success', 4)
                    }

                    button.text(buttonText)

                },

                error: (xhr, status, error) => {
                    button.text(buttonText)
                    console.error(error);
                }
            });

        }

        retrieve_other_products() {
            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action: 'gswpts_product_fetch',
                },
                type: 'post',

                beforeSend: () => {
                    $('.other_products_section').html(`
                             <div class="ui segment gswpts_loader" style="width: 100%;">
                                    <div class="ui active inverted dimmer">
                                        <div class="ui massive text loader"></div>
                                    </div>
                                    <p></p>
                                    <p></p>
                                    <p></p>
                            </div>
                    `);
                },
                success: res => {
                    console.log(res)
                    $('.other_products_section').html(res);
                },

                error: err => {
                    $('.other_products_section').html("");
                    this.call_alert('Error &#128683;', '<b>Something went wrong on fetching related products</b>', 'error', 3)
                },

            })
        }
    }
    new Dashboard;
})