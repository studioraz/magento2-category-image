<?php
namespace SR\CategoryImage\Controller\Adminhtml\Category\Mobile;

use SR\CategoryImage\Controller\Adminhtml\AbstractUpload;

/**
 * Class Upload
 */
class Upload extends AbstractUpload
{
    const CATEGORY_ATTRIBUTE_IMAGE = 'image_mobile';

    /**
     * {@inheritdoc}
     */
    protected function getCategoryAttributeImage()
    {
        return self::CATEGORY_ATTRIBUTE_IMAGE;
    }
}