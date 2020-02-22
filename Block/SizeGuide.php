<?php

namespace Magepow\Ajaxcart\Block;

class SizeGuide extends \Magento\Framework\View\Element\Template
{
    protected $_productAttributeRepository;
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_productAttributeRepository = $productAttributeRepository;
    }

    public function getProductAttributeByCode($code)
    {
        $attribute = $this->_productAttributeRepository->get($code);
        return $attribute;
    }


}
