<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-06-14 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-06-29 12:45:00
 */
namespace Magepow\Ajaxcart\Controller\Index;

use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * Form key validator
     *
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    private $formKeyValidator;

    /**
     * Customer cart
     *
     * @var CustomerCart
     */
    protected $cart;

    /**
     * Result page factory.
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Resolver.
     *
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    private $resolverInterface;

    /**
     * Escaper.
     *
     * @var \Magento\Framework\Escaper
     */
    private $escaper;

    /**
     * Url builder.
     *
     * @var \Magento\Framework\UrlInterface
     */
    private $urlInterface;

    /**
     * Logger.
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $loggerInterface;

    /**
     * Product repository.
     *
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * Store manager.
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * Ajax cart helper.
     *
     * @var \Magepow\Ajaxcart\Helper\Data
     */
    protected $ajaxHelper;

    /**
     * Localized to normalized.
     *
     * @var \Magepow\Ajaxcart\Filter\LocalizedToNormalized
     */
    private $localizedToNormalized;

    /**
     * Data object factory.
     *
     * @var \Magento\Framework\DataObjectFactory
     */
    private $dataObjectFactory;

    /**
     * Core registry.
     *
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var bool
     */
    private $relatedAdded = false;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param CustomerCart $cart
     * @param \Magento\Framework\Locale\ResolverInterface $resolverInterface
     * @param \Magento\Framework\Escaper $escaper
     * @param \Psr\Log\LoggerInterface $loggerInterface
     * @param PageFactory $resultPageFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magepow\Ajaxcart\Helper\Data $ajaxHelper
     * @param \Magepow\Ajaxcart\Filter\LocalizedToNormalized $localizedToNormalized
     * @param \Magento\Framework\DataObjectFactory $dataObjectFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        CustomerCart $cart,
        \Magento\Framework\Locale\ResolverInterface $resolverInterface,
        \Magento\Framework\Escaper $escaper,
        \Psr\Log\LoggerInterface $loggerInterface,
        PageFactory $resultPageFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magepow\Ajaxcart\Helper\Data $ajaxHelper,
        \Magepow\Ajaxcart\Filter\LocalizedToNormalized $localizedToNormalized,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context);
        $this->formKeyValidator = $formKeyValidator;
        $this->cart = $cart;
        $this->resolverInterface = $resolverInterface;
        $this->escaper = $escaper;
        $this->urlInterface = $context->getUrl();
        $this->loggerInterface = $loggerInterface;
        $this->resultPageFactory = $resultPageFactory;
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
        $this->ajaxHelper = $ajaxHelper;
        $this->localizedToNormalized = $localizedToNormalized;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->registry = $registry;
    }

    /**
     * Set back redirect url to response
     *
     * @param null|string $backUrl
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    protected function _goBack($backUrl = null)
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($backUrl || $backUrl = $this->getBackUrl($this->_redirect->getRefererUrl())) {
            $resultRedirect->setUrl($backUrl);
        }

        return $resultRedirect;
    }

    public function getProductId()
    {
        $productId = (int)$this->getRequest()->getParam('product');

        if (!$productId) {
            $productId = (int)$this->getRequest()->getParam('id');
        }

        return $productId;
    }

    /**
     * Execute add to cart.
     *
     * @return $this|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        if (!$this->ajaxHelper->isEnabled()) {
            return parent::execute();
        }

        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return $this->resultRedirectFactory->create()->setPath('/');
        }

        $params = $this->getRequest()->getParams();
        try {
            if (array_key_exists('qty', $params)) {
                $filter = $this->localizedToNormalized;
                $params['qty'] = $filter->filter($params['qty']);
            }

            $product = $this->initProduct();

            /**
             * Check product availability
             */
            if (!$product) {
                return $this->resultRedirectFactory->create()->setPath('/');
            }

            $data = [
                'status' => true,
                'added' => false,
                'messages' => []
            ];

            $result = $this->dataObjectFactory->create()->setData($data);

            $this->_eventManager->dispatch(
                'magepow_ajaxcart_add_before',
                ['product' => $product, 'request' => $this->getRequest(), 'result' => $result]
            );

            if (!$result->getData('status') && empty($messages)) {
                return $this->resultRedirectFactory->create()->setPath('/');
            }

            $this->processAddProduct($result, $product, $params);
            $this->cart->save();

            $this->_eventManager->dispatch(
                'checkout_cart_add_product_complete',
                ['product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse()]
            );

            $resultItem = $product->getTypeId() == \Magento\GroupedProduct\Model\Product\Type\Grouped::TYPE_CODE ?
                                $this->dataObjectFactory->create()->setProduct($product)
                                : $this->registry->registry('last_added_quote_item');
            return $this->returnResult($resultItem, $this->relatedAdded);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addNoticeMessage(
                $this->escaper->escapeHtml($e->getMessage())
            );

            $result = [];
            $productId = $this->getProductId();
            $result['error'] = true;
            $result['error_info'] = $e->getMessage();
            $result['id'] = $productId;
            $result['url'] = $this->escaper->escapeUrl(
                $this->urlInterface->getUrl('ajaxcart/index/view', ['id' => $productId])
            );
            $result['view'] = true;
            // $qty = isset($params['qty']) ? $params['qty'] : 1;
            // $stockQty = $product->getExtensionAttributes()->getStockItem()->getQty();
            if( ($product->getTypeId() == 'simple' && !$product->getData('has_options')) || $this->getRequest()->getPost('related_product') ) {
                $result['view'] = false;
            }
            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $resultJson->setData($result);
            return $resultJson;
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('We can\'t add this item to your shopping cart right now.')
            );
            $this->loggerInterface->critical($e);

            $result = [];
            $result['error'] = true;

            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $resultJson->setData($result);
            return $resultJson;
        }
    }

    /**
     * Init requested product.
     *
     * @return bool|\Magento\Catalog\Api\Data\ProductInterface
     */
    private function initProduct()
    {
        $productId = $this->getProductId();

        if ($productId) {
            $storeId = $this->storeManager->getStore()->getId();
            try {
                return $this->productRepository->getById($productId, false, $storeId);
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * Return add to cart result.
     *
     * @param \Magento\Quote\Model\Quote\Item $resultItem
     * @param boolean $relatedAdded
     * @return \Magento\Framework\Controller\ResultInterface
     */
    private function returnResult($resultItem, $relatedAdded)
    {
        if (!$this->cart->getQuote()->getHasError()) {
            $result = [];

            $resultPage = $this->resultPageFactory->create();
            $popupBlock = $resultPage->getLayout()
                ->createBlock(\Magepow\Ajaxcart\Block\Ajax::class)
                ->setTemplate('Magepow_Ajaxcart::popup.phtml')
                ->setItem($resultItem)
                ->setRelatedAdded($relatedAdded);

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

            $message = __(
                'You added %1 to your shopping cart.',
                $resultItem->getName()
            );
            $this->messageManager->addSuccessMessage($message);

            $result['popup'] = $html;

            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $resultJson->setData($result);
            return $resultJson;
        }
    }

    /**
     * Add message from magepow_ajaxcart_add_before result
     *
     * @param array $message
     * @return void
     */
    private function addResultMessage($message)
    {
        if (isset($message['type'])) {
            switch ($message['type']) {
                case "notice":
                    $this->messageManager->addNoticeMessage(
                        $this->escaper->escapeHtml($message['message'])
                    );
                    break;
                case "error":
                    $this->messageManager->addErrorMessage(
                        $this->escaper->escapeHtml($message['message'])
                    );
                    break;
                case "success":
                    $this->messageManager->addSuccessMessage(
                        $this->escaper->escapeHtml($message['message'])
                    );
                    break;
                default:
                    $this->messageManager->addNoticeMessage(
                        $this->escaper->escapeHtml($message['message'])
                    );
            }
        }
    }

    /**
     * Process add product to cart.
     *
     * @param \Magento\Framework\DataObject $result
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param array $params
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function processAddProduct($result, $product, $params)
    {
        $messages = $result->getData('messages');
        if (!empty($messages)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                $messages[0]['message']
            );
        }

        if (!$result->getData('added')) {
            $this->cart->addProduct($product, $params);
        }

        $related = $this->getRequest()->getParam('related_product');
        $messages = $result->getData('messages');
        foreach ($messages as $message) {
            $this->addResultMessage($message);
        }

        if (!empty($related)) {
            $this->relatedAdded = true;
            $this->cart->addProductsByIds(explode(',', $related));
        }
    }
}
