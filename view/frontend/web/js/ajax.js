define([
    'jquery',
    'Magento_Ui/js/modal/modal'
    ], function ($, modal) {
        'use strict';

        $.widget('magepow.ajaxcart', {
            options: {
                addUrl: '',
                quickview: false,
                isProductView: false,
                isSuggestPopup: false,
                quickViewUrl: '',
                addToCartSelector: '.action.tocart'
            },

            productIdInputName: ["product", "product-id", "data-product-id", "data-product"],

            _create: function () {
                this._initAjaxcart();
                window.ajaxCart = this;
            },

            _initAjaxcart: function () {
                var options = this.options;
                var self = this;

                self.element.find(options.addToCartSelector).off('click');
                self.element.find(options.addToCartSelector).click(function (e) {
                    e.preventDefault();

                    var form = $(this).parents('form').get(0);
                    var data = '';
                    if (form) {
                        var isValid = true;
                        if (options.isProductView) {
                            try {
                                isValid = $(form).valid();
                            } catch(err) {
                                isValid = true;
                            }
                        }

                        if (isValid) {
                            var oldAction = $(form).attr('action');
                            var serialize = $(form).serialize();
                            var id = self._findId(this, oldAction, form);

                            if ($.isNumeric(id)) {
                                data += 'id=' + id;
                                if (serialize == '') {
                                    $(form).find('input, select').each(function () {
                                        data += "&" + $(this).attr('name') + "=" + $(this).val();
                                    });
                                } else {
                                    data += "&" + serialize;
                                }

                                if (options.quickview) {
                                    window.parent.ajaxCart._sendAjax(options.addUrl, data, oldAction);
                                    return false;
                                }

                                self._sendAjax(options.addUrl, data, oldAction);
                                return false;
                            }

                            window.location.href = oldAction;
                        }
                    } else {
                        var dataPost = $.parseJSON($(this).attr('data-post'));
                        if (dataPost) {
                            var formKey = $("input[name='form_key']").val();
                            var oldAction = dataPost.action;
                            data += 'id=' + dataPost.data.product + '&product=' + dataPost.data.product + '&form_key=' + formKey + '&uenc=' + dataPost.data.uenc;
                            self._sendAjax(options.addUrl, data, oldAction);
                            return false;
                        } else {
                            var id = self._findId(this);
                            if (id) {
                                e.stopImmediatePropagation();
                                self._requestQuickview(options.quickViewUrl + 'id/' + id);
                                return false;
                            }
                        }
                    }
                });
            },

            _findId: function (btn, oldAction, form) {
                var self = this;
                var id = $(btn).attr('data-product-id');

                if($.isNumeric(id)) {
                    return id;
                }

                var item = $(btn).closest('li.product-item');
                id = $(item).find('[data-product-id]').attr('data-product-id');

                if ($.isNumeric(id)) {
                    return id;
                }

                if (oldAction) {
                    var formData = oldAction.split('/');
                    for (var i = 0; i < formData.length; i++) {
                        if (self.productIdInputName.indexOf(formData[i]) >= 0) {
                            if ($.isNumeric(formData[i + 1])) {
                                id = formData[i + 1];
                            }
                        }
                    }

                    if ($.isNumeric(id)) {
                        return id;
                    }
                }

                if (form) {
                    $(form).find('input').each(function () {
                        if (self.productIdInputName.indexOf($(this).attr('name')) >= 0) {
                            if ($.isNumeric($(this).val())) {
                                id = $(this).val();
                            }
                        }
                    });

                    if ($.isNumeric(id)) {
                        return id;
                    }
                }

                var priceBox = $(btn).closest('.price-box.price-final_price');
                id = $(priceBox).attr('data-product-id');

                if ($.isNumeric(id)) {
                    return id;
                }

                return false;
            },

            _sendAjax: function (addUrl, data, oldAction) {
                var options = this.options;
                var self = this;

                $.ajax({
                    type: 'post',
                    url: addUrl,
                    data: data,
                    showLoader: true,
                    dataType: 'json',
                    success: function (data) {
                        if (data.popup) {
                            var _qsModalContent = '<div class="content-ajaxcart">quickview placeholder</div>';
                            if(!$('#modals_ajaxcart').length){
                                $(document.body).append('<div id="modals_ajaxcart" style="display:none">' + _qsModalContent + '</div>');
                            }

                            var _qsModal = $('#modals_ajaxcart .content-ajaxcart');

                            self._showPopup(_qsModal, _qsModalContent, data.popup);
                        } else if (data.error && data.view) {
                            /*show Quick View*/ 
                            if(data.error_info.search("not available") == -1)
                                $.fn.quickview({url:options.quickViewUrl  + 'id/' + data['id']});
                        } else {
                            if($('.modals-ajaxcart')){
                                $('.modals-ajaxcart').remove();
                            }
                        }
                    },
                    error: function () {
                        window.location.href = oldAction;
                    }
                });
            },

            _showPopup: function (_qsModal, _qsModalContent, data) {
                if($('.modals-quickview')){
                    $('.modals-quickview').remove();
                }
                if(_qsModal.length) $('#modals_ajaxcart').html(_qsModalContent);
                _qsModal.trigger('contentUpdated');
                
                _qsModal.html(data);
                modal({
                    type: 'popup',
                    modalClass: 'modals-ajaxcart',
                    responsive: true, 
                    buttons: false,
                    focus:'#modals_ajaxcart .header',
                    closed: function(){
                        clearInterval(window.count);
                    }                           
                }, _qsModal);
                _qsModal.modal('openModal');
                _qsModal.trigger('contentUpdated');
                /*Show count down*/
                this._showCountdown(); 
            },

            _showCountdown: function () {
                var self = $('.content-ajaxcart');
                var options = this.options;
                var countDown = options.countDown;
                if(countDown > 0) {
                    window.count = setInterval(function () {
                        countDown -= 1;
                        self.find('span.countdown').text("(" + countDown + ")");
                        if (countDown <= 0) {
                            self.find('span.countdown').parent().trigger("click");
                            clearInterval(window.count);
                        }
                    }, 1000);
                }
            }
        });

$.fn.magiccart=$.magepow.ajaxcart;
return $.magepow.ajaxcart;
});