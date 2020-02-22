<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-06-14 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-06-29 12:45:42
 */
namespace Magepow\Ajaxcart\Model\Config\Backend\Number;

class GreaterThanZero extends \Magento\Framework\App\Config\Value
{
    /**
     * Validate field value before save.
     *
     * @return void
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    public function beforeSave()
    {
        $label = $this->getData('field_config/label');

        if ($this->getValue() == '') {
            throw new \Magento\Framework\Exception\ValidatorException(__($label . ' is required.'));
        } elseif (!is_numeric($this->getValue())) {
            throw new \Magento\Framework\Exception\ValidatorException(__($label . ' is not a number.'));
        } elseif ($this->getValue() <= 0) {
            throw new \Magento\Framework\Exception\ValidatorException(__($label . ' must greater than 0.'));
        }

        $this->setValue((int) $this->getValue());
        parent::beforeSave();
    }
}