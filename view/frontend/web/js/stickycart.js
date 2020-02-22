define([
  "jquery",
  "jquery/ui",
  ], function ($) {
    'use strict';
    $.widget('mage.magepowStickycart', {
        options: {
            ScrollHeight:0
        },
      _create: function () {
        var options = this.options;

        if ($('body').hasClass('cookie-message'))
          $('body').removeClass('cookie-message')

        $(document).scroll(function() {
          var y = $(this).scrollTop();
          if (y > options.ScrollHeight) {
            $(".stickyCart").fadeIn("fast");
            
            $('body').addClass('show-add-cart-bottom')
          } else {
            $(".stickyCart").fadeOut("fast");
           
            if ($('body').hasClass('show-add-cart-bottom')){
              $('body').removeClass('show-add-cart-bottom')
            }
          }
        });

        $('#qtySticky').change(function(){
          $('#qty').val(this.value);
        });
        $('#qty').change(function(){
          $('#qtySticky').val(this.value);
        });

        $('#qtyGrouped').change(function(){
          $('form .data.grouped tr:first-child .qty').val(this.value);
        });

        $( "input[type='number']" ).change(function() {
          $('#qtyGrouped').val(this.value);
        });

        $('#btnSticky').click(function() {
          var $this = $(this)
          $this.attr("disabled", "disabled");
          setTimeout(function() {
            $this.removeAttr("disabled");
          }, 1500);
            $('#product-addtocart-button').click();
        });

        var clicks = 0;
        $('.btnCustom').click(function() {
          if (clicks == 0){
              $('#bundle-slide').click(); 
              $(this).text("Add To Cart");
              } else{
              $('#product-addtocart-button').click();
            }
          ++clicks;
        });


        $('.custom-qty').each(function() {
          var spinner = $(this),
          input = spinner.find('input[type="number"]'),
          btnUp = spinner.find('.increase'),
          btnDown = spinner.find('.reduced');
          btnUp.click(function() {
            spinner.find("input").trigger("change");
          });

          btnDown.click(function() {
            spinner.find("input").trigger("change");
          });
        });
      }
    });
  return $.mage.magepowStickycart;
});