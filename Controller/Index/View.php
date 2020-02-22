<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-06-14 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-06-29 12:45:10
 */
namespace Magepow\Ajaxcart\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class View extends \Magento\Catalog\Controller\Product\View
{
    /**
     * Ajax cart helper.
     *
     * @var \Magepow\Ajaxcart\Helper\Data
     */
    private $ajaxHelper;

    /**
     * Initialize dependencies.
     *
     * @param Context $context
     * @param \Magento\Catalog\Helper\Product\View $viewHelper
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     * @param \Magepow\Ajaxcart\Helper\Data $ajaxHelper
     */
    public function __construct(
        Context $context,
        \Magento\Catalog\Helper\Product\View $viewHelper,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        \Magepow\Ajaxcart\Helper\Data $ajaxHelper
    ) {
        parent::__construct($context, $viewHelper, $resultForwardFactory, $resultPageFactory);
        $this->ajaxHelper = $ajaxHelper;
    }

    /**
     * Execute quick view.
     *
     * @return \Magento\Framework\Controller\Result\Forward|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        if ($this->ajaxHelper->isEnabled()) {
            return parent::execute();
        } else {
            $resultForward = $this->resultForwardFactory->create();
            $resultForward->forward('no-route');
            return $resultForward;
        }
    }
}
