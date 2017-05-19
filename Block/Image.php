<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Product description block
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace SR\CategoryImage\Block;

use Magento\Catalog\Model\Product;

class Image extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Product
     */
    protected $_category = null;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \SR\CategoryImage\Helper\Category
     */
    protected $_categoryHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \SR\CategoryImage\Helper\Category $categoryHelper,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        $this->_categoryHelper = $categoryHelper;
        parent::__construct($context, $data);
    }


    /**
     * Retrieve current category model object
     *
     * @return \Magento\Catalog\Model\Category
     */
    public function getCurrentCategory()
    {
        if (!$this->_category) {
            $this->_category = $this->_coreRegistry->registry('current_category');

            if (!$this->_category) {
                throw new \Magento\Framework\Exception\LocalizedException(__('Category object could not be found in core registry'));
            }
        }
        return $this->_category;
    }


    public function getImageUrl()
    {

        $imageCode = $this->hasImageCode() ? $this->getImageCode() : 'image';

        $image = $this->getCurrentCategory()->getData($imageCode);

        return $this->_categoryHelper->getImageUrl($image);
    }


}
