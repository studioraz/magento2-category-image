<?php
namespace SR\CategoryImage\Plugin\Catalog\Controller\Adminhtml\Category;

use Magento\Catalog\Controller\Adminhtml\Category\Save as SaveController;
use SR\CategoryImage\Controller\Adminhtml\Category\Mobile\Upload as MobileUpload;
use SR\CategoryImage\Controller\Adminhtml\Category\Thumbnail\Upload as ThumbnailUpload;

class SavePlugin
{
    /**
     * Add additional images
     *
     * @param SaveController $subject
     * @param array $data
     * @return array
     */
    public function beforeImagePreprocessing(SaveController $subject, $data)
    {
        foreach ($this->getAdditionalImages() as $imageType) {
            if (empty($data[$imageType])) {
                unset($data[$imageType]);
                $data[$imageType]['delete'] = true;
            }
        }

        return [$data];
    }

    /**
     * Get additional Images
     *
     * @return array
     */
    protected function getAdditionalImages() {
        return [
            MobileUpload::CATEGORY_ATTRIBUTE_IMAGE,
            ThumbnailUpload::CATEGORY_ATTRIBUTE_IMAGE
        ];
    }
}