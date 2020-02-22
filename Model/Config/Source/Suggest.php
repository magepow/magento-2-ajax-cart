<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-06-14 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-06-29 12:45:56
 */
namespace Magepow\Ajaxcart\Model\Config\Source;

class Suggest implements \Magento\Framework\Option\ArrayInterface
{
    const SUGGEST_SOURCE_RELATED = 0;
    const SUGGEST_SOURCE_UPSELL = 1;
    const SUGGEST_SOURCE_XSELL = 2;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return  [
            ['value' => self::SUGGEST_SOURCE_RELATED, 'label' => __('Related Products')],
            ['value' => self::SUGGEST_SOURCE_UPSELL, 'label' => __('Up-Sell Products')],
            ['value' => self::SUGGEST_SOURCE_XSELL, 'label' => __('Cross-Sell Products')]
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
            self::SUGGEST_SOURCE_RELATED => __('Related Products'),
            self::SUGGEST_SOURCE_UPSELL => __('Up-Sell Products'),
            self::SUGGEST_SOURCE_XSELL => __('Cross-Sell Products')
        ];
    }
}