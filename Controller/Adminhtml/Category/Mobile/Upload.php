<?php
namespace SR\CategoryImage\Controller\Adminhtml\Category\Mobile;

use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Controller\Adminhtml\Category\Image\Upload as BaseUpload;

/**
 * Class Upload
 */
class Upload extends BaseUpload
{
    const CATEGORY_ATTRIBUTE_IMAGE_MOBILE = 'image_mobile';

    /**
     * Upload file controller action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $result = $this->imageUploader->saveFileToTmpDir(self::CATEGORY_ATTRIBUTE_IMAGE_MOBILE);

            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}