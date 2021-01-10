import Base_Class from './../Base/base_class';

jQuery(document).ready(function ($) {
    class Dashboard extends Base_Class {
        constructor() {
            super($);
            this.sortcode_copy_btn = $('.dashboard_sortcode_copy_btn');
            this.events();
        }
        events() {
            this.sortcode_copy_btn.on('click', (e) => {
                this.copy_shorcode(e)
            })
            if (this.get_slug_parameter('page') == 'gswpts-dashboard') {
                this.retrieve_wppool_post()
            }
            this.send_subscriber()
        }
        retrieve_wppool_post() {
            $.ajax({
                url: 'https://wppool.dev/wp-json/wp/v2/posts?per_page=10',

                type: 'GET',

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
                    console.log(res)
                    if (res) {
                        let blogs = ''
                        res.forEach(blog => {
                            blogs += ` <a class="col-12 p-0 d-flex justify-content-between align-items-center"  href="${blog.link}" target="blank">
                                                    <p class="m-0 p-0">${blog.title.rendered}</p>
                                                    <span><i class="fas fa-link"></i></span>
                                            </a>
                                            <div class="ui fitted divider mt-2 mb-2"></div>`
                        });
                        $('.useful_links').html(blogs);
                    } else {
                        $('.useful_links').html('');
                    }
                },
                error: err => {
                    this.call_alert('Error &#128683;', '<b>Plugins Blog could not be loaded. Try again</b>', 'error', 3)
                    $('.useful_links').html('');
                },
            })
        }

        send_subscriber() {

            var form = document.getElementById('wemail-embedded-subscriber-form');
            var button = form.querySelector('#subscribe_btn');
            var buttonText = button.innerText;
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const xhr = new XMLHttpRequest();
                xhr.addEventListener('loadstart', function () {
                    button.setAttribute('disabled', true);
                    button.innerText = 'Submitting';
                });
                xhr.addEventListener('load', function () {
                    var data = JSON.parse(xhr.response);

                    $.suiAlert({
                        title: 'Successfull &#128077;',
                        description: `<b>${data.message}</b>`,
                        type: 'success',
                        time: 3,
                        position: 'bottom-right',
                    });

                });
                xhr.addEventListener('error', function () {

                    $.suiAlert({
                        title: 'Error &#128683;',
                        description: '<b>Something went wrong. Subscription could not be made</b>',
                        type: 'error',
                        time: 4,
                        position: 'bottom-right',
                    });
                });
                xhr.addEventListener('loadend', function () {
                    button.removeAttribute('disabled');
                    button.innerHTML = buttonText;
                });
                xhr.open(form.method, form.action);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.send(new FormData(form));
            })
        }
    }
    new Dashboard;
})
