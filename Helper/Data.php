<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-06-14 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-06-29 12:45:32
 */
namespace Magepow\Ajaxcart\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    const PRICE_SHIPPING_BAR = 'carriers/freeshipping/free_shipping_subtotal';

    /**
     * @var array
     */
    protected $configModule;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $_moduleManager;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Module\Manager $moduleManager
    )
    {
        $this->_moduleManager = $moduleManager;
        parent::__construct($context);
        $this->configModule = $this->getConfig(strtolower($this->_getModuleName()));
    }

    public function getConfig($cfg='')
    {
        if($cfg) return $this->scopeConfig->getValue( $cfg, \Magento\Store\Model\ScopeInterface::SCOPE_STORE );
        return $this->scopeConfig;
    }

    public function getConfigModule($cfg='', $value=null)
    {
        $values = $this->configModule;
        if( !$cfg ) return $values;
        $config  = explode('/', $cfg);
        $end     = count($config) - 1;
        foreach ($config as $key => $vl) {
            if( isset($values[$vl]) ){
                if( $key == $end ) {
                    $value = $values[$vl];
                }else {
                    $values = $values[$vl];
                }
            } 

        }
        return $value;
    }

    /**
     * Returns if module exists or not
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function isModuleEnabled($moduleName)
    {
      return $this->_moduleManager->isEnabled($moduleName);
    }

    /**
    * Return if maximum price for shipping bar
    * @return int
    */
    public function getPriceForShippingBar()
    {
         return $this->scopeConfig->getValue(
             self::PRICE_SHIPPING_BAR,
             \Magento\Store\Model\ScopeInterface::SCOPE_STORE
         );
    }

    /**
     * Is ajax cart enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            'magepow_ajaxcart/general/enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is ajax cart enabled in product view.
     *
     * @return bool
     */
    public function isEnabledProductView()
    {
        return $this->scopeConfig->isSetFlag(
            'magepow_ajaxcart/general/enabled_product_view',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get add to cart button selector.
     *
     * @return string
     */
    public function getAddToCartSelector()
    {
        return $this->scopeConfig->getValue(
            'magepow_ajaxcart/general/selector',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is show product image in success popup.
     *
     * @return bool
     */
    public function isShowProductImage()
    {
        return $this->scopeConfig->isSetFlag(
            'magepow_ajaxcart/success_popup/product_image',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get product image size in success popup.
     *
     * @return string
     */
    public function getImageSize()
    {
        return $this->scopeConfig->getValue(
            'magepow_ajaxcart/success_popup/product_image_size',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get product image width in success popup.
     *
     * @return string
     */
    public function getImageWidth()
    {
        return $this->scopeConfig->getValue(
            'magepow_ajaxcart/success_popup/product_image_width',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get product image height in success popup.
     *
     * @return string
     */
    public function getImageHeight()
    {
        return $this->scopeConfig->getValue(
            'magepow_ajaxcart/success_popup/product_image_height',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is show added product price in success popup.
     *
     * @return bool
     */
    public function isShowProductPrice()
    {
        return $this->scopeConfig->isSetFlag(
            'magepow_ajaxcart/success_popup/product_price',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is show continue button in success popup.
     *
     * @return bool
     */
    public function isShowContinueBtn()
    {
        return $this->scopeConfig->isSetFlag(
            'magepow_ajaxcart/success_popup/continue_button',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get countdown enabled for which button.
     *
     * @return string
     */
    public function getCountDownActive()
    {
        return $this->scopeConfig->getValue(
            'magepow_ajaxcart/success_popup/enabled_countdown',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get countdown time in second.
     *
     * @return string
     */
    public function getCountDownTime()
    {
        return $this->scopeConfig->getValue(
            'magepow_ajaxcart/success_popup/countdown_time',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is show cart info in success popup.
     *
     * @return bool
     */
    public function isShowCartInfo()
    {
        return $this->scopeConfig->isSetFlag(
            'magepow_ajaxcart/success_popup/mini_cart',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is show checkout link in success popup.
     *
     * @return bool
     */
    public function isShowCheckoutLink()
    {
        return $this->scopeConfig->isSetFlag(
            'magepow_ajaxcart/success_popup/mini_checkout',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is show suggested products.
     *
     * @return bool
     */
    public function isShowSuggestBlock()
    {
        return $this->scopeConfig->isSetFlag(
            'magepow_ajaxcart/success_popup/suggest_product',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get suggest title.
     *
     * @return string
     */
    public function getSuggestTitle()
    {
        return $this->scopeConfig->getValue(
            'magepow_ajaxcart/success_popup/suggest_title',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get suggested source.
     *
     * @return int
     */
    public function getSuggestSource()
    {
        return $this->scopeConfig->getValue(
            'magepow_ajaxcart/success_popup/suggest_source',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get suggested limit.
     *
     * @return int
     */
    public function getSuggestLimit()
    {
        return $this->scopeConfig->getValue(
            'magepow_ajaxcart/success_popup/suggest_limit',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get button text color.
     *
     * @return mixed|string
     */
    public function getBtnTextColor()
    {
        $color = $this->scopeConfig->getValue(
            'magepow_ajaxcart/success_popup_design/button_text_color',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $color = ($color == '') ? 'ffffff' : $color;
        return $color;
    }

    /**
     * Get continue button text.
     *
     * @return string
     */
    public function getBtnContinueText()
    {
        return $this->scopeConfig->getValue(
            'magepow_ajaxcart/success_popup_design/continue_text',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get continue button background.
     *
     * @return mixed|string
     */
    public function getBtnContinueBackground()
    {
        $backGround = $this->scopeConfig->getValue(
            'magepow_ajaxcart/success_popup_design/continue',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $backGround = ($backGround == '') ? '1979c3' : $backGround;
        return $backGround;
    }

    /**
     * Get continue button color when hover.
     *
     * @return mixed|string
     */
    public function getBtnContinueHover()
    {
        $hover = $this->scopeConfig->getValue(
            'magepow_ajaxcart/success_popup_design/continue_hover',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $hover = ($hover == '') ? '006bb4' : $hover;
        return $hover;
    }

    /**
     * Get view cart button text.
     *
     * @return string
     */
    public function getBtnViewcartText()
    {
        return $this->scopeConfig->getValue(
            'magepow_ajaxcart/success_popup_design/viewcart_text',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get view cart button background.
     *
     * @return mixed|string
     */
    public function getBtnViewcartBackground()
    {
        $backGround = $this->scopeConfig->getValue(
            'magepow_ajaxcart/success_popup_design/viewcart',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $backGround = ($backGround == '') ? '1979c3' : $backGround;
        return $backGround;
    }

    /**
     * Get view cart button color when hover.
     *
     * @return mixed|string
     */
    public function getBtnViewcartHover()
    {
        $hover = $this->scopeConfig->getValue(
            'magepow_ajaxcart/success_popup_design/viewcart_hover',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $hover = ($hover == '') ? '006bb4' : $hover;
        return $hover;
    }

    /**
     * Get free shipping message text.
     *
     * @return string
     */
    public function getFreeShippingMessageText()
    {
        return $this->scopeConfig->getValue(
            'magepow_ajaxcart/success_popup_design/freeShipping_message',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get congratulation message text.
     *
     * @return string
     */
    public function getCongratulationMessageText()
    {
        return $this->scopeConfig->getValue(
            'magepow_ajaxcart/success_popup_design/congratulation',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is show go to product link in quick view.
     *
     * @return bool
     */
    public function isShowQuickviewGotoLink()
    {
        return $this->scopeConfig->isSetFlag(
            'magepow_ajaxcart/quickview_popup/go_to_product',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is show additional data in quick view.
     *
     * @return bool
     */
    public function isShowQuickviewAddData()
    {
        return $this->scopeConfig->isSetFlag(
            'magepow_ajaxcart/quickview_popup/additional_data',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get display product prices type in catalog.
     *
     * @return string
     */
    public function getProductTaxDisplayType()
    {
        return $this->scopeConfig->getValue(
            'tax/display/type',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}