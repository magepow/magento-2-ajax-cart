<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-06-14 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-06-29 12:46:03
 */
namespace Magepow\Ajaxcart\Model\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class AdditionalCheck implements ObserverInterface
{
    /**
     * Ajax cart helper.
     *
     * @var \Magepow\Ajaxcart\Helper\Data
     */
    private $helper;

    /**
     * Http request.
     *
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * Initialize dependencies.
     *
     * @param \Magepow\Ajaxcart\Helper\Data $helper
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Magepow\Ajaxcart\Helper\Data $helper,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->helper = $helper;
        $this->request = $request;
    }

    /**
     * Check is show additional data in quick view.
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        $layout = $observer->getLayout();
        $block = $layout->getBlock('product.info.details');
        if ($block && $this->request->getModuleName() == 'ajaxcart') {
            $isShow = $this->helper->isShowQuickviewAddData();

            if (!$isShow) {
                $layout->unsetElement('product.info.details');
            }
        }
    }
}