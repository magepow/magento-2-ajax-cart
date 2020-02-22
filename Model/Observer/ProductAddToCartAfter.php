<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-06-14 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-06-29 12:46:07
 */
namespace Magepow\Ajaxcart\Model\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class ProductAddToCartAfter implements ObserverInterface
{
    /**
     * Ajax cart helper.
     *
     * @var \Magepow\Ajaxcart\Helper\Data
     */
    private $helper;

    /**
     * Core registry.
     *
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * Initialize dependencies.
     *
     * @param \Magepow\Ajaxcart\Helper\Data $helper
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magepow\Ajaxcart\Helper\Data $helper,
        \Magento\Framework\Registry $registry
    ) {
        $this->helper = $helper;
        $this->registry = $registry;
    }

    /**
     * Check is show additional data in quick view.
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        if ($this->helper->isEnabled()) {
            $resultItem = $observer->getQuoteItem();
            $this->registry->register('last_added_quote_item', $resultItem);
        }
    }
}