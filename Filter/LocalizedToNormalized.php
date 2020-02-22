<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-06-14 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-06-29 12:45:22
 */
namespace Magepow\Ajaxcart\Filter;

class LocalizedToNormalized extends \Magento\Framework\Filter\LocalizedToNormalized
{
    /**
     * Resolver.
     *
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    private $resolverInterface;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Framework\Locale\ResolverInterface $resolverInterface
     */
    public function __construct(
        \Magento\Framework\Locale\ResolverInterface $resolverInterface
    ) {
        parent::__construct();
        $this->resolverInterface = $resolverInterface;
    }

    /**
     * Filter local value.
     *
     * @param string $value
     * @return array|string
     * @throws \Zend_Locale_Exception
     */
    public function filter($value)
    {
        $this->_options = ['locale' => $this->resolverInterface->getLocale()];
        if (!isset($this->_options['date_format'])) {
            $this->_options['date_format'] = null;
        }

        if (\Zend_Locale_Format::isNumber($value, $this->_options)) {
            return \Zend_Locale_Format::getNumber($value, $this->_options);
        } elseif (($this->_options['date_format'] === null) && (strpos($value, ':') !== false)) {
            // Special case, no date format specified, detect time input
            return \Zend_Locale_Format::getTime($value, $this->_options);
        } elseif (\Zend_Locale_Format::checkDateFormat($value, $this->_options)) {
            // Detect date or time input
            return \Zend_Locale_Format::getDate($value, $this->_options);
        }

        return $value;
    }
}