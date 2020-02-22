<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-07-15 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-07-23 21:18:42
 */
namespace Magepow\Ajaxcart\Block\Product;

use Magento\Framework\View\Element\Template;

class Stickycart extends Template
{
	protected $_registry;

    protected $stockRegistry;

	public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
		array $data = []
	) {
		$this->_registry = $registry;
        $this->stockRegistry = $stockRegistry;
		parent::__construct($context, $data);
	}

    
    public function getCurrentProduct()
    {        
        return $this->_registry->registry('current_product');
    }

    public function getStockItem($productId)
    {
        return $this->stockRegistry->getStockItem($productId);
    } 
}