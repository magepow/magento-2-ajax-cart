define([
    'jquery'
], function ($) {
    'use strict';

    return function (widget) {
        $.widget('mage.configurable', widget, {
            /**
             * Initialize tax configuration, initial settings, and options values.
             * @private
             */
            _initializeOptions: function () {
                var element;

                element = $(this.options.priceHolderSelector);
                if (!element.data('magePriceBox')) {
                    element.priceBox();
                }

                return this._super();
            }
        });

        return $.mage.configurable;
    };
});