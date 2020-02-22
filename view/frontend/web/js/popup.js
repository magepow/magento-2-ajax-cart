define([
    'jquery',
    'magepowOwl'
    ], function ($) {
        'use strict';

        $.widget('magepow.popup', {
            options: {
                countDown: 0
            },

            _create: function () {
                var self = this;
                var options = this.options;

                self.element.find('.btn-continue').click(function () {
                    $.fancybox.close();
                    clearInterval(window.count);
                });

                var countDown = options.countDown;

                if(countDown > 0) {
                    window.count = setInterval(function () {
                        countDown -= 1;
                        self.element.find('span.countdown').text("(" + countDown + ")");
                        if (countDown <= 0) {
                            self.element.find('span.countdown').parent().trigger("click");
                            clearInterval(window.count);
                        }
                    }, 1000);
                }
            }
        });

        return $.magepow.popup;
    });