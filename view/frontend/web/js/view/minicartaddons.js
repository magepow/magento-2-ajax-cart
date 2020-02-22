define([
       'ko',
       'uiComponent',
       'Magento_Customer/js/customer-data',
   ], function (ko, Component, customerData) {
       'use strict';
       var subtotalAmount;
       var maxPrice = maxpriceShipping;
       var percentage;
       return Component.extend({
           displaySubtotal: ko.observable(true),
           maxprice:maxPrice.toFixed(2),
           /**
            * @override
            */
           initialize: function () {
               this._super();
               this.cart = customerData.get('cart');
           },
           getTotalCartItems: function () {
               return customerData.get('cart')().summary_count;
           },
           getpercentage: function () {
               subtotalAmount = customerData.get('cart')().subtotalAmount;
               if (subtotalAmount > maxPrice) {
                   subtotalAmount = maxPrice;
               }
               percentage = ((subtotalAmount * 100) / maxPrice);
               return percentage;
           }
       });
   });