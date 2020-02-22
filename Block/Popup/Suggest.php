<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-06-14 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-06-29 12:44:50
 */
namespace Magepow\Ajaxcart\Block\Popup;

class Suggest extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /**
     * Ajax cart helper.
     *
     * @var \Magepow\Ajaxcart\Helper\Data
     */
    private $helper;

    /**
     * Catalog product model.
     *
     * @var \Magento\Catalog\Model\Product
     */
    private $product;

    /**
     * Post data helper.
     *
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    private $postDataHelper;

    /**
     * Catalog product visibility helper.
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    private $catalogProductVisibility;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param \Magepow\Ajaxcart\Helper\Data $helper
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magepow\Ajaxcart\Helper\Data $helper,
        \Magento\Catalog\Model\Product $product,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );
        
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->helper = $helper;
        $this->product = $product;
        $this->postDataHelper = $postDataHelper;
    }

    /**
     * Get ajax cart helper.
     *
     * @return \Magepow\Ajaxcart\Helper\Data
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * Get post data helper.
     *
     * @return \Magento\Framework\Data\Helper\PostHelper
     */
    public function getPostDataHelper()
    {
        return $this->postDataHelper;
    }

    /**
     * Get suggested product collection.
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getProductCollection()
    {
        $helper = $this->getHelper();
        $product = $this->getProduct();
        $source = $helper->getSuggestSource();

        switch ($source) {
            case \Magepow\Ajaxcart\Model\Config\Source\Suggest::SUGGEST_SOURCE_RELATED:
                $collection = $product->getRelatedProductCollection();
                break;
            case \Magepow\Ajaxcart\Model\Config\Source\Suggest::SUGGEST_SOURCE_UPSELL:
                $collection = $product->getUpSellProductCollection();
                break;
            case \Magepow\Ajaxcart\Model\Config\Source\Suggest::SUGGEST_SOURCE_XSELL:
                $collection = $product->getCrossSellProductCollection();
                break;
            default:
                $collection = $product->getRelatedProductCollection();
        }

        $collection->addAttributeToSelect('required_options')->setPositionOrder()->addStoreFilter();
        $this->_addProductAttributesAndPrices($collection);
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        $limit = $helper->getSuggestLimit();

        if ($limit && $limit > 0) {
            $collection->setPageSize($limit);
        }

        $collection->load();

        return $collection;
    }

    /**
     * Get suggested block title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getHelper()->getSuggestTitle();
    }

    /**
     * Get added product.
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        if (!$this->product->getId()) {
            $productId = $this->getProductId();
            $this->product->load($productId);
        }

        return $this->product;
    }
}
