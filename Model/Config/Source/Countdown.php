<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-06-14 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-06-29 12:45:48
 */
namespace Magepow\Ajaxcart\Model\Config\Source;

class Countdown implements \Magento\Framework\Option\ArrayInterface
{
    const POPUP_COUNTDOWN_DISABLED = 0;
    const POPUP_COUNTDOWN_CONTINUE_BTN = 1;
    const POPUP_COUNTDOWN_VIEW_CART_BTN = 2;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return  [
            ['value' => self::POPUP_COUNTDOWN_DISABLED, 'label' => __('No')],
            ['value' => self::POPUP_COUNTDOWN_CONTINUE_BTN, 'label' => __('Continue button')],
            ['value' => self::POPUP_COUNTDOWN_VIEW_CART_BTN, 'label' => __('View Cart button')]
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            self::POPUP_COUNTDOWN_DISABLED => __('No'),
            self::POPUP_COUNTDOWN_CONTINUE_BTN => __('Continue button'),
            self::POPUP_COUNTDOWN_VIEW_CART_BTN => __('View Cart button')
        ];
    }
}