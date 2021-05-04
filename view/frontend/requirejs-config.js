var config = {
	map: {
        '*': {
            magepowAjaxcart: 'Magepow_Ajaxcart/js/ajax',
            magepowPopup: 'Magepow_Ajaxcart/js/popup',
            magepowGoto: 'Magepow_Ajaxcart/js/goto',
            magepowProductSuggest: 'Magepow_Ajaxcart/js/suggest'
        }
    },
    config:{
    	mixins: {
         'Magento_ConfigurableProduct/js/configurable': {
               'Magepow_Ajaxcart/js/mixin/configurable': true
           }
      }
  }
};