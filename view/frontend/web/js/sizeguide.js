define([
    "jquery",
    'Magento_Ui/js/modal/modal'
], function ($, modal) {
    'use strict';
    $.widget('mage.sizeguide', {
        _create: function () {
            $('.size-guide .size-guide-text').click(function () {
                $('#size-guide-popup').modal({
                    type: 'popup',
                    modalClass: 'modals-sizeguide',
                    responsive: true, 
                    buttons: false           
                });
                $("#size-guide-popup").modal("openModal");
            });

            $('.delivery-return .delivery-return-text').click(function () {
                $('#delivery-return-popup').modal({
                    type: 'popup',
                    modalClass: 'modals-sizeguide',
                    responsive: true, 
                    buttons: false           
                });
                $("#delivery-return-popup").modal("openModal");
            });
        }
    });
    return $.mage.sizeguide;
});