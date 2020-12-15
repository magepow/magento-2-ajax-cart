<?php
/**
 * Allcart
 *
 * @copyright Copyright © 2020 Magepow. All rights reserved.
 * @author    @copyright Copyright (c) 2014 Magepow (<https://www.magepow.com>)
 * @license <https://www.magepow.com/license-agreement.html>
 * @Author: magepow<support@magepow.com>
 * @github: <https://github.com/magepow>
 */
namespace Magepow\Ajaxcart\Controller\Index;

use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;

class Allcart extends Index
{

    /**
     * Execute add to cart.
     *
     * @return $this|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {

        $productIds = $this->getRequest()->getParam('productIds');
        
        if ($productIds) {
            $productIds = explode(',', $productIds);
            $this->cart->addProductsByIds($productIds);
            $this->cart->save();
            $result     =  $this->returnResult();
        }else{
            $result['error'] = true;
        }

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($result);
        return $resultJson;
    }

    private function returnResult()
    {
        if (!$this->cart->getQuote()->getHasError()) {
            $result = [];

            $resultPage = $this->resultPageFactory->create();
            $popupBlock = $resultPage->getLayout()
                ->createBlock(\Magepow\Ajaxcart\Block\Ajax::class)
                ->setTemplate('Magepow_Ajaxcart::all_tocart_popup.phtml');

            if ($this->ajaxHelper->isShowSuggestBlock()) {
                $suggestBlock = $resultPage->getLayout()
                    ->createBlock(\Magepow\Ajaxcart\Block\Popup\Suggest::class)
                    ->setTemplate('Magepow_Ajaxcart::popup/suggest.phtml')
                    ->setProductId($resultItem->getProductId());

                $popupAjaxBlock = $resultPage->getLayout()
                    ->createBlock(\Magepow\Ajaxcart\Block\Ajax::class)
                    ->setTemplate('Magepow_Ajaxcart::popup/ajax.phtml');

                $suggestBlock->setChild('ajaxcart.popup.ajax.suggest', $popupAjaxBlock);
                $popupBlock->setChild('ajaxcart.popup.suggest', $suggestBlock);
            }

            $html = $popupBlock->toHtml();
            $qty = $this->cart->getQuote()->getItemsQty();
            $message = __(
                'You added %1 product(s) to your shopping cart.',
                $qty
            );
            $this->messageManager->addSuccessMessage($message);

            $result['popup'] = $html;

            return $result;
        }
    } 

}
