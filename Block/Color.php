<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-07-9 17:19:50
 * @LastEditors: DavidDuong
 * @LastEditTime: 2020-06-29 12:59:07
 */
namespace Magepow\Ajaxcart\Block;
 
class Color extends \Magento\Config\Block\System\Config\Form\Field {
 
	public function __construct(
	\Magento\Backend\Block\Template\Context $context, array $data = []
	) {
		parent::__construct($context, $data);
	}
	
	protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element) {
		$html = $element->getElementHtml();
		$value = $element->getData('value');
	
		$html .= '<script type="text/javascript">
			require(["jquery", "jquery/colorpicker/js/colorpicker"], function ($) {
				$(document).ready(function (e) {
                   	var $el = $("#' . $element->getHtmlId() . '");
                    $el.css("backgroundColor", "'. $value .'");

                    // Attach the color picker
                    $el.ColorPicker({
                        color: "'. $value .'",
                        onChange: function (hsb, hex, rgb) {
                            $el.css("backgroundColor", "#" + hex).val("#" + hex);
                        }
                    });
				});
			});
			</script>';

		return $html;
	}
 
}