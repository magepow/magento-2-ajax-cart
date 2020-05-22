define([
    'jquery',
    ], function ($) {
        'use strict';

        $.widget('magepow.popup', {
            options: {
                countDown: 0
            },

            _create: function () {
                var self = this;
                var options = this.options;

                $('.btn-continue').on('click', function() {
                    $('.modals-ajaxcart').find('.action-close').trigger('click');   
                    clearInterval(window.count);
                });

                var countDown = options.countDown;

                if(countDown > 0) {
                    window.count = setInterval(function () {
                        countDown -= 1;
                        $('.content-ajaxcart').find('span.countdown').text("(" + countDown + ")");
                        if (countDown <= 0) {
                            $('.modals-ajaxcart').find('.action-close').trigger('click');
                            clearInterval(window.count);
                        }
                    }, 1000);
                }
            }
        });

        return $.magepow.popup;
    });