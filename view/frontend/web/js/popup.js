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
                var countDown = options.countDown;

                if(countDown > 0) {
                    window.ajaxcart_countdown = setInterval(function () {
                        countDown -= 1;
                        $('.content-ajaxcart span.countdown').text("(" + countDown + ")");
                        if (countDown <= 0) {
                            self._closePopup();
                        }
                    }, 1000);
                }
                
                $('.content-ajaxcart .btn-continue').on('click', function() {
                    self._closePopup();
                });

            },

            _closePopup: function(){
                // $('.modals-ajaxcart, .modals-overlay').remove();
                // $('body').removeClass('_has-modal');
                $('.modals-ajaxcart').find('.action-close').trigger('click');
                clearInterval(window.ajaxcart_countdown);
            }
        });

        return $.magepow.popup;
    });